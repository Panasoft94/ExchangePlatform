@echo off
setlocal

set "PROJECT_ROOT=%~dp0"
set "WAMP_EXE=C:\wamp64\wampmanager.exe"

if exist "%WAMP_EXE%" (
    start "WampServer" "%WAMP_EXE%"
) else (
    echo WampServer non trouve a l'emplacement %WAMP_EXE%
)

where npm >nul 2>nul
if errorlevel 1 (
    echo npm n'est pas disponible sur cette machine.
    pause
    exit /b 1
)

if not exist "%PROJECT_ROOT%node_modules\ws" (
    echo Installation des dependances Node.js...
    pushd "%PROJECT_ROOT%"
    call npm install
    popd
)

start "Salon Reunion Local" cmd /k "cd /d ""%PROJECT_ROOT%"" && call npm run visio:signal"
start "Salon Reunion TURN" cmd /k "cd /d ""%PROJECT_ROOT%"" && call npm run visio:turn"

echo Le serveur local de reunion est en cours de demarrage.
echo Une fenetre WampServer, une fenetre de signalisation et une fenetre TURN doivent s'ouvrir.
endlocal
