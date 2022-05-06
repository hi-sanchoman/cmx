<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Repositories\ResultRepository;
use App\Models\Result;
use App\Models\Client;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ResultController extends AppBaseController
{
    /** @var  ResultRepository */
    private $resultRepository;

    public function __construct(ResultRepository $resultRepo)
    {
        $this->resultRepository = $resultRepo;
    }

    /**
     * Display a listing of the Result.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        $results = Result::with([
            'sample', 'sample.point', 'sample.point.polygon', 'sample.point.polygon.field'
            ])
            ->orderBy('created_at', 'DESC');

        $samplesIds = [];
        if ($request->has('query')) {
            // dd($query);
            $query = $input['query'];

            $clients = Client::with(['fields', 'fields.polygon', 'fields.polygon.points', 'fields.polygon.points.sample'])->orderBy('id');
            $clients = $clients->orWhere('khname', 'like', '%'.$query.'%');
            $clients = $clients->orWhere('lastname', 'like', '%'.$query.'%');
            $clients = $clients->orWhere('firstname', 'like', '%'.$query.'%');
            $clients = $clients->get();

            foreach ($clients as $client) {
                foreach ($client->fields as $field) {
                    foreach ($field->polygon->points as $point) {
                        $samplesIds[] = $point->sample->id;
                    }
                }
            }

            // dd($clientsIds);

            $results = $results->whereIn('sample_id', $samplesIds);
        }

        $results = $results->get();

        return view('results.index')
            ->with('results', $results);
    }

    /**
     * Show the form for creating a new Result.
     *
     * @return Response
     */
    public function create()
    {
        return view('results.create');
    }

    /**
     * Store a newly created Result in storage.
     *
     * @param CreateResultRequest $request
     *
     * @return Response
     */
    public function store(CreateResultRequest $request)
    {
        $input = $request->all();

        dd($input['humus']);

        $result = $this->resultRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/results.singular')]));

        return redirect(route('results.index'));
    }

    /**
     * Display the specified Result.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $result = $this->resultRepository->find($id);

        if (empty($result)) {
            Flash::error(__('messages.not_found', ['model' => __('models/results.singular')]));

            return redirect(route('results.index'));
        }

        return view('results.show')->with('result', $result);
    }

    /**
     * Show the form for editing the specified Result.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $result = $this->resultRepository->find($id);

        if (empty($result)) {
            Flash::error(__('messages.not_found', ['model' => __('models/results.singular')]));

            return redirect(route('results.index'));
        }

        $mode = $request->has('mode') ? $request->mode : null;

        return view('results.edit', compact('result', 'mode'));
    }

    /**
     * Update the specified Result in storage.
     *
     * @param int $id
     * @param UpdateResultRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateResultRequest $request)
    {
        $result = $this->resultRepository->find($id);

        if (empty($result)) {
            Flash::error(__('messages.not_found', ['model' => __('models/results.singular')]));

            return redirect(route('results.index'));
        }

        $input = $request->all();

        $values = ['humus', 'p', 'no3', 's', 'k', 'ph', 'b', 'fe', 'salinity', 'absorbed_sum', 'mn', 'zn', 'cu', 'calcium', 'magnesium', 'na',];

        foreach ($values as $value) {
            if (!isset($input[$value])) continue;
            $input[$value] = str_replace(',', '.', $input[$value]);
        }
        
        // dd($input);

        $result = $this->resultRepository->update($input, $id);

        Flash::success(__('messages.updated', ['model' => __('models/results.singular')]));

        return redirect(route('results.index'));
    }

    /**
     * Remove the specified Result from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $result = $this->resultRepository->find($id);

        if (empty($result)) {
            Flash::error(__('messages.not_found', ['model' => __('models/results.singular')]));

            return redirect(route('results.index'));
        }

        $this->resultRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/results.singular')]));

        return redirect(route('results.index'));
    }
}
