<?php

require_once('../functions.php');

$date=getmyDate();
$day = strtotime($date);
$dayVal= date("d", $day);

if ($dayVal=="8"){
	require_once('getPurchaseData.php');
}