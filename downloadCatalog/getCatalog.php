<?php
require_once('c:/mpulse/scripts/functions.php');

set_time_limit(0);   

ini_set('mysql.connect_timeout','0');   

ini_set('max_execution_time', '0'); 


$companyid=getCompanyId($storeid);

//Get Promotions

$query = "SELECT CONCAT('ON',p.promoid) as promoid, promoname, DATE_FORMAT(startdate,'%Y%m%d') as startdate, 
DATE_FORMAT(enddate,'%Y%m%d') as enddate, '0', '24', subcat, '2', amount, ctnamount, REPLACE(REPLACE(disabled, '1', 'RECALLED_ACK'),'0','ACKNOWLEDGED') as remote, disabled as markedexpired  FROM promotions p

JOIN promostore ps ON p.promoid=ps.promoid AND ps.storeid={$storeid} and year(enddate) >= '2019'";
			  
$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));

while ($row = mysqli_fetch_assoc($result)) {	
	
	$fields=array();

	foreach($row as $field) {
		$fields[]="'".$field."'";
	}

	$val[]="(".implode(",",$fields).")";

}

$values = implode(",", $val);

$query = "INSERT INTO promo_header(id,name,startdate,enddate,starthour,endhour,articlecategory,type,amount,ctnamount,remote,markedexpired) 
values {$values}

ON DUPLICATE KEY UPDATE
	AMOUNT     = VALUES(AMOUNT),
	CTNAMOUNT = VALUES(CTNAMOUNT),
	STARTDATE=VALUES(STARTDATE),
	ENDDATE=VALUES(ENDDATE),
	CTNAMOUNT=VALUES(CTNAMOUNT),
	TYPE=VALUES(TYPE),
	ARTICLECATEGORY=VALUES(ARTICLECATEGORY),
	REMOTE=VALUES(REMOTE),
	MARKEDEXPIRED=VALUES(MARKEDEXPIRED);";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));	

//ReInit Arrays
$val = array();
$fields=array();

//Setting DefaultCategory
$query = "insert ignore into categories(id,name,parentid) values ('DefaultCategory','View Catalog',null);";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

// Inserting Categories
$query = "select categoryid as categoryid,categoryname,'DefaultCategory' from category where companyid={$companyid} or categoryid='ON-19'";
			  
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
$query = "select categoryid as categoryid from category where isvisible=1 and (companyid={$companyid} or categoryid='ON-19')";
			  
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


$query = "SELECT p.productid AS id, p.productid AS ref, barcode, productname AS productname, cost, IFNULL(sp.saleprice,p.saleprice)/(t.taxperc+1) AS pricesell, p.categoryid AS categoryid, p.taxid AS taxid, isvariable,productname AS display, null as sub_category  
FROM product p
JOIN category c ON c.categoryid=p.categoryid AND (c.companyid={$companyid} or  c.categoryid='ON-19')
LEFT JOIN storeproduct sp ON sp.productid=p.productid AND sp.storeid={$storeid}
JOIN taxclass t ON t.taxid = p.taxid

where p.isactive=1 AND updated_at BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()";

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
SET FOREIGN_KEY_CHECKS = 1;

UPDATE products_cat 
JOIN products ON products.reference=products_cat.product
SET products_cat.product=products.id";

$result = $link->multi_query($query) or die("Error in the consult3.." . mysqli_error($link));



//Truncating
$query0 = "delete from dwh_storesubcat_flat where storeid={$storeid}";	 
$result0 = $link2->query($query0) or die("Error in the consult.." . mysqli_error($link2));

//Get Prices
$query_new = "SELECT p1.sub_category, round(IFNULL(t1.pricesell,0),2) AS ctnprice, round(IFNULL(t2.pricesell,0),2) AS pktprice FROM products p1

LEFT JOIN (SELECT p.sub_category,pricebuy,IFNULL(((pricesell*0.1)+pricesell)-pr.ctnamount,(pricesell*0.1)+pricesell) AS pricesell FROM products p
LEFT JOIN (SELECT articlecategory AS subcat,SUM(amount) AS pktamount,SUM(ctnamount) AS ctnamount
FROM promo_header pr

WHERE DATE(pr.startdate) <= CURDATE() AND DATE(pr.enddate) >= CURDATE() 
AND pr.markedexpired=0 AND (remote ='ACKNOWLEDGED' OR remote='RECEIVED')
GROUP BY articlecategory) pr ON p.sub_category=pr.subcat

WHERE p.category='001' AND sub_category IS NOT NULL
GROUP BY p.sub_category) t1 ON t1.sub_category=p1.sub_category

LEFT JOIN (SELECT p.sub_category,pricebuy,IFNULL(((pricesell*0.1)+pricesell)-pr.pktamount,(pricesell*0.1)+pricesell) AS pricesell FROM products p
LEFT JOIN (SELECT articlecategory AS subcat,SUM(amount) AS pktamount,SUM(ctnamount) AS ctnamount
FROM promo_header pr

WHERE DATE(pr.startdate) <= CURDATE() AND DATE(pr.enddate) >= CURDATE() 
AND pr.markedexpired=0 AND (remote ='ACKNOWLEDGED' OR remote='RECEIVED')
GROUP BY articlecategory) pr 
ON p.sub_category=pr.subcat

WHERE p.category IN ('002','006') AND sub_category IS NOT NULL
GROUP BY p.sub_category) t2 ON t2.sub_category=p1.sub_category

WHERE p1.category IN ('001','002','004','006') AND p1.sub_category IS NOT NULL
GROUP BY p1.sub_category";

$result_new = $link->query($query_new) or die("Error in the consult2.." . mysqli_error($link));

while ($row = mysqli_fetch_assoc($result)) {

$subcat[$row['sub_category']]=$row['sub_category'];	
$packet_price[$row['sub_category']]=$row['pktprice'];
$carton_price[$row['sub_category']]=$row['ctnprice'];
}

foreach ($subcat as $item) {
$query1 = "insert ignore into dwh_storesubcat_flat(subcat, storeid, packet_price, carton_price) 
values ('{$subcat[$item]}','{$storeid}','{$packet_price[$item]}','{$carton_price[$item]}')";	 
$result1 = $link2->query($query1) or die("Error in the consult.." . mysqli_error($link2));
}



?>
