<?php

Route::get('/me', function (\Illuminate\Http\Request $request) {
    return $request->user()->append('current_membership');
})->middleware('auth:api');

Route::group(['namespace' => 'Auth'], function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/refresh_token', 'LoginController@refreshToken');
    Route::post('/register', 'RegisterController@register');
});

Route::group([
    'middleware' => ['auth:api', 'admin'],
    'prefix' => 'admin',
    'namespace' => 'Admin'],
    function () {
        Route::apiResource('users', 'UserController');
        Route::apiResource('categories', 'CategoryController');
        Route::apiResource('membership', 'MembershipController');
        Route::apiResource('file', 'FileController');
        Route::apiResource('discount', 'DiscountController');
        Route::apiResource('comment', 'CommentController')->only('index', 'update', 'destroy');

        Route::get('payment', 'PaymentController@index');
        Route::get('dashboard', 'DashboardController@index');
        Route::get('dashboard/charts', 'DashboardController@charts');

        Route::get('membership-search', 'Search\MembershipController@index');
        Route::get('category-search', 'Search\CategoryController@index');
    });

Route::get('file/tagged/{category}', 'File\FileTaggedController@index');
Route::get('file/{file}/like', 'File\LikeFileController@like')->middleware('auth:api');
Route::apiResource('file', 'File\FileController');
Route::post('comment', 'Front\CommentController@store')->middleware('auth:api');
Route::delete('comment/{comment}', 'Front\CommentController@destroy')->middleware('auth:api');

Route::post('discount', 'Front\ApplyDiscountController@store')->middleware('auth:api');


Route::group(['middleware' => 'auth:api', 'namespace' => 'Dashboard'], function () {
    Route::get('membership', 'MembershipController@index');
    Route::get('dashboard/my-files', 'MyFilesController@index');
    Route::post('add-to-files', 'MyFilesController@addToMyFiles');
    Route::get('generate-zip/{file}', 'MyFilesController@generateZip');
    Route::get('ftp/generate-zip/{file}', 'MyFilesController@ftpGenerateZip');

    Route::patch('dashboard/profile/{user}', 'ProfileController@update');
});
