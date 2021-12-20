<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Repositories\ClientRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Client;
use Flash;
use DB;
use Response;
use App\Models\Protocol;
use App\Models\Field;
use App\Models\Sample;
use App\Models\Cartogram;
use Image;

class ClientController extends AppBaseController
{
    /** @var  ClientRepository */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepo)
    {
        $this->clientRepository = $clientRepo;
    }

    /**
     * Display a listing of the Client.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        $clients = Client::with('region')->orderBy('num', 'ASC');

        if ($request->has('query')) {
            // dd($query);
            $query = $input['query'];

            $clients = $clients->orWhere('khname', 'like', '%'.$query.'%');
            $clients = $clients->orWhere('lastname', 'like', '%'.$query.'%');
            $clients = $clients->orWhere('firstname', 'like', '%'.$query.'%');
        }

        $clients = $clients->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new Client.
     *
     * @return Response
     */
    public function create()
    {
        $latestNum = Client::latest()->first();
        $latestNum = ($latestNum != null) ? $latestNum->num : 0;

        return view('clients.create', compact('latestNum'));
    }

    /**
     * Store a newly created Client in storage.
     *
     * @param CreateClientRequest $request
     *
     * @return Response
     */
    public function store(CreateClientRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        $client = $this->clientRepository->create($input);
        $protocol = Protocol::firstOrCreate(['client_id' => $client->id, 'path' => '', 'num' => 0]);

        DB::commit();

        Flash::success(__('messages.saved', ['model' => __('models/clients.singular')]));

        return redirect(route('clients.index'));
    }

    /**
     * Display the specified Client.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $client = Client::with(['fields'])->find($id);
        $fields = $client->fields;

        $ref = 'clients_show';
        
        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));

            return redirect(route('clients.index'));
        }

        return view('clients.show', compact('client', 'fields', 'ref'));
    }

    /**
     * Show the form for editing the specified Client.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));

            return redirect(route('clients.index'));
        }

        return view('clients.edit')->with('client', $client);
    }

    /**
     * Update the specified Client in storage.
     *
     * @param int $id
     * @param UpdateClientRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClientRequest $request)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));

            return redirect(route('clients.index'));
        }

        $client = $this->clientRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/clients.singular')]));

        return redirect(route('clients.index'));
    }

    /**
     * Remove the specified Client from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $client = $this->clientRepository->find($id);

        if (empty($client)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));

            return redirect(route('clients.index'));
        }

        $this->clientRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/clients.singular')]));

        return redirect(route('clients.index'));
    }


    public function protocol($id) {
        $client = Client::findOrFail($id);
        $fields = Field::with(['polygon', 'polygon.points'])->whereClientId($client->id)->get();
    
        $pointsIds = [];

        foreach ($fields as $field) {
            foreach ($field->polygon->points as $point) {
                $pointsIds[] = $point->id;
            }
        }

        $samples = Sample::whereIn('point_id', $pointsIds)->get();
        // dd($samples->toArray());

        if ($samples == null) {
            // no samples yet
            Flash::error('Не готовы результаты испытаний');
            return back();
        }

        $firstSample = $samples->first();
        if ($firstSample->date_started == null || $firstSample->date_completed == null || $firstSample->result == null) {
            Flash::error('Не готовы результаты испытаний');
            return back();
        }

        // dd($samples->first()->date_selected->format('m/d/Y'));

        return view('clients.protocol', compact('client', 'pointsIds', 'samples'));
    }

    
    public function cabinet(Request $request, $id) {
        $client = Client::with(['fields'])->findOrFail($id);
        $fields = [];

        foreach ($client->fields as $field) {
            $cartogram = Cartogram::
                with(['field', 'field.polygon', 'field.polygon.points', 'field.polygon.points.sample'])
                ->whereFieldId($field->id)
                ->first();
            // dd($cartogram->toArray());

            if ($cartogram == null) continue;

            // generate images
            $points = [];
            $results = [
                'humus' => [],
                'ph' => [],
                'p' => [],
                's' => [],
                'k' => [],
                'no3' => [],
                'b' => [],
                'fe' => [],
                'salinity' => [],
                'absorbed_sum' => [],
                'mn' => [],
                'zn' => [],
                'cu' => [],
                'na' => [],
                'calcium' => [],
                'magnesium' => [],
            ];

            $list = $cartogram->field->polygon->points;
            // dd($list->toArray());

            for ($i = 0; $i < count($list); $i++) {
                $point = $list[$i];
                // dd($point->toArray());

                if ($point->sample->result == null) {
                    break;
                }

                $results['humus'][$i] = $point->sample->result->humus;
                $results['ph'][$i] = $point->sample->result->ph;
                $results['p'][$i] = $point->sample->result->p;
                $results['s'][$i] = $point->sample->result->s;
                $results['k'][$i] = $point->sample->result->k;
                $results['no3'][$i] = $point->sample->result->no3;


                $results['b'][$i] = $point->sample->result->b;
                $results['fe'][$i] = $point->sample->result->fe;
                $results['salinity'][$i] = $point->sample->result->salinity;
                $results['absorbed_sum'][$i] = $point->sample->result->absorbed_sum;
                $results['mn'][$i] = $point->sample->result->mn;
                $results['zn'][$i] = $point->sample->result->zn;
                $results['cu'][$i] = $point->sample->result->cu;
                $results['na'][$i] = $point->sample->result->na;
                $results['calcium'][$i] = $point->sample->result->calcium;
                $results['magnesium'][$i] = $point->sample->result->magnesium;

                $points[$i] = [$point->lon, $point->lat];
            }
            // dd([$points, $results]);
            // dd($list);
            
            $markerImgs = [];
            $values = [
                'humus', 'ph', 'p', 's', 'k', 'no3',
                'b',
                'fe',
                'cu',
                'zn',
                'mn',
                'na',
                'calcium',
                'magnesium',
                'salinity',
                'absorbed_sum',
            ];

            for ($i = 0; $i < count($points); $i++) {
                $point = $points[$i];
                $pos = $i;

                $markers = [];

                for ($j = 0; $j < count($values); $j++) {
                    $value = $values[$j];

                    $html = view('cartograms.dot', compact('results', 'points', 'point', 'pos', 'value'))->render();
                    // dd($html);
                    $path = 'img/map/' . $list[$i]->id . '-' . $value . '.png';

                    $markers[$value] = [
                        'path' => $path,
                        'id' => $list[$i]->id,
                    ];

                    // prepare
                    $img = Image::make(public_path('img/map_dot2.png'));

                    // write text at position x , y 
                    $img->text($results[$value][$pos], 32, 32, function($font) {
                        $font->file(public_path('fonts/opensans.ttf'));
                        $font->size(20);
                        $font->align('center');
                        // $font->valign('middle');
                    });

                    // Save Image to Path 
                    $img->save(public_path($path));
                }

                $markerImgs[$list[$i]->id] = $markers;

                // dd($markerImgs);
            }
            // dd($markerImgs);

            $value = $request->has('value') ? $request->value : 'humus';

            $fields[$field->id] = [
                'field' => $field,
                'cartogram' => $cartogram,
                'points' => $points,
                'results' => $results,
                'value' => $value,
                'images' => $markerImgs,
            ];
        }

        // dd($fields);

        return view('clients.map', compact('client', 'fields'));
    }
}