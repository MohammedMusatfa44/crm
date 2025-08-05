@echo off
cd /d "C:\Users\LENOVO\crm\crm-system"
echo Starting notification checker...
echo Press Ctrl+C to stop

:loop
php artisan notifications:check
timeout /t 30 /nobreak > nul
goto loop
