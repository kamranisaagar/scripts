<?php

require_once('c:/mpulse/scripts/functions.php');

$date=getmyDate();
$day = strtotime($date);
$dayVal= date("d", $day);

if ($dayVal=="5"){
	require_once('getPurchaseData.php');
}