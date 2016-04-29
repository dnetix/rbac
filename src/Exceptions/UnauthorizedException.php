<?php

namespace Dnetix\Rbac\Exceptions;


use Dnetix\HttpCodes;
use Exception;

class UnauthorizedException extends Exception
{

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        if($message === ""){
            $message = "You are unauthorized to make this action";
        }
        
        parent::__construct($message, HttpCodes::HTTP_UNAUTHORIZED, $previous);
    }

}