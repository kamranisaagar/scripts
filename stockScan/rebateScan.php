<?php
require_once('c:/mpulse/scripts/functions.php');
require_once('c:/mpulse/scripts/stockScan/writeTrans.php');
require_once('c:/mpulse/scripts/stockScan/mailerClass.php');


//$currentDate="2019-02-03";

$query = "SELECT articlecategory, startdate, enddate FROM promo_header ph
WHERE DATE(enddate)=CURDATE() AND TYPE=1 AND remote='RECEIVED'

AND articlecategory IN (SELECT sub_category FROM products WHERE supplier<>1 GROUP BY sub_category)

UNION
SELECT subcat, startdate,enddate FROM mpulse.recalled_promotions
WHERE DATE(enddate) =CURDATE() 
AND subcat IN (SELECT sub_category FROM products WHERE supplier<>1 GROUP BY sub_category);";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
$subcats=array();
$subcatProfile=getSubcatSticks();
$parents=array();
$newArray=getParents();
$parents=$newArray[0];
$prods=$newArray[1];

$toScan = array();
while ($row = mysqli_fetch_assoc($result)) {
	$subcats[$row['articlecategory']]=$row['startdate'];
}
foreach ($subcats as $subcat => $startdate){
	$subcatParents=array_keys($parents, $subcat);
	foreach ($subcatParents as $key => $barcode){
	$sales[$barcode]=getProductSaleSticks($barcode,$startdate)/$subcatProfile[$subcat];
	$purchase[$barcode]=getProductPurchaseSticks($barcode,$startdate)/$subcatProfile[$subcat];
	$toScan[$barcode]=ceil($purchase[$barcode]-$sales[$barcode]);
		if ($toScan[$barcode] <= 0){
			unset($toScan[$barcode]);
		}
	}	
}
if (count($toScan) > 0){
	pushToPOS($toScan);
	emailItems($companyemail,$toScan);
}
else {
	"No Stock to be scanned.";
}
function getProductSaleSticks($barcode,$startdate){
	global $link;
	global $currentDate;
	global $parentChilds;
$query="SELECT SUM(tl.units*sticks) as sticks FROM receipts r
JOIN ticketlines tl ON r.id=tl.ticket
JOIN products p ON p.id=tl.product
WHERE date(datenew) >='$startdate' AND date(datenew) <='$currentDate' AND (p.code='$barcode' 
OR 
p.code = (SELECT p.code AS child FROM products p
JOIN products pp ON p.reference=pp.sub_product AND pp.category='001' AND pp.code='$barcode'))";
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
$sticks=0;
while ($row = mysqli_fetch_assoc($result)) {
	$sticks=$row['sticks'];
}
return $sticks;
}
function getProductPurchaseSticks($barcode,$startdate){
	global $link2;
	global $currentDate;
	global $storeid;
	global $prods;	
	
	
	$prodid=$prods[$barcode];
	
$query="SELECT p.subcat,ROUND(SUM(dl.qty*p.sticks/pr.maxsticks)*pr.maxsticks,2) AS sticks 
FROM storeops.deliveryline dl
JOIN storeops.delivery d ON d.deliveryid=dl.deliveryid
join storeops.invoice i on i.invoiceid=d.invoiceid
JOIN storeops.product p ON p.productid=dl.productid
JOIN storeops.productcat pr ON pr.subcat=p.subcat
join storeops.store s on s.storeid=d.storeid
WHERE  d.storeid='$storeid' AND s.companyid=3 and DATE(i.deliverydate) >= '$startdate' AND DATE(i.deliverydate) <= '$currentDate' AND p.productid='$prodid'
GROUP BY p.productid;";


$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
$sticks=0;
while ($row = mysqli_fetch_assoc($result)) {
	$sticks=$row['sticks'];
}
return $sticks;
}
function getSubcatSticks(){
	global $link2;
$query="SELECT subcat,maxsticks FROM productcat;";
$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
while ($row = mysqli_fetch_assoc($result)) {
	$subcatProfile[$row['subcat']]=$row['maxsticks'];
}
return $subcatProfile;
}
function getParents(){
	global $link2;
$query="SELECT * FROM product WHERE categoryid in ('001','006') AND parentid IS NOT NULL AND subcat IS NOT NULL;";
$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
while ($row = mysqli_fetch_assoc($result)) {
	$arrayvar[$row['barcode']]=$row['subcat'];
	$arrayvar1[$row['barcode']]=$row['productid'];
}

$newArray[0]=$arrayvar;
$newArray[1]=$arrayvar1;

return $newArray;
}
?>
