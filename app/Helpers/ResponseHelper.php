<?php

namespace App\Helpers;

/**
 * ResponseHelper class.
 *
 * Provides methods for formatting and sending HTTP responses.
 */
class ResponseHelper
{
    /**
     * Sends a JSON response with the given status code and data.
     *
     * @param mixed $data Response data.
     * @param int $status HTTP status code.
     * @return void
     */
    public static function response($data ,int $status): void
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit;
    }
}
