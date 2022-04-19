<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function formatResponse($message, $data, $code = 200)
    {
        error_log("Unauthorized access 2");
        return response()->json([
            'message' => $message,
            'success' => floor($code / 100) == 2,
            'data' => $data
        ], $code);
    }

    protected function unauthorized()
    {
        return $this->formatResponse(
            "Anda tidak memiliki akses ke API ini",
            null,
            401
        );
    }
}
