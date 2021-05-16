<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class Errors extends Exception
{
    protected $errors;

    public function __construct($errors = [], $message = "There is some errors...", $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return Response
     */
    public function render(): Response
    {
        return response([
            'success' => false,
            'message' => $this->message,
            'errors' => $this->errors,
        ], $this->code);
    }
}
