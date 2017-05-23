<?php

require_once('../functions.php');

$storeinfo = parse_ini_file("../../storeinfo.ini");

$storeid=$storeinfo['storeid'];

//Get Products

$query = "SELECT CONCAT('ON',p.promoid) as promoid, promoname, DATE_FORMAT(startdate,'%Y%m%d') as startdate, 
DATE_FORMAT(enddate,'%Y%m%d') as enddate, '0', '24', NULL, subcat, '2', amount, NULL, NULL,
 NULL, NULL, NULL, NULL, ctnamount, REPLACE(REPLACE(disabled, '1', 'RECALLED_ACK'),'0','ACKNOWLEDGED') as remote , '0' FROM promotions p

JOIN promostore ps ON p.promoid=ps.promoid AND ps.storeid={$storeid}";
			  
$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));

while ($row = mysqli_fetch_assoc($result)) {	
	
	$fields=array();

	foreach($row as $field) {
		$fields[]=$field;
	}

	$val[]=implode(",",$fields);

}

$values = implode(",", $val);

$query = "INSERT IGNORE INTO promo_header values {$values}";

echo $query;

//$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));	


// Get Promotions
