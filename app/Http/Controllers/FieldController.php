<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Repositories\FieldRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Models\Field;
use App\Models\Polygon;
use App\Models\Trip;
use App\Models\Cartogram;
use App\Models\Client;
use App\Models\Point;
use App\Models\Protocol;
use App\Models\Qrcode;
use App\Models\Sample;
use App\Models\Result;

use DB;
use Flash;

class FieldController extends AppBaseController
{
    /** @var  FieldRepository */
    private $fieldRepository;

    public function __construct(FieldRepository $fieldRepo)
    {
        $this->fieldRepository = $fieldRepo;
    }

    /**
     * Display a listing of the Field.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        $fields = Field::with(['region', 'client']);

        $clientsIds = [];
        if ($request->has('query')) {
            // dd($query);
            $query = $input['query'];

            $clients = Client::orderBy('id');
            $clients = $clients->orWhere('khname', 'like', '%'.$query.'%');
            $clients = $clients->orWhere('lastname', 'like', '%'.$query.'%');
            $clients = $clients->orWhere('firstname', 'like', '%'.$query.'%');
            $clients = $clients->get(['id']);

            foreach ($clients as $client) {
                $clientsIds[] = $client->id;
            }

            // dd($clientsIds);

            $fields = $fields->whereIn('client_id', $clientsIds);
        }

        $fields = $fields->get();
        $ref = 'fields_index';

        return view('fields.index', compact('fields', 'ref'));
    }


    public function formImport() {
        return view('fields.import_form');
    }

    /**
     * Show the form for creating a new Field.
     *
     * @return Response
     */
    public function create()
    {
        $latestNum = Field::latest()->first();
        // $latestNum = ($latestNum != null) ? $latestNum->num : 0;
        $latestNum = 0;

        return view('fields.create', compact('latestNum'));
    }

    /**
     * Store a newly created Field in storage.
     *
     * @param CreateFieldRequest $request
     *
     * @return Response
     */
    public function store(CreateFieldRequest $request)
    {
        $input = $request->all();

        // cadnum
        $input['cadnum'] = str_replace('-', '', $input['cadnum']);

        // start transaction
        \DB::beginTransaction();

        $field = $this->fieldRepository->create($input);

        // download from aisgzk
        $layer = $this->_getLayerId($field);
        
        $url = 'http://www.aisgzk.kz/aisgzk/Proxy/aisgzkZem2/MapServer/find?f=json&searchText='.$field->cadnum.'&contains=false&returnGeometry=true&layers='.$layer.'&searchFields=KAD_NOMER&sr=3857';
        $response = Http::get($url);

        // TODO: check for errors

        $rings = $response->object()->results[0]->geometry->rings;

        // create polygon
        $polygon = Polygon::create([
            'field_id' => $field->id,
            'geometry' => json_encode($rings),
        ]);
        // dd($polygon->toArray());

        // create pending trip
        $trip = Trip::create([
            'field_id' => $field->id,
            'status' => 'pending',
            // 'date' => now(),
        ]);
        // dd($trip->toArray());

        // create pending cartogram
        $cartogram = Cartogram::create([
            'field_id' => $field->id,
            'status' => 'pending',
            'path' => '',
            'access_url' => '',
        ]);
        // dd($cartogram->toArray());

        \DB::commit();

        Flash::success(__('messages.saved', ['model' => __('models/fields.singular')]));

        return redirect(route('fields.index'));
    }

    public function show(Request $request, $id)
    {
        $field = Field::with(['client', 'polygon', 'polygon.points', 'polygon.points.qrcode', 'cartogram'])->find($id);
        $points = $field->polygon->points;

        $pointsIds = [];        
        $qrcodes = [];

        foreach ($points as $point) {
            if ($point == null) continue;

            $pointsIds[] = $point->id;
            $qrcodes[] = $point->qrcode;
        }

        $samples = Sample::with('result')->whereIn('point_id', $pointsIds)->get();

        $results = [];
        foreach ($samples as $sample) {
            if ($sample->result == null) continue;
            
            $results[] = $sample->result;
        }

        // dd($results);
        $cartograms = [];
        $cartogram = Cartogram::whereFieldId($field->id)->first();
        if ($cartogram != null) $cartograms[] = $cartogram;

        $protocol = Protocol::whereClientId($field->client_id)->first();
        

        if (empty($field)) {
            Flash::error(__('messages.not_found', ['model' => __('models/clients.singular')]));

            return redirect(route('fields.index'));
        }

        if ($samples == null) {
            $protocol = null;
            $cartograms = null;
        }

        $firstSample = $samples->first();
        if ($firstSample->date_started == null || $firstSample->date_completed == null || $firstSample->result == null) {
            $protocol = null;
            $cartograms = null;
        }


        $ref = '';
        if ($request->has('ref')) {
            $ref = $request->ref;
        }

        $fieldId = $field->id;

        return view('fields.show', compact('field', 'points', 'qrcodes', 'samples', 'results', 'cartograms', 'protocol', 'ref', 'fieldId'));
    }


    /**
     *  Editor 
     */
    public function editor()
    {
        return view('fields.editor');
    }

    /**
     * Display the specified Field on map.
     *
     * @param int $id
     *
     * @return Response
     */
    public function map($id)
    {
        $field = Field::with('polygon')->find($id);

        // $fields = Field::where('client_id', $field->client_id)->get();
        // dd($fields->toArray());
        $fields = [$field];

        if (empty($field)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fields.singular')]));

            return redirect(route('fields.index'));
        }

        return view('fields.map', compact('field', 'fields'));
    }

    /**
     * Show the form for editing the specified Field.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $field = $this->fieldRepository->find($id);
        // dd($field);

        if (empty($field)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fields.singular')]));

            return redirect(route('fields.index'));
        }

        $ref = '';
        if ($request->has('ref')) {
            $ref = $request->ref;
        }

        $clientId = '';
        if ($request->has('client_id')) {
            $clientId = $request->client_id;
        }

        return view('fields.edit', compact('field', 'ref', 'clientId'));
    }

    /**
     * Update the specified Field in storage.
     *
     * @param int $id
     * @param UpdateFieldRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $field = $this->fieldRepository->find($id);

        if (empty($field)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fields.singular')]));

            return redirect(route('fields.index'));
        }

        \DB::beginTransaction();

        if ($field->cadnum == $request->cadnum || $field->cadnum == 0) {
            $field = $this->fieldRepository->update($request->all(), $id);
        } else {
            $field = $this->fieldRepository->update($request->all(), $id);
    
            // download from aisgzk
            $layer = 50;    // where to find?
            $url = 'http://www.aisgzk.kz/aisgzk/Proxy/aisgzkZem2/MapServer/find?f=json&searchText='.$field->cadnum.'&contains=false&returnGeometry=true&layers='.$layer.'&searchFields=KAD_NOMER&sr=3857';
            $response = Http::get($url);

            // TODO: check for errors

            $rings = $response->object()->results[0]->geometry->rings;

            // create polygon
            $polygon = Polygon::firstOrCreate([
                'field_id' => $field->id,
                // 'geometry' => json_encode($rings),
            ]);
            $polygon->geometry = json_encode($rings);
            // dd($polygon->toArray());

            // create pending trip
            $trip = Trip::firstOrCreate([
                'field_id' => $field->id,
                // 'status' => 'pending',
                // 'date' => now(),
            ]);
            $trip->status = 'pending';
            // dd($trip->toArray());

            // create pending cartogram
            $cartogram = Cartogram::firstOrCreate([
                'field_id' => $field->id,
                // 'status' => 'pending',
                // 'path' => '',
                // 'access_url' => '',
            ]);
            $cartogram->update([
                'status' => 'pending',
                'path' => '',
                'access_url' => '',
            ]);
            // dd($cartogram->toArray());

        }

        \DB::commit();

        Flash::success(__('messages.updated', ['model' => __('models/fields.singular')]));

        if ($request->has('ref')) {
            // dd('show_client');
            return redirect('/clients/' . $field->client_id);
        }

        return redirect(route('fields.index'));

    }

    /**
     * Remove the specified Field from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        // dd($request->all());

        $field = Field::with(['polygon', 'polygon.points', 'cartogram'])->find($id);
        // dd($field->toArray());
        
        if (empty($field)) {
            Flash::error(__('messages.not_found', ['model' => __('models/fields.singular')]));

            if ($request->has('ref')) {
                return redirect('/clients/' . $field->client_id);
            }

            return redirect(route('fields.index'));
        }

        foreach ($field->polygon->points()->with(['qrcode', 'sample', 'sample.result'])->get() as $point) {

            if ($point->sample != null) {
                if ($point->sample->result != null) {
                    $point->sample->result->delete();
                }

                $point->sample->delete();
            }

            if ($point->qrcode != null) {
                $point->qrcode->delete();
            }

            if ($point != null) {
                $point->delete();
            }
        }

        $protocol = Protocol::whereClientId($field->id)->first();
        // dd($protocol);

        if ($protocol != null) {
            $protocol->delete();
        }

        if ($field->cartogram != null) {
            $field->cartogram->delete();
        }

        if ($field->polygon != null) {
            $field->polygon->delete();
        }

        $field->delete();

        // success msg
        Flash::success(__('messages.deleted', ['model' => __('models/fields.singular')]));

        if ($request->has('ref')) {
            return redirect('/clients/' . $field->client_id);
        }

        return redirect(route('fields.index'));
    }


    /**
     * Get layer ID
     */
    public function _getLayerId($field) {

        $name = substr($field->cadnum, 0, 2) . '_' . substr($field->cadnum, 2, 3);
        // dd([$field->cadnum, $name]);

        $layer = DB::table('layers')->where('name', $name)->first();
        // dd($layer);

        return $layer->num;
    } 


    /**
     * Generate KML
     */
    public function kml(Response $response, $id) {
        $field = Field::with(['polygon', 'polygon.points'])->whereId($id)->firstOrFail();

        $arr = json_decode($field->polygon->geometry);
        // dd($arr);
        
        $points = $field->polygon->points;

        $coordinates = [];

        foreach ($arr as $ring) {
            // dd($ring);

            foreach ($ring as $point) {
                // dd($ring);
                $coordinates[] = Field::m2d($point);
            }   
        }

        $kml = view('fields.kml', compact('coordinates', 'points'))->render();

        $xml = '<?xml version="1.0" encoding="UTF-8" ?>' . $kml;

        $response = Response::create($xml, 200);
        $response->header('Content-Type', 'text/xml');
        $response->header('Cache-Control', 'public');
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=field.kml');
        $response->header('Content-Transfer-Encoding', 'binary');
        return $response;
    }


    public function prepareImport(Request $request) {
        // dd($request);
        $client = Client::findOrFail($request->client_id);

        $xmlDataString = file_get_contents($request->kml);
        $xmlObject = simplexml_load_string($xmlDataString);
                   
        $json = json_encode($xmlObject);
        $data = json_decode($json, true); 
        // dd($data);
        
        $fields = [];

        if (!isset($data['Document']['Folder'])) {
            dd('not valid kml');
        };




        if (isset($data['Document']['Folder']['Folder']['Folder'])) {
            $dd = $data['Document']['Folder']['Folder']['Folder'];
            // dd($dd);

            if (isset($dd['Folder'])) {
                // dd($data['Folder']);

                foreach ($dd['Folder'] as $f) {
                    // dd($f);

                    $square = '';
                    $geometry = [];
                    $points = [];

                    foreach ($f['Placemark'] as $placemark) {
                        if (isset($placemark['Polygon'])) {
                            $square = $placemark['name'];
                            $geometry = $placemark['Polygon'];
                        } 

                        if (isset($placemark['Point'])) {
                            $points[] = [
                                'name' => $placemark['name'],
                                'geometry' => $placemark['Point']
                            ];
                        }

                        // dd($square);
                    }

                    $fields[] = [
                        'cadnum' => $dd['name'],
                        'square' => doubleval($square),
                        'geometry' => $geometry,
                        'points' => $points
                    ];
                }
            } else {
                // dd($dd);

                if (isset($dd['Placemark'])) {
                    // dd($dd['Placemark']);

                    $square = '';
                    $geometry = [];
                    $points = [];

                    foreach ($dd['Placemark'] as $placemark) {
                        if (isset($placemark['Polygon'])) {
                            $square = $placemark['name'];
                            $geometry = $placemark['Polygon'];
                        } 

                        if (isset($placemark['Point'])) {
                            $points[] = [
                                'name' => $placemark['name'],
                                'geometry' => $placemark['Point']
                            ];
                        }

                        // dd($square);
                    }

                    $fields[] = [
                        'cadnum' => $dd['name'],
                        'square' => doubleval($square),
                        'geometry' => $geometry,
                        'points' => $points
                    ];
                } else {
                    foreach ($dd as $field) {  
                        // dd($field);

                        if (isset($field['Folder'])) {

                            foreach ($field['Folder'] as $f) {
                                $square = '';
                                $geometry = [];
                                $points = [];

                                foreach ($f['Placemark'] as $placemark) {
                                    if (isset($placemark['Polygon'])) {
                                        $square = $placemark['name'];
                                        $geometry = $placemark['Polygon'];
                                    } 

                                    if (isset($placemark['Point'])) {
                                        $points[] = [
                                            'name' => $placemark['name'],
                                            'geometry' => $placemark['Point']
                                        ];
                                    }

                                    // dd($square);
                                }

                                $fields[] = [
                                    'cadnum' => $field['name'],
                                    'square' => doubleval($square),
                                    'geometry' => $geometry,
                                    'points' => $points
                                ];
                            }

                        } else {
                            $square = '';
                            $geometry = [];
                            $points = [];

                            foreach ($field['Placemark'] as $placemark) {
                                if (isset($placemark['Polygon'])) {
                                    $square = $placemark['name'];
                                    $geometry = $placemark['Polygon'];
                                } 

                                if (isset($placemark['Point'])) {
                                    $points[] = [
                                        'name' => $placemark['name'],
                                        'geometry' => $placemark['Point']
                                    ];
                                }

                                // dd($square);
                            }

                            $fields[] = [
                                'cadnum' => $field['name'],
                                'square' => doubleval($square),
                                'geometry' => $geometry,
                                'points' => $points
                            ];
                        }
                    }
                }

                
            }

            
        }

        // dd($fields);

        return view('fields.prepare_import', compact('client', 'fields'));
    }


    public function import(Request $request) {
        $input = $request->all();
        // dd($input);

        $client = Client::findOrFail($input['client_id']);
        // dd($client);

        $fields = json_decode($input['fields']);

        // start transaction
        DB::beginTransaction();

        $fieldNum = 1;

        foreach ($fields as $fieldJson) {
            // dd($field);

            $field = null;
            $polygon = null;
            $webCoordinates = [];
            $num = 1;

            // field
            // coordinates
            $coordinatesStr = $fieldJson->geometry->outerBoundaryIs->LinearRing->coordinates;
            $coordinates = explode(',0 ', $coordinatesStr);
            // dd($coordinates);

            foreach ($coordinates as $key => $coord) {
                if ($coord == '' || $coord == ' ' || $coord == null) continue;

                $point = explode(',', $coord);
                $webPoint = Field::d2m($point);
                $webCoordinates[] = $webPoint;
            }
            // dd($webCoordinates);

            // field
            $field = Field::create([
                'cadnum' => str_replace('-', '', $fieldJson->cadnum),
                'type' => 'irrigated',
                'square' => trim(str_replace('га', '', $fieldJson->square)),
                'culture' => '',
                'description' => '',
                'region_id' => 1,
                'client_id' => $client->id,
                'num' => $fieldNum,
                'address' => '',
            ]);

            // create polygon
            $polygon = Polygon::create([
                'field_id' => $field->id,
                'geometry' => json_encode([$webCoordinates])
            ]);
            // dd($polygon);

            // create cartogram
            $cartogram = Cartogram::create([
                'field_id' => $field->id,
                'status' => 'pending',
                'access_url' => '',
                'path' => '',
            ]);

            // protocol
            $protocol = Protocol::create([
                'client_id' => $client->id,
                'path' => '',
                'access_url' => '',
                'num' => 0,
            ]);


            // points
            $points = $fieldJson->points;
            foreach ($points as $key => $pointJson) {
                // dd($pointJson);
                
                // point
                $pointStr = str_replace(',0 ', '', $pointJson->geometry->coordinates);    
                // dd($pointStr);        
                $p = explode(',', $pointStr);
                
                $point = Point::create([
                    'polygon_id' => $polygon->id,
                    'lat' => $p[1],
                    'lon' => $p[0],
                    'num' => intval(trim(str_replace('Метка', '', $pointJson->name))),
                ]);
                // dd($point);

                $qrcode = Qrcode::create([
                    'point_id' => $point->id,
                    'content' => 'content',
                ]);

                $sample = Sample::create([
                    'point_id' => $point->id,
                    'date_selected' => now(),
                    'date_received' => now(),
                    'num' => 0,
                    'quantity' => 1,
                    'passed' => 'сдал',
                    'accepted' => 'принял',
                ]);
            }

            $fieldNum += 1;
        }
        
        // commit db
        DB::commit();

        return redirect('/clients/' . $client->id);
    }


    public function updatePolygon(Request $request) {
        $input = $request->all();
    
        $field = Field::with(['polygon'])->findOrFail($input['field_id']);
        // $polygon = Polygon::whereFieldId($field->id)->firstOrfail();

        $geometry = [];

        foreach ($input['coordinates'] as $coordinate) {
            $geometry[] = Field::d2m($coordinate);
        }

        if ($geometry[0] != $geometry[count($geometry) - 1]) {
            $geometry[] = $geometry[0];
        }

        // dd($geometry);

        $field->polygon->geometry = json_encode([$geometry]);
        // dd($field->geometry);

        $field->polygon->save();

        return 1;
    }
}
