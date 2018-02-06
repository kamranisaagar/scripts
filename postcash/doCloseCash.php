<?php

require_once('c:/mpulse/scripts/functions.php');

$currentTime=getmyTimeStamp();
$currentSequence = getSequenceNumber();
$currentCloseCashUID=getcurrentUID($currentSequence);

$count = checkTransactions($currentCloseCashUID);

$uniqueID = md5(uniqid());

if ($count > 0){
updateRecord();
insertRecord();
updateProperties();

// Post Close Shift
$tempid=postCash($storeid);
$resultSet= getVoidLines($currentSequence);
uploadVoidLines($resultSet,$tempid);
}

else {
	echo "No Transactions in Current Sequence";
}

	
function updateRecord(){
	global $link;
	global $currentTime;
	global $currentSequence;
	
	$query="update closedcash set dateend='$currentTime' where hostsequence='$currentSequence' and host='TSG'";
	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
  }

function insertRecord(){
	global $link;
	global $currentTime;
	global $currentSequence;
	global $uniqueID;
	
	$newSequence = $currentSequence+1;
	
	$query="insert into closedcash values ('$uniqueID','TSG','$newSequence','$currentTime',NULL,'0')";
	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
  }
  
function updateProperties(){
	global $link;
	global $uniqueID;
	
	$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>

<!DOCTYPE properties SYSTEM \"http://java.sun.com/dtd/properties.dtd\">

<properties>

<comment>TSG POS</comment>

<entry key=\"location\">0</entry>

<entry key=\"activecash\">$uniqueID</entry>

</properties>";
	
	$query="update resources set content='$xml' where name='TSG/properties'";
	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
  }


function getSequenceNumber(){
	global $link;
	
	$query="select max(hostsequence) as seq from closedcash where host='TSG'";

	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
	
	while ($row = mysqli_fetch_assoc($result)) {
		
	$sequence= $row['seq'];
	  }
	return $sequence;
  }
  
 function getcurrentUID($sequence){
	global $link;
	
	$query="select money as money from closedcash where hostsequence='$sequence' and host='TSG'";

	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
	
	$money="";
	
	while ($row = mysqli_fetch_assoc($result)) {
		
	$money= $row['money'];
	  }
	return $money;
  } 

  function checkTransactions($money){
	global $link;
	
	$query="SELECT COUNT(id) as count FROM receipts WHERE money='$money'";
	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
	
	$count=0;
	
	while ($row = mysqli_fetch_assoc($result)) {
		
	$count= $row['count'];
	  }

	  return $count;
  }

  function getVoidLines($sequence){
	global $link;

	$query="select * from closedcash where hostsequence='$sequence' and host='TSG'";
	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

	while ($row = mysqli_fetch_assoc($result)) {
	$start= $row['DATESTART'];
	$end= $row['DATEEND'];
	}
	
	$query="select * from lineremoved where removeddate >= '$start' and removeddate <= '$end'";
	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

	$resultSet = array();

	while ($row = mysqli_fetch_assoc($result)) {
		$resultSet[] = $row;
	}
	  return $resultSet;
  }

  function uploadVoidLines($resultSet,$tempid){
	global $link2;

	foreach ($resultSet as $arrayval)
	{
		$productname=$arrayval['PRODUCTNAME'];
		$qty=$arrayval['UNITS'];
		$datetime=$arrayval['REMOVEDDATE'];
		
		$query="insert into voidlines(datetime, productname, units, tempid) values ('$datetime','$productname','$qty','$tempid')";
		$result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
	}


  }
  