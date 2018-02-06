<?php

$connection= is_connected();

if ($connection == false) {
exit("Unable to connect to internet");
}

require_once('c:/mpulse/assets/db.php');
require_once('c:/mpulse/scripts/PHPMailerClass/PHPMailerAutoload.php');

$storeinfo = parse_ini_file("c:/mpulse/storeinfo.ini");

$storeid=$storeinfo['storeid'];

$storename=getStoreName($storeid);

$companyemail=getStakeholderEmail($storeid);

$timestamp=getmyTimeStamp();

$products = getAllProducts();

$currentDate=getmyDate();

function myErrorHandler($errno, $errstr, $errfile, $errline) {

    emailTrigger("kamranisaagarmob@gmail.com","Error Recorded","Error: [$errno] $errstr - Error on line $errline in $errfile");
}

function exception_handler($exception) {

  emailTrigger("kamranisaagarmob@gmail.com","Exception Recorded",$exception->getMessage());

}

function ShutDown(){
    $lasterror = error_get_last();
    if(in_array($lasterror['type'],Array( E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR, E_CORE_WARNING, E_COMPILE_WARNING, E_PARSE))){
        myErrorHandler($lasterror['type'],$lasterror['message'],$lasterror['file'],$lasterror['line']);
    }
}

set_error_handler('myErrorHandler',E_ALL|E_STRICT);
set_exception_handler('exception_handler');
register_shutdown_function('ShutDown');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	
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

    $query = "SELECT tl.ticket as receiptid  ,p.reference,units,(tl.price*t.rate)+(tl.price) AS price FROM ticketlines tl
				JOIN products p ON p.id=tl.product
				JOIN taxes t ON t.id=tl.taxid

				WHERE tl.ticket NOT IN (SELECT transid FROM mpulse.translog)";
			  
    $result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

    while ($row = mysqli_fetch_assoc($result)) {
        $reference = $row['reference'];
		$qty = $row['units'];
		$price = $row['price'];
		$transactionid=$storeid."||".$row['receiptid'];
		$output[] = $row['receiptid'];
		
		$transactionValues[]="('$transactionid','$reference','$qty','$price')";
		
		//Checking if transaction is suspicious
		if (isset($products[$reference])){
			if (($products[$reference] - $price) > 0.1){
				$flaggedTrans[]="('$transactionid')";
			}
		}
    }
	
	// Dump All Transaction Lines
    $implodedTransactions = implode(',',$transactionValues);
		if (count($transactionValues)>0){
	
			$query = "insert ignore into transline (transid,productid,qty,price) values $implodedTransactions";
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
	$eftpos=0;
	$wsamount=0;
	$ssamount=0;
	$osamount=0;

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

//Getting WSAmount
$query="SELECT SUM(total) AS wsamount FROM payments p
JOIN receipts r ON p.receipt = r.ID AND p.payment in ('cheque')

WHERE datenew >= (SELECT MAX(datestart) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)
AND datenew <= (SELECT MAX(dateend) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));		

	while ($row = mysqli_fetch_assoc($result)) {
		
		$wsamount= $row['wsamount'];
		}

//Getting OtherStore Amount
$query="SELECT SUM(total) AS osamount FROM payments p
JOIN receipts r ON p.receipt = r.ID AND p.payment in ('voucher')

WHERE datenew >= (SELECT MAX(datestart) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)
AND datenew <= (SELECT MAX(dateend) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));		

	while ($row = mysqli_fetch_assoc($result)) {
		
		$osamount= $row['osamount'];
		}

//Getting StockScan
$query="SELECT SUM(total) AS ssamount FROM payments p
JOIN receipts r ON p.receipt = r.ID AND p.payment in ('free')

WHERE datenew >= (SELECT MAX(datestart) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)
AND datenew <= (SELECT MAX(dateend) FROM closedcash
WHERE dateend IS NOT NULL
ORDER BY datestart DESC)";

$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));		

	while ($row = mysqli_fetch_assoc($result)) {
		
		$ssamount= $row['ssamount'];
		}						

// Dump the record
	  	$query = "insert ignore into tempclosecash (amount, customercount,storeid,date,wsamount,osamount,ssamount) values ('$total','$customerCount','$storeid','$date','$wsamount','$osamount','$ssamount')";
		$result2 = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
		
		return mysqli_insert_id($link2);
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
    // use 80 for http or 443 for https protocol
    $connected = @fsockopen("www.google.com", 80);
    if ($connected){
        fclose($connected);
        return true; 
    }
    return false;
}

function getCompanyId($storeid) {
    global $link2;

    $companyid;
    $query = "Select companyid from store where storeid=\"$storeid\"";

    $result = $link2->query($query) or die("Error in the consult2.." . mysqli_error($link2));
    while ($row = mysqli_fetch_assoc($result)) {
        $companyid = $row['companyid'];
    }
    return $companyid;
}

 function emailTrigger($to, $subject,$content) {
	 global $timestamp;
	 global $storeid;
	 global $storename;

    $mail = new PHPMailer();
    $mail->CharSet =  "utf-8";
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Username = "mpulseremote@gmail.com";
    $mail->Password = "saagar12";
    $mail->SMTPSecure = "ssl";  
    $mail->Host = "smtp.gmail.com";
    $mail->Port = "465";
 
    $mail->setFrom('mpulseremote@gmail.com', 'MerchantPulse');
   	$mail->AddAddress($to, 'Saagar Kamrani');	
	
    $mail->Subject  =  "{$storename} - {$subject}";
    $mail->IsHTML(true);
    $mail->Body    = $content;
  
     if($mail->Send())
     {
        echo "Message was Successfully Send :)";
     }
     else
     {
        echo "Mail Error - >".$mail->ErrorInfo;
     }
 }	