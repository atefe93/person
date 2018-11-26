<?php

namespace MyModels;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;

class Person extends Model
{
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'name',
            new Uniqueness(
                [
                    'field'   => 'name',
                    'message' => 'name must be unique',
                ]
            )
        );
        $validator->add(
            'email',
            new Uniqueness(
                [
                    'field'   => 'email',
                    'message' => 'email must be unique',
                ]
            )
        );

        if ($this->validationHasFailed() === true) {
            return false;
        }
    }
}