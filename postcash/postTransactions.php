<?php

require_once('c:/mpulse/scripts/functions.php');

//Putting Transactions

putTransactionLog($storeid);

$processedTransactions= putTransactionLine($storeid);

markTransactions($processedTransactions);

$totalCount= count($processedTransactions);

echo "\r\n"."Transactions Uploaded: ".$totalCount."\r\n";

?>