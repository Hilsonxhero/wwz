<?php

namespace Modules\Comment\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Enums\CommentStatus;

class CommentRepository implements CommentRepositoryInterface
{
    public function get()
    {
        return Comment::orderBy('created_at', 'desc')
            ->get();
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
