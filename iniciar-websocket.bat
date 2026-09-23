@echo off
cd /d "%~dp0"
title DriveGo - Servidor WebSocket
"C:\xampp\php\php.exe" websocket\servidor.php start
pause
