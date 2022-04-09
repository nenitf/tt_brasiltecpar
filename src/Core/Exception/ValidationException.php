<?php

namespace App\Core\Exception;

use App\Core\Exception\CoreException;

class ValidationException extends CoreException {
    public function __construct(string $message, $valorErrado) {
        $this->message = $message;

        parent::__construct($this->message);
    }

    public function mensagemAmigavel(): string
    {
        return $this->message;
    }

    public function mensagemLog(): string
    {
        return "$this->message: '$valorErrado' foi utilizado";
    }
}
