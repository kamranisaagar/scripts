<?php

require_once('../functions.php');

$query = "UPDATE ticketlines l, receipts r, tickets t
SET l.REMOTE = 'DELIVERED'
WHERE r.DATENEW > '2017-05-29'
AND r.id = t.id
AND t.id = l.ticket;";
			  
$result = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

echo "Affected rows: " . mysqli_affected_rows($link);

?>