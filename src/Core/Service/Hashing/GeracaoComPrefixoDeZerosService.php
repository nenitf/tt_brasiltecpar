<?php

namespace App\Core\Service\Hashing;

use App\Core\Model\{
    Resultado,
    HashTipoZero,
};

use App\Core\Provider\{
    IRandomizerProvider,
    ICryptProvider,
};

use App\Core\Exception\ValidationException;

class GeracaoComPrefixoDeZerosService
{
    function __construct(
        private IRandomizerProvider $randomizer,
        private ICryptProvider $crypt,
    ){}

    public function execute(string $entrada): Resultado
    {
        $hashNaoEncontrado  = true;
        $tentativas         = 0;
        $hash               = null;

        while($hashNaoEncontrado) {
            $tentativas++;

            $key        = $this->randomizer->text(8);
            $rawHash    = $this->crypt->encrypt($entrada.$key);

            try {
                $hash               = new HashTipoZero($rawHash);
                $hashNaoEncontrado  = false;
            } catch(ValidationException $e){}
        }

        $resultado = new Resultado(
            entrada:    $entrada,
            key:        $key,
            tentativas: $tentativas,
            hash:       $hash,
        );

        return $resultado;
    }
}
