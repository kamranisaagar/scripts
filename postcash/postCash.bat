Taskkill /IM javaw.exe /F

ren C:\unicenta\unicenta.jar unicenta.mpulse

cd C:/mpulse/scripts/postcash/

echo "Performing Close Cash"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" doCloseCash.php

ren C:\unicenta\unicenta.mpulse unicenta.jar

exit

