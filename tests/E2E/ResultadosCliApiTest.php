<?php

namespace App\Tests\E2E;

class ResultadosCliApiTest extends WebTestCase
{
    private $ep = '/api/resultados-cli';

    protected function given(string $context, ...$params)
    {
        switch ($context) {
        case 'resultado existente':
            return $this->criaResultado($params[0] ?? null);
        case 'resultados existentes':
            return array_map(
                fn() => $this->criaResultado(null), range(1, $params[0])
            );
        }
    }

    public function criaResultado(?array $values)
    {
        $time = time();

        $batch      = $values['batch'] ?? date('Y-m-d H:i:s');
        $bloco      = $values['bloco'] ?? 1;
        $entrada    = $values['entrada'] ?? 'entrada';
        $key        = $values['chave'] ?? 'key';
        $hash       = $values['hash'] ?? "0000{$time}";
        $tentativas = $values['tentativas'] ?? $time;

        $query = <<<SQL
INSERT INTO resultados_cli (batch, bloco, entrada, chave, hash, tentativas) VALUES (
    '$batch',
    $bloco,
    '$entrada',
    '$key',
    '$hash',
    $tentativas
)
SQL;

        $conn = $this
            ->entityManager
            ->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->execute();
    }

    public function testRestringePropriedadesExibidas()
    {
        $this->given('resultado existente');

        $response = $this->request('GET', $this->ep);

        $resultado = (array) $response[0];

        $this->assertArrayHasKey('batch', $resultado);
        $this->assertArrayHasKey('entrada', $resultado);
        $this->assertArrayHasKey('key', $resultado);
        $this->assertArrayHasKey('bloco', $resultado);
        $this->assertCount(4, $resultado);
    }

    /**
     * @dataProvider paginationRequestsProvider()
     */
    public function testRetornaResultadosPaginados(
        $context, $page, $limit, $count
    ) {
        $this->given('resultados existentes', $context);

        $response = $this->request(
            'GET', $this->ep."?page={$page}&limit={$limit}"
        );
        $this->assertCount($count, $response);
    }

    public function paginationRequestsProvider()
    {
        yield [7, 1, 4, 4];
        yield [7, 2, 4, 3];
    }

    /**
     * @dataProvider filterRequestsProvider()
     */
    public function testFiltraResultadoPorMaximoDeTentativas($corte, $count)
    {
        $this->given('resultado existente', ['tentativas' => 400]);
        $this->given('resultado existente', ['tentativas' => 500]);
        $this->given('resultado existente', ['tentativas' => 200]);
        $this->given('resultado existente', ['tentativas' => 600]);

        $response = $this->request('GET', $this->ep."?tentativas=$corte");
        $this->assertCount($count, $response);
    }

    public function filterRequestsProvider()
    {
        yield [401, 2];
        yield [400, 1];
    }
}
