<?php

require_once('c:/mpulse/scripts/functions.php');


$currentDate="2017-08-06";

$query = "SELECT articlecategory, startdate, enddate FROM promo_header WHERE date(enddate)='$currentDate' AND TYPE=1 AND remote='ACKNOWLEDGED';";

			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$subcats=array();
$subcatProfile=getSubcatSticks();
$parents=array();
$parents=getParents();


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

print_r($sales);
print_r($purchase);
print_r($toScan);


function getProductSaleSticks($barcode,$startdate){
	global $link;
	global $currentDate;
	global $parentChilds;

$query="SELECT SUM(tl.units*sticks) as sticks FROM receipts r
JOIN ticketlines tl ON r.id=tl.ticket
JOIN products p ON p.id=tl.product
WHERE datenew >='$startdate' AND datenew <='$currentDate' AND (p.code='$barcode' 
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

$query="SELECT p.subcat,ROUND(SUM(dl.qty*p.sticks/pr.maxsticks)*pr.maxsticks,2) AS sticks 
FROM storeops.deliveryline dl
JOIN storeops.delivery d ON d.deliveryid=dl.deliveryid AND d.isinvoice=1
JOIN storeops.product p ON p.productid=dl.productid
JOIN storeops.productcat pr ON pr.subcat=p.subcat
WHERE  d.storeid='$storeid' AND DATE(d.createdon) >= '$startdate' AND DATE(d.createdon) <= '$currentDate' AND p.barcode='$barcode'
GROUP BY p.barcode;";

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

$query="SELECT * FROM product WHERE category in ('001','006') AND parent IS NOT NULL AND subcat IS NOT NULL;";

$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));

while ($row = mysqli_fetch_assoc($result)) {
	$arrayvar[$row['barcode']]=$row['subcat'];
}
return $arrayvar;
}


?>