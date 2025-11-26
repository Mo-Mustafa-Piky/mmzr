<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\BlogsBannerController;
use App\Http\Controllers\Api\HomepageController;
use App\Http\Controllers\Api\NavbarMenuController;
use App\Http\Controllers\Api\AreaGuidesController;
use App\Http\Controllers\Api\AwardsController;
use App\Http\Controllers\Api\FooterFaqController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Goyzer API routes
use App\Http\Controllers\Api\GoyzerController;

Route::prefix('goyzer')->group(function () {
    Route::get('properties', [GoyzerController::class, 'getProperties']);
    Route::get('properties/{unitPk}', [GoyzerController::class, 'getProperty']);
    Route::get('stats', [GoyzerController::class, 'getSyncStats']);
    Route::post('test-data', [GoyzerController::class, 'createTestData']);
    Route::get('reference-data', [GoyzerController::class, 'getReferenceData']);
    Route::get('agents', [GoyzerController::class, 'getAgents']);
    Route::get('amenities', [GoyzerController::class, 'getAmenities']);
    Route::get('bedrooms', [GoyzerController::class, 'getBedrooms']);
    Route::get('budget', [GoyzerController::class, 'getBudget']);
    Route::get('budget-by-country', [GoyzerController::class, 'getBudgetByCountry']);
    Route::get('cities', [GoyzerController::class, 'getCities']);
    Route::get('states', [GoyzerController::class, 'getStates']);
    Route::get('countries', [GoyzerController::class, 'getCountries']);
    Route::get('districts', [GoyzerController::class, 'getDistricts']);
    Route::get('communities', [GoyzerController::class, 'getCommunities']);
    Route::get('sub-communities', [GoyzerController::class, 'getSubCommunities']);
    Route::get('nationalities', [GoyzerController::class, 'getNationalities']);
    Route::get('titles', [GoyzerController::class, 'getTitles']);
    Route::get('requirement-types', [GoyzerController::class, 'getRequirementTypes']);
    Route::get('contact-methods', [GoyzerController::class, 'getContactMethods']);
    Route::get('unit-categories', [GoyzerController::class, 'getUnitCategories']);
    Route::get('unit-sub-types', [GoyzerController::class, 'getUnitSubTypes']);
    Route::get('unit-views', [GoyzerController::class, 'getUnitViews']);
    Route::get('facilities', [GoyzerController::class, 'getFacilities']);
    Route::get('get-properties', [GoyzerController::class, 'getGoyzerProperties']);
    Route::get('updated-units-sales', [GoyzerController::class, 'getUpdatedUnitsForSales']);
    Route::get('updated-units-rentals', [GoyzerController::class, 'getUpdatedUnitsForRentals']);
    Route::get('updated-projects', [GoyzerController::class, 'getUpdatedProjectsForSales']);
    Route::get('sales-listings', [GoyzerController::class, 'getSalesListings']);
    Route::get('rental-listings', [GoyzerController::class, 'getRentalListings']);
    Route::get('sold-listings', [GoyzerController::class, 'getSoldListings']);
    Route::post('contact-insert', [GoyzerController::class, 'contactInsert']);
});

Route::get('/homepage', [HomepageController::class, 'index']);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);
Route::get('/blogs-banner', [BlogsBannerController::class, 'index']);
Route::get('/navbar-menu', [NavbarMenuController::class, 'index']);
Route::get('/area-guides-banner', [AreaGuidesController::class, 'banner']);
Route::get('/area-guides', [AreaGuidesController::class, 'index']);
Route::get('/awards-banner', [AwardsController::class, 'banner']);
Route::get('/awards', [AwardsController::class, 'index']);
Route::get('/footer', [\App\Http\Controllers\Api\FooterFaqController::class, 'footer']);
Route::get('/faqs', [\App\Http\Controllers\Api\FooterFaqController::class, 'faqs']);


