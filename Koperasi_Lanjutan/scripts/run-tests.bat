@echo off
REM Script Automation Testing untuk Laravel (Windows)
REM Usage: scripts\run-tests.bat [options]

setlocal enabledelayedexpansion

echo ========================================
echo   Automation Testing - Koperasi App   
echo ========================================
echo.

REM Cek apakah di dalam direktori Laravel
if not exist "artisan" (
    echo Error: File artisan tidak ditemukan. Pastikan Anda berada di root direktori Laravel.
    exit /b 1
)

REM Parse arguments
set TEST_TYPE=Feature
set FILTER=
set SETUP_DB=false
set COVERAGE=false

:parse_args
if "%~1"=="" goto :run_tests
if "%~1"=="--unit" set TEST_TYPE=Unit & shift & goto :parse_args
if "%~1"=="--feature" set TEST_TYPE=Feature & shift & goto :parse_args
if "%~1"=="--all" set TEST_TYPE=all & shift & goto :parse_args
if "%~1"=="--setup-db" set SETUP_DB=true & shift & goto :parse_args
if "%~1"=="--coverage" set COVERAGE=true & shift & goto :parse_args
if "%~1"=="--help" (
    echo Usage: scripts\run-tests.bat [options]
    echo.
    echo Options:
    echo   --unit              Run unit tests only
    echo   --feature           Run feature tests only (default)
    echo   --all               Run all tests
    echo   --filter=^<name^>     Run specific test by name
    echo   --setup-db          Setup test database before running tests
    echo   --coverage          Generate code coverage report
    echo   --help              Show this help message
    exit /b 0
)
set FILTER=%~1
shift
goto :parse_args

:run_tests
REM Setup database jika diminta
if "%SETUP_DB%"=="true" (
    echo Setup database untuk testing...
    php artisan migrate:fresh --force
)

REM Jalankan test
if "%TEST_TYPE%"=="all" (
    echo Menjalankan semua tests...
    php artisan test
) else if not "!FILTER!"=="" (
    echo Menjalankan test dengan filter: !FILTER!
    php artisan test --filter="!FILTER!"
) else (
    echo Menjalankan %TEST_TYPE% tests...
    php artisan test --testsuite=%TEST_TYPE%
)

REM Generate coverage jika diminta
if "%COVERAGE%"=="true" (
    echo Generating code coverage...
    php artisan test --coverage
)

echo.
echo ========================================
echo   Testing selesai!                     
echo ========================================

endlocal

