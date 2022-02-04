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

Route::get('/', function () {
    return view('index');
})->name('/');

Route::get('/disclaimer', function () {
    return view('disclaimer');
});

Route::get('/about_us', function () {
    return view('about_us');
});

Route::get('/packages', function () {
    return view('packages');
});


Route::get('/privacy_policy', function () {
    return view('privacy_policy');
});

Route::get('/terms_and_conditiond', function () {
    return view('terms');
});


Auth::routes(['verify' => true]);
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');


            /*/*/ /* ADMIN */ /*/*/
Route::resource('web_status','Web\Admin\StatusController')->middleware('Admin');

Route::resource('web_tag','Web\Admin\TagController')->middleware('Admin');

Route::resource('web_category','Web\Admin\CategoryController')->middleware('Admin');

Route::resource('web_campaign','Web\Admin\CampaignController')->middleware('Admin');

Route::resource('web_service','Web\Admin\ServiceController')->middleware('Admin');

Route::resource('web_media','Web\Admin\MediaController')->middleware('Admin');

Route::resource('web_user','Web\Admin\UserController')->middleware('Admin');

Route::resource('web_notification','Web\Admin\NotificationController')->middleware('Admin');

Route::get('APP/VIEWS','Web\Admin\ReportController@APP_VIEWS')->name('APP.VIEWS')->middleware('Admin');

Route::get('RESTAURANT/VIEWS','Web\Admin\ReportController@RESTAURANT_VIEWS')->name('RESTAURANT.VIEWS')->middleware('Admin');

Route::get('RESTAURANT/VIEWS/WITH_SEARCH','Web\Admin\ReportController@RESTAURANT_VIEWS_WITH_SEARCH')->name('RESTAURANT.VIEWS.WITH_SEARCH')->middleware('Admin');


Route::get('redemption','Web\Admin\ReportController@REDEMPTIONS')->name('REDEMPTIONS')->middleware(['Admin'||'RestaurantManager']);


Route::resource('web_restaurant','Web\Admin\RestaurantController')->middleware('Admin');

Route::resource('web_offer','Web\Admin\OfferController');


            /*/*/ /* Restaurant Manager */ /*/*/
Route::resource('my_restaurant','Web\Manager\MyRestaurantController')->middleware('RestaurantManager');

Route::resource('web_my_offer','Web\Manager\MyOfferController')->middleware('RestaurantManager');

            /*/*/ /* Restaurant Manager && ADMIN */ /*/*/
Route::resource('profile','Web\ProfileController')->middleware(['RestaurantManager'|| 'Admin']);


Route::get('delete_photo','Web\Manager\MyRestaurantController@delete_photo')->name('restaurant.delete_photo')->middleware(['RestaurantManager'|| 'Admin']);

Route::get('email_resend','Web\PasswordResetController@resend')->name('web_user.email_resend')->middleware(['RestaurantManager'|| 'Admin']);


Route::group([
    'middleware' => 'web',
    'prefix' => 'web/password'
], function () {
    Route::post('create', 'Web\PasswordResetController@create');
    Route::get('find/{token}', 'Web\PasswordResetController@find');
    Route::post('reset_pass', 'Web\PasswordResetController@reset');
});




