cd C:/mpulse/scripts/scheduler/

echo "Executing Scheduler"

call phpiniFix.bat

"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" execute.php

cd C:/mpulse/scripts/downloadCatalog/

echo "Downloading Catalog"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" getCatalog.php

cd C:/mpulse/scripts/postcash/

echo "Posting Transactions"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" postTransactions.php
