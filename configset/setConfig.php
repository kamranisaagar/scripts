<?php

require_once('../functions.php');
require_once('roles.php');


$query = "UPDATE people SET visible=FALSE WHERE id NOT IN ('0','1','2','3');";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE people SET apppassword='sha1:38828E996B767B36BB04B64B1F08272547A522B1' WHERE id='0';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE people SET apppassword='sha1:04E8696E6424C21D717E46008780505D598EB59A' WHERE id='1';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE people SET apppassword= NULL WHERE id NOT IN ('0','1');";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE roles SET permissions='{$managerRole}' WHERE id='1';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE roles SET permissions='{$empRole}' WHERE id='2';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

