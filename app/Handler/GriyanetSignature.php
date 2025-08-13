<?php

namespace App\Handler;

use Illuminate\Http\Request;
//use App\Models\WhatsappGateway\WhatsappNumber;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\WhatsappGatewayGeneral;
use Spatie\WebhookClient\WebhookConfig;
use Illuminate\Support\Facades\Validator;
use Spatie\WebhookClient\Exceptions\InvalidConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;

class GriyanetSignature implements SignatureValidator
{
    // public function isValid(Request $request, WebhookConfig $config): bool
    public function isValid(Request $request, WebhookConfig $config): bool
    {
       //Log::info($request->all());
       // Log::info($request->header($config->signatureHeaderName));
        $signature = $request->header($config->signatureHeaderName);
        Log::info($signature);
        if (!$signature) {
          return false;
           // throw InvalidConfig::invalidWebhookResponse();
        }
      //  Log::info($signature);

        $signingSecret = $config->signingSecret;
        if (empty($signingSecret)) {
            throw InvalidConfig::signingSecretNotSet();
        }
        Log::info($signingSecret);
        
      
        $computedSignature = hash('sha256', $signingSecret);
        return hash_equals($signature, $computedSignature);
    }
}
