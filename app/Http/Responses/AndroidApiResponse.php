<?php

namespace App\Http\Responses;


class AndroidApiResponse
{
    public function errorResponse(string $msg, int $http_code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $msg,
        ], $http_code);
    }
    
    public function successResourceResponse($dataResource)
    {
        return response()->json([
            'success' => true,
            'result' => $dataResource
            ]
        , 200);
    }

    


}
