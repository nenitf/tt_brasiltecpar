<?php

namespace App\Core\Exception;

abstract class CoreException extends \RuntimeException {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    abstract public function mensagemAmigavel(): string;

    abstract public function mensagemLog(): string;
}

