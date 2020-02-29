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
            switch ($rule) {
                case 'required':
                    if (!isset($this->attributes[$key]) || !$this->attributes[$key])
                        $errors[$key] = ucfirst($key) . ' field is required.';
                    break;
                default:
                    break;
            }
        }

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
        $keys = array_keys($this->errors);
        $msgs = array_values($this->errors);

        if (!isset($msgs[0]))
            return null;

        if ($messageOnly)
            return $msgs[0];

        return [
            $keys[0] => $msgs[0]
        ];
    }
}
