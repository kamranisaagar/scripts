cd C:/mpulse/scripts/scheduler/

echo "Executing Scheduler"

"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" execute.php

cd C:/mpulse/scripts/downloadCatalog/

echo "Downloading Catalog"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" getCatalog.php
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" setPercentages.php

cd C:/mpulse/scripts/stockScan/

echo "Stock Scanner"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" rebateScan.php
