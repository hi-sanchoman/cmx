<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePathRequest;
use App\Http\Requests\UpdatePathRequest;
use App\Repositories\PathRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Flash;
use App\Models\Path;

class PathController extends AppBaseController
{
    /** @var  PathRepository */
    private $pathRepository;

    public function __construct(PathRepository $pathRepo)
    {
        $this->pathRepository = $pathRepo;
    }

    /**
     * Display a listing of the Path.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $paths = Path::orderBy('created_at', 'DESC')->get();;
        // dd($paths->toArray());

        return view('paths.index', compact('paths'));
    }

    /**
     * Show the form for creating a new Path.
     *
     * @return Response
     */
    public function create()
    {
        return view('paths.create');
    }

    /**
     * Store a newly created Path in storage.
     *
     * @param CreatePathRequest $request
     *
     * @return Response
     */
    public function store(CreatePathRequest $request)
    {
        $input = $request->all();

        $path = $this->pathRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/paths.singular')]));

        return redirect(route('paths.index'));
    }

    /**
     * Display the specified Path.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $path = Path::findOrFail($id);

        $arr = json_decode($path->path);
        // dd($arr);
        
        $totalPath = '';
        foreach ($arr as $item) {
            $totalPath .= $item[1] . ',' . $item[0] . ',0 ';
        }

        $kml = view('paths.kml', compact('path', 'totalPath'))->render();
        // dd($kml);

        $response = Response::create($kml, 200);
        $response->header('Content-Type', 'text/xml');
        $response->header('Cache-Control', 'public');
        $response->header('Content-Description', 'File Transfer');
        $response->header('Content-Disposition', 'attachment; filename=Path.kml');
        $response->header('Content-Transfer-Encoding', 'binary');
        
        return $response;
    }

    /**
     * Show the form for editing the specified Path.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $path = $this->pathRepository->find($id);

        if (empty($path)) {
            Flash::error(__('messages.not_found', ['model' => __('models/paths.singular')]));

            return redirect(route('paths.index'));
        }

        return view('paths.edit')->with('path', $path);
    }

    /**
     * Update the specified Path in storage.
     *
     * @param int $id
     * @param UpdatePathRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePathRequest $request)
    {
        $path = $this->pathRepository->find($id);

        if (empty($path)) {
            Flash::error(__('messages.not_found', ['model' => __('models/paths.singular')]));

            return redirect(route('paths.index'));
        }

        $path = $this->pathRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/paths.singular')]));

        return redirect(route('paths.index'));
    }

    /**
     * Remove the specified Path from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $path = $this->pathRepository->find($id);

        if (empty($path)) {
            Flash::error(__('messages.not_found', ['model' => __('models/paths.singular')]));

            return redirect(route('paths.index'));
        }

        $this->pathRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/paths.singular')]));

        return redirect(route('paths.index'));
    }

    // Save Wialon route
    public function save(Request $request) {
        $input = $request->all();
        // dd($input);

        $path = Path::create([
            'unit' => $input['unit'],
            'date_started' => $input['date_start'],
            'date_completed' => $input['date_end'],
            'path' => $input['path'],
        ]);

        if ($path != null) {
            return 1;
        }
    }
}
