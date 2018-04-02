<?php
$date = $_GET["rankdate"];
$rank = $_GET["rank"];
echo json_encode($date.$rank);
?>