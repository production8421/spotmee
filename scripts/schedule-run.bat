@echo off
REM Laravel scheduler — run every minute from Windows Task Scheduler.
REM Program: cmd.exe
REM Arguments: /c "C:\path\to\spotmee\scripts\schedule-run.bat"

cd /d "%~dp0\.."
php artisan schedule:run --no-interaction --verbose
