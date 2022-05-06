<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use App\Repositories\RegionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class RegionController extends AppBaseController
{
    /** @var  RegionRepository */
    private $regionRepository;

    public function __construct(RegionRepository $regionRepo)
    {
        $this->regionRepository = $regionRepo;
    }

    /**
     * Display a listing of the Region.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $regions = $this->regionRepository->all();

        return view('regions.index')
            ->with('regions', $regions);
    }

    /**
     * Show the form for creating a new Region.
     *
     * @return Response
     */
    public function create()
    {
        return view('regions.create');
    }

    /**
     * Store a newly created Region in storage.
     *
     * @param CreateRegionRequest $request
     *
     * @return Response
     */
    public function store(CreateRegionRequest $request)
    {
        $input = $request->all();

        $region = $this->regionRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/regions.singular')]));

        return redirect(route('regions.index'));
    }

    /**
     * Display the specified Region.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $region = $this->regionRepository->find($id);

        if (empty($region)) {
            Flash::error(__('messages.not_found', ['model' => __('models/regions.singular')]));

            return redirect(route('regions.index'));
        }

        return view('regions.show')->with('region', $region);
    }

    /**
     * Show the form for editing the specified Region.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $region = $this->regionRepository->find($id);

        if (empty($region)) {
            Flash::error(__('messages.not_found', ['model' => __('models/regions.singular')]));

            return redirect(route('regions.index'));
        }

        return view('regions.edit')->with('region', $region);
    }

    /**
     * Update the specified Region in storage.
     *
     * @param int $id
     * @param UpdateRegionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRegionRequest $request)
    {
        $region = $this->regionRepository->find($id);

        if (empty($region)) {
            Flash::error(__('messages.not_found', ['model' => __('models/regions.singular')]));

            return redirect(route('regions.index'));
        }

        $region = $this->regionRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/regions.singular')]));

        return redirect(route('regions.index'));
    }

    /**
     * Remove the specified Region from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $region = $this->regionRepository->find($id);

        if (empty($region)) {
            Flash::error(__('messages.not_found', ['model' => __('models/regions.singular')]));

            return redirect(route('regions.index'));
        }

        $this->regionRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/regions.singular')]));

        return redirect(route('regions.index'));
    }
}
