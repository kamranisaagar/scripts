<?php
require_once('c:/mpulse/scripts/functions.php');
set_time_limit(0);   
ini_set('mysql.connect_timeout','0');   
ini_set('max_execution_time', '0'); 

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

while ($row = mysqli_fetch_assoc($result_new)) {

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
