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
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\ProgressBar;

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

        $section1 = $output->section();
        $section2 = $output->section();

        $table = new Table($section1);
        $table->setHeaders(['Bloco', 'Ação', 'timestamp']);
        $table->render();

        $progress = new ProgressBar($section2);
        $progress->start($requests);

        for($i = 0; $i < $requests; $i++) {
            $request = Request::createFromGlobals();
            $controller = new HashController();

            $bloco = $i+1;
            $dumptime = date('H:i:s');
            $table->appendRow([$bloco, "Aguardando Limiter", $dumptime]);

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
                bloco:      $bloco,
                entrada:    $response->entrada,
                key:        $response->key,
                hash:       $response->hash,
                tentativas: $response->tentativas
            );

            $this->repo->save($resultado);

            $dumptime = date('H:i:s');
            $table->appendRow([
                "$bloco",
                "<info>Cadastrado com sucesso</info>",
                "<info>$dumptime</info>",
            ]);
            $progress->advance();
        }

        return Command::SUCCESS;
    }
}

