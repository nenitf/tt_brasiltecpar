<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class AvatoTestCommandTest extends CommandTestCase
{
    public function testCriaResultadoAguardandoTempoMinimo()
    {
        $application = new Application(self::$kernel);

        $string = 'avato';
        $requests = 20; # superior as requisições máximas por minuto

        $command = $application->find('avato:test');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'string' => $string,
            '--requests' => $requests,
        ]);

        $commandTester->assertCommandIsSuccessful();

        $resultados = $this->executeQuery('SELECT * FROM resultados_cli');

        $this->assertCount($requests, $resultados);
    }
}
