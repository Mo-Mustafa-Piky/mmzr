# Goyzer Integration Documentation

## Overview
This Laravel application integrates with Goyzer's webservice API to sync property listings for both sales and rentals.

## Setup

### 1. Environment Configuration
Add these variables to your `.env` file:

```env
GOYZER_BASE_URL=http://webapi.goyzer.com/Company.asmx
GOYZER_ACCESS_CODE=your_access_code_here
GOYZER_GROUP_CODE=your_group_code_here
GOYZER_BATCH_SIZE=100
GOYZER_TIMEOUT=120
GOYZER_CACHE_DURATION=24
```

### 2. Database Migration
Run the migration to update the properties table:

```bash
php artisan migrate
```

### 3. Test Connection
Test your Goyzer API connection:

```bash
php artisan goyzer:test
```

## Usage

### Console Commands

#### Sync All Listings
```bash
php artisan goyzer:sync --type=all
```

#### Sync Sales Only
```bash
php artisan goyzer:sync --type=sales
```

#### Sync Rentals Only
```bash
php artisan goyzer:sync --type=rentals
```

#### Sync Updated Listings (Last 24 Hours)
```bash
php artisan goyzer:sync-updated
```

### API Endpoints

#### Test Connection
```
GET /api/goyzer/test
```

#### Sync All Listings
```
POST /api/goyzer/sync/all
```

#### Sync Sales Listings
```
POST /api/goyzer/sync/sales
```

#### Sync Rental Listings
```
POST /api/goyzer/sync/rentals
```

#### Sync Updated Listings
```
POST /api/goyzer/sync/updated
```

#### Get Properties with Filters
```
GET /api/goyzer/properties?type=sale&bedrooms=3&city=Dubai
```

Parameters:
- `type`: sale|rental
- `status`: active|sold|leased|reserved
- `bedrooms`: integer
- `min_price`: numeric
- `max_price`: numeric
- `city`: string
- `community`: string
- `unit_type`: string
- `per_page`: integer (default: 15)

#### Get Single Property
```
GET /api/goyzer/properties/{unit_pk}
```

#### Get Sync Statistics
```
GET /api/goyzer/stats
```

### Background Jobs

Queue a sync job:
```php
use App\Jobs\SyncGoyzerListingsJob;

// Sync all
SyncGoyzerListingsJob::dispatch('all');

// Sync sales only
SyncGoyzerListingsJob::dispatch('sales');

// Sync rentals only
SyncGoyzerListingsJob::dispatch('rentals');

// Sync updated only
SyncGoyzerListingsJob::dispatch('updated');
```

## Data Structure

### Property Model Fields

The Property model includes all Goyzer API fields:

- **Core Fields**: `unit_pk`, `unit_reference_no`, `type`, `status`
- **Property Details**: `marketing_title`, `description`, `bedrooms`, `bathrooms`, etc.
- **Pricing**: `selling_price`, `rent_per_annum`, `maintenance_fee`
- **Location**: `city`, `state`, `community`, `sub_community`, `district`
- **Media**: `images`, `floor_plan`, `external_images`
- **Agent Info**: `listing_agent`, `listing_agent_phone`, `listing_agent_email`
- **Company Info**: `company_name`, `company_phone`, `company_logo`
- **Additional**: `facilities`, `amenities`, `map_coordinates`

### Unit Status Handling

When units change to Reserved, Sold, or Leased, they are marked as DELETED in the LastUpdated methods and the local status is updated accordingly.

## Scheduled Tasks

Add to your `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Sync updated listings every hour
    $schedule->command('goyzer:sync-updated')->hourly();
    
    // Full sync daily at 2 AM
    $schedule->command('goyzer:sync --type=all')->dailyAt('02:00');
}
```

## Error Handling

All API calls include comprehensive error handling and logging. Check `storage/logs/laravel.log` for sync issues.

## Caching

Listings are cached for 24 hours by default. Cache keys:
- `goyzer_sales_all`: All sales listings
- `goyzer_rentals_all`: All rental listings
- `goyzer_sales_updated`: Updated sales listings
- `goyzer_rentals_updated`: Updated rental listings

## Services

### GoyzerService
Handles all API communication with Goyzer webservice.

### GoyzerSyncService
Processes and syncs data from Goyzer API to local database.

## Configuration

See `config/goyzer.php` for additional configuration options including unit types, statuses, and sync settings.