<?php

namespace App\Tests\Unit;

use App\Core\Exception\ValidationException;
use App\Core\Model\HashTipoZero;

use PHPUnit\Framework\TestCase;

class HashTipoZeroTest extends TestCase
{
    /**
     * @dataProvider hashComPrefixosErradosProvider()
     */
    public function testFalhaAoCriarComPrefixoErrado($hash)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Hash inválido');

        new HashTipoZero($hash);
    }

    public function hashComPrefixosErradosProvider()
    {
        yield ['Ávato'];
        yield ['000e5a78737e88a6065b37aa698bb4d'];
        yield ['1000a93f9f287a03912c8a61d79ffb23'];
        yield ['0100e4c93fbdc09d46361dafc0b5a00c'];
        yield ['00303794acd6639aa65e1be105a568e6'];
        yield ['0r00e350aea8329b93a7346e77cb47b3'];
        yield ['02f2f06cc0271af767f1976526a0a4ec'];
    }

    /**
     * @dataProvider hashComPrefixosCertosProvider()
     */
    public function testCriaComPrefixoCerto($hash)
    {
        $this->expectNotToPerformAssertions();
        new HashTipoZero($hash);
    }

    public function hashComPrefixosCertosProvider()
    {
        yield ['0000'];
        yield ['0000e5a78737e88a6065b37aa698bb4d'];
        yield ['0000a93f9f287a03912c8a61d79ffb23'];
        yield ['0000e4c93fbdc09d46361dafc0b5a00c'];
        yield ['00003794acd6639aa65e1be105a568e6'];
        yield ['0000e350aea8329b93a7346e77cb47b3'];
        yield ['0000f06cc0271af767f1976526a0a4ec'];
    }
}
