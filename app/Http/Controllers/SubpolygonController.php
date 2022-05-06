<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubpolygonRequest;
use App\Http\Requests\UpdateSubpolygonRequest;
use App\Repositories\SubpolygonRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SubpolygonController extends AppBaseController
{
    /** @var  SubpolygonRepository */
    private $subpolygonRepository;

    public function __construct(SubpolygonRepository $subpolygonRepo)
    {
        $this->subpolygonRepository = $subpolygonRepo;
    }

    /**
     * Display a listing of the Subpolygon.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $subpolygons = $this->subpolygonRepository->all();

        return view('subpolygons.index')
            ->with('subpolygons', $subpolygons);
    }

    /**
     * Show the form for creating a new Subpolygon.
     *
     * @return Response
     */
    public function create()
    {
        return view('subpolygons.create');
    }

    /**
     * Store a newly created Subpolygon in storage.
     *
     * @param CreateSubpolygonRequest $request
     *
     * @return Response
     */
    public function store(CreateSubpolygonRequest $request)
    {
        $input = $request->all();

        $subpolygon = $this->subpolygonRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/subpolygons.singular')]));

        return redirect(route('subpolygons.index'));
    }

    /**
     * Display the specified Subpolygon.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $subpolygon = $this->subpolygonRepository->find($id);

        if (empty($subpolygon)) {
            Flash::error(__('messages.not_found', ['model' => __('models/subpolygons.singular')]));

            return redirect(route('subpolygons.index'));
        }

        return view('subpolygons.show')->with('subpolygon', $subpolygon);
    }

    /**
     * Show the form for editing the specified Subpolygon.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $subpolygon = $this->subpolygonRepository->find($id);

        if (empty($subpolygon)) {
            Flash::error(__('messages.not_found', ['model' => __('models/subpolygons.singular')]));

            return redirect(route('subpolygons.index'));
        }

        return view('subpolygons.edit')->with('subpolygon', $subpolygon);
    }

    /**
     * Update the specified Subpolygon in storage.
     *
     * @param int $id
     * @param UpdateSubpolygonRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSubpolygonRequest $request)
    {
        $subpolygon = $this->subpolygonRepository->find($id);

        if (empty($subpolygon)) {
            Flash::error(__('messages.not_found', ['model' => __('models/subpolygons.singular')]));

            return redirect(route('subpolygons.index'));
        }

        $subpolygon = $this->subpolygonRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/subpolygons.singular')]));

        return redirect(route('subpolygons.index'));
    }

    /**
     * Remove the specified Subpolygon from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $subpolygon = $this->subpolygonRepository->find($id);

        if (empty($subpolygon)) {
            Flash::error(__('messages.not_found', ['model' => __('models/subpolygons.singular')]));

            return redirect(route('subpolygons.index'));
        }

        $this->subpolygonRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/subpolygons.singular')]));

        return redirect(route('subpolygons.index'));
    }
}
