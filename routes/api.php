<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => env('APP_VERSION', 'v1')], function () {

    Route::get('/', function () {
        return "Zuragan Ongkir API " . env('APP_VERSION');
    });

    Route::get('carriers', 'AssetCarrierController@index');

    Route::get('carriers/list', 'AssetCarrierController@list');

    Route::get('countries', 'AssetCountryController@index');

    Route::get('countries/list', 'AssetCountryController@list');

    Route::get('countries/nested_list', 'AssetCountryController@getNestedList');

    Route::get('provinces', 'AssetProvinceController@index');

    Route::get('provinces/list', 'AssetProvinceController@list');

    Route::get('districts', 'AssetDistrictController@index');

    Route::get('districts/list', 'AssetDistrictController@list');

    Route::get('regions', 'AssetRegionController@index');

    Route::get('regions/list', 'AssetRegionController@list');

    Route::get('cities', 'AssetRegionController@searchCities');

    Route::post('ongkir/domestic-costs', 'RajaOngkirController@getDomesticCosts');

    Route::post('ongkir/international-cost', 'RajaOngkirController@getInternationalCost');

    Route::post('ongkir/lp-shipping-cost', 'LionParcelController@shippingCost');

    Route::post('ongkir/lp-track', 'LionParcelController@trackShipment');

    Route::post('check-waybill', 'RajaOngkirController@getWaybill');

    Route::post('ongkir/shipping-costs', 'BrokerController@shippingCosts');

    Route::post('ongkir/track-shipments', 'BrokerController@trackShipments');

});
