<?php

namespace App\Application\ErrorHandler;

use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ErrorHandler
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $data
     * @return array|null
     */
    public function validate($data)
    {
        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {
            $errorsArray = [];

            for ($i = 0; $i < $errors->count(); $i++) {
                $errorsArray[$i] = [
                    'message' => $errors->get($i)->getMessage(),
                    'parameters' => "Invalid parameter:" . ' ' . $errors->get($i)->getPropertyPath(),
                    'value' => "Invalid value:" . ' ' . $errors->get($i)->getInvalidValue()
                ];
            }

            return $errorsArray;
        }

        return null;
    }
}