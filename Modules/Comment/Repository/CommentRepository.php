<?php

namespace Modules\Comment\Repository;

use App\Services\ApiService;
use Illuminate\Support\Facades\DB;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Enums\CommentStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentRepository implements CommentRepositoryInterface
{
    public function get()
    {
        return Comment::orderBy('created_at', 'desc')
            ->get();
    }

    public function scores($product)
    {
        $scores = Comment::with('scores.score_model')
            ->where('commentable_id', $product)
            ->select('id', 'commentable_id')
            ->join('comment_scores', 'comments.id', '=', 'comment_scores.comment_id')
            ->join('score_models', 'comment_scores.score_model_id', '=', 'score_models.id')
            ->groupBy('score_models.id')
            ->select('score_models.id', 'score_models.title', DB::raw('AVG(comment_scores.value) as avg_value'))->get();

        return $scores;
    }
    public function all()
    {
        return Comment::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return Comment::orderBy('created_at', 'desc')
            ->where('status', CommentStatus::Approved)
            ->with('parent')
            ->paginate();
    }

    public function create($commentable, $data)
    {
        // $comment =  Comment::query()->create($data);
        $comment =  $commentable->comments()->create($data);
        return $comment;
    }
    public function update($id, $data)
    {
        $comment = $this->find($id);
        $comment->update($data);
        return $comment;
    }
    public function show($id)
    {
        $comment = $this->find($id);
        return $comment;
    }


    public function find($id)
    {
        try {
            $comment = Comment::query()->where('id', $id)->firstOrFail();
            return $comment;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $comment = $this->find($id);
        $comment->delete();
    }
}
