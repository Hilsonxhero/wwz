<?php

namespace Modules\Comment\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Entities\ScoreModel;
use Modules\Comment\Enums\CommentStatus;

class ScoreModelRepository implements ScoreModelRepositoryInterface
{
    public function get()
    {
        return ScoreModel::orderBy('created_at', 'desc')
            ->get();
    }
    public function all()
    {
        return ScoreModel::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return ScoreModel::orderBy('created_at', 'desc')
            ->where('status', CommentStatus::Approved)
            ->with('parent')
            ->paginate();
    }

    public function create($data)
    {
        $score =  ScoreModel::query()->create($data);
        return $score;
    }
    public function update($id, $data)
    {
        $score = $this->find($id);
        $score->update($data);
        return $score;
    }
    public function show($id)
    {
        $score = $this->find($id);
        return $score;
    }


    public function find($id)
    {
        try {
            $score = ScoreModel::query()->where('id', $id)->firstOrFail();
            return $score;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $score = $this->find($id);
        $score->delete();
    }
}
