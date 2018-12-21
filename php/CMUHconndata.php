<?php
	try
{
    // 連接資訊 
    //$pdo      = new PDO("sqlsrv:Server=10.32.211.16\PWS;Database=CMUH","sa","ABC!@#456");
    $pdo      = new PDO("sqlsrv:Server=192.168.10.143\SQL2014;Database=CMUH","sa","ABC!@#456");
    //$pdo      = new PDO("sqlsrv:Server=192.168.20.154\SQL2014;Database=CMUH","sa","ABC!@#456");
    $pdo->exec("set names utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
//連線失敗時顯示
catch (Exception $e) 
 {
     die(print_r($e->getMessage()));
 }
?>