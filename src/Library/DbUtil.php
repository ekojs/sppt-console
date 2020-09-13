<?php namespace EkoJunaidiSalam\App\Library;

require_once 'vendor/adodb/adodb-php/adodb-exceptions.inc.php';
date_default_timezone_set('Asia/Jakarta');
class DbUtil{
    private static $instance = null;
    private $db;
    private $trx;
    private $bind;

    private function __construct(){
        $d = array(
            'dbdriver' => 'postgres9',
            'hostname' => '127.0.0.1:5432',
            'dbname' => 'sppt',
            'username' => base64_decode('usermu=='),
            'password' => base64_decode('passwordmu==')
        );
        $this->db = newAdoConnection($d["dbdriver"]);
        $this->db->setFetchMode(ADODB_FETCH_ASSOC);
        $this->db->connect($d["hostname"],$d["username"],$d["password"],$d["dbname"]);
    }

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new DbUtil();
        }
        return self::$instance;
    }

    public function getConn(){
        return $this->db;
    }

    public function setDebug($bool=false){
        $this->db->debug = $bool;
    }

    public function setTrx($bool=false){
        $this->trx = $bool;
    }

    public function getTrx(){
        return $this->trx;
    }

    public function setBulk($bool=false){
        $this->db->bulkBind = $bool;
    }

    public function setBind($params=null){
        if(!empty($params) && is_array($params)){
            $this->bind = $params;
        }
    }

    public function query($sql=null,$bind=null){
        $res = null;
        if(!empty($sql)){
            try{
                if($this->trx){
                    $this->db->startTrans();
                    $adors=(!empty($bind)?$this->db->execute($sql,$bind):$this->db->execute($sql));
                    if($adors) $res=$adors;
                    $this->db->completeTrans();
                }else{
                    $adors=(!empty($bind)?$this->db->execute($sql,$bind):$this->db->execute($sql));
                    if($adors) $res=$adors;
                }
            }catch(ADODB_Exception $e){
                // @codeCoverageIgnoreStart
                $res=$e->getMessage();
                if($this->trx) $this->db->failTrans();
                // @codeCoverageIgnoreEnd
            }
        }
        return $res;
    }
    
}