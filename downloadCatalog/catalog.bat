cd C:/mpulse/scripts/downloadCatalog/

echo "Downloading Catalog"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" getCatalog.php >> log.txt

echo "Setting Percentages"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setPercentages.php

exit