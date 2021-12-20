<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCartogramRequest;
use App\Http\Requests\UpdateCartogramRequest;
use App\Repositories\CartogramRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Cartogram;
use Flash;
use Response;
use Spatie\Browsershot\Browsershot;
use Image;
use PDF;
use App\Jobs\GenerateCartogram;
use Artisan;

class CartogramController extends AppBaseController
{
    /** @var  CartogramRepository */
    private $cartogramRepository;

    public function __construct(CartogramRepository $cartogramRepo)
    {
        $this->cartogramRepository = $cartogramRepo;
    }

    /**
     * Display a listing of the Cartogram.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $cartograms = Cartogram::whereStatus('completed')->get();

        return view('cartograms.index')
            ->with('cartograms', $cartograms);
    }

    /**
     * Show the form for creating a new Cartogram.
     *
     * @return Response
     */
    public function create()
    {
        return view('cartograms.create');
    }

    /**
     * Store a newly created Cartogram in storage.
     *
     * @param CreateCartogramRequest $request
     *
     * @return Response
     */
    public function store(CreateCartogramRequest $request)
    {
        $input = $request->all();

        $cartogram = $this->cartogramRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/cartograms.singular')]));

        return redirect(route('cartograms.index'));
    }

    /**
     * Display the specified Cartogram.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cartogram = $this->cartogramRepository->find($id);

        if (empty($cartogram)) {
            Flash::error(__('messages.not_found', ['model' => __('models/cartograms.singular')]));

            return redirect(route('cartograms.index'));
        }

        return view('cartograms.show')->with('cartogram', $cartogram);
    }

    /**
     * Show the form for editing the specified Cartogram.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cartogram = $this->cartogramRepository->find($id);

        if (empty($cartogram)) {
            Flash::error(__('messages.not_found', ['model' => __('models/cartograms.singular')]));

            return redirect(route('cartograms.index'));
        }

        return view('cartograms.edit')->with('cartogram', $cartogram);
    }

    /**
     * Update the specified Cartogram in storage.
     *
     * @param int $id
     * @param UpdateCartogramRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCartogramRequest $request)
    {
        $cartogram = $this->cartogramRepository->find($id);

        if (empty($cartogram)) {
            Flash::error(__('messages.not_found', ['model' => __('models/cartograms.singular')]));

            return redirect(route('cartograms.index'));
        }

        $cartogram = $this->cartogramRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/cartograms.singular')]));

        return redirect(route('cartograms.index'));
    }

    /**
     * Remove the specified Cartogram from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cartogram = $this->cartogramRepository->find($id);

        if (empty($cartogram)) {
            Flash::error(__('messages.not_found', ['model' => __('models/cartograms.singular')]));

            return redirect(route('cartograms.index'));
        }

        $this->cartogramRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/cartograms.singular')]));

        return redirect(route('cartograms.index'));
    }



    public function prepare(Request $request, $id) {
        // exec('php artisan cemex:cartogram ' . $id);

        $input = $request->all();

        $cartogram = Cartogram::with(['field', 'field.client'])->whereFieldId($id)->firstOrfail();
        $field = $cartogram->field;
        $client = $cartogram->field->client;

        $specialist = $input['specialist'];
        $date = $input['date'];
        
        // $full = false;
        $full = $input['full'];
        $no3 = $input['no3'];
        $salinity = $input['salinity'];

        // dd([$full, $no3, $salinity]);

        GenerateCartogram::dispatch($cartogram->id, $full, $specialist, $date, $no3, $salinity);

        return back();
    }


    public function download(Request $request, $id) {
        $cartogram = Cartogram::with(['field', 'field.client'])->whereFieldId($id)->firstOrfail();

        $zipname = public_path('docs/cartograms' . $cartogram->id . '.zip');

        header('Content-Type: application/zip');
        header("Content-Transfer-Encoding: Binary");
        header("Content-Disposition: attachment; filename=Cartograms-" . $cartogram->id . ".zip");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        header('Cache-Control: must-revalidate');
        header('Content-Length: ' . filesize($zipname));

        // download
        readfile($zipname);
    }




    // generate
    public function generate(Request $request, $id) {
        
        $cartogram = Cartogram::
            with(['field', 'field.polygon', 'field.polygon.points', 'field.polygon.points.sample'])
            ->whereId($id)
            ->firstOrfail();
        // dd($cartogram->toArray());

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
                return back();
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
        // console.log(points);
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

        // foreach ($values as $val) {
        //     $markerImgs[$val] = [];
        // }

        // dd($markerImgs);
        
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

                // Browsershot::
                //     url('https://mail.ru')
                //     // html($html)
                //     // ->setIncludePath('$PATH:/opt/homebrew/bin/')
                //     // ->setNodeBinary('C:\\Program Files\\nodejs\\')
                //     // ->setNpmBinary('C:\\Users\\sanchoman\\AppData\\Roaming\\')
                //     ->windowSize(100, 100)
                //     ->hideBackground()
                //     ->greyscale()
                //     // ->waitUntilNetworkIdle()
                //     // ->noSandbox()
                //     // ->usePipe()
                //     // ->ignoreHttpsErrors()
                //     // ->timeout(500)
                //     ->save(public_path($path));
            }

            $markerImgs[$list[$i]->id] = $markers;

            // dd($markerImgs);
        }

        // dd($markerImgs);

        $value = $request->has('value') ? $request->value : 'humus';

        return view('cartograms.map', compact('cartogram', 'markerImgs', 'value'));
    }






    private function _generateCartogram($value, $id, $input) {
        return 'cartogram' . $id . '-' . $value . '.pdf';
    }
}
