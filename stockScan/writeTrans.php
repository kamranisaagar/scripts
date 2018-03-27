<?php

function pushToPOS($items) {
	$unique = uniqid();
	$receiptid="SS-".$unique;
	$paymentid="SSPAY-".$unique;
	$money=getMoneyid();
	$ticketid=getTicketId();
	$taxlineid="SSTAX-".$unique;
	
	$products=getProducts($items);
	
	$transAmount=0;
	
	foreach ($products as $product){
		$transAmount = $transAmount + ($product[2]*$product[3]);
	}
	
	$taxedAmount=$transAmount+($transAmount*0.1);
	
	$tax=$taxedAmount-$transAmount;
	
	writeReceipt($receiptid,$money);
	writeTicket($receiptid, $ticketid);
	writeTicketLines($receiptid,$products);
	writePayment($paymentid,$receiptid,$taxedAmount);
	writeTaxLines($taxlineid,$transAmount,$tax,$receiptid);
	writeTicketsNum($ticketid);
}

function writeReceipt($receiptid,$money){
	global $link;
	$xml="<?xml version=\\\"1.0\\\" encoding=\\\"UTF-8\\\" standalone=\\\"no\\\"?><!DOCTYPE properties SYSTEM \\\"http://java.sun.com/dtd/properties.dtd\\\"><properties><comment>TSG POS</comment></properties>";
//	echo $xml;
    $query_writeReceipt = "INSERT INTO receipts VALUES (\"$receiptid\",\"$money\",NOW(),\"$xml\",NULL)";
   $result_writeReceipt = $link->query($query_writeReceipt) or die("Error in the consult.." . mysqli_error($link));
    echo "Written in Receipts<br><br>";
}

function writeTicket($receiptid, $ticketid){
	global $link;

    $query_writeTicket = "INSERT INTO tickets VALUES (\"$receiptid\",\"0\",\"$ticketid\",\"1\",NULL,\"0\")";
    $result_writeTicket = $link->query($query_writeTicket) or die("Error in the consult.." . mysqli_error($link));
    echo "Written in Tickets<br><br>";
}

function writeTicketLines($receiptid,$products){
	global $link;
	$line=0;
	foreach($products as $row) {
    $attributes_xml="<?xml version=\\\"1.0\\\" encoding=\\\"UTF-8\\\" standalone=\\\"no\\\"?><!DOCTYPE properties SYSTEM \\\"http://java.sun.com/dtd/properties.dtd\\\"><properties><comment>TSG POS</comment><entry key=\\\"product.taxcategoryid\\\">001</entry><entry key=\\\"product.warranty\\\">false</entry><entry key=\\\"product.verpatrib\\\">false</entry><entry key=\\\"product.name\\\">{$row[1]}</entry><entry key=\\\"product.service\\\">false</entry><entry key=\\\"product.com\\\">false</entry><entry key=\\\"PROMO\\\">Online Order {$receiptid}</entry><entry key=\\\"product.texttip\\\">{$row[1]}</entry><entry key=\\\"product.categoryid\\\">001</entry><entry key=\\\"product.vprice\\\">false</entry><entry key=\\\"product.kitchen\\\">false</entry></properties>";
	
	
    $query_writeTicketLines = "INSERT INTO ticketlines (ticket, line, product, attributesetinstance_id, units, price, taxid, attributes, remote, refunded) VALUES (\"$receiptid\",\"$line\",\"$row[0]\",NULL,\"$row[2]\",\"$row[3]\",\"001\",\"$attributes_xml\",\"DELIVERED\",\"0\")";
    $result_writeTicketLines = $link->query($query_writeTicketLines) or die("Error in the consult.." . mysqli_error($link));
	$line++;
	}
    echo "Written in TicketLines<br><br>";
}

function writePayment($paymentid,$receiptid,$paymentamount){
	global $link;

    $query_writePayment = "INSERT INTO payments (id, receipt, payment, total, paid, changegiven, transid, returnmsg, notes, createddate) VALUES (\"$paymentid\",\"$receiptid\",\"free\",\"$paymentamount\",\"$paymentamount\",\"$paymentamount\",NULL,\"OK\",NULL,now())";
    $result_writePayment = $link->query($query_writePayment) or die("Error in the consult.." . mysqli_error($link));
    echo "Written in Payments<br><br>";
}

function writeTaxLines($taxlineid,$untaxed_am,$total_tax,$receiptid)
{
	global $link;

    $query_writeTaxLine = "INSERT INTO taxlines VALUES (\"$taxlineid\",\"$receiptid\",\"001\",\"$untaxed_am\",\"$total_tax\")";
    $result_writeTaxLine = $link->query($query_writeTaxLine) or die("Error in the consult.." . mysqli_error($link));
    echo "Written in TaxLines<br><br>";
}

function writeTicketsNum($ticketid)
{
	global $link;
	$ticketid = $ticketid+1;

    $query_writeTicketsNum = "Update pickup_number set ID=\"$ticketid\"";
    $result_writeTicketsNum = $link->query($query_writeTicketsNum) or die("Error in the consult.." . mysqli_error($link));
    echo "Written in TicketsNum<br><br>";
}

function getMoneyId() {
	global $link;

    $query_moneyid = "select money from closedcash where hostsequence = (select max(hostsequence) from closedcash) and dateend is null";
    $result_moneyid = $link->query($query_moneyid) or die("Error in the consult.." . mysqli_error($link));
   
    while ($row_moneyid = mysqli_fetch_assoc($result_moneyid)) {
        $moneyid = $row_moneyid['money'];
    }
    return $moneyid;
}

function getTicketId() {
	global $link;

    $query_ticketid = "SELECT ID AS ticketid FROM pickup_number";
    $result_ticketid = $link->query($query_ticketid) or die("Error in the consult.." . mysqli_error($link));
   
    while ($row_ticketid = mysqli_fetch_assoc($result_ticketid)) {
        $ticketid = $row_ticketid['ticketid'];
    }
    return $ticketid;
}

function getProducts($items) {
    global $link2;

    $query = "SELECT * FROM freechoicepos.products";
    $result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
    $prices = array();

    while ($row = mysqli_fetch_assoc($result)) {
		$prices[$row['barcode']]=$row['pricesell'];		
    }
	
	foreach ($items as $barcode => $qty){
		$products[$barcode][0] = current(getProductID($barcode));
		$products[$barcode][1] = str_ireplace("&","and",key(getProductID($barcode)));
		$products[$barcode][2] = $qty;
		$products[$barcode][3] = $prices[$barcode]/1.1;
	}
	
    return $products;
}

function getProductID($sku) {
    global $link;

    $query_productid = "SELECT * FROM products WHERE CODE=\"$sku\"";
    $result_productid = $link->query($query_productid) or die("Error in the consult.." . mysqli_error($link));
	if(mysqli_num_rows($result_productid) == 0)
	{
	 $productid['Unknown Product']="ON-MISC123";	
	}
    else 
	{
	while ($row_productid = mysqli_fetch_assoc($result_productid)) {
        
			$productid[$row_productid['NAME']] = $row_productid['ID'];
		 }
    }
    return $productid;
}

?>
