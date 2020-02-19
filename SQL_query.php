<?php
class db{
    private $host='192.168.10.30\PWS';
    private $user='sa';
    private $pass = 'ABC!@#456';
    private $dbname='CMUH';

    private $dbh;
    private $error;
    private $stmt;

    public function __construct(){
        $this->connect();   
    }

    public function connect(){
        $dsn="sqlsrv:Server=".$this->host.";Database=".$this->dbname;        
        try {
            $this->dbh = new PDO($dsn,$this->user,$this->pass);
            $this->dbh->exec("set names utf8");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "錯誤</br>";
            die(print_r($e->getMessage()));
        }
    }

    public function search($col,$tbl,$whe){
        $str="SELECT $col FROM $tbl WHERE $whe=1";
        return  $this->stmt=$this->dbh->query($str)->fetchAll();
    }

    public function page(){
        
    }
}
$DB= new db;
?>



<?php
$col='equipName';
$tbl='FA.Equipment_System';
$whe='sysID';
$QUERY=$DB->search($col,$tbl,$whe);
foreach ($QUERY as $value) {
    echo $value["equipName"]."</br>";
}
?>