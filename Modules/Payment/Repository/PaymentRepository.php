<?php

namespace Modules\Payment\Repository;

use App\Services\ApiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Category\Entities\Category;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Entities\PaymentMethod;

class PaymentRepository implements PaymentRepositoryInterface
{

    public function all()
    {
        return Payment::orderBy('created_at', 'desc')
            ->paginate();
    }

    public function allActive()
    {
        return Payment::orderBy('created_at', 'desc')

            ->paginate();
    }

    public function create($data)
    {
        $payment =  Payment::query()->create($data);
        return $payment;
    }

    public function update($id, $data)
    {
        $payment = $this->find($id);
        $payment->update($data);
        return $payment;
    }

    public function show($id)
    {
        $payment = $this->find($id);
        return $payment;
    }

    public function find($id)
    {
        try {
            $payment = Payment::query()->where('id', $id)->firstOrFail();
            return $payment;
        } catch (ModelNotFoundException $e) {
            return  ApiService::_response(trans('response.responses.404'), 404);
        }
    }

    public function findByInvoice($invoice_id)
    {
        $payment = Payment::query()->where('invoice_id', $invoice_id)->first();
        return $payment;
    }
    public function findByReferenceCode($reference_code)
    {
        $payment = Payment::query()->where('reference_code', $reference_code)->first();
        return $payment;
    }
    public function delete($id)
    {
        $payment = $this->find($id);
        if ($payment)   $payment->delete();
    }
}
