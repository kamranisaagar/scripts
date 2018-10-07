<?php

require_once('c:/mpulse/scripts/functions.php');
require_once('roles.php');

$query = "UPDATE people SET visible=FALSE WHERE id NOT IN ('0','1','2','3');";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE people SET apppassword='sha1:8974B8371B6EF83D5E4699EA78BE68BE596453FE' WHERE id='0';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE people SET apppassword='sha1:CB58C376C77FA09772E5859694EB0176C2E0E3BA' WHERE id='1';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE people SET apppassword= NULL WHERE id NOT IN ('0','1');";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE roles SET permissions='{$managerRole}' WHERE id='1';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

$query = "UPDATE roles SET permissions='{$empRole}' WHERE id='2';";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
