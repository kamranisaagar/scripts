<?php

$query = "UPDATE products SET pricebuy='184.84' WHERE category='001' AND sub_category='HOR93'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='18.48' WHERE category='002' AND sub_category='HOR93'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='196.39' WHERE category='001' AND sub_category='JPS25'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='24.55' WHERE category='002' AND sub_category='JPS25'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='157.33' WHERE category='001' AND sub_category='JPS03'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='26.22' WHERE category='002' AND sub_category='JPS03'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='147.05' WHERE category='001' AND sub_category='JPS40'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='36.76' WHERE category='002' AND sub_category='JPS40'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='222.63' WHERE category='001' AND sub_category='PST25'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='27.83' WHERE category='002' AND sub_category='PST25'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='28.08' WHERE sub_category='CHA25'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

$query = "UPDATE products SET pricebuy='24.00' WHERE sub_category='JPSR2'";
$result = $link->query($query) or die("Error in the consult3.." . mysqli_error($link));

echo "Price Rise Successful";


?>
