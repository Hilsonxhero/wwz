<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Enums\CommentStatus;
use Modules\Product\Entities\ProductQuestion;

class ProductQuestionRepository implements ProductQuestionRepositoryInterface
{
    public function get()
    {
        return ProductQuestion::orderBy('created_at', 'desc')
            ->get();
    }
    public function all()
    {
        return ProductQuestion::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return ProductQuestion::orderBy('created_at', 'desc')
            ->where('status', CommentStatus::Approved)
            ->paginate();
    }

    public function create($data)
    {
        $question =  ProductQuestion::query()->create($data);
        return $question;
    }
    public function update($id, $data)
    {
        $question = $this->find($id);
        $question->update($data);
        return $question;
    }
    public function show($id)
    {
        $question = $this->find($id);
        return $question;
    }


    public function find($id)
    {
        try {
            $question = ProductQuestion::query()->where('id', $id)->firstOrFail();
            return $question;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $question = $this->find($id);
        $question->delete();
    }
}
