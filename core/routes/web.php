<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');
Route::get('cron', 'CronController@cron')->name('cron');


Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::controller('GoogleMapController')->name('google.map.')->group(function () {
    Route::get('location-by-lat-lon', 'locationByLatLon')->name('location.by.lat.lng');
    Route::get('suggested-places', 'suggestedPlaces')->name('suggested.places');
    Route::get('place-details', 'getPlaceDetails')->name('place.details');
});

Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::post('subscribe', 'subscribe')->name('subscribe');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('blog', 'blog')->name('blog');
    Route::post('start-ride', 'startRide')->name('start.ride')->middleware('web');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    Route::get('how-to-work', 'howToWork')->name('how.to.work');
    Route::get('join-as-a-driver', 'driverJoin')->name('driver.join');
    Route::get('rider-join', 'riderJoin')->name('rider.join');
    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');
    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');
    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});

Route::controller('PusherController')->group(function () {
    Route::post('pusher/auth', 'authenticationApp')->name('pusher.auth');
    Route::post('pusher/auth/{socketId}/{channelName}', 'authentication')->name('pusher.auth.socket');
});
