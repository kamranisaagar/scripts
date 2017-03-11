del /F /Q "C:\mpulse\clone.bat"

echo "Post Cash and Transactions"
cd postcash
postCash.bat

rmdir C:\postcash\ /q /s 2>nul


