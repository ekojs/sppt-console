<?php namespace EkoJunaidiSalam\App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\Table;
use EkoJunaidiSalam\App\Library\DbUtil;

class HelperCommand extends Command {
    protected static $defaultName = 'helper';
    protected $listType = ['list','check'];

    /**
     * @codeCoverageIgnore
     */
    protected function configure(){
        $this->setDescription('SPPT-TI Helper')
            ->setHelp('Perintah ini untuk menjalankan library helper dari SPPT-TI')
            ->addArgument('type', InputArgument::REQUIRED, 'Tipe helper [list]')
            ->addArgument('object', InputArgument::OPTIONAL, 'Object data')
            ->addArgument('filter', InputArgument::OPTIONAL, 'Filter data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('Helper '.ucfirst($input->getArgument('type')));

        if(in_array($input->getArgument('type'),$this->listType)){
            if("list" == $input->getArgument('type')){
                if("mapping" == $input->getArgument('object') && !empty($input->getArgument('filter'))){
                    $db = DbUtil::getInstance();
                    $headers = [];
                    $filter = $input->getArgument('filter');
                    $dataTable = function() use ($db,&$headers,$filter){
                        $res = $db->query("select * from sppt.tb_mapping where kategori=? or param=? or mapping=?",array($filter,$filter,$filter));
                        while($r = $res->fetchRow()){
                            if(empty($headers)){
                                $headers = array_keys($r);
                            }
                            yield array_values($r);
                        }
                    };
                    $data = iterator_to_array($dataTable());
                    
                    $table = new Table($output);
                    $table
                        ->setHeaderTitle('Data Mapping')
                        ->setHeaders($headers)
                        ->setRows($data);
                    $table->render();
                }
            }
        }
        
        return Command::SUCCESS;
    }
}