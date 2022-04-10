<?php

namespace App\Core\Model;

class Resultado
{
    public function __construct(
        public string $entrada,
        public string $key,
        public string $tentativas,
        public Hash $hash
    ) {}
}
