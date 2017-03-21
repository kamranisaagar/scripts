cd C:/mpulse/scripts/postcash/

echo "Deleting Logs"
del /F /Q "C:\mpulse\scripts\postcash\logCC.txt"
del /F /Q "C:\mpulse\scripts\postcash\logPOST.txt"

echo "Performing Close Cash"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" doCloseCash.php >> logCC.txt

echo "Post Cash and Transactions"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" postTransactions.php >> logPOST.txt

exit

