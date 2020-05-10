<?php

namespace App\Command;

use App\Service\ImportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCommand extends Command
{
    private const COMMAND_NAME = 'pairs:import';

    private ImportService $service;
    private SymfonyStyle $io;

    public function __construct(ImportService $service)
    {
        parent::__construct(self::COMMAND_NAME);
        $this->service = $service;
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Imports new data for currency pairs');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title('Import crypto-currency pairs data');
        $this->service->importNewData();
        $this->io->success('Data loaded successfully');
        return 0;
    }
}
