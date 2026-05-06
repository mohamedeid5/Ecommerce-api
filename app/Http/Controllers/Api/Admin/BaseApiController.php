<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

abstract class BaseApiController extends Controller
{
    protected function successResponse(
        $data = null,
        $message = 'Success',
        $code = 200
    )
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse(
        $message = 'Error',
        $code = 400,
        $erros = null
        )
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if(!empty($erros)) {
            $response['errors'] = $erros;
        }

        return response()->json($response, $code);
    }

    protected function createdResponse(
        $data = null,
        $message = 'Resource created successfully',
        $code = 201
    )
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
