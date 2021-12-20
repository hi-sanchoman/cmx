<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePointRequest;
use App\Http\Requests\UpdatePointRequest;
use App\Repositories\PointRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Point;
use App\Models\Qrcode;
use App\Models\Sample;
use App\Models\Polygon;
use Flash;
use Response;

class PointController extends AppBaseController
{
    /** @var  PointRepository */
    private $pointRepository;

    public function __construct(PointRepository $pointRepo)
    {
        $this->pointRepository = $pointRepo;
    }

    /**
     * Display a listing of the Point.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $points = $this->pointRepository->all();

        return view('points.index')
            ->with('points', $points);
    }

    /**
     * Show the form for creating a new Point.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $polygon = null;

        $ref = '';
        if ($request->has('ref')) {
            $ref = $request->ref;
        }

        $fieldId = '';
        if ($request->has('field_id')) {
            $fieldId = $request->field_id;
            $polygon = Polygon::whereFieldId($fieldId)->firstOrfail();
        }

        return view('points.create', compact('ref', 'fieldId', 'polygon'));
    }

    /**
     * Store a newly created Point in storage.
     *
     * @param CreatePointRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        // dd($input);

        \DB::beginTransaction();

        $point = $this->pointRepository->create($input);

        // create qrcode
        $qrcode = Qrcode::create([
            'point_id' => $point->id,
            'content' => json_encode($point),
        ]);

        // create sample
        $sample = Sample::create([
            'point_id' => $point->id,
            'date_selected' => now(),
            'date_received' => now(),
            'num' => 0,
            'quantity' => 1,
            'passed' => 'сдал',
            'accepted' => 'принял',
            'notes' => null,
        ]);

        \DB::commit();

        Flash::success(__('messages.saved', ['model' => __('models/points.singular')]));

        if (isset($input['ajax'])) {
            return $point->id;
        }

        if ($request->has('ref')) {
            return redirect('/fields/' . $request->field_id);
        }        

        return redirect()->route('points.index');
    }

    /**
     * Display the specified Point.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $point = $this->pointRepository->find($id);

        if (empty($point)) {
            Flash::error(__('messages.not_found', ['model' => __('models/points.singular')]));

            return redirect(route('points.index'));
        }

        return view('points.show')->with('point', $point);
    }

    /**
     * Show the form for editing the specified Point.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $point = $this->pointRepository->find($id);

        if (empty($point)) {
            Flash::error(__('messages.not_found', ['model' => __('models/points.singular')]));

            return redirect(route('points.index'));
        }

        $ref = '';
        if ($request->has('ref')) {
            $ref = $request->ref;
        }

        $fieldId = '';
        if ($request->has('field_id')) {
            $fieldId = $request->field_id;
            $polygon = Polygon::whereFieldId($fieldId)->firstOrfail();
        }

        return view('points.edit', compact('point', 'ref', 'fieldId', 'polygon'));
    }

    /**
     * Update the specified Point in storage.
     *
     * @param int $id
     * @param UpdatePointRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        // dd($request->all());

        $point = $this->pointRepository->find($id);

        if (empty($point)) {
            Flash::error(__('messages.not_found', ['model' => __('models/points.singular')]));

            return redirect(route('points.index'));
        }

        $point = $this->pointRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/points.singular')]));

        if ($request->has('ref')) {
            return redirect('/fields/' . $request->field_id);
        }  

        return redirect(route('points.index'));
    }

    /**
     * Remove the specified Point from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $point = Point::with(['qrcode', 'sample', 'sample.result'])->find($id);

        if (empty($point)) {
            Flash::error(__('messages.not_found', ['model' => __('models/points.singular')]));

            if ($request->has('ref')) {
                return redirect('/fields/' . $request->field_id);
            } 

            return redirect(route('points.index'));
        }

        // remove sample result
        if ($point->sample != null) {
            if ($point->sample->result != null) {
                $point->sample->result->delete();
            }
            // remove sample
            $point->sample->delete();
        }

        // remove qrode
        if ($point->qrcode != null) {
            $point->qrcode->delete();
        }

        // remove point
        $point->delete();

        Flash::success(__('messages.deleted', ['model' => __('models/points.singular')]));

        if (request->has('ajax')) {
            return 1;
        }

        if ($request->has('ref')) {
            return redirect('/fields/' . $request->field_id);
        } 

        return redirect(route('points.index'));
    }

    public function deletePoint(Request $request)
    {
        // dd($request->all());

        $point = Point::with(['qrcode', 'sample', 'sample.result'])
            // ->wherePolygonId($request->polygon_id)
            // ->whereLat($request->lat)
            // ->whereLon($request->lng)
            ->find($request->point_id);

        if (empty($point)) {
            return 0;
        }

        // remove sample result
        if ($point->sample != null) {
            if ($point->sample->result != null) {
                $point->sample->result->delete();
            }
            // remove sample
            $point->sample->delete();
        }

        // remove qrode
        if ($point->qrcode != null) {
            $point->qrcode->delete();
        }

        // remove point
        $point->delete();

        return 1;
    }

    public function updatePoint(Request $request) {
        $point = Point::with(['qrcode', 'sample', 'sample.result'])
            // ->wherePolygonId($request->polygon_id)
            // ->whereLat($request->lat)
            // ->whereLon($request->lng)
            ->find($request->point_id);

        if (empty($point)) {
            return 0;
        }

        $point->lat = $request->lat;
        $point->lon = $request->lng;
        $point->save();
        
        return 1;
    }
}
