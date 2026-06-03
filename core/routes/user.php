<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->middleware('guest')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('/login/check-email', 'checkEmail')->name('login.check.email');
        Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register');
        Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    Route::get('user-data', 'User\UserController@userData')->name('data');
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

    //authorization
    Route::middleware('registration.complete')->namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });

    Route::middleware(['check.status', 'registration.complete'])->group(function () {

        Route::namespace('User')->group(function () {
            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('details', 'details')->name('details');
                Route::get('activity', 'activity')->name('activity');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');

                Route::post('account-delete', 'deleteAccount')->name('delete.account');
                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            // Ride
            Route::controller('RideController')->prefix('ride')->name('ride.')->group(function () {
                Route::get('process-step','processRideStep')->name('process.step');
                Route::get('calculate', 'findAreaAndDistance')->name('calculate');
                Route::post('create', 'create')->name('create');
                Route::get('reject', 'reject')->name('reject');
                Route::post('cancel', 'cancel')->name('cancel');
                Route::post('accept', 'accept')->name('accept');
                Route::post('payment-save', 'paymentSave')->name('payment.save');
                Route::get('receipt/{id}', 'receipt')->name('receipt');
                Route::get('details', 'details')->name('details');
            });

            // Coupon
            Route::controller('CouponController')->prefix('coupon')->name('coupon.')->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('apply', 'apply')->name('apply');
                Route::get('remove', 'remove')->name('remove');
            });

            // Tips
            Route::controller('TipsController')->prefix('tips')->name('tips.')->group(function () {
                Route::get('add', 'add')->name('add');
                Route::get('remove', 'remove')->name('remove');
            });
            // Review
            Route::controller('ReviewController')->prefix('review')->name('review.')->group(function () {
                Route::get('all', 'index')->name('index');
                Route::post('store', 'store')->name('store');
            });
        });

        Route::prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::any('history', 'depositHistory')->name('history');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
