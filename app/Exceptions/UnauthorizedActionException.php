<?php
namespace App\Exceptions;

use Exception;

class UnauthorizedActionException extends Exception
{
    public function __construct(string $message = "You are not authorized to perform this action.")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 403);
    }
}
