<?php

class Validator
{
    private $rules;
    private $attributes;
    private $errors;

    /**
     * Validator constructor.
     * - - -
     * @param $attributes
     * @param $rules
     */
    public function __construct($attributes, $rules)
    {
        $this->attributes = $attributes;
        $this->rules = $rules;
    }

    public function validate()
    {
        $errors = [];
        foreach ($this->rules as $key => $rule) {
            echo $key . "\n";
            die($rule);
            switch ($rule) {
                case 'required':
                    echo $this->attributes[$key] . "\n";
                    if (!isset($this->attributes[$key]) || !$this->attributes[$key])
                        $errors[$key] = ucfirst($key) . ' field is required.';
                    break;
                default:
                    break;
            }
        }

        print_r($errors);
        if (count($errors) > 0) {
            $this->errors = $errors;
            return false;
        }

        return true;
    }

    public function getErrors($messageOnly = false)
    {
        if ($messageOnly)
            return array_values($this->errors);

        return $this->errors;
    }

    public function getError($messageOnly = false)
    {
        if ($messageOnly)
            return isset($this->errors[0]) ? array_values($this->errors[0]) : null;

        return isset($this->errors[0]) ? $this->errors[0] : null;
    }
}
