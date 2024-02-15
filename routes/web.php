<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('example-1', function () {
    return view('social.vk_comment');
});

Route::group(['middleware' => ['web']], function () {
    Route::get('example-2', function () {
        dd(Auth::check()); // works
    });
});
Route::get('/bot/servers/ping','BotManController@serverMon');
Route::any('/botman','BotManController@handle');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');




Route::group(['middleware' => ['web']], function() {
    /*Публично доступные ресурсы*/
    //Управляющая компания
    Route::get('/public/stopcovid/', 'Modules\COVID\CovidInfoController@ShowMapPublic');
});

Route::get('/licence', function () {
    return view('licence');
})->name('licence');

Route::group(['middleware' => ['auth','role:taskmanager']],function(){
	 Route::get('citizens/{id}/edit', 'CitizenController@edit')->name('citizens.edit');
        Route::patch('citizens/{id}/update', 'CitizenController@update')->name('citizens.update');
        Route::delete('citizens/{id}/destroy', 'CitizenController@destroy')->name('citizens.destroy');
        Route::get('/tasks/{id}/edit', 'TaskController@edit')->name('tasks.edit');
        Route::patch('/tasks/{id}/update', 'TaskController@update')->name('tasks.update');
        Route::delete('/tasks/{id}/destroy', 'TaskController@destroy')->name('tasks.destroy');
        Route::get('requests/{id}/edit', 'RequestsController@edit')->name('requests.edit');
        Route::patch('requests/{id}/update', 'RequestsController@update')->name('requests.update');

});

// Полномочия админа
Route::group(['middleware' => ['auth','role:admin']], function() {
    Route::get('/polls','Modules\MONO\APIController@getVkVotes');

    Route::get('/test/','BotManController@test');
    Route::get('/task_json/', 'TaskController@index_json');
    Route::get('/infinite/', function () {
        return view('tasks.infinite');
    })->name('infinite');
    //EDDS только для админа удаление
    Route::resource('edds', 'Modules\EDDS\EddsRequestsController')->only(['destroy']);
    //ToDO - надо переоформить по нормальному в resource, нет времени разбираться
    Route::resource('/spravochniki/task/cat', 'TaskCategoriesController', ['names' => [
        'index'  => '/spravochniki/task/cat',
        'create' => '/spravochniki/task/cat.create',
        'edit'   => 'spravochniki.task.cat.edit',
        'show'   => 'spravochniki.task.cat.show'
    ]]);
    Route::delete('/spravochniki/task/cat/{id}/destroy', 'TaskCategoriesController@destroy')->name('spravochniki.task.cat.destroy');
    Route::patch('/spravochniki/task/cat/{id}/update', 'TaskCategoriesController@update')->name('spravochniki.task.cat.update');
    Route::resource('/spravochniki/request/type', 'RequestTypeController', ['names' => [
        'index'  => '/spravochniki/request/type',
        'create' => '/spravochniki/request/type.create',
        'edit'   => 'spravochniki.request.type.edit',
        'show'   => 'spravochniki.request.type.show'
    ]]);
    Route::delete('/spravochniki/request/type/{id}/destroy', 'RequestTypeController@destroy')->name('spravochniki.request.type.destroy');
    Route::patch('/spravochniki/request/type/{id}/update', 'RequestTypeController@update')->name('spravochniki.request.type.update');
    Route::resource('/spravochniki/approve/otdelid', 'ApproveOtdelIdController', ['names' => [
        'index'  => '/spravochniki/approve/otdelid',
        'create' => '/spravochniki/approve/otdelid.create',
        'edit'   => 'spravochniki.approve.otdelid.edit',
        'show'   => 'spravochniki.approve.otdelid.show'
    ]]);
    Route::delete('/spravochniki/approve/otdelid/{id}/destroy', 'ApproveOtdelIdController@destroy')->name('spravochniki.approve.otdelid.destroy');
    Route::patch('/spravochniki/approve/otdelid/{id}/update', 'ApproveOtdelIdController@update')->name('spravochniki.approve.otdelid.update');
    Route::delete('requests/{id}/destroy', 'RequestsController@destroy')->name('requests.destroy');

    //Ограничение редактирования и удаления
    if(env('APP_ENABLE_TASKMANAGER_EDITABLE')==''||env('APP_ENABLE_TASKMANAGER_EDITABLE')==false){
        Route::get('citizens/{id}/edit', 'CitizenController@edit')->name('citizens.edit');
        Route::patch('citizens/{id}/update', 'CitizenController@update')->name('citizens.update');
        Route::delete('citizens/{id}/destroy', 'CitizenController@destroy')->name('citizens.destroy');
        Route::get('/tasks/{id}/edit', 'TaskController@edit')->name('tasks.edit');
        Route::patch('/tasks/{id}/update', 'TaskController@update')->name('tasks.update');
        Route::delete('/tasks/{id}/destroy', 'TaskController@destroy')->name('tasks.destroy');
        Route::get('requests/{id}/edit', 'RequestsController@edit')->name('requests.edit');
        Route::patch('requests/{id}/update', 'RequestsController@update')->name('requests.update');
    }

});

 Route::get('requests/{id}/edit', 'RequestsController@edit')->name('requests.edit');
        Route::patch('requests/{id}/update', 'RequestsController@update')->name('requests.update');

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
// Проверка доступа к модулю
Route::group(['middleware' => ['auth','moduleaccess:KPI']], function() {
    /*БЛОК KPI*/
    //Выводим список данных
    Route::get('/kpi/funds/', 'Modules\KPI\UsersFundController@index')->name('kpi.funds');
    Route::get('/kpi/funds/add', 'Modules\KPI\UsersFundController@create');
    Route::post('/kpi/funds/store', 'Modules\KPI\UsersFundController@store')->name('kpi.funds.store');
    Route::get('/kpi/funds/{id}/edit', 'Modules\KPI\UsersFundController@edit')->name('kpi.funds.edit');
    Route::patch('/kpi/funds/{id}/update', 'Modules\KPI\UsersFundController@update')->name('kpi.funds.update');
    Route::delete('/kpi/funds/{id}/delete', 'Modules\KPI\UsersFundController@destroy')->name('kpi.funds.destroy');
    Route::get('/kpi/archive/', 'Modules\KPI\UsersFundController@archive');

    Route::get('/kpi/targets/', 'Modules\KPI\UsersTargetsController@index')->name('kpi.targets');;
    Route::get('/kpi/targets/add', 'Modules\KPI\UsersTargetsController@create');
    Route::post('/kpi/targets/store', 'Modules\KPI\UsersTargetsController@store')->name('kpi.targets.store');
    Route::get('/kpi/targets/{id}/edit', 'Modules\KPI\UsersTargetsController@edit');
    Route::patch('/kpi/targets/{id}/update', 'Modules\KPI\UsersTargetsController@update')->name('kpi.targets.update');
    Route::delete('/kpi/targets/{id}/delete', 'Modules\KPI\UsersTargetsController@destroy')->name('kpi.targets.destroy');
    /*БЛОК KPI*/
});
/*Проектное управление*/

Route::group(['middleware' => ['auth']], function() {

    Route::get('/projects/{id}/add_extra', 'Modules\PROJECTS\ProjectsController@addExtra');
    Route::get('/projects/{project}/jobs', 'Modules\PROJECTS\ProjectsController@addJobs');
    Route::get('/projects/{project}/gantt', 'Modules\PROJECTS\ProjectsController@showGantt');
    Route::get('/projects/{project}/setStatus/{status}', 'Modules\PROJECTS\ProjectsController@setCustomStatus');
    Route::get('/projects/{project}/remove_project_comment_and_file/{comment}','Modules\PROJECTS\ProjectsController@removeCommentAttachment');
    Route::resource('projectJobs', 'Modules\PROJECTS\JobsController', ['names' => [
        'index' => 'projectJobs',
        'create' => 'projectJobs.create',
        'edit' => 'projectJobs.edit',
        'show' => 'projectJobs.show'
    ]]);
    Route::post('/projects/{project}/add_extra', 'Modules\PROJECTS\ProjectsController@addExtraStore');
    Route::resource('projects', 'Modules\PROJECTS\ProjectsController', ['names' => [
        'index' => 'projects',
        //'create' => 'projects.create',
        //'edit' => 'projects.edit',
        'show' => 'projects.show'
    ]]);
});
Route::group(['middleware' => ['auth','moduleaccess:Модератор Проектное управление']], function() {
    Route::get('/projects/{id}/edit', 'Modules\PROJECTS\ProjectsController@edit')->name('projects.edit');
    Route::get('/projects/create', 'Modules\PROJECTS\ProjectsController@create')->name('projects.create');
    Route::post('projects/store', 'Modules\PROJECTS\ProjectsController@store')->name('projects.store');
    Route::patch('projects/{id}/update', 'Modules\PROJECTS\ProjectsController@update')->name('projects.update');
    Route::delete('projects/{id}/destroy', 'Modules\PROJECTS\ProjectsController@destroy')->name('projects.destroy');
});
/*Проектное управление*/

// Проверка доступа к модулю
Route::group(['middleware' => ['auth','moduleaccess:MONO']], function() {
    /*БЛОК KPI*/
    Route::get('/mono/targets', 'Modules\MONO\TargetsController@index');
    Route::get('/mono/send', 'Modules\MONO\TargetsController@SendList');
    Route::post('/mono/targets/store', 'Modules\MONO\TargetsController@store')->name('mono.targets.store');
    Route::get('/mono/head', 'Modules\MONO\HeadInfoController@index');
    Route::get('/mono/head/add', 'Modules\MONO\HeadInfoController@create');
    Route::post('/mono/head/store', 'Modules\MONO\HeadInfoController@store')->name('mono.head.store');
    Route::get('/mono/head/{id}/edit', 'Modules\MONO\HeadInfoController@edit')->name('mono.head.edit');
    Route::patch('/mono/head/{id}/update', 'Modules\MONO\HeadInfoController@update')->name('mono.head.update');
    Route::get('/mono/factory', 'Modules\MONO\FactoryInfoController@index');
    Route::get('/mono/factory/add', 'Modules\MONO\FactoryInfoController@create');
    Route::post('/mono/factory/store', 'Modules\MONO\FactoryInfoController@store')->name('mono.factory.store');
    Route::get('/mono/factory/{id}/edit', 'Modules\MONO\FactoryInfoController@edit')->name('mono.factory.edit');
    Route::patch('/mono/factory/{id}/update', 'Modules\MONO\FactoryInfoController@update')->name('mono.factory.update');
    //Выводим список данных
    Route::get('/mono/','Modules\MONO\TargetsController@ShowStatistic');
    /*БЛОК API*/
    Route::get('/api/sendHeadInfo','Modules\MONO\APIController@sendHeadInfo');
    Route::get('/api/sendFactoryInfo','Modules\MONO\APIController@sendFactoryInfo');
    Route::get('/api/sendMonthTargetsInfo','Modules\MONO\APIController@sendMonthTargetsInfo');
    Route::get('/api/sendKvartalTargetsInfo','Modules\MONO\APIController@sendKvartalTargetsInfo');
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
// Модуль APPROVE
Route::group(['middleware' => ['auth','moduleaccess:APPROVE']], function() {
});
//APPROVE конец
// Проверка доступа к модулю
Route::group(['middleware' => ['auth','moduleaccess:Земельные отношения']], function() {
    /*БЛОК ЗЕМЕЛЬНЫХ ОТНОШЕНИЙ*/
    //Обращения, отфильтрованные по категории "Земельные отношения"
    Route::get('/land/', 'Modules\LAND\RequestsController@index');
    Route::get('/land/requests/create', 'Modules\LAND\RequestsController@create');
    Route::get('/land/citizens', 'Modules\LAND\CitizenController@index');
    //Гражданин создать
    Route::get('/land/citizens/create', 'Modules\LAND\CitizenController@create');
    //Гражданин редактировать
    Route::post('/land/citizens/store', 'Modules\LAND\CitizenController@store')->name('land.citizens.store');
    Route::get('/land/citizens/find', 'Modules\LAND\CitizenController@find');
    Route::resource('land', 'Modules\LAND\RequestsController', ['names' => [
        'index'  => 'land',
        'create' => 'land.create',
        'show'   => 'land.show',
    ]]);

    /*БЛОК ЕДДС*/
});
// Проверка доступа к модулю
Route::group(['middleware' => ['auth','moduleaccess:Земельные задачи']], function() {
    /*БЛОК ЗЕМЕЛЬНЫХ ОТНОШЕНИЙ*/
    Route::get('/mywork/land/destroy/{id}', 'Modules\LAND\MyWorkController@destroy');
    //Обращения, отфильтрованные по категории "Земельные отношения"
    Route::get('/mywork/land', 'Modules\LAND\MyWorkController@index');

    Route::get('/mywork/dmi', 'Modules\LAND\MyWorkDMIController@index');
    Route::get('/mywork/dmi/destroy/{id}', 'Modules\LAND\MyWorkDMIController@destroy');

    Route::get('/mywork/zhil', 'Modules\LAND\MyWorkZhilPolitController@index');
    Route::get('/mywork/dmi/destroy/{id}', 'Modules\LAND\MyWorkZhilPolitController@destroy');
    /*БЛОК ЕДДС*/
});
// Проверка доступа к модулю
Route::group(['middleware' => ['auth','moduleaccess:Уведомления главы']], function() {
    Route::get('/tasks/iknow','HNotificationsController@update_head_read');
    /*Уведомления Главы*/
    Route::resource('notifications', 'HNotificationsController', ['names' => [
        'index'  => 'notifications',
        'create' => 'notifications.create',
        'edit'   => 'notifications.edit',
        'show'   => 'notifications.show'
    ]]);

});

Route::group(['middleware' => ['auth','moduleaccess:Аналитика']], function() {
    /*БЛОК АНАЛИТИКА*/
    //Отчеты
    Route::get('/reports',function (\Illuminate\Http\Request $requests){
        return view('reports.index',['requests'=>$requests,'u_info'=>Auth::user()]);
    })->name('reports');
    //Сводная аналитика по корреспондентам
    Route::get('/reports/analyze',function (){
        return view('reports.citizens_analyze');
    });
    //Все задачи
    Route::get('/reports/tasks/', 'TaskController@get_tasks');
    Route::get('/reports/month_state', 'TaskController@month_state_mod');
    Route::get('/reports/month_state_mod', 'TaskController@month_state_mod');
    Route::get('/reports/users_report', 'TaskController@users_report');

});
//Override - доступ к созданию поручений через TaskPolicy
Route::get('task/create/own','TaskController@create_owner_based')->middleware('can:create,App\Task');
Route::get('/tasks/store', 'TaskController@store')->name('tasks.store')->middleware('can:create,App\Task');
Route::get('/task/{id}/edit', function (App\Task $id,\Illuminate\Http\Request $requests) {
    // Текущий пользователь может редактировать задачу...
    return view('tasks.edit',['requests'=>$requests,'u_info'=>Auth::user(),'tasks'=>$id,
        'important'=>\App\Classess\Helpers\EnumFields::getEnumValues('tasks','important')]);
})->name('mywork.edit')->middleware('can:update,id');
Route::get('/tasks/{id}/update', 'TaskController@update')->name('tasks.update')->middleware('can:create,App\Task');

//Задачи
Route::group(['middleware' => ['auth','role:taskmanager,admin,worker']], function() {
    //Общедоступные ресурсы
    Route::get('/home', function () {
        return view('welcome');
    })->name('home');
    Route::get('/notifytotelegram','BotManController@AttachTokenToUser');
    Route::get('/unsubscribetotelegram','BotManController@DetachTokenToUser');
    Route::resource('wikis', 'WikiController');
    // ---------МОДУЛЬ APPROVE----------------
//Testing routes
    Route::get('/approve/testing', function () {
        return view('approve.create2');
    });
    //Поиск по справочнику рассылки
    Route::get('/approve/delivery/find', 'Modules\APPROVE\DeliveryController@find');
    Route::get('/approve/{id}/delivery/new','Modules\APPROVE\ApproveController@deliveryListCreate');
    Route::post('/approve/delivery/new','Modules\APPROVE\ApproveController@deliveryListStore')->name('approve.delivery.new.store');
    Route::get('/approve/{id}/delivery/print','Modules\APPROVE\ApproveController@DeliveryList');

    //Справочник списка рассылки
    Route::group(['middleware' => ['auth','moduleaccess:Модератор Согласование']], function() {
        Route::resource('/approve/delivery', 'Modules\APPROVE\DeliveryController', ['names' => [
            'index'  => 'approve.delivery',
            'create' => 'approve.delivery.create',
            'edit'   => 'approve.delivery.edit',
            'show'   => 'approve.delivery.show',
            'update'   => 'approve.delivery.update',
            'store'   => 'approve.delivery.store',
            'destroy'   => 'approve.delivery.destroy',
        ]]);

    });
    Route::post('/approve/test','Modules\APPROVE\ApproveController@test')->name('approve.test');
    Route::get('/approve/mass_sign','Modules\APPROVE\ApproveController@massSign');

//    Route::get('/approve/test','Modules\APPROVE\ApproveController@test')->name('approve.test');
    Route::get('/document/{id}/add_extra','Modules\APPROVE\ApproveController@AddExtraInfo');
    Route::get('/approve/{approve_id}/npa/toggle','Modules\APPROVE\ApproveController@NpaToggle');
    Route::post('/approve/add_extra','Modules\APPROVE\ApproveController@StoreExtraInfo')->name('approve.add_extra');
    Route::get('/approve/review','Modules\APPROVE\ApproveController@IReview');
    Route::get('/approve/onsign','Modules\APPROVE\ApproveController@ISign');
    Route::get('/approve/signed','Modules\APPROVE\ApproveController@SignedDocuments');
    Route::get('/approve/archive','Modules\APPROVE\ApproveController@Archive');
    Route::get('/approve/document/{document_id}','Modules\APPROVE\ApproveController@ApproveList');
    Route::get('/approve/getAnswers','Modules\APPROVE\ApproveController@getAnswers');
    Route::get('/approve/{approve_id}/zipapprove','Modules\APPROVE\ApproveController@ZipApproveToggle');
    Route::get('/approve/{approve_id}/status/{status}','Modules\APPROVE\ApproveController@SetStatus');
    Route::get('/approve_json/','Modules\APPROVE\ApproveController@index_json');
    Route::resource('approve', 'Modules\APPROVE\ApproveController', ['names' => [
        'index'  => 'approve',
        'create' => 'approve.create',
        'edit'   => 'approve.edit',
        'show'   => 'approve.show'
    ]]);

    //Согласовать текущую редакцию документа текущим пользователем
    Route::get('/document/create/{approve_id}','Modules\APPROVE\DocumentController@create');
    Route::post('/document/create', 'Modules\APPROVE\DocumentController@store')->name('document.store');
    Route::get('/document/{id}/confirm','Modules\APPROVE\DocumentController@Approve');
    Route::get('/document/{id}/decline','Modules\APPROVE\DocumentController@Decline');
    Route::get('/document/{id}/sign','Modules\APPROVE\DocumentController@SignDocument');
    Route::post('/document/decline','Modules\APPROVE\DocumentController@DeclineDocument')->name('document.decline');
    // ---------КОНЕЦ-------------------------
    //Авторизованная загрузка файла
    Route::get('/readf/{filename}','FilesController@secure_download');
    Route::get('/readf_approve/{filename}','FilesController@secure_download_approve');
    Route::get('/readf_wiki/{filename}','FilesController@secure_download_wiki');
    //Поиск пользователя
    Route::get('/users/find', 'UserController@find');
    //Профиль пользователя
    Route::get('/user/profile', 'UserController@userProfile');
    //Подгрузка аватарки,либо другой инфы о пользователе
    Route::post('/user/profile/update', 'UserController@updateProfile');
    //Комментарии,где упомянули пользователя
    Route::get('/comments/my','CommentController@userComments')->name('comments/my');
    //Поручения, где я ответственный,либо соисполнитель
    Route::get('/user/tasks/', 'TaskController@myWork');
    //Обновить статус задачи
    Route::get('/tasks/update_status', 'TaskController@updateStatus');
    //Обновить список соисполнителей
    Route::post('/task/addhelpers','TaskController@addHelpers');
    //Просмотр файлов, приложенных к задаче или обращению
    Route::get('/files/show', 'FilesController@show');
    //Управление задачами
    Route::get('mywork', 'MyWorkController@index')->name('mywork');
    Route::get('/tasks/{id}/', 'MyWorkController@show');

    Route::resource('comments', 'CommentController', ['names' => [
        'index'  => 'comments',
        'create' => 'comments_add',
        'edit'   => 'comments.edit',
        'show'   => 'comments.show'
    ]]);


});

//Корреспонденты, обращения
Route::group(['middleware' => ['auth','role:taskmanager,admin']], function() {

    Route::get('tasks', 'TaskController@index')->name('tasks');
    Route::get('tasks/create', 'TaskController@create')->name('tasks.create');
    Route::get('tasks/store', 'TaskController@store')->name('tasks.store');
    Route::get('tasks/{id}/edit', 'TaskController@edit')->name('tasks.edit');
    Route::get('tasks/{id}/update', 'TaskController@update')->name('tasks.update');
    Route::get('tasks/{id}/show', 'TaskController@show')->name('tasks.show');
    Route::get('tasks/{id}/destroy', 'TaskController@destroy')->name('tasks.destroy');
    //Управление задачами
    Route::get('task/create', 'TaskController@create')->name('task.create');
    Route::resource('ttask', 'TaskController', ['names' => [
        'index'  => 'tasks',
        'create' => 'tasks.create',
        'edit'   => 'tasks.edit',
        'show'   => 'tasks.show'
    ]]);
    Route::resource('tasks', 'TaskController', ['names' => [
        'index'  => 'tasks',
        'create' => 'tasks.create',
        'edit'   => 'tasks.edit',
        'show'   => 'tasks.show'
    ]]);

    /*
     * Citizens - это граждане, либо контрагенты, которые направляют обращения Requests
     * */

// Разрешить  радектирование контактов для менеджеров
 Route::get('citizens/{id}/edit', 'CitizenController@edit')->name('citizens.edit');
        Route::patch('citizens/{id}/update', 'CitizenController@update')->name('citizens.update');
        Route::delete('citizens/{id}/destroy', 'CitizenController@destroy')->name('citizens.destroy');
        Route::get('/tasks/{id}/edit', 'TaskController@edit')->name('tasks.edit');
        Route::patch('/tasks/{id}/update', 'TaskController@update')->name('tasks.update');
        Route::delete('/tasks/{id}/destroy', 'TaskController@destroy')->name('tasks.destroy');
        Route::get('requests/{id}/edit', 'RequestsController@edit')->name('requests.edit');
        Route::patch('requests/{id}/update', 'RequestsController@update')->name('requests.update');

    //Find
    Route::get('/citizens/find', 'CitizenController@find');
    //Управление пользователями
    Route::resource('citizens', 'CitizenController', ['names' => [
        'index'  => 'citizens',
        'create' => 'citizens_add',
        'edit'   => 'citizens.edit',
        'show'   => 'citizens.show'
    ]]);
    //Зарегистрировать обращения из раздела Корреспондентов
    Route::get('/requests/createCi','RequestsController@create_ci_based');
    //Создать задачу на основе обращения
    Route::get('/task/createReq','TaskController@create_req_based');

    /*
     *  Requests это обращения граждан или контрагентов Citizens
     * */
    Route::resource('requests', 'RequestsController', ['names' => [
        'index'  => 'requests',
        'create' => 'requests_add',
        'edit'   => 'requests.edit',
        'show'   => 'requests.show'
    ]]);


});
// Полномочия админа
Route::group(['middleware' => ['auth','role:admin']], function() {
    Route::get('settings','SettingsController@index');
    Route::post('settings','SettingsController@store')->name('settings');
    //Управление пользователями
    Route::resource('users', 'UserController', ['names' => [
        'index'  => 'users',
        'create' => 'users.create',
        'edit'   => 'users.edit',
        'show'   => 'users.show'
    ]]);
    Route::resource('otdel', 'OtdelController', ['names' => [
        'index'  => 'otdel',
        'create' => 'otdel.create',
        'edit'   => 'otdel.edit',
        'show'   => 'otdel.show'
    ]]);

    Route::get('/uip', 'UserController@exportCsv');
});

Auth::routes([ 'register' => false ,'password.request' => false, 'reset' => false]);

// проверка залогиненного пользователя


