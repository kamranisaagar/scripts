<?php

$query = "SELECT * FROM supplierpurchasedata where PURCHASEMONTH=4";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$values = "(0,0,0,0,0,0,0)\r\n";

while ($row = mysqli_fetch_assoc($result)) {

	$values .= ", (\"{$row['ID']}\", {$storeid}, \"{$row['SUPPLIER_ID']}\", \"{$row['PURCHASEDATE']}\", \"{$row['STICKS']}\", \"{$row['PURCHASEMONTH']}\", \"{$row['PURCHASEYEAR']}\")\r\n";

	}

$query = "insert ignore into tsg_purchaseData values {$values}";

$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));	

