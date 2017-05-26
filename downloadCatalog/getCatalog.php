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

// Inserting Categories
$query = "select categoryid,name,'ON-9' from categories";
			  
$result = $link3->query($query) or die("Error in the consult.." . mysqli_error($link3));

while ($row = mysqli_fetch_assoc($result)) {	
	
	$fields=array();

	foreach($row as $key => $value) {
		if ($key == "name"){
			$fields[]="\"-".$value."\"";
		}
		else{
			$fields[]="\"".$value."\"";
		}
	}

	$val[]="(".implode(",",$fields).")";

}

$values = implode(",", $val);

$query = "insert ignore into categories(id,name,parentid) values {$values};";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "update categories set parentid='ON-9', catshowname=true where id like 'ON-%' and id <> 'ON-9';";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "update categories set parentid=null, catshowname=true where id='ON-9';";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "update categories set parentid=id, catshowname=false where id not like 'ON-%';";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "delete from categories where id='ON-16';";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

//ReInit Arrays
$val = array();
$fields=array();


// Get Products
$query = "SELECT CONCAT('ON-',barcode) as id, barcode as ref, barcode, CONCAT(product_name,'-') as product_name, cost, price/1.1 AS pricesell, CONCAT('ON-',categoryid) as categoryid, '001' as taxid, isVariable,CONCAT(product_name,'-') as display  FROM product;";
			  
$result = $link3->query($query) or die("Error in the consult.." . mysqli_error($link3));

while ($row = mysqli_fetch_assoc($result)) {	
	
	$fields=array();

	foreach($row as $key => $value) {
		if ($key == "isVariable"){
			$fields[]=$value;
		}
		else{
			$fields[]="\"".$value."\"";
		}
	}

	$val[]="(".implode(",",$fields).")";

}

$values = implode(",", $val);

$query = "INSERT INTO products(ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, isvprice, display) 
values {$values}

ON DUPLICATE KEY UPDATE
	PRICEBUY = VALUES(PRICEBUY),
PRICESELL=VALUES(PRICESELL),
CATEGORY=VALUES(CATEGORY),
NAME=VALUES(NAME),
TAXCAT=VALUES(TAXCAT),
isvprice=VALUES(isvprice),
display=VALUES(display);";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

//ReInit Arrays
$val = array();
$fields=array();


// Showing Products in Catalog

$query = "SELECT CONCAT('ON-',barcode) as id from product where isVisible = 1;";
			  
$result = $link3->query($query) or die("Error in the consult.." . mysqli_error($link3));

while ($row = mysqli_fetch_assoc($result)) {	
	
	$productIds[]="(\"".$row['id']."\")";
}

$values = implode(",", $productIds);

//Truncate
$query = "TRUNCATE TABLE products_cat";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));


//Inserting Now
$query = "Insert into products_cat(product) values {$values};";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

?>