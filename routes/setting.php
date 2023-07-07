<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\VersionControlController;
use App\Http\Controllers\Settings\SplashScreenController;
use App\Http\Controllers\Settings\PrivacyPolicyController;
use App\Http\Controllers\Settings\TermsOfServiceController;
use App\Http\Controllers\Settings\OnboardingController;
use App\Http\Controllers\Settings\FAQController;
use App\Http\Controllers\Settings\AutoResponseController;

Route::prefix('setting')->middleware('validate_session')->group(function () {
    Route::prefix('version')->group(function () {
        Route::get('/', [VersionControlController::class, 'getVersionControllSetting'])->middleware('feature_control:13');
        Route::post('/', [VersionControlController::class, 'updateVersionControllSetting'])->name('version.control.update')->middleware('log:activity','feature_control:14');
    });

    Route::prefix('splash-screen')->group(function () {
        Route::get('/', [SplashScreenController::class, 'getSplashScreenSetting'])->middleware('feature_control:26');
        Route::post('/', [SplashScreenController::class, 'updateSplashScreenSetting'])->name('splash.screen.update')->middleware('log:activity', 'feature_control:27');
    });

    Route::prefix('privacy-policy')->group(function () {
        Route::get('/', [PrivacyPolicyController::class, 'getPrivacyPolicySetting'])->middleware('feature_control:22');
        Route::post('/', [PrivacyPolicyController::class, 'updatePrivacyPolicySetting'])->name('privacy.policy.update')->middleware('log:activity', 'feature_control:23');
    });

    Route::prefix('terms-of-service')->group(function () {
        Route::get('/', [TermsOfServiceController::class, 'getTermsOfServiceSetting'])->middleware('feature_control:24');
        Route::post('/', [TermsOfServiceController::class, 'updateTermsOfServiceSetting'])->name('terms.of.service.update')->middleware('log:activity','feature_control:25');
    });

    Route::prefix('on-boarding')->group(function () {
        Route::get('/', [OnboardingController::class, 'getOnboardingSetting'])->middleware('feature_control:20');
        Route::post('/', [OnboardingController::class, 'updateOnboardingSetting'])->name('onboarding.update')->middleware('log:activity', 'feature_control:21');
        Route::post('/add', [OnboardingController::class, 'addOnboardingImage'])->name('onboarding.add')->middleware('log:activity', 'feature_control:19');
    });

    Route::prefix('faq')->group(function () {
        Route::get('/', [FAQController::class, 'getFAQSetting'])->middleware('feature_control:16');
        Route::get('/new', [FAQController::class, 'newFAQSetting'])->middleware('feature_control:15');
        Route::get('/{id}', [FAQController::class, 'getDetialFAQSetting'])->middleware('feature_control:16');

        Route::middleware('log:activity')->group(function () {
            Route::post('/', [FAQController::class, 'updateFAQSetting'])->name('frequently.asked.question.order.update')->middleware('feature_control:17');
            Route::post('/new', [FAQController::class, 'createFAQSetting'])->name('frequently.asked.question.add')->middleware('feature_control:15');
            Route::post('/{id}', [FAQController::class, 'updateDetialFAQSetting'])->name('frequently.asked.question.detail.update')->middleware('feature_control:17');
            Route::get('/delete/{id}', [FAQController::class, 'deleteFAQSetting'])->name('frequently.asked.question.delete')->middleware('feature_control:18');
        });
    });

    Route::prefix('autoresponse')->group(function () {
        Route::get('/', [AutoResponseController::class, 'getAutoResponseSetting'])->middleware('feature_control:40');
        Route::get('/{code}', [AutoResponseController::class, 'getAutoResponseDetail'])->middleware('feature_control:40');
        Route::middleware('log:activity')->group(function () {
            Route::post('/{code}', [AutoResponseController::class, 'updateAutoResponse'])->name('auto.response.update')->middleware('feature_control:41');
        });
    });
});


Route::prefix('profile')->middleware('validate_session')->group(function () {
    Route::get('/', [ProfileController::class, 'getProfileSetting']);
    Route::post('/', [ProfileController::class, 'updateProfileSetting'])->name('profile.update')->middleware('log:activity');
});
