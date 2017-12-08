Taskkill /IM javaw.exe /F

echo "Setting Properties"

-- cd C:/mpulse/scripts/configset/

-- "C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setProperties.php

-- "C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setConfig.php

-- "C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setPercentages.php

schtasks /create /tn "MPulseSendTrans" /tr C:/mpulse/scripts/postcash/sendTrans.bat /sc minute /mo 60 /st 09:40

exit