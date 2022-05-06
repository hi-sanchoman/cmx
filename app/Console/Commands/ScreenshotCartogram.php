<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Cartogram;
use Spatie\Browsershot\Browsershot;
use Image;
use PDF;

class ScreenshotCartogram extends Command
{
    
    protected $cartogramId;
    protected $full;
    protected $specialist;
    protected $date;
    protected $no3;
    protected $salinity;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cemex:cartogram {id} {--full}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate cartogram screenshot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
	parent::__construct();

        //$this->cartogramId = $this->argument('id');
        $this->full = '';
        $this->specialist = 'Баймуратов А.Ш.';
        $this->date = '2022-04-21';
        $this->no3 = 'no3';
        $this->salinity = 'salinity';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {		
    //     $id = $this->argument('id');
    //     $cartogram = Cartogram::find($id);

    //     if ($cartogram == null) {
    //         dd('no cartogram found');
    //     }

    //     $input = [
    //         'specialist' => 'Сарыбаева Г.М.',
    //         'date' => '18.11.2021',
    //         // 'value' => 'humus'
    //     ];

    //     $values = [];

    //     if ($this->option('full')) {
    //         $values = [
    //             'humus', 'ph',
    //             'no3', 'k', 
    //             's', 'p',
    //             'b', 'fe', 'na',
    //             'calcium', 'magnesium', 'absorbed_sum',
    //             'zn', 'cu', 'mn', 'salinity',
    //         ];    
    //     } else {
    //         // $values = [
    //         //     'humus', 'ph',
    //         //     'no3', 'k', 
    //         //     's', 'p',
    //         // ];

    //         $values = [
    //             'no3', 'no3_2', 
    //             'salinity', 'salinity_2',
    //         ];
    //     }

    //     try {
    //         foreach ($values as $value) {
    //             $this->_generateCartogram($value, $id, $input);
    //         }

    //         return true;
    //     } catch (\Exception $e) {
    //         dd($e->getMessage());

    //         return $e->getMessage();
    //     }
    // }


    // private function _generateCartogram($value, $id, $input) {
    //     // cartogram
    //     /*Browsershot::
    //         url('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/show-cartogram/' . $id . '/' . $value)
    //         ->setDelay(5000)
	// 		->windowSize(700, 675)
	// 		->noSandbox()
	// 		->timeout(120)
	// 		->save(public_path('img/map/cartograms/' . $id . '-' . $value . '2.png'));*/
		
	// 	exec('/usr/bin/google-chrome --headless --hide-scrollbars --window-size=700,675 --screenshot="' . public_path('img/map/cartograms/' . $id . '-' . $value . '.png') . '" "http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/show-cartogram/' . $id . '/' . $value . '" --no-sandbox --disable-gpu --disable-software-rasterizer');

    //     // legend
    //     /* if (!in_array($value, ['b', 'fe', 'na'])) {
    //         Browsershot::
    //             url('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/show-legend/' . $id . '/' . $value)
    //             ->setDelay(5000)
	// 			->windowSize(240, 210)
	// 			->noSandbox()
	// 			->timeout(120)
	// 			//->setNodeBinary('/usr/bin/node')
	// 			//->setNpmBinary('/usr/local/bin/npm')
    //             ->save(public_path('img/map/legends/' . $id . '-' . $value . '2.png'));
    //     }*/
		
	// 	exec('/usr/bin/google-chrome --headless --hide-scrollbars --window-size=240,210 --screenshot="' . public_path('img/map/legends/' . $id . '-' . $value . '.png') . '" "http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/show-legend/' . $id . '/' . $value . '" --no-sandbox --disable-gpu --disable-software-rasterizer');

    //     $cartogram = Cartogram::with(['field', 'field.client'])->whereId($id)->firstOrfail();
    //     $field = $cartogram->field;
    //     $client = $cartogram->field->client;

    //     $specialist = $input['specialist'];
    //     $date = $input['date'];

    //     $title = 'Карта содержания ';
    //     if ($value == 'humus') {
    //         $title .= 'органического вещества в почве';
    //     } else if ($value == 'ph') {
    //         $title .= 'pH почвы';
    //     } else if ($value == 'k') {
    //         $title .= 'подвижного калия в почве';
    //     } else if ($value == 'no3' || $value == 'no3_2') {
    //         $title .= 'нитратного азота в почве';
    //     } else if ($value == 'p') {
    //         $title .= 'подвижного фосфора в почве';
    //     } else if ($value == 's') {
    //         $title .= 'подвижной серы в почве';
    //     } else if ($value == 'b') {
    //         $title .= 'подвижного бора';
    //     } else if ($value == 'fe') {
    //         $title .= 'подвижного железа';
    //     } else if ($value == 'mn') {
    //         $title .= 'подвижного марганца';
    //     } else if ($value == 'cu') {
    //         $title .= 'подвижной меди';
    //     } else if ($value == 'zn') {
    //         $title .= 'подвижного цинка';
    //     } else if ($value == 'na') {
    //         $title .= 'обменного натрия';
    //     } else if ($value == 'calcium') {
    //         $title .= 'обменного кальция';
    //     } else if ($value == 'magnesium') {
    //         $title .= 'обменного магния';
    //     } else if ($value == 'salinity' || $value == 'salinity_2') {
    //         $title .= 'общей засоленности';
    //     } else if ($value == 'absorbed_sum') {
    //         $title .= 'суммы поглощенных оснований';
    //     }

    //     // TODO: title

    //     // dd([$client, $field, $cartogram]);

    //     $pdf = \PDF::loadView('cartograms._print.default', compact('cartogram', 'field', 'client', 'specialist', 'date', 'value', 'id', 'title'));
    //     $pdf->save(public_path('docs/cartogram' . $id . '-' . $value . '.pdf'));
        
	$this->cartogramId = $this->argument('id');

    $cartogram = Cartogram::with(['field', 'field.client'])->findOrFail($this->cartogramId);
    $cartogram->status = 'pending';
    $cartogram->access_url = '';
    $cartogram->save();

    $field = $cartogram->field;
    $client = $cartogram->field->client;

    $specialist = $this->specialist;
    $date = $this->date;
    
    // $full = false;
    $full = $this->full;
    $no3 = $this->no3;
    $salinity = $this->salinity;

    // dd([$full, $no3, $salinity]);

    $values = [];

    if ($full == 'full') {
        $values = [
            'humus', 'ph',
            $no3, 'k', 
            's', 'p',
            'b', 'fe', 'na',
            'calcium', 'magnesium', 'absorbed_sum',
            'zn', 'cu', 'mn', $salinity,
        ];    
    } else {
        $values = [
            'humus', 'ph',
            $no3, 'k', 
            's', 'p',
        ];
    }

    
    $input = [
        'specialist' => $specialist,
        'date' => $date,
    ];

    $pdfs = [];

    $filename = 'docs/cartograms' . $cartogram->id . '-' . intval(microtime(true)) . '.zip';
    $zipname = public_path($filename);
    $zip = new \ZipArchive();      
    $zip->open($zipname, \ZipArchive::CREATE);
    
    try {
        foreach ($values as $value) {
            $pdfs[] = [
                'name' => $this->_generateCartogram($value, $cartogram->id, $input),
                'value' => $value,
            ];
        }

        // $pdf->download('Cartogram-' . $id . '-' . $value . '.pdf');
        // dd($pdfs);

        foreach ($pdfs as $pdf) {
            // $tmp = file_get_contents(public_path('docs/' . $pdf['name']));
            $zip->addFile(public_path('docs/' . $pdf['name']), $pdf['name']);
        }
        // return $pdfs[0]->download('Cartogram.pdf');
    } catch (\Exception $e) {
        dd($e->getMessage());

        return $e->getMessage();
    }

    $zip->close();

    $cartogram->status = 'completed';
    $cartogram->access_url = $filename; 
    $cartogram->save();
}

private function _generateCartogram($value, $id, $input) {
    // cartogram
    $cmd = '/usr/bin/node ' . public_path('screenshot.js') . ' ' . $id . ' ' . $value;
    //dd($cmd);
    exec($cmd);

    // legend
    exec('/usr/bin/google-chrome --headless --hide-scrollbars --window-size=240,210 --screenshot="' . public_path('img/map/legends/' . $id . '-' . $value . '.png') . '" "http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/show-legend/' . $id . '/' . $value . '" --no-sandbox --disable-gpu --disable-software-rasterizer');

    $cartogram = Cartogram::with(['field', 'field.client'])->whereId($id)->firstOrfail();
    $field = $cartogram->field;
    $client = $cartogram->field->client;

    $specialist = $input['specialist'];
    $date = $input['date'];

    $title = 'Карта содержания ';
    if ($value == 'humus') {
        $title .= 'органического вещества в почве';
    } else if ($value == 'ph') {
        $title .= 'pH почвы';
    } else if ($value == 'k') {
        $title .= 'подвижного калия в почве';
    } else if ($value == 'no3' || $value == 'no3_2') {
        $title .= 'нитратного азота в почве';
    } else if ($value == 'p') {
        $title .= 'подвижного фосфора в почве';
    } else if ($value == 's') {
        $title .= 'подвижной серы в почве';
    } else if ($value == 'b') {
        $title .= 'подвижного бора';
    } else if ($value == 'fe') {
        $title .= 'подвижного железа';
    } else if ($value == 'mn') {
        $title .= 'подвижного марганца';
    } else if ($value == 'cu') {
        $title .= 'подвижной меди';
    } else if ($value == 'zn') {
        $title .= 'подвижного цинка';
    } else if ($value == 'na') {
        $title .= 'обменного натрия';
    } else if ($value == 'calcium') {
        $title .= 'обменного кальция';
    } else if ($value == 'magnesium') {
        $title .= 'обменного магния';
    } else if ($value == 'salinity' || $value == 'salinity_2') {
        $title .= 'общей засоленности';
    } else if ($value == 'absorbed_sum') {
        $title .= 'суммы поглощенных оснований';
    }

    // dd([$client, $field, $cartogram]);
    $pdfName = 'cartogram' . $id . '-' . $value . '-' . intval(microtime(true)) . '.pdf';
    $pdf = \PDF::loadView('cartograms._print.default', compact('cartogram', 'field', 'client', 'specialist', 'date', 'value', 'id', 'title'));
    $pdf->save(public_path('docs/' . $pdfName));
    // dd($pdf);

    return $pdfName;
}

}

