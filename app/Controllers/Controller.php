<?php

class Controller
{
    /**
     * Success response
     * - - -
     * @param $data
     * @param int $code
     * @param bool $isMessage
     * @return false|string
     */
    public function success($data, $code = 200, $isMessage = false)
    {
        $data = $isMessage ? $data : ['data' => $data];
        http_response_code($code ? : 200);
        return json_encode($data);
    }

    /**
     * Error response
     * - - -
     * @param $message
     * @param int $code
     * @return false|string
     */
    public function error($message, $code = 500)
    {
        $code = $code && is_numeric($code) ? $code : 500;
        http_response_code($code);
        return json_encode([
            'error_message' => $message
        ]);
    }

}
