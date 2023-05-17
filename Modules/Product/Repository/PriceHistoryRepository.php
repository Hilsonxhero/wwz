<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Product\Entities\Feature;
use Modules\Product\Entities\PriceHistory;
use Modules\Product\Enums\FeatureStatus;

class PriceHistoryRepository implements PriceHistoryRepositoryInterface
{

    public function all()
    {
        return PriceHistory::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function create($data)
    {
        $price_history =  PriceHistory::query()->create($data);
        return $price_history;
    }
    public function update($id, $data)
    {
        $price_history = $this->find($id);
        $price_history->update($data);
        return $price_history;
    }
    public function show($id)
    {
        $price_history = $this->find($id);
        return $price_history;
    }


    public function find($id)
    {
        try {
            $price_history = PriceHistory::query()->where('id', $id)->firstOrFail();
            return $price_history;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $price_history = $this->find($id);
        return $price_history->delete();
    }
}
