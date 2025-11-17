<?php

return [
    'base_url' => env('GOYZER_BASE_URL', 'http://webapi.goyzer.com/Company.asmx'),
    'access_code' => env('GOYZER_ACCESS_CODE'),
    'group_code' => env('GOYZER_GROUP_CODE'),
    
    'sync' => [
        'batch_size' => env('GOYZER_BATCH_SIZE', 100),
        'timeout' => env('GOYZER_TIMEOUT', 120),
        'cache_duration' => env('GOYZER_CACHE_DURATION', 24), // hours
    ],
    
    'unit_types' => [
        'Apartment',
        'Villa',
        'Townhouse',
        'Penthouse',
        'Studio',
        'Office',
        'Shop',
        'Warehouse',
        'Land',
        'Building',
    ],
    
    'unit_statuses' => [
        'active' => 'Active',
        'sold' => 'Sold',
        'leased' => 'Leased',
        'reserved' => 'Reserved',
        'deleted' => 'Deleted',
    ],
];