cd C:/mpulse/scripts/downloadCatalog/

echo "Upload Prices"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" getPrices.php >> log.txt

echo "Downloading Catalog"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" getCatalog.php >> log.txt

echo "Setting Percentages"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setPercentages.php

exit
