<?php

class Request
{
    private $attributes;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->attributes = $_POST;
    }

    public function all()
    {
        return $this->attributes;
    }

    public function input($key)
    {
        return isset($this->attributes[$key]) ?
            $this->attributes[$key] :
            null;
    }
}
