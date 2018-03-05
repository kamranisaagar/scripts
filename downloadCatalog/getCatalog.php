<?php
require_once('c:/mpulse/scripts/functions.php');

$companyid=getCompanyId($storeid);

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

//Setting DefaultCategory
$query = "insert ignore into categories(id,name,parentid) values ('DefaultCategory','View Catalog',null);";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

// Inserting Categories
$query = "select categoryid as categoryid,categoryname,'DefaultCategory' from category where companyid={$companyid}";
			  
$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));

while ($row = mysqli_fetch_assoc($result)) {	
	
	$fields=array();

	
	foreach($row as $key => $value) {
		$fields[]="\"".$value."\"";
	}

	$val[]="(".implode(",",$fields).")";

}

$values = implode(",", $val);

$query = "insert ignore into categories(id,name,parentid) values {$values};";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));


// Enabling all active categories
$query = "select categoryid as categoryid from category where isvisible=1 and companyid={$companyid}";
			  
$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));

while ($row = mysqli_fetch_assoc($result)) {
	$activeCategories[]="\"".$row['categoryid']."\"";
}

$activeCats=implode(",",$activeCategories);

$query = "update categories set parentid='DefaultCategory', catshowname=true where id in ({$activeCats})";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "update categories set parentid=id, catshowname=false where id not in ({$activeCats});";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "update categories set parentid=null, catshowname=true where id='DefaultCategory';";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

//ReInit Arrays
$val = array();
$fields=array();


// Get Products
$query = "SELECT p.productid AS id, p.productid AS ref, barcode, productname AS productname, cost, IFNULL(sp.saleprice,p.saleprice)/1.1 AS pricesell, p.categoryid AS categoryid, taxid AS taxid, isvariable,productname AS display, null as sub_category  
FROM product p
JOIN category c ON c.categoryid=p.categoryid AND c.companyid={$companyid}
LEFT JOIN storeproduct sp ON sp.productid=p.productid AND sp.storeid={$storeid}";
			  
$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));

while ($row = mysqli_fetch_assoc($result)) {	
	
	$fields=array();

	foreach($row as $key => $value) {
		if ($key == "isvariable"){
			$fields[]=$value;
		}
		else{
			$fields[]="\"".$value."\"";
		}
	}

	$val[]="(".implode(",",$fields).")";

}

$values = implode(",", $val);

$query = "INSERT INTO products(ID, REFERENCE, CODE, NAME, PRICEBUY, PRICESELL, CATEGORY, TAXCAT, isvprice, display, sub_category) 
values {$values}

ON DUPLICATE KEY UPDATE
PRICEBUY = VALUES(PRICEBUY),
PRICESELL=VALUES(PRICESELL),
CATEGORY=VALUES(CATEGORY),
NAME=VALUES(NAME),
TAXCAT=VALUES(TAXCAT),
isvprice=VALUES(isvprice),
display=VALUES(display),
REFERENCE=VALUES(REFERENCE),
sub_category=null;";

$result = $link->query($query) or die("Error in the consult1.." . mysqli_error($link));

//ReInit Arrays
$val = array();
$fields=array();


// Showing Products in Catalog

$query = "SELECT productid as id from product where isvisible = 1;";
			  
$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));

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

$query = "UPDATE products_cat 
JOIN products ON products.reference=products_cat.product
SET products_cat.product=products.id";

$result = $link->query($query) or die("Error in the consult2.." . mysqli_error($link));

?>
