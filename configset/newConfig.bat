echo "Setting Configuration"
del C:\Unicenta\unicentaopos.properties

cd C:/mpulse/scripts/configset/

rem "C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setConfig.php

copy unicentaopos.properties C:\Unicenta\unicentaopos.properties
exit