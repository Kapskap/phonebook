<?php

namespace App\Command;

use App\Service\InsertRecords;
use App\Service\ReadFile;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;

#[AsCommand(
    name: 'apps:random-records',
    description: 'Send to insert random value',
)]
class AppsRandomRecordsCommand extends Command
{

    public function __construct(
        private InsertRecords $insertRecords, private ReadFile $readFile)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Podaj ilość losowych kontaktów ktore pojawią się w bazie.')
            ->addArgument('how-much', InputArgument::OPTIONAL, 'how much')
//            ->addOption('yell', null, InputOption::VALUE_NONE, 'Yell?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $howMuch = $input->getArgument('how-much');

        if ($howMuch) {
            $io->note(sprintf('Podano: %s rekordów. Rozpoczynam pracę.', $howMuch));
        }
        else{
            $howMuch = 1000;
            $io->note(sprintf('Generuje: %s rekordów', $howMuch));
        }

        //Odczyt "losowych" danych do tablic
        $first_names = $this->readFile->readTxt("first_name.txt");
        $last_names = $this->readFile->readTxt("last_name.txt");
        $companies = $this->readFile->readTxt("company.txt");

        $progressBar = new ProgressBar($output, $howMuch);
        $progressBar->setBarCharacter('<fg=green>=</>');
        $progressBar->setEmptyBarCharacter("<fg=red>=</>");
        $progressBar->setProgressCharacter("<fg=green>➤</>");
        $progressBar->start();

        for($i=0;$i<$howMuch;$i++) {
            $first_name = $first_names[array_rand($first_names)];
            $last_name = $last_names[array_rand($last_names)];
            $company = $companies[array_rand($companies)];
//            $created_at = new \DateTimeImmutable(sprintf('-%d days', rand(1, 100)));

            $this->insertRecords->insertContact($first_name, $last_name, $company);
            $progressBar->advance();
        }

        $progressBar->finish();
        $io->success("Operacja zakończona");

        return 0;
    }
}
