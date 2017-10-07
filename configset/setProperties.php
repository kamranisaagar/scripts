<?php

require_once('c:/mpulse/scripts/functions.php');

$tsgid=getTSGID($storeid);

$propertiesFile="#unicentaopos.properties
#Mon Jun 19 19:47:55 AEST 2017
db.URL=jdbc\:mysql\://localhost\:3306/unicenta
db.driver=com.mysql.jdbc.Driver
db.driverlib=C\:\\\Unicenta\\\lib\\\mysql-connector-java-5.1.40-bin.jar
db.mysqlpath=C\:\\\Program Files\\\MySQL\\\MySQL Server 5.7\\\bin
db.password=crypt\:EA0785288F8105400E97FB8142236C49
db.user=root
format.currency=
format.date=
format.datetime=
format.double=
format.integer=
format.percent=
format.time=
ill.marineoption=false
machine.display=window
machine.hostname=TSG
machine.priceboarddisplay=Not Defined
machine.printer=epson\:file,COM8
machine.printer.2=epson\:file,COM8
machine.printer.3=Not defined
machine.printer.4=screen
machine.printer.5=screen
machine.printer.6=screen
machine.printername=(Default)
machine.scale=Not defined
machine.scanner=Not defined
machine.screenmode=window
machine.ticketsbag=standard
machine.uniqueinstance=false
paper.receipt.height=546
paper.receipt.mediasizename=A4
paper.receipt.width=190
paper.receipt.x=10
paper.receipt.y=287
paper.standard.height=698
paper.standard.mediasizename=A4
paper.standard.width=451
paper.standard.x=72
paper.standard.y=72
payment.commerceid=
payment.commercepassword=password
payment.eftposip=
payment.eftposport=
payment.gateway=external
payment.magcardreader=Not defined
payment.testmode=false
payments.textoverlay=false
pos.colortheme=-1
pos.promotionhighlightcolor=\#ff00cc
priceboard.fontsize=Large
priceboard.show=true
screensaver.enable=true
screensaver.picturesfolder=C\:\\\Uniback\\\Seafile\\\TSG Install\\\Unicenta\\\images
screensaver.timeout=300
screensaver.transitiontime=30
splashscreen.picturesfolder=C\:\\\Uniback\\\Seafile\\\TSG Install\\\Unicenta\\\splashscreenimages
splashscreen.transitiontime=30
start.logo=C\:\\\Uniback\\\Seafile\\\TSG Install\\\Unicenta\\\start\\\Logo.png
start.text=C\:\\\Uniback\\\Seafile\\\TSG Install\\\Unicenta\\\start\\\SplashText.txt
swing.defaultlaf=javax.swing.plaf.nimbus.NimbusLookAndFeel
table.customercolour=blue
table.showcustomerdetails=false
table.showwaiterdetails=false
table.tablecolour=black
table.waitercolour=red
till.amountattop=false
till.autoLogoff=false
till.autoLogoffrestaurant=false
till.autotimer=100
till.hideinfo=false
till.marineoption=false
till.pickupsize=1
till.pricewith00=false
till.receiptprefix=
till.receiptprintoff=true
till.receiptsize=1
till.surcharge=0
till.taxincluded=true
tsg.databaseserver=tobaccosgpos.com
tsg.defaultMargin=11
tsg.doupdates=true
tsg.franchiseid={$tsgid}
tsg.managestock=false
tsg.mode=LIVE
tsg.serverurl=http\://tobaccosgpos.com\:7080/TSG
tsg.splashscreenadv=true
tsg.updateinterval=60
user.country=AU
user.language=en
user.locale=English (Australia)
user.variant=
";

$filename="C:\Unicenta\unicentaopos.properties";

unlink($filename);

$file = fopen($filename,"w");
fwrite($file,$propertiesFile);
fclose($file);

?>
