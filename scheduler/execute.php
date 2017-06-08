<?php

require_once('../functions.php');

$storeinfo = parse_ini_file("../../storeinfo.ini");

$storeid=$storeinfo['storeid'];

$date=getmyDate();
$day = strtotime($date);
$dayVal= date("d", $day);

if ($dayVal=="8"){
	require_once('getPurchaseData.php');
}