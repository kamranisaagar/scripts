<?php

require_once('c:/mpulse/scripts/functions.php');

    global $link;
	global $link2;

    $query = "select * from store where storeid='{$storeid}'";
			  
    $result = $link2->query($query) or die("Error in the consult.." . mysqli_error($link2));
   
    while ($row = mysqli_fetch_assoc($result)) {
		
		$ctnperc=$row['ctnperc'];
		$pktperc=$row['pktperc'];
		$tobacperc = $row['tobacperc'];
	}
	
	$query = "update categories set defaultmargin={$ctnperc} where id='001'";  
	$result2 = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
	
	$query = "update categories set defaultmargin={$pktperc} where id='002'";  
	$result2 = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
	
	$query = "update categories set defaultmargin={$tobacperc} where id='006'";  
	$result2 = $link->query($query) or die("Error in the consult.." . mysqli_error($link));
	
	$query = "UPDATE products p
	JOIN categories c ON c.id=p.category
	SET p.pricesell=((p.pricebuy*(c.defaultmargin/100))+p.pricebuy)/1.1
	WHERE p.category IN ('001','002','006')";
	
	$result3 = $link->query($query) or die("Error in the consult.." . mysqli_error($link));

?>
