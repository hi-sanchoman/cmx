<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSampleRequest;
use App\Http\Requests\UpdateSampleRequest;
use App\Repositories\SampleRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\Sample;
use App\Models\Result;
use App\Models\Client;
use DB;

class SampleController extends AppBaseController
{
    /** @var  SampleRepository */
    private $sampleRepository;

    public function __construct(SampleRepository $sampleRepo)
    {
        $this->sampleRepository = $sampleRepo;
    }

    /**
     * Display a listing of the Sample.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        $samples = Sample::orderBy('num', 'ASC');

        $pointsIds = [];
        if ($request->has('query')) {
            // dd($query);
            $query = $input['query'];

            $clients = Client::with(['fields', 'fields.polygon', 'fields.polygon.points'])->orderBy('id');
            $clients = $clients->orWhere('khname', 'like', '%'.$query.'%');
            $clients = $clients->orWhere('lastname', 'like', '%'.$query.'%');
            $clients = $clients->orWhere('firstname', 'like', '%'.$query.'%');
            $clients = $clients->get();

            foreach ($clients as $client) {
                foreach ($client->fields as $field) {
                    foreach ($field->polygon->points as $point) {
                        $pointsIds[] = $point->id;
                    }
                }
            }

            // dd($clientsIds);

            $samples = $samples->whereIn('point_id', $pointsIds);
        }

        $samples = $samples->get();

        return view('samples.index')
            ->with('samples', $samples);
    }

    /**
     * Show the form for creating a new Sample.
     *
     * @return Response
     */
    public function create()
    {
        return view('samples.create');
    }

    /**
     * Store a newly created Sample in storage.
     *
     * @param CreateSampleRequest $request
     *
     * @return Response
     */
    public function store(CreateSampleRequest $request)
    {
        $input = $request->all();

        $sample = $this->sampleRepository->create($input);

        $this->_prepareResult($sample);

        Flash::success(__('messages.saved', ['model' => __('models/samples.singular')]));

        return redirect(route('samples.index'));
    }

    /**
     * Display the specified Sample.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $sample = $this->sampleRepository->find($id);

        if (empty($sample)) {
            Flash::error(__('messages.not_found', ['model' => __('models/samples.singular')]));

            return redirect(route('samples.index'));
        }

        return view('samples.show')->with('sample', $sample);
    }

    /**
     * Show the form for editing the specified Sample.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $sample = $this->sampleRepository->find($id);
        // dd($sample->toArray());

        if (empty($sample)) {
            Flash::error(__('messages.not_found', ['model' => __('models/samples.singular')]));

            return redirect(route('samples.index'));
        }

        return view('samples.edit')->with('sample', $sample);
    }

    /**
     * Update the specified Sample in storage.
     *
     * @param int $id
     * @param UpdateSampleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSampleRequest $request)
    {
        $sample = $this->sampleRepository->find($id);

        if (empty($sample)) {
            Flash::error(__('messages.not_found', ['model' => __('models/samples.singular')]));

            return redirect(route('samples.index'));
        }

        $input = $request->all();

        $values = ['p', 'k', 's', 'humus', 'humus_mass', 'no3', 'ph', 'b', 'fe', 'cu', 'zn', 'mn', 'na', 'calcium', 'magnesium', 'salinity', 'absorbed_sum', 'na_x2', 'calcium_v1', 'calcium_v2', 'calcium_c', 'magnesium_v1', 'magnesium_v2', 'magnesium_c', 'absorbed_sum_v', 'absorbed_sum_m', 'absorbed_sum_c',];

        foreach ($values as $value) {
            if (!isset($input[$value])) continue;
            $input[$value] = str_replace(',', '.', $input[$value]);
        }
        // dd($input);

        $sample = $this->sampleRepository->update($input, $id);

        $this->_prepareResult($sample);

        Flash::success(__('messages.updated', ['model' => __('models/samples.singular')]));

        return redirect(route('samples.index'));
    }

    /**
     * Remove the specified Sample from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $sample = Sample::with(['result'])->find($id);

        if (empty($sample)) {
            Flash::error(__('messages.not_found', ['model' => __('models/samples.singular')]));

            if ($request->has('ref')) {
                return redirect('/clients/' . $request->client_id);
            }

            return redirect(route('samples.index'));
        }

        if ($sample->result != null) {
            $sample->result->delete();
        }

        $sample->delete();

        Flash::success(__('messages.deleted', ['model' => __('models/samples.singular')]));

        if ($request->has('ref')) {
            return redirect('/clients/' . $request->client_id);
        }

        return redirect(route('samples.index'));
    }







    private function _prepareResult(Sample $sample) {
        $m = 5;
        $no3_v = 0.025;
        $no3_m = 10;

        DB::beginTransaction();

        $result = Result::firstOrNew([
            'sample_id' => $sample->id,
        ]);

        // calculate values
        $result->p = round(($sample->p - 0.0154) / 0.007 * 100) / 100;
        $result->k = round(($sample->k * 0.1 * 1000) / $m * 100) / 100;
        $result->s = round(($sample->s - 0.1973) / 0.01 * 100) / 100;
        $result->humus = round(((($sample->humus - 0.0224) / 0.0456 * 0.97) / $sample->humus_mass) * 100 * 100) / 100;
        $result->no3 = round(($sample->no3 * $no3_v * 1000) / $no3_m * 1000) / 1000;
        $result->ph = round($sample->ph * 100) / 100;

        $fe1 = ($sample->fe - 0.0107) / 461.88;
        $fe2 = $fe1 * 1000;
        $result->fe = ($fe2 * 0.05 * 1000) / 5;

        $b = ($sample->b - 0.5355) / 0.4654;
        $result->b = ($b * 0.05 * 1000) / 10;

        $result->mn = ($sample->mn - 0.0457) / 0.0231;
        $result->cu = ($sample->cu - 0.0707) / 0.3324;
        $result->zn = ($sample->zn - 0.4939) / 0.1713;
        $result->na = $sample->na - $sample->na_x2;

        if (isset($sample->calcium)) {
            $result->calcium = ($sample->calcium - $sample->calcium_v1) * $sample->calcium_c * 500 / $sample->calcium_v2;
        }

        if (isset($sample->magnesium)) {
            $result->magnesium = ($sample->magnesium - $sample->magnesium_v1) * $sample->magnesium_c * 500 / $sample->magnesium_v2;
        }

        $result->salinity = $sample->salinity * 0.009;

        if (isset($sample->absorbed_sum)) {
            $result->absorbed_sum = ($sample->absorbed_sum - $sample->absorbed_sum_v) * $sample->absorbed_sum_c * 100 / $sample->absorbed_sum_m;
        }

        $result->passed = 'сдал';
        $result->accepted = 'принял';
        $result->save();

        // dd([$sample->toArray(), $result->toArray()]);

        DB::commit();
    }
}
