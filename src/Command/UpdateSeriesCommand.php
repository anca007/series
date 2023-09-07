<?php

namespace App\Command;

use App\Utils\UpdateSerie;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-series',
    description: 'Update all series',
)]
class UpdateSeriesCommand extends Command
{


    public function __construct(private UpdateSerie $updateSerie, string $name = null)
    {
        parent::__construct($name);

    }

    protected function configure(): void
    {
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
//        $arg1 = $input->getArgument('arg1');

//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }

//        $io->text("Salut ");
//        $response = $io->ask("ça va ?");
//        $io->confirm("En forme ?");
//        $response = $io->choice("Parfum de la galce ?", ["Fraise", "Chocolat", "Pistache"]);
//        $io->error("attention ça va pas !");
//        $io->writeln("Mais si c'est bon t'inquiètes !");
        try {
            $number = $this->updateSerie->removeOldSeries();
            $io->success("Tout s'est bien passé ! $number serie(s) ont été supprimées !");
        }catch (\Exception $exception){
            $io->error("ça a pas marché ! " . $exception->getMessage());
        }
        
        return Command::SUCCESS;
    }
}
