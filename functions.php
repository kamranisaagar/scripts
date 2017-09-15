<?php

require_once('c:/mpulse/assets/db.php');

if ($connection == false) {
exit("Unable to connect to internet");
}

$storeinfo = parse_ini_file("c:/mpulse/storeinfo.ini");

$storeid=$storeinfo['storeid'];

$storename=getStoreName($storeid);

$companyemail=getStakeholderEmail($storeid);

$timestamp=getmyTimeStamp();

$products = getAllProducts();

$currentDate=getmyDate();
	
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

    $query = "SELECT r.id AS receiptid, t.ticketid AS ticketid, r.datenew AS newtstamp,p.payment AS ptype,SUM(p.total) AS total FROM receipts r
			  JOIN payments p ON p.receipt=r.ID
			  JOIN tickets t ON t.id=r.id
			  WHERE r.id NOT IN (SELECT transid FROM mpulse.translog)
			  GROUP BY r.id";
			  
    $result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
    $output = array();
	
	$transactionValues = array();
	
    while ($row = mysqli_fetch_assoc($result)) {
		
		
		$transactionid=$storeid."||".$row['receiptid'];
		$ticketid=$row['ticketid'];
		$timestamp = $row['newtstamp'];
		$transtypeid = getPaymentType($row['ptype']);
		$total = $row['total'];
		
		$transactionValues[]="('$transactionid','$ticketid','$storeid','$timestamp','$transtypeid','$total')";
    }
	
	$implodedTransactions = implode(',',$transactionValues);
	
	if (count($transactionValues)>0){
	// Dump All Transactions
	$query = "insert ignore into transaction(transid,ticketid,storeid,timestamp,transtypeid,total) values $implodedTransactions";  
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
		
		//Checking if transaction is suspicious
		if (isset($products[$barcode])){
			if (($products[$barcode] - $price) > 0.1){
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

	else if($pString == "voucher")
	{
		return "4";
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

// Getting Last CloseCash ID

$query="SELECT money FROM closedcash WHERE HOST='TSG'

AND datestart=(SELECT MAX(datestart) FROM closedcash

WHERE dateend IS NOT NULL)";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

	while ($row = mysqli_fetch_assoc($result)) {
		$money= $row['money'];
		}
	
//Getting Customer Count and Totals	
$query="SELECT SUM(p.total) as total, COUNT(r.id) as cc, curdate() as date

FROM payments p

JOIN receipts r ON p.receipt = r.id

WHERE r.money= '$money' and p.payment not in ('voucher','cheque','free')";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

while ($row = mysqli_fetch_assoc($result)) {		
		$total= $row['total'];
		$customerCount = $row['cc'];
		$date = $row['date'];
		}

// Getting Cashout Amount
$query="SELECT SUM(price) AS cashout FROM ticketlines tl
JOIN receipts r ON r.id=tl.ticket
WHERE r.money= '$money' AND tl.product='ON-cashoutbtn'";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));		

	while ($row = mysqli_fetch_assoc($result)) {
		
		$cashout= $row['cashout'];
		}

//Adjusting Total Sale
$total=$total-$cashout;						

//Getting EFT
$query="SELECT SUM(total) AS eft FROM payments p
JOIN receipts r ON p.receipt = r.ID AND p.payment in ('card','surcharge')

WHERE datenew >= (SELECT MAX(datestart) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)
AND datenew <= (SELECT MAX(dateend) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));		

	while ($row = mysqli_fetch_assoc($result)) {
		
		$eftpos= $row['eft'];
		}

// Dump the record
	  	$query = "insert ignore into tempclosecash (amount, customercount,storeid,eftpos,date) values ('$total','$customerCount','$storeid','$eftpos','$date')";
		$result2 = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
		
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

function getTSGID($storeid){
	global $link2;
		
	//Get Franchise ID
	$query = "select * from store where storeid={$storeid}";
			
	$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));

		while ($row = mysqli_fetch_assoc($result)) {
			$franchiseid=$row['tsgid'];
		}
	return $franchiseid;
}

function getStoreName($storeid) {
    global $link2;

    $name;
    $query_storename = "
		Select storename from store where storeid=\"$storeid\"";

    $storename_result = $link2->query($query_storename) or die("Error in the consult2.." . mysqli_error($link2));
    while ($row_storename = mysqli_fetch_assoc($storename_result)) {
        $name = $row_storename['storename'];
    }
    return $name;
}

function getStakeholderEmail($storeid) {
    global $link2;

    $email;
    $query_companyemail = "
		Select companyemail from company c join store s on c.companyid=s.companyid and s.storeid=\"$storeid\"";

    $companyemail_result = $link2->query($query_companyemail) or die("Error in the consult2.." . mysqli_error($link2));
    while ($row_email = mysqli_fetch_assoc($companyemail_result)) {
        $email = $row_email['companyemail'];
    }
    return $email;
}

function is_connected()
{
  $connected = fopen("http://www.google.com:80/","r");
  if($connected)
  {
     return true;
  } else {
   return false;
  }

}		