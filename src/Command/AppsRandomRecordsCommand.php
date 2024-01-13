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
    name: 'apps:random-records',
    description: 'Add a short description for your command',
)]
class AppsRandomRecordsCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Podaj ilość losowych kontaktów ktore pojawią się w bazie.')
            ->addArgument('how-much', InputArgument::OPTIONAL, 'how much')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Yell?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $howMuch = $input->getArgument('how-much');

        if ($howMuch) {
            $io->note(sprintf('Podano: %s rekordów', $howMuch));
        }
        else{
            $howMuch = 100;
            $io->note(sprintf('Generuje: %s rekordów', $howMuch));
        }

        $first_names = [
            'Jan',
            'Adam',
            'Karol',
            'Ewa',
            'Anna',
            'Janusz',
            'Monika',
        ];
        $first_name = $first_names[array_rand($first_names)];

        if ($input->getOption('yell')) {
            $first_name = strtoupper($first_name);
        }

        $io->success($first_name);

        return 0;
    }
}
