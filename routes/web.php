<?php

Route::get('download/{file}', 'DownloadFiles@download')->middleware(['auth:api', 'admin']);

Route::get('download-zip/{download}', 'DownloadFiles@downloadZip')->middleware('auth:api');

Route::post('buy', 'Front\PaymentController@buy')->middleware('auth:api');
Route::get('callback', 'Front\PaymentController@callback')->name('callback');

Route::post('buy/membership', 'Dashboard\BuyMembershipController@buy')->middleware('auth:api');
Route::get('callback/membership', 'Dashboard\BuyMembershipController@callback')->name('callback.membership');

Route::get('/auth/login', function () {
    return view('home');
})->name('login');

Route::fallback(function () {
    return view('home');
});
