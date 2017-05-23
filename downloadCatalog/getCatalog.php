<?php

$link3 = mysqli_connect("104.131.184.100","saagar","saagar12","product_catalog") or die("Error Making Connection" . mysqli_error($link2)); // MerchantPulse Link

require_once('../functions.php');

$storeinfo = parse_ini_file("../../storeinfo.ini");

$storeid=$storeinfo['storeid'];

//Get Promotions

$query = "SELECT CONCAT('ON',p.promoid) as promoid, promoname, DATE_FORMAT(startdate,'%Y%m%d') as startdate, 
DATE_FORMAT(enddate,'%Y%m%d') as enddate, '0', '24', subcat, '2', amount, ctnamount, REPLACE(REPLACE(disabled, '1', 'RECALLED_ACK'),'0','ACKNOWLEDGED') as remote FROM promotions p

JOIN promostore ps ON p.promoid=ps.promoid AND ps.storeid={$storeid}";
			  
$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));

while ($row = mysqli_fetch_assoc($result)) {	
	
	$fields=array();

	foreach($row as $field) {
		$fields[]="'".$field."'";
	}

	$val[]="(".implode(",",$fields).")";

}

$values = implode(",", $val);

$query = "INSERT INTO promo_header(id,name,startdate,enddate,starthour,endhour,articlecategory,type,amount,ctnamount,remote) 
values {$values}

ON DUPLICATE KEY UPDATE
	AMOUNT     = VALUES(AMOUNT),
	CTNAMOUNT = VALUES(CTNAMOUNT),
	STARTDATE=VALUES(STARTDATE),
	ENDDATE=VALUES(ENDDATE),
	CTNAMOUNT=VALUES(CTNAMOUNT),
	TYPE=VALUES(TYPE),
	ARTICLECATEGORY=VALUES(ARTICLECATEGORY),
	REMOTE=VALUES(REMOTE);";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));	

//ReInit Arrays
$val = array();
$fields=array();


// Get Products
$query = "SELECT CONCAT('ON-',barcode) as id, barcode as ref, barcode, CONCAT(product_name,'-') as product_name, cost, price/1.1 AS pricesell, CONCAT('ON-',categoryid) as categoryid, '001' as taxid FROM product where price > 0;";
			  
$result = $link3->query($query) or die("Error in the consult.." . mysqli_error($link3));

while ($row = mysqli_fetch_assoc($result)) {	
	
	$fields=array();

	foreach($row as $field) {
		$fields[]="\"".$field."\"";
	}

	$val[]="(".implode(",",$fields).")";

}

$values = implode(",", $val);

$query = "INSERT INTO products(ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT) 
values {$values}

ON DUPLICATE KEY UPDATE
	PRICEBUY = VALUES(PRICEBUY),
PRICESELL=VALUES(PRICESELL),
CATEGORY=VALUES(CATEGORY),
NAME=VALUES(NAME),
TAXCAT=VALUES(TAXCAT);";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));