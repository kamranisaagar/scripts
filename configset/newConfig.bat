echo "Setting Configuration"
del C:\Unicenta\unicenta.jar
cd C:/mpulse/scripts/configset/
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setConfig.php
copy unicenta.jar C:\Unicenta\unicenta.jar
exit