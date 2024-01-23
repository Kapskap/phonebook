<?php

namespace App\Command;

use App\Service\ContactService;
use App\Service\FileService;
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
    description: 'Insert random value to table contacs',
)]
class AppsRandomRecordsCommand extends Command
{

    public function __construct(
        private ContactService $contactService, private FileService $fileService)
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
        $firstNames = $this->fileService->readFile("first_name.txt");
        $lastNames = $this->fileService->readFile("last_name.txt");
        $companies = $this->fileService->readFile("company.txt");

        $progressBar = new ProgressBar($output, $howMuch);
        $progressBar->setBarCharacter('<fg=green>=</>');
        $progressBar->setEmptyBarCharacter("<fg=red>=</>");
        $progressBar->setProgressCharacter("<fg=green>➤</>");
        $progressBar->start();

        for($i=0;$i<$howMuch;$i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $company = $companies[array_rand($companies)];
            $createdAt = new \DateTimeImmutable(sprintf('-%d days', rand(1, 100)));

            $this->contactService->insertContact($firstName, $lastName, $company, $createdAt);
            $progressBar->advance();
        }

        $progressBar->finish();
        $io->success("Operacja zakończona");

        return 0;
    }
}
