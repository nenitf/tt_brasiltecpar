<?php

namespace App\Core\Model;

class Resultado implements \JsonSerializable
{
    public function __construct(
        public string $entrada,
        public string $key,
        public string $tentativas,
        public Hash $hash
    ) {}

    public function jsonSerialize(): mixed
    {
        return [
            'entrada'    => $this->entrada,
            'key'        => $this->key,
            'tentativas' => $this->tentativas,
            'hash'       => $this->hash->getContent(),
        ];
    }
}
