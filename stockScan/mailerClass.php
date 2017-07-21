<?php 

 require_once('c:/mpulse/scripts/PHPMailerClass/PHPMailerAutoload.php');
 
 function emailItems($to, $items) {
	 global $timestamp;
	 global $storeid;
	 $itemsString ="";
	 foreach ($items as $barcode => $qty){
		 $name=key(getProductID($barcode));
		 $itemsString .= $name.": ".$qty."<br>";
	 }
 
    $mail = new PHPMailer();
    $mail->CharSet =  "utf-8";
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Username = "mpulseremote@gmail.com";
    $mail->Password = "saagar12";
    $mail->SMTPSecure = "ssl";  
    $mail->Host = "smtp.gmail.com";
    $mail->Port = "465";
 
    $mail->setFrom('mpulseremote@gmail.com', 'MerchantPulse');
    $mail->AddAddress($to, 'Mpulse Subscriber');
 
    $mail->Subject  =  "Items Scanned {$timestamp} - {$storeid}";
    $mail->IsHTML(true);
    $mail->Body    = "Following items have been scanned ,
                        <br /> {$itemsString}
                        ";
  
     if($mail->Send())
     {
        echo "Message was Successfully Send :)";
     }
     else
     {
        echo "Mail Error - >".$mail->ErrorInfo;
     }
 } 
?>