<?php

require_once('c:/mpulse/scripts/functions.php');

$query = "UPDATE ticketlines l, receipts r, tickets t SET l.REMOTE = 'DELIVERED' WHERE r.DATENEW like '2017-12-31%' AND r.id = t.id AND t.id = l.ticket;";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

?>