Taskkill /IM javaw.exe /F

echo "Setting Properties"

cd C:/mpulse/scripts/configset/

"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setProperties.php

del C:\unicenta\unicenta.jar
copy "unicenta.jar" "C:\unicenta\unicenta.jar" 

exit