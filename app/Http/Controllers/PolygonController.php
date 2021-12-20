<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePolygonRequest;
use App\Http\Requests\UpdatePolygonRequest;
use App\Repositories\PolygonRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\Polygon;

class PolygonController extends AppBaseController
{
    /** @var  PolygonRepository */
    private $polygonRepository;

    public function __construct(PolygonRepository $polygonRepo)
    {
        $this->polygonRepository = $polygonRepo;
    }

    /**
     * Display a listing of the Polygon.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $polygons = $this->polygonRepository->all();

        return view('polygons.index')
            ->with('polygons', $polygons);
    }

    /**
     * Show the form for creating a new Polygon.
     *
     * @return Response
     */
    public function create()
    {
        return view('polygons.create');
    }

    /**
     * Store a newly created Polygon in storage.
     *
     * @param CreatePolygonRequest $request
     *
     * @return Response
     */
    public function store(CreatePolygonRequest $request)
    {
        $input = $request->all();

        $polygon = $this->polygonRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/polygons.singular')]));

        return redirect(route('polygons.index'));
    }

    /**
     * Display the specified Polygon.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $polygon = $this->polygonRepository->find($id);

        if (empty($polygon)) {
            Flash::error(__('messages.not_found', ['model' => __('models/polygons.singular')]));

            return redirect(route('polygons.index'));
        }

        return view('polygons.show')->with('polygon', $polygon);
    }

    /**
     * Show the form for editing the specified Polygon.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $polygon = $this->polygonRepository->find($id);

        if (empty($polygon)) {
            Flash::error(__('messages.not_found', ['model' => __('models/polygons.singular')]));

            return redirect(route('polygons.index'));
        }

        return view('polygons.edit')->with('polygon', $polygon);
    }

    /**
     * Update the specified Polygon in storage.
     *
     * @param int $id
     * @param UpdatePolygonRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePolygonRequest $request)
    {
        $polygon = $this->polygonRepository->find($id);

        if (empty($polygon)) {
            Flash::error(__('messages.not_found', ['model' => __('models/polygons.singular')]));

            return redirect(route('polygons.index'));
        }

        $polygon = $this->polygonRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/polygons.singular')]));

        return redirect(route('polygons.index'));
    }

    /**
     * Remove the specified Polygon from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $polygon = $this->polygonRepository->find($id);

        if (empty($polygon)) {
            Flash::error(__('messages.not_found', ['model' => __('models/polygons.singular')]));

            return redirect(route('polygons.index'));
        }

        $this->polygonRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/polygons.singular')]));

        return redirect(route('polygons.index'));
    }


    public function clearPolygon(Request $request) {
        $polygon = Polygon::with(['points', 'points.sample', 'points.qrcode', 'points.sample.result'])->findOrFail($request->id);

        foreach ($polygon->points as $point) {
            if ($point->qrcode != null) $point->qrcode->delete();
            if ($point->sample != null && $point->sample->result != null) $point->sample->result->delete();
            if ($point->sample != null) $point->sample->delete();
            
            $point->delete();
        }

        return 1;
    }
}
