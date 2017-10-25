Taskkill /IM javaw.exe /F

del C:/mpulse/assets/db.php

copy C:/mpulse/scripts/temp/db.php C:/mpulse/assets/db.php

echo "Setting Properties"

cd C:/mpulse/scripts/configset/

"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setProperties.php

"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setConfig.php

"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setPercentages.php

exit