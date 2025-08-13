<?php

namespace App\Http\Controllers\Api\Android;

use Illuminate\Http\Request;
use App\Models\Billings\Order;
use App\Models\PaymentGateway;
use Illuminate\Support\Carbon;
use App\Models\Billings\Invoice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\Android\OrderResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\AndroidApiResponse;
use App\Services\Payments\PaymentGatewayService;
use App\Http\Resources\Android\UserBillingResource;
use App\Http\Resources\Android\PaymentChannelResource;

class PaymentController extends Controller
{
    private $paymentGatewayService;
    public $paymentChanels = [];
    public function __construct()
    {
        $this->paymentGatewayService = new PaymentGatewayService;
    }
    /**
     * Display a listing of the resource.
     */
    public function requestPayment(Invoice $invoice, AndroidApiResponse $androidApiResponse)
    {
        $user = Auth::user();
        $this->paymentChanels = $this->paymentGatewayService->requestPaymentChanel();

        if ($this->paymentChanels['payment_chanels']['success'] && $invoice) {

            return $androidApiResponse->successResourceResponse(
                [
                    "paymentChannel" => PaymentChannelResource::collection($this->paymentChanels['payment_chanels']['data']),
                    "invoice" => new UserBillingResource($invoice)
                ]

            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */

     public function createOrder(Request $request, AndroidApiResponse $androidApiResponse)
    {
        //Log::info($request->all());
        $invoice = Invoice::whereId($request->invoiceId)->first();

        //Log::info($invoice);


        $res = $this->paymentGatewayService->createTransaction($invoice, $request->paymentMethod);
        if ($res['success'] && $res['data']['status'] == 'UNPAID') {

            if ($invoice->orders->count()) $invoice->orders()->forceDelete();

            $invoice->forceFill(['status' => 'process'])->save();
            $res['data']['status'] = 'pending';
            unset($res['data']['pay_url']);
            unset($res['data']['checkout_url']);
            unset($res['data']['callback_url']);
            unset($res['data']['return_url']);
            $res['data']['expired_time'] = Carbon::parse($res['data']['expired_time'])->setTimezone('Asia/Jakarta');
            $res['data']['order_items'] = json_encode($res['data']['order_items']);
            $res['data']['instructions'] = json_encode($res['data']['instructions']);
            $res['data']['payment_gateway_channel'] = PaymentGateway::whereIsActive(true)->first()->value;

            $order = new Order();
            $order = $invoice->order()->save($order);
            $order->forceFill($res['data'])->save();

            return $androidApiResponse->successResourceResponse(
                [
                    "order" => new OrderResource($order)
                ]
            );
        } else {
            return $androidApiResponse->errorResponse("Kesalahan saat membuat kode bayar.");
        }

    }
    public function requestOrder(Order $order, Request $request, AndroidApiResponse $androidApiResponse)
    {
       // Log::info("REQUEST ORDER");
       // Log::info($order);
        return $androidApiResponse->successResourceResponse(
            [
                "order" => new OrderResource($order)
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
