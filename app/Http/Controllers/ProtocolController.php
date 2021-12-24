<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProtocolRequest;
use App\Http\Requests\UpdateProtocolRequest;
use App\Repositories\ProtocolRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\Client;
use App\Models\Protocol;
use App\Models\Sample;
use App\Models\Field;
use Carbon\Carbon;

class ProtocolController extends AppBaseController
{
    /** @var  ProtocolRepository */
    private $protocolRepository;

    public function __construct(ProtocolRepository $protocolRepo)
    {
        $this->protocolRepository = $protocolRepo;
    }

    /**
     * Display a listing of the Protocol.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $protocols = Protocol::with(['client'])->orderBy('client_id', 'ASC')->get();

        return view('protocols.index')
            ->with('protocols', $protocols);
    }

    /**
     * Show the form for creating a new Protocol.
     *
     * @return Response
     */
    public function create()
    {
        return view('protocols.create');
    }

    /**
     * Store a newly created Protocol in storage.
     *
     * @param CreateProtocolRequest $request
     *
     * @return Response
     */
    public function store(CreateProtocolRequest $request)
    {
        $input = $request->all();

        $protocol = $this->protocolRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/protocols.singular')]));

        return redirect(route('protocols.index'));
    }

    /**
     * Display the specified Protocol.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $protocol = $this->protocolRepository->find($id);

        if (empty($protocol)) {
            Flash::error(__('messages.not_found', ['model' => __('models/protocols.singular')]));

            return redirect(route('protocols.index'));
        }

        return view('protocols.show')->with('protocol', $protocol);
    }

    /**
     * Show the form for editing the specified Protocol.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $protocol = $this->protocolRepository->find($id);

        if (empty($protocol)) {
            Flash::error(__('messages.not_found', ['model' => __('models/protocols.singular')]));

            return redirect(route('protocols.index'));
        }

        return view('protocols.edit')->with('protocol', $protocol);
    }

    /**
     * Update the specified Protocol in storage.
     *
     * @param int $id
     * @param UpdateProtocolRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProtocolRequest $request)
    {
        $protocol = $this->protocolRepository->find($id);

        if (empty($protocol)) {
            Flash::error(__('messages.not_found', ['model' => __('models/protocols.singular')]));

            return redirect(route('protocols.index'));
        }

        $protocol = $this->protocolRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/protocols.singular')]));

        return redirect(route('protocols.index'));
    }

    /**
     * Remove the specified Protocol from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $protocol = $this->protocolRepository->find($id);

        if (empty($protocol)) {
            Flash::error(__('messages.not_found', ['model' => __('models/protocols.singular')]));

            return redirect(route('protocols.index'));
        }

        $this->protocolRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/protocols.singular')]));

        return redirect(route('protocols.index'));
    }



    public function redirect(Request $request) {
        return redirect('/clients/' . $request->client_id . '/protocol');
    }



    public function prepare($id, $fieldId) {
        $protocol = Protocol::with(['client', 'client.fields'])->whereId($id)->firstOrfail();
        $client = $protocol->client;

        $field = Field::with(['polygon', 'polygon.points'])->whereId($fieldId)->firstOrfail();
        $points = $field->polygon->points;

        $pointsIds = [];
        foreach ($points as $point) {
            $pointsIds[] = $point->id;
        }

        $samples = Sample::whereIn('point_id', $pointsIds)->get();
        // dd($samples->toArray());

        if ($samples == null) {
            // no samples yet
            Flash::error('Не готовы результаты испытаний');
            return redirect()->route('protocols.index');
        }

        $firstSample = $samples->first();
        if ($firstSample->date_started == null || $firstSample->date_completed == null || $firstSample->result == null) {
            Flash::error('Не готовы результаты испытаний');
            return redirect()->route('protocols.index');
        }

        // dd($samples->first()->date_selected->format('m/d/Y'));

        return view('protocols.prepare', compact('protocol', 'client', 'field', 'points', 'samples'));
    }

    public function preparePost(Request $request) {
        $input = $request->all();
        // dd($input);

        return redirect('/protocols/' . $input['protocol_id'] . '/' . $input['field_id'] . '/prepare');
    }



    public function generateForClient(Request $request, $id) {
        $input = $request->all();
        // dd($input);

        $client = Client::with(['fields', 'fields.polygon', 'fields.polygon.points'])->findOrFail($id);
        $now = Carbon::now();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(11);
        $phpWord->setDefaultParagraphStyle(['spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0), 'spacing' => 120, 'lineHeight' => 1.2,]);

        $phpWord->addFontStyle('h1', ['bold' => true, 'size' => 14]);
        $phpWord->addFontStyle('default', ['bold' => false, 'size' => 11]);
        $phpWord->addFontStyle('bold', ['bold' => true, 'size' => 11]);
        $phpWord->addFontStyle('top', ['bold' => false, 'size' => 11]);
        $phpWord->addFontStyle('top_bold', ['bold' => true, 'size' => 11]);
        $phpWord->addFontStyle('bottom', ['bold' => false, 'size' => 9]);
        $phpWord->addFontStyle('footer', ['bold' => false, 'size' => 10]);

        $phpWord->addParagraphStyle('center', ['align' => 'center', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0), 'spacing' => 120, 'lineHeight' => 1,]);

        $phpWord->addParagraphStyle('left', ['align' => 'left', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0), 'spacing' => 120, 'lineHeight' => 1,]);

        $phpWord->addParagraphStyle('right', ['align' => 'right', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0), 'spacing' => 120, 'lineHeight' => 1,]);
        
        // new section
        $section = $phpWord->addSection([
            'marginLeft' => 900, 
            'marginRight' => 600,
            'marginTop' => 300,
            'marginBottom' => 300
        ]);

        $fields = [];
        foreach ($client->fields as $field) {
            $fields[] = $field;
        }
        
        // header
        $this->_generateHeader($section, $input, $fields);

        // loop of results for each Point's sample
        foreach ($client->fields as $field) {
            $this->_generateForField($section, $field, $client, $input);
        }

        // footer
        $this->_generateFooter($section, $input);
    
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $filename = 'protocol_client_' . $client->id . '.docx';
        $objWriter->save('docs/' . $filename);

        return response()->download(public_path('docs/' . $filename), 'Протокол общий ' . $client->khname . '.docx');
    }




    public function generate(Request $request, $id) {
        $input = $request->all();
        // dd($input);

        $protocol = Protocol::with(['client', 'client.fields'])->whereId($id)->firstOrfail();
        $client = $protocol->client;
        $field = Field::whereId($input['field_id'])->firstOrfail();

        $now = Carbon::now();

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(11);
        $phpWord->setDefaultParagraphStyle(['spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0), 'spacing' => 120, 'lineHeight' => 1.2,]);

        $phpWord->addFontStyle('h1', ['bold' => true, 'size' => 14]);
        $phpWord->addFontStyle('default', ['bold' => false, 'size' => 11]);
        $phpWord->addFontStyle('bold', ['bold' => true, 'size' => 11]);
        $phpWord->addFontStyle('top', ['bold' => false, 'size' => 11]);
        $phpWord->addFontStyle('top_bold', ['bold' => true, 'size' => 11]);
        $phpWord->addFontStyle('bottom', ['bold' => false, 'size' => 9]);
        $phpWord->addFontStyle('footer', ['bold' => false, 'size' => 10]);

        $phpWord->addParagraphStyle('center', ['align' => 'center', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0), 'spacing' => 120, 'lineHeight' => 1,]);

        $phpWord->addParagraphStyle('left', ['align' => 'left', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0), 'spacing' => 120, 'lineHeight' => 1,]);

        $phpWord->addParagraphStyle('right', ['align' => 'right', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(0), 'spacing' => 120, 'lineHeight' => 1,]);
        
        // cell style
        $cellStyle = ['unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'valign' => 'center'];

        // new section
        $section = $phpWord->addSection([
            'marginLeft' => 900, 
            'marginRight' => 600,
            'marginTop' => 300,
            'marginBottom' => 300
        ]);

        // header
        $this->_generateHeader($section, $input, $field);

        // loop of results for each Point's sample
        $this->_generateForField($section, $field, $client, $input);

        // footer
        $this->_generateFooter($section, $input);
    
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $filename = 'protocol_' . $protocol->id . '.docx';
        $objWriter->save('docs/' . $filename);

        return response()->download(public_path('docs/' . $filename), 'Протокол ' . $client->khname . ' - Поле №' . $field->num . '.docx');
    }


    private function _generateHeader($section, $input, $field) {
        $pointsIds = [];        

        if (is_array($field)) {
            foreach ($field as $f) {
                foreach ($f->polygon->points as $point) {
                    if ($point == null) continue;
                    $pointsIds[] = $point->id;
                }  
            }
        } else {
            foreach ($field->polygon->points as $point) {
                if ($point == null) continue;
                $pointsIds[] = $point->id;
            }    
        }

        
        // get samples
        $samples = Sample::with(['point', 'result'])->whereIn('point_id', $pointsIds)->orderBy('num', 'ASC')->get();

        $datesSelected = [];
        $datesReceived = [];

        foreach ($samples as $sample) {
            $selected = Carbon::createFromFormat('Y-m-d', $sample->date_selected->format('Y-m-d'))->format('d.m.Y');
            $received = Carbon::createFromFormat('Y-m-d', $sample->date_received->format('Y-m-d'))->format('d.m.Y');

            if (!isset($datesSelected[$selected]))
                $datesSelected[$selected] = $selected;

            if (!isset($datesReceived[$received]))
                $datesReceived[$received] = $received; 
        }

        // dd([$datesSelected, $datesReceived]);

        // header start
        $header = $section->createHeader();
        $header->addImage(public_path('img/protocol_bg.jpg'), [
            'width'         => 530,
            'height'        => 30,
            'marginTop'     => -1,
            'marginLeft'    => -1,
            'wrappingStyle' => 'behind'
        ]); 

        // top
        $section->addText();$section->addText();
        $table = $section->addTable(['unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'width' => 100 * 50]);
        $table->addRow();
        
        // image        
        $leftCell = $table->addCell(20 * 50, ['unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT]);
        $leftCell->addImage(public_path('img/protocol_image_left.png', ['width' => 3, 'height' => 3]));

        // texts
        $middleCell = $table->addCell();
        $middleCell->addText('Испытательная лаборатория', 'top_bold', 'center');
        $middleCell->addText('ТОО "CemEX Engineering"', 'top_bold', 'center');
        $middleCell->addText('РК, г. Алматы, ул. Аль-Фараби, д. 30Б, оф. 61', 'top', 'center');
        $middleCell->addText('e-mail: office@cemex.kz, lab@cemex.kz', 'top', 'center');
        $middleCell->addText('Аттестат аккредитации KZ.T.02.2272', 'top', 'center');
        $middleCell->addText('от 14 октября 2019 г.', 'top', 'center');

        // logo
        $rightCell = $table->addCell(20 * 50, ['unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT]);
        $rightCell->addImage(public_path('img/protocol_image_right.jpg', ['width' => 50, 'height' => 50, 'align' => 'center']));

        // title
        $section->addText();$section->addText();
        $section->addText('ПРОТОКОЛ ИСПЫТАНИЙ ПРОБ ПОЧВЫ', 'h1', 'center');
        $section->addText('№' . $input['num'] . '/П от ' . Carbon::createFromFormat('Y-m-d', $input['protocol_date'])->format('d.m.Y'), 'h1', 'center');
        $section->addText();

        // list of params
        $section->addText('1. Наименование, адрес заказчика - ' . $input['client_khname'] . ';', ['lineHeight' => 1]);
        $section->addText('2. Объект испытания - ' . $input['object'] . ';', ['lineHeight' => 1]);
        
        $actStr = '';
        if ($input['act_num'] != null) {
            $actStr = 'Акт отбора №' . $input['act_num'];
        }

        // $section->addText('3. Дата отбора - ' . Carbon::createFromFormat('Y-m-d', $input['date_selected'])->format('d.m.Y') . ';' . $actStr);
        $section->addText('3. Дата отбора - ' . implode(',', $datesSelected) . ';' . $actStr);
        

        $section->addText('4. НД на метод отбора - ' . $input['nd_method'] . ';');
        $section->addText('5. Место отбора проб - ' . $input['field_address'] . ';');
        $section->addText('6. Количество проб - ' . $input['points_num'] . ';');
        
        // $section->addText('7. Дата поступления проб на испытание - ' . Carbon::createFromFormat('Y-m-d', $input['date_received'])->format('d.m.Y') . ';');
        $section->addText('7. Дата поступления проб на испытание - ' . implode(',', $datesReceived) . ';');
        
        
        $section->addText('8. Дата проведения испытаний - ' . Carbon::createFromFormat('Y-m-d', $input['date_started'])->format('d.m.Y') . ' - ' . Carbon::createFromFormat('Y-m-d', $input['date_completed'])->format('d.m.Y') . ';');
        
        $section->addText('9. Место проведения испытаний - ' . $input['cemex_address'] . ';');
        $section->addText('10. НД на продукцию - ' . $input['nd_product'] . ';');
        $section->addText('11. Цель - ' . $input['goal'] . ';');
        $section->addText('12. Условия проведения испытаний: ' . $input['conditions']);
    }


    private function _generateFooter($section, $input) {
        $section->addText();
        $section->addText();

        $section->addText('Исполнитель: ');
        $section->addText('Специалист    _________________________________  Сарыбаева Г.М.', 'default', 'center');
        $section->addText('Заведущая лабораторией    _____________________  Даулетова М.Д.', 'default', 'center');
        

        $section->addText();
        $section->addText();
        $section->addText();
        $section->addText();
        $section->addText("Протокол распространяется только на образцы (пробы) подвергнутые испытаниям. Частичная или полная перепечатка протокола запрещена.\nКопии протокола без печати ИЛ ТОО 'CemEX Engineering' не действительны.", 'bottom', 'center');

        // footer
        $footer = $section->createFooter();
        $table = $footer->addTable(['unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'width' => 100 * 50]);
        $table->addRow();
        $table->addCell()->addText('Протокол испытаний проб почвы №' . $input['num'] . '/П от ' . Carbon::createFromFormat('Y-m-d', $input['protocol_date'])->format('d.m.Y'), 'footer', 'left');

        $table->addCell()->addPreserveText("Всего страниц: {NUMPAGES}. Страница {PAGE} из {NUMPAGES}", 'footer', 'right');
    }



    private function _generateForField($section, $field, $client, $input) {
        // cell style
        $cellStyle = ['unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT, 'valign' => 'center'];

        $pointsIds = [];        

        foreach ($field->polygon->points as $point) {
            if ($point == null) continue;
            $pointsIds[] = $point->id;
        }

        $samples = Sample::with(['point', 'result'])->whereIn('point_id', $pointsIds)->orderBy('num', 'ASC')->get();

        foreach ($samples as $sample) {                
            if ($sample == null) continue;

            $result = $sample->result;
            if ($result == null) continue;

            // if ($result->humus == null) continue;

            $year = '';
            if ($sample->date_completed != null) {
                $year = $sample->date_completed->format('Y');
            }

            $section->addText();$section->addText();
            $section->addText('Проба почвы - ' . $sample->num . '/П/' . $client->num . '/' . $year . ' (метка ' . $sample->point->num . ', ' . $field->cadnum . ', ' . $field->square . ' га)', 'bold');

            $table = $section->addTable(['borderSize' => 1, 'borderColor' => '000000']);
            $table->addRow();
            $table->addCell(40 * 50, $cellStyle)->addText('Наименование показателей', 'bold', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText('Результаты испытаний', 'bold', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText('НД на метод испытания', 'bold', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText('Содержание питательного элемента в почве, ПДК мг/кг (для микроэлементов)', 'bold', 'center');

            

            // humus
            $table->addRow();
            $table->addCell()->addText('Органические вещества (гумус), %');
            $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->humus), 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 26213-91', 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateHumus($result->humus), 'default', 'center');

            // ph
            $table->addRow();
            $table->addCell()->addText('Водородный показатель, pH');
            $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->ph), 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 26423-85', 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduatePh($result->ph), 'default', 'center');

            // no3
            $table->addRow();
            $table->addCell()->addText('Азот нитратный (NO3), мг/кг');
            $table->addCell()->addText($this->_format($result->no3), 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 26423-85', 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateNo3($result->no3), 'default', 'center');

            // p
            $table->addRow();
            $table->addCell()->addText('Фосфор (P) подвижный, мг/кг');
            $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->p), 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 26205-91', 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateP($result->p), 'default', 'center');

            // k
            $table->addRow();
            $table->addCell()->addText('Калий (К) подвижный, мг/кг');
            $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->k), 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 26205-91', 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateK($result->k), 'default', 'center');

            // s
            $table->addRow();
            $table->addCell()->addText('Сера (S) подвижная, мг/кг');
            $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->s), 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 26490-85', 'default', 'center');
            $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateS($result->s), 'default', 'center');

            if ($input['quantity'] == 'full') {
                // b
                $table->addRow();
                $table->addCell()->addText('Бор (B) подвижный, мг/кг');
                $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->b), 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ Р 50688-94', 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('-', 'default', 'center');

                // fe
                $table->addRow();
                $table->addCell()->addText('Железо (Fe) подвижное, мг/кг');
                $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->fe), 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 27395-87', 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('-', 'default', 'center');

                // mn
                $table->addRow();
                $table->addCell()->addText('Марганец (Mn) подвижный, мг/кг');
                $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->mn), 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ Р 50685-94', 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateMn($result->mn), 'default', 'center');

                // cu
                $table->addRow();
                $table->addCell()->addText('Медь (Cu) подвижная, мг/кг');
                $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->cu), 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('СТ РК ГОСТ Р 50683-2008', 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateCu($result->cu), 'default', 'center');

                // zn
                $table->addRow();
                $table->addCell()->addText('Цинк (Zn) подвижный, мг/кг');
                $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->zn), 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ Р 50686-94', 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateZn($result->zn), 'default', 'center');

                // na
                $table->addRow();
                $table->addCell()->addText('Натрий (Na) обменный, ммоль/100 г');
                $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->na), 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 26950-86', 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('-', 'default', 'center');

                // calcium
                $table->addRow();
                $table->addCell()->addText('Кальций обменный, ммоль/100 г');
                $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->calcium), 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 26428-85', 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateCalcium($result->calcium), 'default', 'center');

                // magnesium
                $table->addRow();
                $table->addCell()->addText('Магний обменный, ммоль/100 г');
                $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->magnesium), 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 26428-85', 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateMagnesium($result->magnesium), 'default', 'center');

                // salinity
                $table->addRow();
                $table->addCell()->addText('Общая засоленность, мСм/см');
                $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->salinity), 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 27753.4-88', 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateSalinity($result->salinity), 'default', 'center');

                // absorbed_sum
                $table->addRow();
                $table->addCell()->addText('Сумма поглощенных оснований, ммоль/100 г');
                $table->addCell(20 * 50, $cellStyle)->addText($this->_format($result->absorbed_sum), 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText('ГОСТ 27821-88', 'default', 'center');
                $table->addCell(20 * 50, $cellStyle)->addText(Sample::graduateAbsorbedSum($result->absorbed_sum), 'default', 'center');
            }
        }
    }



    private function _format($value) {
        if ($value < 0) {
            return 'н.о.';
        }

        return round($value * 10000) / 10000;
    }
}
