#!/bin/bash

###############################################################################
# Matrimony App - Zero-Downtime Deployment Script
# Usage: ./scripts/deploy.sh [environment]
# Example: ./scripts/deploy.sh production
###############################################################################

set -e # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
ENVIRONMENT=${1:-production}
APP_DIR="/var/www/matrimony"
BACKUP_DIR="/var/www/backups"
DATE=$(date +%Y%m%d-%H%M%S)

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Matrimony App Deployment${NC}"
echo -e "${GREEN}Environment: ${ENVIRONMENT}${NC}"
echo -e "${GREEN}Date: ${DATE}${NC}"
echo -e "${GREEN}========================================${NC}"

# Step 1: Pre-deployment checks
echo -e "\n${YELLOW}[1/10] Running pre-deployment checks...${NC}"
if [ ! -d "$APP_DIR" ]; then
    echo -e "${RED}Error: Application directory not found${NC}"
    exit 1
fi

cd $APP_DIR

# Check if .env exists
if [ ! -f ".env" ]; then
    echo -e "${RED}Error: .env file not found${NC}"
    exit 1
fi

# Step 2: Create backup
echo -e "\n${YELLOW}[2/10] Creating backup...${NC}"
mkdir -p $BACKUP_DIR
tar -czf $BACKUP_DIR/backup-${DATE}.tar.gz \
    --exclude=storage/logs/*.log \
    --exclude=node_modules \
    --exclude=.git \
    .
echo -e "${GREEN}✓ Backup created: ${BACKUP_DIR}/backup-${DATE}.tar.gz${NC}"

# Step 3: Enable maintenance mode
echo -e "\n${YELLOW}[3/10] Enabling maintenance mode...${NC}"
php artisan down --render="errors::503" --retry=60
echo -e "${GREEN}✓ Maintenance mode enabled${NC}"

# Step 4: Pull latest code
echo -e "\n${YELLOW}[4/10] Pulling latest code...${NC}"
git fetch origin
git reset --hard origin/main
echo -e "${GREEN}✓ Code updated${NC}"

# Step 5: Install dependencies
echo -e "\n${YELLOW}[5/10] Installing dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --production
echo -e "${GREEN}✓ Dependencies installed${NC}"

# Step 6: Build assets
echo -e "\n${YELLOW}[6/10] Building assets...${NC}"
npm run build
echo -e "${GREEN}✓ Assets built${NC}"

# Step 7: Run database migrations
echo -e "\n${YELLOW}[7/10] Running database migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}✓ Migrations completed${NC}"

# Step 8: Clear and cache
echo -e "\n${YELLOW}[8/10] Optimizing application...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
echo -e "${GREEN}✓ Application optimized${NC}"

# Step 9: Restart services
echo -e "\n${YELLOW}[9/10] Restarting services...${NC}"
php artisan queue:restart
sudo systemctl restart php8.2-fpm || echo "Could not restart PHP-FPM"
sudo supervisorctl restart all || echo "Could not restart supervisor"
echo -e "${GREEN}✓ Services restarted${NC}"

# Step 10: Disable maintenance mode
echo -e "\n${YELLOW}[10/10] Disabling maintenance mode...${NC}"
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
    echo -e "${YELLOW}Rolling back...${NC}"
    bash scripts/rollback.sh
    exit 1
fi

echo -e "\n${GREEN}========================================${NC}"
echo -e "${GREEN}Deployment completed successfully!${NC}"
echo -e "${GREEN}========================================${NC}"

# Clean old backups (keep last 5)
echo -e "\n${YELLOW}Cleaning old backups...${NC}"
cd $BACKUP_DIR
ls -t backup-*.tar.gz | tail -n +6 | xargs -r rm
echo -e "${GREEN}✓ Old backups cleaned${NC}"

exit 0
