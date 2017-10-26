<?php
require_once('c:/mpulse/scripts/functions.php');

$link3 = mysqli_connect("162.243.35.72","mpulse","saagar12","product_catalog") or die("Error Making Connection" . mysqli_error($link2)); // MerchantPulse Link

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
$query = "select concat('ON-',categoryid) as categoryid,name,'ON-9' from categories";
			  
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


// Enabling all active categories
$query = "select concat('ON-',categoryid) as categoryid from categories where isactive=1";
			  
$result = $link3->query($query) or die("Error in the consult.." . mysqli_error($link3));

while ($row = mysqli_fetch_assoc($result)) {
	$activeCategories[]="\"".$row['categoryid']."\"";
}

$activeCats=implode(",",$activeCategories);

$query = "update categories set parentid='ON-9', catshowname=true where id in ({$activeCats})";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "update categories set parentid=null, catshowname=true where id='ON-9';";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "update categories set parentid=id, catshowname=false where id not in ({$activeCats});";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

//ReInit Arrays
$val = array();
$fields=array();


// Get Products
$query = "SELECT CONCAT('ON-',barcode) as id, barcode as ref, barcode, CONCAT(product_name,'-') as product_name, cost, price/1.1 AS pricesell, CONCAT('ON-',categoryid) as categoryid, taxid as taxid, isVariable,CONCAT(product_name,'-') as display  FROM product;";
			  
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

$result = $link->query($query) or die("Error in the consult1.." . mysqli_error($link));

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

$result = $link->query($query) or die("Error in the consult2.." . mysqli_error($link));


//Inserting Now
$query = "SET FOREIGN_KEY_CHECKS = 0;
SELECT @@FOREIGN_KEY_CHECKS;
Insert ignore into products_cat(product) values {$values};
SET FOREIGN_KEY_CHECKS = 1;";

$result = $link->multi_query($query) or die("Error in the consult3.." . mysqli_error($link));



?>
