<?php

namespace App\Exceptions;

use Exception;

class AppException extends Exception
{
    protected $cause = null;

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 400, $cause = null, Exception $previous = null)
    {
        // some code
        $this->cause = $cause;

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message} {$this->cause}\n";
    }

    public function getCause()
    {
        return $this->cause;
    }

    /**
     * @param $message
     * @param int $code
     * @param null $cause
     * @param Exception|null $previous
     * @return AppException
     */
    public static function inst($code = 400, $message = "", $cause = null, Exception $previous = null)
    {
        return new self($message, $code, $cause, $previous);
    }
}
