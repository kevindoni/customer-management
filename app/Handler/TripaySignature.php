<?php

namespace App\Handler;

use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\Exceptions\InvalidConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;

class TripaySignature implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $signature = $request->header($config->signatureHeaderName);
        if (!$signature) {
            return false;
        }

        if (PaymentGateway::whereDefault(true)->first()->value == 'tripay') {
            // Hentikan proses jika callback event-nya bukan payment_status
            $callBackEvent = $request->header('X-Callback-Event');
            if ($callBackEvent != 'payment_status') {
                return false;
            }
        }

        //Private Key
        $signingSecret = $config->signingSecret;
        if (empty($signingSecret)) {
            throw InvalidConfig::signingSecretNotSet();
        }


        $computedSignature = hash_hmac('sha256', $request->getContent(), $signingSecret);
        return hash_equals($signature, $computedSignature);
    }
}
