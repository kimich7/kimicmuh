<?php
	try
{
    // 連接資訊 
    $pdo      = new PDO("sqlsrv:Server=192.168.10.30\PWS;Database=CMUH","sa","ABC!@#456");
<<<<<<< HEAD
    //$pdo      = new PDO("sqlsrv:Server=192.168.10.143\SQL2014;Database=CMUH","sa","ABC!@#456");
=======
    // $pdo      = new PDO("sqlsrv:Server=192.168.10.143\SQL2014;Database=CMUH","sa","ABC!@#456");
>>>>>>> feature/mtinsertChangeHtml
    $pdo->exec("set names utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
//連線失敗時顯示
catch (Exception $e) 
 {
     die(print_r($e->getMessage()));
 }
?>