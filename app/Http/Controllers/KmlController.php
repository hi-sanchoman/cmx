<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateKmlRequest;
use App\Http\Requests\UpdateKmlRequest;
use App\Repositories\KmlRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class KmlController extends AppBaseController
{
    /** @var  KmlRepository */
    private $kmlRepository;

    public function __construct(KmlRepository $kmlRepo)
    {
        $this->kmlRepository = $kmlRepo;
    }

    /**
     * Display a listing of the Kml.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $kmls = $this->kmlRepository->all();

        return view('kmls.index')
            ->with('kmls', $kmls);
    }

    /**
     * Show the form for creating a new Kml.
     *
     * @return Response
     */
    public function create()
    {
        return view('kmls.create');
    }

    /**
     * Store a newly created Kml in storage.
     *
     * @param CreateKmlRequest $request
     *
     * @return Response
     */
    public function store(CreateKmlRequest $request)
    {
        $input = $request->all();

        $kml = $this->kmlRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/kmls.singular')]));

        return redirect(route('kmls.index'));
    }

    /**
     * Display the specified Kml.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $kml = $this->kmlRepository->find($id);

        if (empty($kml)) {
            Flash::error(__('messages.not_found', ['model' => __('models/kmls.singular')]));

            return redirect(route('kmls.index'));
        }

        return view('kmls.show')->with('kml', $kml);
    }

    /**
     * Show the form for editing the specified Kml.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $kml = $this->kmlRepository->find($id);

        if (empty($kml)) {
            Flash::error(__('messages.not_found', ['model' => __('models/kmls.singular')]));

            return redirect(route('kmls.index'));
        }

        return view('kmls.edit')->with('kml', $kml);
    }

    /**
     * Update the specified Kml in storage.
     *
     * @param int $id
     * @param UpdateKmlRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateKmlRequest $request)
    {
        $kml = $this->kmlRepository->find($id);

        if (empty($kml)) {
            Flash::error(__('messages.not_found', ['model' => __('models/kmls.singular')]));

            return redirect(route('kmls.index'));
        }

        $kml = $this->kmlRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/kmls.singular')]));

        return redirect(route('kmls.index'));
    }

    /**
     * Remove the specified Kml from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $kml = $this->kmlRepository->find($id);

        if (empty($kml)) {
            Flash::error(__('messages.not_found', ['model' => __('models/kmls.singular')]));

            return redirect(route('kmls.index'));
        }

        $this->kmlRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/kmls.singular')]));

        return redirect(route('kmls.index'));
    }
}
