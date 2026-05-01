<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:exec',
    description: 'Add a short description for your command',
)]
class ExecCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dataFilename = 'public/schedule_data.json';
        $schedulerData = [
            'last_execution' => (new \DateTime())->format('Y-m-d H:i:s'),
            'execution_count' => $this->getExecutionCount(),
            'status' => 'success',
            'message' => 'Scheduler exécuté avec succès',
        ];

        file_put_contents($dataFilename, json_encode($schedulerData, JSON_PRETTY_PRINT));

        $io->success('Scheduler exécuté avec succès.'  );

        return Command::SUCCESS;
    }

    private function getExecutionCount(): int
    {
        $dataFilename = 'public/schedule_data.json';

        if (file_exists($dataFilename)) {
            $data = json_decode(file_get_contents($dataFilename), true);
            return ($data['execution_count'] ?? 0) + 1;
        }

        return 1;
    }
}
