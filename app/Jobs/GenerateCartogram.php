<?php

namespace App\Jobs;

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

class GenerateCartogram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $cartogramId;
    protected $full;
    protected $specialist;
    protected $date;
    protected $no3;
    protected $salinity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cartogramId, $full, $specialist, $date, $no3, $salinity)
    {
        $this->cartogramId = $cartogramId;
        $this->full = $full;
        $this->specialist = $specialist;
        $this->date = $date;
        $this->no3 = $no3;
        $this->salinity = $salinity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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
