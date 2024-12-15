<?php
namespace Portainer\Exceptions;
class InvalidCredentialsException extends \Exception {
    public function __construct($message = "Invalid Credentials", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
