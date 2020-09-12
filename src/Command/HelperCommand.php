<?php namespace EkoJunaidiSalam\App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelperCommand extends Command {
    protected static $defaultName = 'helper';

    protected function configure(){
        $this->setDescription('SPPT-TI Helper')
            ->setHelp('Perintah ini untuk menjalankan library helper dari SPPT-TI')
            ->addArgument('type', InputArgument::REQUIRED, 'Tipe helper')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('Helper '.ucfirst($input->getArgument('type')));
        
        return Command::SUCCESS;
    }
}