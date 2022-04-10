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
    protected function sut(?callable $containerResolve = null)
    {
        self::bootKernel();

        $container = static::getContainer();

        if(!is_null($containerResolve)) {
            $containerResolve($container);
        }

        return $container->get(GeracaoComPrefixoDeZerosService::class);
    }
    public function testCriaHash()
    {
        $entrada = 'avato';
        $hashGeradoComSucesso = '0000qualquercoisa';
        $keyDoHashComSucesso = 'blabla';
        $tentativas = 7;

        $cr = function(&$container) use(
            $entrada, $hashGeradoComSucesso, $keyDoHashComSucesso, $tentativas
        ) {
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
            $container->set(IRandomizerProvider::class, $randomizerProviderMock);

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
            $container->set(ICryptProvider::class, $cryptProviderMock);
        };

        $resultado = $this->sut($cr)->execute($entrada);

        $this->assertInstanceOf(HashTipoZero::class, $resultado->hash);
        $this->assertEquals($resultado->hash->getContent(), $hashGeradoComSucesso);
        $this->assertEquals($resultado->key, $keyDoHashComSucesso);
        $this->assertEquals($resultado->tentativas, $tentativas);
    }
}
