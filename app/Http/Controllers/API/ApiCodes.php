<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

class ApiCodes extends Controller
{
    public const HTTP_NOT_FOUND = 404;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const PAYMENT_REQUIRED = 402;
    public const METHODE_NOT_ALLOWED = 405;
    public const NOT_ACCEPTABLE = 406;
    public const SOMETHING_WENT_WRONG = 250;
    public const OK = 200;
    public const CREATED = 201;
    public const ACCEPTED = 202;
    public const NO_CONTENT = 204;
    public const NOT_MODIFIED = 304;
}
