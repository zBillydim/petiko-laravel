<?php

namespace App\Exceptions;

use Exception;

class TaskException extends Exception
{
    protected $message;
    protected $code;

    public function __construct($message = "Error", $code = 400)
    {
        parent::__construct($message, $code);
    }

    public function render()
    {
        return response()->json([
            'success' => false,
            'errors' => [$this->getMessage()],
            'message' => 'Failed to retrieve tasks'
        ], $this->getCode());
    }
}
