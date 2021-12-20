<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;
use App\Models\Cartogram;
use PDF;

class ScreenshotCartogram extends Command
{
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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {		
        $id = $this->argument('id');
        $cartogram = Cartogram::find($id);

        if ($cartogram == null) {
            dd('no cartogram found');
        }

        $input = [
            'specialist' => 'Сарыбаева Г.М.',
            'date' => '18.11.2021',
            // 'value' => 'humus'
        ];

        $values = [];

        if ($this->option('full')) {
            $values = [
                'humus', 'ph',
                'no3', 'k', 
                's', 'p',
                'b', 'fe', 'na',
                'calcium', 'magnesium', 'absorbed_sum',
                'zn', 'cu', 'mn', 'salinity',
            ];    
        } else {
            // $values = [
            //     'humus', 'ph',
            //     'no3', 'k', 
            //     's', 'p',
            // ];

            $values = [
                'no3', 'no3_2', 
                'salinity', 'salinity_2',
            ];
        }

        try {
            foreach ($values as $value) {
                $this->_generateCartogram($value, $id, $input);
            }

            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());

            return $e->getMessage();
        }
    }


    private function _generateCartogram($value, $id, $input) {
        // cartogram
        /*Browsershot::
            url('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/show-cartogram/' . $id . '/' . $value)
            ->setDelay(5000)
			->windowSize(700, 675)
			->noSandbox()
			->timeout(120)
			->save(public_path('img/map/cartograms/' . $id . '-' . $value . '2.png'));*/
		
		exec('/usr/bin/google-chrome --headless --hide-scrollbars --window-size=700,675 --screenshot="' . public_path('img/map/cartograms/' . $id . '-' . $value . '.png') . '" "http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/show-cartogram/' . $id . '/' . $value . '" --no-sandbox --disable-gpu --disable-software-rasterizer');

        // legend
        /* if (!in_array($value, ['b', 'fe', 'na'])) {
            Browsershot::
                url('http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/show-legend/' . $id . '/' . $value)
                ->setDelay(5000)
				->windowSize(240, 210)
				->noSandbox()
				->timeout(120)
				//->setNodeBinary('/usr/bin/node')
				//->setNpmBinary('/usr/local/bin/npm')
                ->save(public_path('img/map/legends/' . $id . '-' . $value . '2.png'));
        }*/
		
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

        // TODO: title

        // dd([$client, $field, $cartogram]);

        $pdf = \PDF::loadView('cartograms._print.default', compact('cartogram', 'field', 'client', 'specialist', 'date', 'value', 'id', 'title'));
        $pdf->save(public_path('docs/cartogram' . $id . '-' . $value . '.pdf'));
    }
}
