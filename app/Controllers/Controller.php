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
        $data       = $isMessage ? $data : ['data' => $data];
        $response   = new Response($data, $code ? : 200);
        return $response->json();
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
        $code       = $code && is_numeric($code) ? $code : 500;
        $message    = is_array($message) ? json_encode($message) : $message;
        $response   = new Response($message, $code);
        return $response->json();
    }

}
