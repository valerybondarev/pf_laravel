@echo off
setlocal
    SET LOCAL_DOMAIN=paste_here_domain.gc
    SET DOCKER_CONTAINER_NAME="container_name"


    SET NEWLINE=^& echo.
    FIND /C /I "127.0.0.1 %LOCAL_DOMAIN%" %WINDIR%\system32\drivers\etc\hosts
    IF %ERRORLEVEL% NEQ 0 ECHO %NEWLINE%^127.0.0.1 %LOCAL_DOMAIN%>>%WINDIR%\System32\drivers\etc\hosts

    docker-compose up -d
    docker-compose exec %DOCKER_CONTAINER_NAME% composer create-project --prefer-dist
    echo Site available here: http://%LOCAL_DOMAIN%/
endlocal
