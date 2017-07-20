<?php

require_once('c:/mpulse/scripts/functions.php');


$currentDate="2017-08-06";

$query = "SELECT articlecategory, startdate, enddate FROM promo_header WHERE enddate='$currentDate' AND TYPE=1 AND remote='ACKNOWLEDGED';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$subcats=array();
$subcatProfile=getSubcatSticks();

while ($row = mysqli_fetch_assoc($result)) {
	$subcats[$row['articlecategory']]=$row['startdate'];
}

foreach ($subcats as $subcat => $startdate){
	$sales[$subcat]=getProductSaleSticks($subcat,$startdate)/$subcatProfile[$subcat];
	$purchase[$subcat]=getProductPurchaseSticks($subcat,$startdate)/$subcatProfile[$subcat];

	$toScan[$subcat]=$purchase[$subcat]-$sales[$subcat];

	if ($toScan[$subcat] <= 0){
		unset($toScan[$subcat]);
	}

}


function getProductSaleSticks($subcat,$startdate){
	global $link;
	global $currentDate;

$query="SELECT SUM(tl.units*sticks) as sticks FROM receipts r
JOIN ticketlines tl ON r.id=tl.ticket
JOIN products p ON p.id=tl.product
join promo_header on 
WHERE datenew >='$startdate' AND datenew <='$currentDate' AND p.sub_category='$subcat';"

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$sticks=0;

while ($row = mysqli_fetch_assoc($result)) {
	$sticks=$row['sticks'];
}
return $sticks;
}

function getProductPurchaseSticks($subcat,$startdate){
	global $link2;
	global $currentDate;
	global $storeid;

$query="SELECT p.subcat,ROUND(SUM(dl.qty*p.sticks/pr.maxsticks)*pr.maxsticks,2) AS totalSticks 
FROM storeops.deliveryline dl
JOIN storeops.delivery d ON d.deliveryid=dl.deliveryid AND d.isinvoice=1
JOIN storeops.product p ON p.productid=dl.productid
JOIN storeops.productcat pr ON pr.subcat=p.subcat
WHERE  d.storeid='$storeid' AND DATE(d.createdon) >= '$startdate' AND DATE(d.createdon) <= '$currentDate' AND p.subcat='$subcat'
GROUP BY p.subcat;"

$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));

$sticks=0;

while ($row = mysqli_fetch_assoc($result)) {
	$sticks=$row['sticks'];
}
return $sticks;
}


function getSubcatSticks(){
	global $link2;

$query="SELECT subcat,maxsticks FROM productcat;"

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

while ($row = mysqli_fetch_assoc($result)) {
	$subcatProfile[$row['subcat']]=$row['maxsticks'];
}
return $subcatProfile;
}

?>