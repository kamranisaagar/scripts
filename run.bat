setlocal
rem Find latest version of Java 8
for /d %%m in ("C:\Program Files\Java\jre1.8.*") do @(set JAVA_VERSION=%%m)
echo Using Java Version: %JAVA_VERSION%
echo ---

START "" "%JAVA_VERSION%\bin\javaw.exe" -jar ui.jar
exit