<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billings\Order;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Log;
use App\Services\Payments\PartialPaymentService;

class TripayController extends Controller
{
    public function __invoke(Request $request, PartialPaymentService $partialPaymentService)
    {

        $signature = $request->header('X-Callback-Signature');
        if (!$signature) return [
            'success' => false,
            'message' => 'Unauthorized'
        ];

        $callBackEvent = $request->header('X-Callback-Event');
        if ($callBackEvent != 'payment_status') return [
            'success' => false,
            'message' => 'Not payment status.'
        ];

        $tripay = PaymentGateway::whereValue('tripay')->first();
        $signingSecret = $tripay->mode === 'development' ? $tripay->development_secret_key : $tripay->production_secret_key;

        $computedSignature = hash_hmac('sha256', $request->getContent(), $signingSecret);
        if (!hash_equals($signature, $computedSignature)) return [
            'success' => false,
            'message' => 'Unauthorized'
        ];

        if ($request->is_closed_payment != 1) return [
            'success' => false,
            'message' => 'Not closed payment'
        ];

        try {

            $order = Order::whereMerchantRef($request->merchant_ref)
                ->wherePaymentGatewayChannel('tripay')
                ->where('status', '!=', 'paid')
                ->wherePaymentMethod($request->payment_method_code)
                ->whereAmount($request->total_amount)
                ->first();

            if (!$order) return [
                'success' => false,
                'message' => 'Order not found'
            ];

            $status = strtoupper((string) $request->status);

            switch ($status) {
                // handle status PAID
                case 'PAID':
                    $status = 'paid';
                    $message = 'Payment successfully';

                    break;
                // handle status EXPIRED
                case 'EXPIRED':
                    $status = 'expired';
                    $message = 'Payment expired';
                    break;
                // handle status FAILED
                case 'FAILED':
                    $status = 'failed';
                    $message = 'Payment failed';
                    break;
                case 'REFUND':
                    $status = 'refund';
                    $message = 'Payment refund';
                    break;
                case 'UNPAID':
                    $status = 'unpaid';
                    $message = 'Payment unpaid';
                    break;
                default:
                return [
                    'success' => false,
                    'message' => 'Unrecognized payment status'
                ];
            }
            $invoice = $order->invoice;
            $result = $partialPaymentService->processPartialPayment(
                $invoice,
                $request->total_amount,
                'tripay',
                null,
                null,
                'Tripay'
            );

            if ($result['success']){
                $order->forceFill(['status' => $status])->save();
                return [
                    'success' => true,
                    'message' => 'Payment successfully.'
                ];
            }
            return [
                'success' => false,
                'message' => 'Bad request'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
