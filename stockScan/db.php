<?php 
   //PHP Connection
   defined('DB_User')?:  define('DB_User', 'root');
   defined('DB_Password')?: define('DB_Password','T$Gunicenta1');
   defined('DB_Host')?: define('DB_Host','localhost:3306');
   defined('DB_name')?: define('DB_name','unicenta');
   $link = mysqli_connect(DB_Host,DB_User,DB_Password,DB_name) or die("Error Making Connection" . mysqli_error($link));
   $link2 = mysqli_connect("166.62.28.82","sindhizc","Bazinga#123","sindhiz_db") or die("Error Making Connection" . mysqli_error($link2));
   ?>