Taskkill /IM javaw.exe /F

echo "Setting New Config"
cd configset
call "newConfig.bat"

echo "Post Cash and Transactions"
cd ../postcash
call "prompt.bat"


