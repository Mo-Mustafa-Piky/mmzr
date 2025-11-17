# Complete Goyzer Integration Test Commands

## 1. Test Connection
```bash
curl -X GET "http://localhost:8000/api/goyzer/test" -H "Accept: application/json"
```

## 2. Sync Sales Listings
```bash
curl -X POST "http://localhost:8000/api/goyzer/sync/sales" -H "Accept: application/json"
```

## 3. Sync Rental Listings
```bash
curl -X POST "http://localhost:8000/api/goyzer/sync/rentals" -H "Accept: application/json"
```

## 4. Sync All Listings
```bash
curl -X POST "http://localhost:8000/api/goyzer/sync/all" -H "Accept: application/json"
```

## 5. Get Properties
```bash
curl -X GET "http://localhost:8000/api/goyzer/properties" -H "Accept: application/json"
```

## 6. Get Properties with Filters
```bash
curl -X GET "http://localhost:8000/api/goyzer/properties?type=rental&bedrooms=2" -H "Accept: application/json"
```

## 7. Get Sync Statistics
```bash
curl -X GET "http://localhost:8000/api/goyzer/stats" -H "Accept: application/json"
```

## 8. Console Commands
```bash
# Test connection
php artisan goyzer:test

# Sync all listings
php artisan goyzer:sync

# Sync sales only
php artisan goyzer:sync --type=sales

# Sync rentals only
php artisan goyzer:sync --type=rentals

# Sync with filters
php artisan goyzer:sync --bedrooms=3 --min-price=500000
```

## Environment Setup
Make sure your .env file has:
```
GOYZER_ACCESS_CODE=$m$MzRRe@alE$t@tE
GOYZER_GROUP_CODE=5114
```