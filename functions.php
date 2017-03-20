<?php

require_once('c:/mpulse/assets/db.php');

$products = getAllProducts();
	
function getmyDate() {
    date_default_timezone_set("Australia/Canberra");
    return date('Y-m-d');
}

function getmyTimeStamp() {
    date_default_timezone_set("Australia/Canberra");
    return date('Y-m-d H:i:s');
}

function putTransactionLog($storeid) {
    global $link;
	global $link2;

    $query = "SELECT r.id AS receiptid,r.datenew AS newtstamp,p.payment AS ptype,sum(p.total) AS total FROM receipts r
			  JOIN payments p ON p.receipt=r.ID
			  WHERE r.id NOT IN (SELECT transid FROM mpulse.translog)
			  group by r.id";
			  
    $result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
    $output = array();
	
	$transactionValues = array();
	
    while ($row = mysqli_fetch_assoc($result)) {
		
		
		$transactionid=$storeid."||".$row['receiptid'];
		$timestamp = $row['newtstamp'];
		$transtypeid = getPaymentType($row['ptype']);
		$total = $row['total'];
		
		$transactionValues[]="('$transactionid','$storeid','$timestamp','$transtypeid','$total')";
    }
	
	$implodedTransactions = implode(',',$transactionValues);
	
	if (count($transactionValues)>0){
	// Dump All Transactions
	$query = "insert ignore into transaction(transid,storeid,timestamp,transtypeid,total) values $implodedTransactions";  
	$result2 = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));	
	}
	
}

function putTransactionLine($storeid) {
    global $link;
	global $link2;
	global $products;
	
	$output = array();
	
	$flaggedTrans=array();
	
	$transactionValues = array();

    $query = "SELECT tl.ticket as receiptid  ,p.code,units,(tl.price*t.rate)+(tl.price) AS price FROM ticketlines tl
				JOIN products p ON p.id=tl.product
				JOIN taxes t ON t.id=tl.taxid

				WHERE tl.ticket NOT IN (SELECT transid FROM mpulse.translog)";
			  
    $result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

    while ($row = mysqli_fetch_assoc($result)) {
        $barcode = $row['code'];
		$qty = $row['units'];
		$price = $row['price'];
		$transactionid=$storeid."||".$row['receiptid'];
		$output[] = $row['receiptid'];
		
		$transactionValues[]="('$transactionid','$barcode','$qty','$price')";
		+
		//Checking if transaction is suspicious
		if (isset($products[$barcode])){
			if ($products[$barcode] - $price > 0.10){
				$flaggedTrans[]="('$transactionid')";
			}
		}
    }
	
	// Dump All Transaction Lines
    $implodedTransactions = implode(',',$transactionValues);
		if (count($transactionValues)>0){
	
			$query = "insert ignore into transline (transid,barcode,qty,price) values $implodedTransactions";
			$result2 = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
		}
	
	
	//Dump all Flagged Transactions
	$flaggedTrans = array_unique($flaggedTrans);
	$implodedFlags = implode(',',$flaggedTrans);	
	if (count($flaggedTrans)>0){
	// Dump All Transaction Lines
			$query = "insert ignore into flaggedTrans (transid) values $implodedFlags";
			$result3 = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
		}
	return array_unique($output);
}

function getPaymentType($pString){

	if($pString == "cheque")
	{
		return "2";
	}
	
	else if($pString == "free")
	{
		return "3";
	}
	
	else {
		return "1";
	}
	
}

function markTransactions($transactionids){
	global $link;
	
	
	foreach ($transactionids as $transactionid){
	
		// Record Insertion
			$query = "insert ignore into mpulse.translog values ('$transactionid')";
			  
			$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));		
	
	}

}

function postCash($storeid){
    global $link;
	global $link2;
	
$query="SELECT SUM(TICKETLINES.UNITS * TICKETLINES.PRICE*(1 + TAXES.RATE)) AS total, t.cc, CURDATE() AS date

FROM tickets 
INNER JOIN receipts ON tickets.ID = receipts.ID
INNER JOIN ticketlines ON tickets.ID = ticketlines.TICKET
INNER JOIN taxes ON taxes.ID = ticketlines.TAXID 

JOIN (SELECT COUNT(total) AS cc FROM receipts r
JOIN payments p ON r.id=p.receipt
WHERE datenew >= (SELECT MAX(datestart) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)
AND datenew <= (SELECT MAX(dateend) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)) AS t


WHERE datenew >= (SELECT MAX(datestart) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)
AND datenew <= (SELECT MAX(dateend) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)";

	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
	
	while ($row = mysqli_fetch_assoc($result)) {
		
		$total= $row['total'];
		$customerCount = $row['cc'];
		$date = $row['date'];
	  
	  // Dump the record
	  	$query = "insert ignore into tempclosecash (amount, customercount,storeid,date) values ('$total','$customerCount','$storeid','$date')";
		$result2 = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
		}
	  echo "Please wait...";
  }
  
function getAllProducts(){
	global $link;
	
	$products = array();
	
	//All Cartons
	
	$query = "SELECT p.code, ROUND(p.pricesell+(p.pricesell*t.rate)-IFNULL(SUM(ctnamount),0),2) AS pricesell FROM products p
			LEFT JOIN promo_header ph ON ph.articlecategory=p.sub_category AND startdate <= CURDATE() AND enddate >= CURDATE() AND ph.remote='ACKNOWLEDGED'
			JOIN taxes t ON t.id=p.taxcat

			WHERE p.category='001' and p.isvprice = false
			GROUP BY p.code";
			
	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

		while ($row = mysqli_fetch_assoc($result)) {
			$products[$row['code']]=$row['pricesell'];
		}
	
	//All other products
	
	$query = "SELECT p.code, ROUND(p.pricesell+(p.pricesell*t.rate)-IFNULL(SUM(amount),0),2) AS pricesell FROM products p
			LEFT JOIN promo_header ph ON ph.articlecategory=p.sub_category AND startdate <= CURDATE() AND enddate >= CURDATE() AND ph.remote='ACKNOWLEDGED'
			JOIN taxes t ON t.id=p.taxcat

			WHERE p.category<>'001' and p.isvprice = false
			GROUP BY p.code";
			
	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

		while ($row = mysqli_fetch_assoc($result)) {
			$products[$row['code']]=$row['pricesell'];
		}	
	
return $products;	
}