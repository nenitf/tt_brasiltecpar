<?php

namespace App\Tests\Integration;

use App\Core\Service\Hashing\GeracaoComPrefixoDeZerosService;

use App\Core\Model\HashTipoZero;

use App\Core\Provider\{
    IRandomizerProvider,
    ICryptProvider,
};

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GeracaoComPrefixoDeZerosServiceTest extends KernelTestCase
{
    protected function sut(...$constructor)
    {
        self::bootKernel();

        $container = static::getContainer();

        $service = new GeracaoComPrefixoDeZerosService(
            $constructor[0] ?? $container->get(IRandomizerProvider::class),
            $constructor[1] ?? $container->get(ICryptProvider::class),
        );

        return $service;
    }
    public function testCriaHash()
    {
        $entrada = 'avato';
        $hashGeradoComSucesso = '0000qualquercoisa';
        $keyDoHashComSucesso = 'blabla';
        $tentativas = 7;

        $randomizerProviderMock = $this->createMock(irandomizerprovider::class);
        $randomizerProviderMock
            ->expects($this->exactly($tentativas))
            ->method('text')
            ->will(
                $this->onConsecutiveCalls(
                    'keyFalha1',
                    'keyFalha2',
                    'keyFalha3',
                    'keyFalha4',
                    'keyFalha5',
                    'keyFalha6',
                    $keyDoHashComSucesso
                )
            );

        $cryptProviderMock = $this->createMock(ICryptProvider::class);
        $cryptProviderMock
            ->expects($this->exactly($tentativas))
            ->method('encrypt')
            ->withConsecutive(
                [$entrada.'keyFalha1'],
                [$entrada.'keyFalha2'],
                [$entrada.'keyFalha3'],
                [$entrada.'keyFalha4'],
                [$entrada.'keyFalha5'],
                [$entrada.'keyFalha6'],
                [$entrada.$keyDoHashComSucesso]
            )
            ->will(
                $this->onConsecutiveCalls(
                    '0123tentativaInvalida1',
                    '0123tentativaInvalida2',
                    '0123tentativaInvalida3',
                    '0123tentativaInvalida4',
                    '0123tentativaInvalida5',
                    '0123tentativaInvalida6',
                    $hashGeradoComSucesso
                )
            );

        $resultado = $this
            ->sut($randomizerProviderMock, $cryptProviderMock)
            ->execute($entrada);

        $this->assertInstanceOf(HashTipoZero::class, $resultado->hash);
        $this->assertEquals($resultado->hash->getContent(), $hashGeradoComSucesso);
        $this->assertEquals($resultado->key, $keyDoHashComSucesso);
        $this->assertEquals($resultado->tentativas, $tentativas);
    }
}
