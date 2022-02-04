<?php

use Illuminate\Http\Request;

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
/*
Route::post('passport_register', 'RegisterController@register');
Route::post('passport_login', 'RegisterController@login');
*/

Route::get('/user-register', 'RegisterController@ShowRegisterForm')->name('register');

Route::post('/user-register', 'RegisterController@HandleRegister');

Route::get('/user-login', 'RegisterController@ShowLoginForm')->name('login');

Route::post('/user-login-facebook', 'RegisterController@facebookLogin')->name('login-facebook');

Route::post('/user-login', 'RegisterController@HandleLogin');

Route::get('/verify/{token}', 'VerifyController@VerifyEmail')->name('verify');



Route::group([
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset_pass', 'PasswordResetController@reset');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Auth::routes(['verify' => true]);
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');



Route::post('/user-profile_update', 'ProfileController@update');
Route::get('/user-profile_get', 'ProfileController@edit');




Route::resource('status', 'StatusController');

Route::resource('tag', 'TagController');

Route::resource('tagRestaurant', 'TagRestaurantController');

Route::Post('get_tags_restaurant','TagRestaurantController@get_tags_restaurant')->name('get_tags_restaurant');

Route::Post('get_restaurants_tag','TagRestaurantController@get_restaurants_tag')->name('get_restaurants_tag');

Route::resource('restaurant', 'RestaurantController');

Route::post('restaurant/offers', 'RestaurantController@restaurant_offers')->name('restaurant.getOffers');

Route::post('restaurant/services', 'RestaurantController@restaurant_services')->name('restaurant.getServices');

Route::post('restaurant/with_filters', 'RestaurantController@get_restaurants')->name('get_restaurants');

Route::get('is_featured','RestaurantController@get_featured_resturants')->name('restaurant.is_featured');



Route::resource('offer', 'OfferController');
Route::post('offer/services', 'OfferController@offer_services')->name('offer.getServices');

Route::resource('category', 'CategoryController');

Route::resource('service', 'ServiceController');

Route::resource('services_offer', 'ServicesOfferController');


Route::Post('rating', 'RatingController@rating_restaurant');
Route::get('show_rating', 'RatingController@get_rating_for_user');

Route::Post('review', 'ReviewController@review_restaurant');
Route::get('show_reviews', 'ReviewController@get_reviews_for_restaurant');


Route::Post('store_log', 'LogAPIController@store_log');






Route::resource('comment', 'CommentController');

Route::resource('media', 'MediaController');
Route::Post('get_media', 'MediaController@get_media')->name('get_media');

Route::resource('campaign', 'CampaignController');


Route::resource('campaign_offer', 'CampaignOfferController');
Route::post('campaign_get_offers','CampaignOfferController@get_offers')->name('campaign.get_offers');
Route::post('offer_get_campaigns','CampaignOfferController@get_campaigns')->name('offer.get_campaigns');


Route::Post('redeem_insert','UserRedemptionController@insert_redeem');
Route::Get('redeem_show','UserRedemptionController@get_redeem');

Route::resource('device', 'MobileDevicesController');


Route::resource('notification', 'NotificationController');

Route::POST('contact_us', 'ContactUsController@send_mail')->name('contact_us');


