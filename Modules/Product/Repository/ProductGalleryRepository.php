<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Comment\Enums\CommentStatus;
use Modules\Product\Entities\ProductGallery;


class ProductGalleryRepository implements ProductGalleryRepositoryInterface
{
    public function get()
    {
        return ProductGallery::orderBy('created_at', 'desc')
            ->get();
    }
    public function all()
    {
        return ProductGallery::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return ProductGallery::orderBy('created_at', 'desc')
            ->where('status', CommentStatus::Approved)
            ->paginate();
    }

    public function create($data)
    {
        $gallery =  ProductGallery::query()->create($data);
        return $gallery;
    }
    public function update($id, $data)
    {
        $gallery = $this->find($id);
        $gallery->update($data);
        return $gallery;
    }
    public function show($id)
    {
        $gallery = $this->find($id);
        return $gallery;
    }


    public function find($id)
    {
        try {
            $gallery = ProductGallery::query()->where('id', $id)->firstOrFail();
            return $gallery;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $gallery = $this->find($id);
        $gallery->clearMediaCollectionExcept();
        $gallery->delete();
    }
}
