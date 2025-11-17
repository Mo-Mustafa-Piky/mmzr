<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Goyzer API routes
use App\Http\Controllers\Api\GoyzerController;

Route::prefix('goyzer')->group(function () {
    Route::get('test', [GoyzerController::class, 'testConnection']);
    Route::post('sync/all', [GoyzerController::class, 'syncAll']);
    Route::post('sync/sales', [GoyzerController::class, 'syncSales']);
    Route::post('sync/rentals', [GoyzerController::class, 'syncRentals']);
    Route::post('sync/updated', [GoyzerController::class, 'syncUpdated']);
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
    Route::get('goyzer-properties', [GoyzerController::class, 'getGoyzerProperties']);
});


/* the Endpoints implemented:

GetAgents

GetAmenities

GetBedrooms

GetBudget

GetBudgetByCountry

GetCities

GetStates

GetCountry

GetDistricts

GetCommunities

GetSubCommunity

GetNationality

GetTitle

GetRequirementType

GetContactMethods

GetUnitCategory

GetUnitSubType

GetUnitView

GetFacility

*/



