#!/bin/bash

# Script Automation Testing untuk Laravel
# Usage: ./scripts/run-tests.sh [options]

set -e

# Colors untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Automation Testing - Koperasi App   ${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Cek apakah di dalam direktori Laravel
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: File artisan tidak ditemukan. Pastikan Anda berada di root direktori Laravel.${NC}"
    exit 1
fi

# Fungsi untuk menjalankan test
run_tests() {
    local test_type=$1
    local filter=$2
    
    echo -e "${YELLOW}Menjalankan $test_type tests...${NC}"
    
    if [ -z "$filter" ]; then
        php artisan test --testsuite=$test_type
    else
        php artisan test --filter=$filter
    fi
}

# Fungsi untuk setup database test
setup_test_db() {
    echo -e "${YELLOW}Setup database untuk testing...${NC}"
    
    # Backup .env jika belum ada .env.testing
    if [ ! -f ".env.testing" ]; then
        cp .env .env.testing 2>/dev/null || true
    fi
    
    # Set environment untuk testing
    export APP_ENV=testing
    export DB_CONNECTION=sqlite
    export DB_DATABASE=:memory:
    
    # Run migrations
    php artisan migrate --force --env=testing || php artisan migrate:fresh --force --env=testing
}

# Parse arguments
TEST_TYPE="Feature"
FILTER=""
SETUP_DB=false
COVERAGE=false

while [[ $# -gt 0 ]]; do
    case $1 in
        --unit)
            TEST_TYPE="Unit"
            shift
            ;;
        --feature)
            TEST_TYPE="Feature"
            shift
            ;;
        --all)
            TEST_TYPE="all"
            shift
            ;;
        --filter=*)
            FILTER="${1#*=}"
            shift
            ;;
        --setup-db)
            SETUP_DB=true
            shift
            ;;
        --coverage)
            COVERAGE=true
            shift
            ;;
        --help)
            echo "Usage: ./scripts/run-tests.sh [options]"
            echo ""
            echo "Options:"
            echo "  --unit              Run unit tests only"
            echo "  --feature           Run feature tests only (default)"
            echo "  --all               Run all tests"
            echo "  --filter=<name>     Run specific test by name"
            echo "  --setup-db          Setup test database before running tests"
            echo "  --coverage          Generate code coverage report"
            echo "  --help              Show this help message"
            exit 0
            ;;
        *)
            echo -e "${RED}Unknown option: $1${NC}"
            echo "Use --help for usage information"
            exit 1
            ;;
    esac
done

# Setup database jika diminta
if [ "$SETUP_DB" = true ]; then
    setup_test_db
fi

# Jalankan test
if [ "$TEST_TYPE" = "all" ]; then
    echo -e "${GREEN}Menjalankan semua tests...${NC}"
    php artisan test
elif [ -n "$FILTER" ]; then
    echo -e "${GREEN}Menjalankan test dengan filter: $FILTER${NC}"
    php artisan test --filter="$FILTER"
else
    run_tests "$TEST_TYPE" "$FILTER"
fi

# Generate coverage jika diminta
if [ "$COVERAGE" = true ]; then
    echo -e "${YELLOW}Generating code coverage...${NC}"
    php artisan test --coverage
fi

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Testing selesai!                     ${NC}"
echo -e "${GREEN}========================================${NC}"

