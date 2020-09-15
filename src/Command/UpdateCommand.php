<?php namespace EkoJunaidiSalam\App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use EkoJunaidiSalam\App\Library\DbUtil;

class UpdateCommand extends Command {
    protected static $defaultName = 'update';

    /**
     * @codeCoverageIgnore
     */
    protected function configure(){
        $this->setDescription('Update seluruh tabel parent dan child sesuai respon puskarda.')
            ->setHelp('Perintah ini untuk menjalankan update flag pada seluruh tabel parent dan child sesuai respon puskarda.')
            ->addArgument('table', InputArgument::OPTIONAL, 'Nama spesifik tabel yang akan diupdate.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $helper = $this->getHelper('question');
        $table = $input->getArgument('table') ?? null;

        $question = new ConfirmationQuestion('Yakin akan mengupdate '.(!empty($table)?$table:'seluruh tabel').' [y/n] ? ', false);

        if (!$helper->ask($input, $output, $question)) {
            $output->writeln("Proses update dibatalkan.");
            return Command::FAILURE;
        }

        $db = DbUtil::getInstance();

        if(!empty($table)){
            /* $arrUpdate = function() use ($db,$table){
                $res = $db->query("select a.flag,b.tx_id,b.waktu_masuk,b.status from sppt.".$table." a, sppt.queue_puskarda b where a.tx_id = b.tx_id");
                while($r = $res->fetchRow()){
                    if(intval($r["flag"]) == 0){
                        yield [
                            "tx_id" => $r["tx_id"],
                            "tgl_dikirim" => $r["waktu_masuk"],
                            "flag" => (preg_match("/queue/",$r["status"])?1:(preg_match("/gagal/",$r["status"])?2:(preg_match("/berhasil/",$r["status"])?3:9)))
                        ];
                    }
                }
            }; */

            // $output->writeln("Jumlah data : ". count(iterator_to_array($arrUpdate())));
            // $db->setBulk(true);
            // $db->query("insert into sppt.".$table." values(".$val.")",$arr);

            $sql = "UPDATE sppt.".$table." a SET tgl_dikirim = b.waktu_masuk, flag = (case when b.status ~ 'queue' then 1 when b.status ~ 'berhasil' then 2 else 3 end) FROM sppt.queue_puskarda b where b.tx_id = a.tx_id and a.flag = 0";
            $db->query($sql);
            $output->writeln("Data terupdate sebanyak : ".$db->getConn()->affected_rows());
        }else{

        }
        
        $output->writeln((!empty($table)?"Tabel ".$table:"Seluruh tabel")." telah di update.");
        return Command::SUCCESS;
    }
}