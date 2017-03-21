cd C:/mpulse/scripts/postcash/

echo "Performing Close Cash"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" doCloseCash.php >> log.txt

echo "Post Cash and Transactions"
"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" postTransactions.php >> log.txt

exit

