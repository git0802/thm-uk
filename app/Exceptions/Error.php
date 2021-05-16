<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class Error extends Exception
{
    public function __construct($message = "Error", $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
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
        ], $this->code);
    }
}
