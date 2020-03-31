<?php
declare(strict_types=1);


namespace App\Commands;

use App\Service\ParseNews\RbcParseClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;


class ImportNewsCommand extends Command
{
    /**
     * @var RbcParseClient
     */
    private $parse;

    public function __construct(RbcParseClient $parse)
    {
        parent::__construct();
        $this->parse = $parse;
    }

    protected function configure()
    {
        $this
            ->setName('news:import')
            ->setDescription('Import news from RBC.RU');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->parse->updateNews();
        $output->writeln(
           "RBC News Imported"
        );
        return 1;
    }

}