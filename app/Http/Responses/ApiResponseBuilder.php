<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Validator;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;


class ApiResponseBuilder extends ResponseBuilder
{
    public static function errorWithMessage(int $api_code, string $msg = null, int $http_code = null): HttpResponse
    {
        return static::asError($api_code)
            ->withMessage($msg)
            ->withHttpCode($http_code)
            ->build();
    }

    public static function successWithMessage($data, string $msg = null, int $http_code = null, int $api_code = null): HttpResponse
    {
        return static::asSuccess($api_code)
            ->withData($data)
            ->withMessage($msg)
            ->withHttpCode($http_code)
            ->build();
    }

    public static function successCode(int $http_code = null): HttpResponse
    {
        return static::asSuccess()
            ->withHttpCode($http_code)
            ->build();
    }


}
