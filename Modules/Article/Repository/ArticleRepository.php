<?php

namespace Modules\Article\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Article\Entities\Article;

class ArticleRepository implements ArticleRepositoryInterface
{

    public function all()
    {
        return Article::orderBy('created_at', 'desc')
            ->with(['category'])
            ->paginate();
    }

    public function take()
    {
        return Article::with('category')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
    }

    public function allActive()
    {
        return Article::orderBy('created_at', 'desc')
            ->where('status', Article::ENABLE_STATUS)
            ->with('parent')
            ->paginate();
    }


    public function create($data)
    {
        $article =  Article::query()->create([
            'category_id' => $data->category_id,
            'title' => $data->title,
            'content' => $data->content,
            'description' => $data->description,
            'status' => $data->status,
            'publisahed_at' => now(),
        ]);



        base64($data->image) ? $article->addMediaFromBase64($data->image)->toMediaCollection('main')
            : $article->addMedia($data->image)->toMediaCollection('main');


        return $article;
    }
    public function update($id, $data)
    {
        $article = $this->find($id);

        $article->update([
            'category_id' => $data->category_id,
            'title' => $data->title,
            'content' => $data->content,
            'description' => $data->description,
            'status' => $data->status,
            'publisahed_at' => now(),
        ]);


        if ($data->input('image')) {
            base64($data->image) ? $article->addMediaFromBase64($data->image)->toMediaCollection('main')
                : $article->addMedia($data->image)->toMediaCollection('main');
        }

        return $article;
    }
    public function show($id)
    {
        $article = $this->find($id);
        return $article;
    }

    public function find($id)
    {
        try {
            $article = Article::query()->where('id', $id)->firstOrFail();
            return $article;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $article = $this->find($id);
        $article->delete();
    }
}
