<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class PaymentException extends Exception
{
    protected $message;

    public function __construct($message, $code, Exception $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
        $this->message = $message;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request
     * @return Response
     */
    public function render($request)
    {
        return response([
            'success' => false,
            'errors' => [
                'stripe' => [$this->message]
            ]
        ], 422);
    }
}
