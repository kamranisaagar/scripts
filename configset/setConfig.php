<?php

require_once('c:/mpulse/scripts/functions.php');
require_once('roles.php');

$query = "UPDATE people SET visible=FALSE WHERE id NOT IN ('0','1','2','3');";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE people SET apppassword='sha1:8974B8371B6EF83D5E4699EA78BE68BE596453FE' WHERE id='0';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE people SET apppassword='sha1:04E8696E6424C21D717E46008780505D598EB59A' WHERE id='1';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE people SET apppassword= NULL WHERE id NOT IN ('0','1');";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE roles SET permissions='{$managerRole}' WHERE id='1';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE roles SET permissions='{$empRole}' WHERE id='2';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));