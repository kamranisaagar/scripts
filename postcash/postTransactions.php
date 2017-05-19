<?php

require_once('../functions.php');

$storeinfo = parse_ini_file("../../storeinfo.ini");

$storeid=$storeinfo['storeid'];

// Close Shift
postCash($storeid);

//Putting Transactions

putTransactionLog($storeid);

$processedTransactions= putTransactionLine($storeid);

markTransactions($processedTransactions);

$totalCount= count($processedTransactions);

echo "\r\n"."Transactions Uploaded: ".$totalCount."\r\n";

$date=getmyDate();
$day = strtotime($date);
$dayVal= date("d", $day);

if ($dayVal=="19"){
	require_once('getPurchaseData.php');
}

?>