<?php

namespace Modules\Voucher\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Article\Entities\Article;
use Modules\Voucher\Entities\Voucher;

class VoucherRepository implements VoucherRepositoryInterface
{

    public function all()
    {
        return Voucher::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function take()
    {
        return Voucher::with('category')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
    }


    public function create($data)
    {
        $voucher =  Voucher::query()->create($data);
        return $voucher;
    }
    public function update($id, $data)
    {
        $voucher = $this->find($id);

        $voucher =  Voucher::query()->update($data);
        return $voucher;
    }
    public function show($id)
    {
        $voucher = $this->find($id);
        return $voucher;
    }

    public function find($id)
    {
        try {
            $voucher = Voucher::query()->where('id', $id)->firstOrFail();
            return $voucher;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }
    public function delete($id)
    {
        $voucher = $this->find($id);
        $voucher->delete();
    }
}
