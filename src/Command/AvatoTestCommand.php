<?php

namespace App\Command;

use App\Core\Model\{
    ResultadoCli,
    HashTipoZero,
};
use App\Controller\HashController;
use App\Core\Service\Hashing\GeracaoComPrefixoDeZerosService;

use App\Core\Repository\IResultadosCliRepository;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\RateLimiter\RateLimiterFactory;

use Symfony\Component\HttpFoundation\Request;

class AvatoTestCommand extends Command
{
    protected static $defaultName = 'avato:test';

    public function __construct(
        private RateLimiterFactory $anonymousApiLimiter,
        private GeracaoComPrefixoDeZerosService $service,
        private IResultadosCliRepository $repo,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'string',
                InputArgument::REQUIRED,
                'Qual será a entrada?'
            )
            ->addOption(
                'requests',
                null,
                InputOption::VALUE_REQUIRED,
                'Quantas requisições serão realizadas?',
                1
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $ip = gethostbyname(gethostname());

        $entry = $input->getArgument('string');
        $requests = $input->getOption('requests');
        $batch = new \DateTime();

        for($i = 0; $i < $requests; $i++) {
            $request = Request::createFromGlobals();
            $controller = new HashController();

            $response = $controller->create(
                $entry,
                $request,
                $this->service,
                $this->anonymousApiLimiter,
                true
            );

            $response = json_decode($response->getContent());

            $resultado = new ResultadoCli(
                batch:      $batch,
                bloco:      $i+1,
                entrada:    $response->entrada,
                key:        $response->key,
                hash:       $response->hash,
                // hash: new HashTipoZero($response->hash),
                tentativas: $response->tentativas
            );

            $this->repo->save($resultado);
        }

        return Command::SUCCESS;
        // return Command::FAILURE;
        // return Command::INVALID
    }
}

