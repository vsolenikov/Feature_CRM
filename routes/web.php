<?php


//После импорта
//Route::get('/requests/update_important', 'RequestsController@update_important');
//Route::get('/edds/update_ids', 'Modules\EDDS\EddsRequestsController@update_usersIDS');
// Проверка доступа к модулю. Оборачиваем в midlleware web
Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => ['moduleaccess:ЕДДС']], function() {
        /*БЛОК ЕДДС*/
        Route::get('/edds/report/', 'Modules\EDDS\EddsRequestsController@genReportFast');
        //Управляющая компания
        Route::get('/edds/uk/', 'Modules\EDDS\EddsUkController@index');
        Route::get('/edds/uk/getServices', 'Modules\EDDS\EddsUkController@getServices');
        Route::get('/edds/uk/getAnswers', 'Modules\EDDS\EddsServicesController@getAnswers');
        Route::get('/edds/uk/getCities', 'Modules\EDDS\EddsAddressController@getCities');
        Route::get('/edds/uk/getStreets', 'Modules\EDDS\EddsAddressController@getStreets');
        Route::get('/edds/uk/getHouseCnt', 'Modules\EDDS\EddsAddressController@getHouseCnt');
        //Управляющая компания создать
        Route::get('/edds/uk/create', 'Modules\EDDS\EddsUkController@create');
        //Управляющая компания редактировать
        Route::get('/edds/uk/edit', 'Modules\EDDS\EddsUkController@edit');
        //Адреса
        Route::get('/edds/addr/', 'Modules\EDDS\EddsAddressController@index');
        //Управляющая компания создать
        Route::get('/edds/addr/create', 'Modules\EDDS\EddsAddressController@create');
        //Управляющая компания редактировать
        Route::get('/edds/addr/edit', 'Modules\EDDS\EddsAddressController@edit');
        //Сервисы
        Route::get('/edds/services/', 'Modules\EDDS\EddsServicesController@index');
        //Сервис создать
        Route::get('/edds/services/create', 'Modules\EDDS\EddsServicesController@create');
        //Сервис редактировать
        Route::post('/edds/services/store', 'Modules\EDDS\EddsServicesController@store')->name('edds.services.store');
        Route::get('/edds/services/{id}/edit', 'Modules\EDDS\EddsServicesController@edit')->name('edds.services.edit');
        Route::patch('/edds/services/{id}/update', 'Modules\EDDS\EddsServicesController@update')->name('edds.services.update');
        Route::resource('edds', 'Modules\EDDS\EddsRequestsController', ['names' => [
            'index'  => 'edds',
            'create' => 'edds.create',
            'edit'   => 'edds.edit',
            'show'   => 'edds.show'
        ]])->except([
            'destroy'
        ]);
        /*БЛОК ЕДДС*/
    });
});

// Модуль COVID
Route::get('/covid/orgs/','Modules\COVID\CovidInfoController@AllJson');

//Route::group(['middleware' => ['auth','moduleaccess:COVID']], function() {

Route::group(['middleware' => ['auth']], function() {
    Route::get('/covid/map', 'Modules\COVID\CovidInfoController@ShowMap');
     Route::get('/covid/map_eclg', 'Modules\COVID\CovidInfoController@ShowMapEclg');
    Route::get('/covid/map_jkh', 'Modules\COVID\CovidInfoController@ShowMapJkh');
    Route::get('/covid/map_roads', 'Modules\COVID\CovidInfoController@ShowMapRoads');
    Route::get('/covid/map_ecnm', 'Modules\COVID\CovidInfoController@ShowMapEconom');
    Route::get('/covid/map_soc', 'Modules\COVID\CovidInfoController@ShowMapSoc');
    Route::get('/covid/stat', function () {return view('covid.stat');});
    Route::post('/covid/stat/save', 'Modules\COVID\CovidInfoController@save');
    Route::resource('covid', 'Modules\COVID\CovidInfoController', ['names' => [
        'index'  => 'covid',
        'create' => 'covid.create',
        'edit'   => 'covid.edit',
        'show'   => 'covid.show'
    ]]);
});
//COVID конец



