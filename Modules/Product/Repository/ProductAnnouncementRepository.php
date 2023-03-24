<?php

namespace Modules\Product\Repository;

use App\Services\ApiService;
use Modules\Product\Entities\ProductAnnouncement;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductAnnouncementRepository implements ProductAnnouncementRepositoryInterface
{

    public function get($user)
    {
        return ProductAnnouncement::orderBy('created_at', 'desc')
            ->where('user_id', $user)
            ->paginate();
    }

    public function create($data)
    {
        $announcement =  ProductAnnouncement::query()->create($data);
        return $announcement;
    }
    public function update($id, $data)
    {
        $announcement = $this->find($id);
        $announcement->update($data);
        return $announcement;
    }
    public function show($id)
    {
        $announcement = $this->find($id);
        return $announcement;
    }

    public function find($id)
    {
        try {
            $announcement = ProductAnnouncement::query()->where('id', $id)->firstOrFail();
            return $announcement;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $announcement = $this->find($id);
        return $announcement->delete();
    }
}
