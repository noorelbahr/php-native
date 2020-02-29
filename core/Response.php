<?php

class Response
{
    public $body;
    public $httpCode;

    /**
     * Response constructor.
     * - - -
     * @param $body
     * @param $httpCode
     */
    public function __construct($body, $httpCode)
    {
        $this->body = $body;
        $this->httpCode = $httpCode;
    }

    /**
     * Get response json
     * - - -
     * @return false|string
     */
    public function json()
    {
        header('Content-Type: application/json');
        http_response_code($this->httpCode);
        return json_encode($this->body, JSON_NUMERIC_CHECK);
    }
}
