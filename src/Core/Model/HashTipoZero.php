<?php

namespace App\Core\Model;

use App\Core\Exception\ValidationException;

class HashTipoZero
{
    private string $hash;

    public function __construct(string $hash)
    {
        if(!$this->hashValido($hash))
            throw new ValidationException("Hash inválido", $hash);

        $this->hash = $hash;
    }

    protected function hashValido($h)
    {
        return preg_match('/^0000/', $h) === 1;
    }

    public function getContent(): string
    {
        return $hash;
    }
}
