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

// Модуль Стратегическое развитие
Route::get('/strat/orgs/','Modules\strat\StratInfoController@AllJson');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/strat/map', 'Modules\strat\StratInfoController@ShowMap');
     Route::get('/strat/map_eclg', 'Modules\strat\StratInfoController@ShowMapEclg');
    Route::get('/strat/map_jkh', 'Modules\strat\StratInfoController@ShowMapJkh');
    Route::get('/strat/map_roads', 'Modules\strat\StratInfoController@ShowMapRoads');
    Route::get('/strat/map_ecnm', 'Modules\strat\StratInfoController@ShowMapEconom');
    Route::get('/strat/map_soc', 'Modules\strat\StratInfoController@ShowMapSoc');
    Route::get('/strat/stat', function () {return view('strat.stat');});
    Route::post('/strat/stat/save', 'Modules\strat\StratInfoController@save');
    Route::resource('strat', 'Modules\strat\StratInfoController', ['names' => [
        'index'  => 'strat',
        'create' => 'strat.create',
        'edit'   => 'strat.edit',
        'show'   => 'strat.show'
    ]]);
});
//strat конец



