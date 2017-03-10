copy /y C:\mpulse\scripts\php.ini C:\mpulse\assets\php_compiler\php5.4.16

echo "Post Cash and Transactions"
cd postcash
postCash.bat

rmdir C:\postcash\ /q /s 2>nul


