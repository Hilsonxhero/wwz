<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\ProductReview;

class ProductReviewRepository implements ProductReviewRepositoryInterface
{
    public function get()
    {
        return ProductReview::orderBy('created_at', 'desc')
            ->get();
    }
    public function all()
    {
        return ProductReview::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function create($data)
    {
        $review =  ProductReview::query()->create($data);
        return $review;
    }
    public function update($id, $data)
    {
        $review = $this->find($id);
        $review->update($data);
        return $review;
    }
    public function show($id)
    {
        $review = $this->find($id);
        return $review;
    }

    public function find($id)
    {
        try {
            $review = ProductReview::query()->where('id', $id)->firstOrFail();
            return $review;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $review = $this->find($id);
        $review->delete();
    }
}
