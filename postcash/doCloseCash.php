<?php

require_once('../functions.php');

$currentTime=getmyTimeStamp();
$currentSequence = getSequenceNumber();
$currentCloseCashUID=getcurrentUID($currentSequence);

$count = checkTransactions($currentCloseCashUID);

$uniqueID = md5(uniqid());

if ($count > 0){
updateRecord();
insertRecord();
updateProperties();	
}

else {
	echo "No Transactions in Current Sequence.";
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
	
	$query="select max(hostsequence) as seq from closedcash";

	$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
	
	while ($row = mysqli_fetch_assoc($result)) {
		
	$sequence= $row['seq'];
	  }
	return $sequence;
  }
  
 function getcurrentUID($sequence){
	global $link;
	
	$query="select money as money from closedcash where hostsequence='$sequence'";

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
  