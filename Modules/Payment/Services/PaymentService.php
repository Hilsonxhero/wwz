<?php


namespace Modules\Payment\Services;

use Illuminate\Http\Request;
use Shetabit\Multipay\Invoice;
use Modules\Cart\Enums\CartStatus;
use Shetabit\Payment\Facade\Payment;
use Modules\Payment\Entities\Gateway;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Repository\PaymentRepository;
use Modules\Payment\Events\App\PaymentWasSuccessful;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Modules\Payment\Repository\PaymentRepositoryInterface;

class PaymentService
{

    public static function generate($amount, $paymentable, $payment_method)
    {


        $gateway_name = "zarinpal";
        $invoice = (new Invoice)->amount($amount);
        //        $invoice->via($gateway_name);
        return Payment::purchase($invoice, function ($driver, $transactionId) use ($amount, $paymentable, $gateway_name, $payment_method) {
            resolve(PaymentRepository::class)->create([
                'user_id' => auth()->user()->id,
                'payment_method_id' => $payment_method,
                // 'gateway_id' => Gateway::query()->first(),
                'invoice_id' => $transactionId,
                'paymentable_type' => get_class($paymentable),
                'paymentable_id' => $paymentable->id,
                'amount' => $amount,
                'status' => PaymentStatus::Pending,
            ]);
            //            dd($order->payment->amount);
        })->pay()->toJson();
    }

    public static function verify(Request $request)
    {
        if (isset($request->Authority)) {
            $transaction_id = $request->Authority;
        }

        $payment =  resolve(PaymentRepository::class)->findByInvoice($transaction_id);

        $user = $payment->user;

        if (!is_null($user->available_cart)) {
            $user->available_cart->update(['status' => CartStatus::Processed->value]);
        }

        try {

            $amount = $payment->amount;

            $receipt = Payment::amount($amount)->transactionId($transaction_id)->verify();

            $ref_num = $receipt->getReferenceId();


            $payment->update([
                'status' => PaymentStatus::Success,
                'ref_num' => $ref_num
            ]);

            event(new PaymentWasSuccessful($payment));

            return redirect()->to(env('FRONT_CHECKOUT_CALLBACK') . $payment->reference_code);
        } catch (InvalidPaymentException $exception) {

            $payment->update([
                'status' => PaymentStatus::Rejected
            ]);

            return redirect()->to(env('FRONT_CHECKOUT_CALLBACK') . $payment->reference_code);
        }
    }
}
