Taskkill /IM javaw.exe /F

echo "Setting Configuration"

cd C:/mpulse/scripts/configset/

"C:\mpulse\assets\php_compiler\php5.4.16\php.exe" transUpdate.php >> updatelog.txt

exit