<?php

namespace Modules\Comment\Http\Controllers\v1\Panel;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Comment\Http\Requests\Panel\ScoreModelRequest;
use Modules\Comment\Repository\ScoreModelRepositoryInterface;
use Modules\Comment\Transformers\Panel\ScoreModelResource;

class ScoreModelController extends Controller
{
    private $scoreModelRepo;

    public function __construct(ScoreModelRepositoryInterface $scoreModelRepo)
    {
        $this->scoreModelRepo = $scoreModelRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $states = $this->scoreModelRepo->all();
        return ScoreModelResource::collection($states);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ScoreModelRequest $request)
    {

        $data = [
            'title' => $request->title,
            'status' => $request->status,
            'category_id' => $request->category_id,
        ];

        $this->scoreModelRepo->create($data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $state = $this->scoreModelRepo->show($id);
        return new ScoreModelResource($state);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ScoreModelRequest $request, $id)
    {

        $data = [
            'title' => $request->title,
            'status' => $request->status,
            'category_id' => $request->category_id,
        ];

        $this->scoreModelRepo->update($id, $data);

        ApiService::_success(trans('response.responses.200'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $state = $this->scoreModelRepo->delete($id);
        ApiService::_success(trans('response.responses.200'));
    }
}
