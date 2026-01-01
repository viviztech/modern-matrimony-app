#!/bin/bash

###############################################################################
# Matrimony App - Rollback Script
# Usage: ./scripts/rollback.sh [backup_file]
# Example: ./scripts/rollback.sh backup-20260101-120000.tar.gz
###############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_DIR="/var/www/matrimony"
BACKUP_DIR="/var/www/backups"
BACKUP_FILE=$1

echo -e "${RED}========================================${NC}"
echo -e "${RED}Matrimony App Rollback${NC}"
echo -e "${RED}========================================${NC}"

# Find latest backup if not specified
if [ -z "$BACKUP_FILE" ]; then
    echo -e "\n${YELLOW}No backup file specified. Finding latest backup...${NC}"
    BACKUP_FILE=$(ls -t $BACKUP_DIR/backup-*.tar.gz | head -1)
    if [ -z "$BACKUP_FILE" ]; then
        echo -e "${RED}Error: No backup files found${NC}"
        exit 1
    fi
fi

echo -e "${YELLOW}Rollback file: ${BACKUP_FILE}${NC}"
read -p "Are you sure you want to rollback? (yes/no): " CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo -e "${YELLOW}Rollback cancelled${NC}"
    exit 0
fi

# Step 1: Enable maintenance mode
echo -e "\n${YELLOW}[1/6] Enabling maintenance mode...${NC}"
cd $APP_DIR
php artisan down || true
echo -e "${GREEN}✓ Maintenance mode enabled${NC}"

# Step 2: Backup current state (just in case)
echo -e "\n${YELLOW}[2/6] Creating pre-rollback backup...${NC}"
tar -czf $BACKUP_DIR/pre-rollback-$(date +%Y%m%d-%H%M%S).tar.gz .
echo -e "${GREEN}✓ Pre-rollback backup created${NC}"

# Step 3: Extract backup
echo -e "\n${YELLOW}[3/6] Extracting backup...${NC}"
tar -xzf $BACKUP_FILE -C $APP_DIR
echo -e "${GREEN}✓ Backup extracted${NC}"

# Step 4: Run migrations (down if needed)
echo -e "\n${YELLOW}[4/6] Checking database state...${NC}"
php artisan migrate:status || echo "Migration status check failed"

# Step 5: Clear caches
echo -e "\n${YELLOW}[5/6] Clearing caches...${NC}"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓ Caches cleared and rebuilt${NC}"

# Step 6: Restart services
echo -e "\n${YELLOW}[6/6] Restarting services...${NC}"
php artisan queue:restart
sudo systemctl restart php8.2-fpm || echo "Could not restart PHP-FPM"
sudo supervisorctl restart all || echo "Could not restart supervisor"
echo -e "${GREEN}✓ Services restarted${NC}"

# Disable maintenance mode
echo -e "\n${YELLOW}Disabling maintenance mode...${NC}"
php artisan up
echo -e "${GREEN}✓ Maintenance mode disabled${NC}"

# Health check
echo -e "\n${YELLOW}Running health check...${NC}"
sleep 5
HEALTH_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health/simple)
if [ "$HEALTH_STATUS" = "200" ]; then
    echo -e "${GREEN}✓ Health check passed${NC}"
else
    echo -e "${RED}✗ Health check failed (HTTP $HEALTH_STATUS)${NC}"
fi

echo -e "\n${GREEN}========================================${NC}"
echo -e "${GREEN}Rollback completed!${NC}"
echo -e "${GREEN}========================================${NC}"

exit 0
