@echo off
:A
cls
echo.
echo Close Cash Menu
echo.
set /p menu="Do you want to closecash? (Y/N): "
if %menu%==Y goto Yes
if %menu%==y goto Yes
if %menu%==N goto No
if %menu%==n goto No

cls
echo.
echo Please answer with Y/N!
echo.
set /p pause="Press any key to continue!... "
goto A
:Yes
cls
echo.
cd C:/mpulse/scripts/postcash/
postCash.bat
set /p pause="Press any key to exit!... "
exit

:No
cls
echo.
echo Okay, let's exit...
echo.
set /p pause="Press any key to exit!... "
exit