<?php

namespace App\Validators;

use Illuminate\Validation\Validator;

class CustomCheckValidator extends Validator
{
    protected $myCustomMessages = [
        "password_match" => "Your :attribute is incorrect.",
    ];

    public function __construct($translator, $data, $rules, $messages = array(), $customAttributes = array())
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
        $this->setCustomMessages($this->myCustomMessages);
    }

    protected function validatePasswordMatch($attribute, $value, $parameters)
    {
        return app('hash')->check($value, current($parameters));
    }
}
