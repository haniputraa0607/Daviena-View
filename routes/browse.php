<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Browses\ApplyForCasionController;
use App\Http\Controllers\Browses\VehicleController;
use App\Http\Controllers\Browses\LocationController;
use App\Http\Controllers\Browses\ECSController;
use App\Http\Controllers\Browses\MobileUserController;
use App\Http\Controllers\Browses\CMSUserController;
use App\Http\Controllers\Browses\RoleController;

Route::prefix('browse')->middleware('validate_session')->group(function () {
    Route::prefix('vehicle')->group(function () {
        Route::get('/', [VehicleController::class, 'getVehicleBrand'])->middleware('feature_control:6');
        Route::get('/{id}', [VehicleController::class, 'getDetailVehicleBrand'])->middleware('feature_control:6');

        Route::middleware('log:activity')->group(function () {
            Route::post('/add', [VehicleController::class, 'createVehicleBrand'])->middleware('feature_control:5')->name('vehicle.brand.add');
            Route::post('/type/add', [VehicleController::class, 'createVehicleType'])->middleware('feature_control:5')->name('vehicle.type.add');
            Route::post('/{id}', [VehicleController::class, 'updateDetailVehicleBrand'])->middleware('feature_control:7')->name('vehicle.brand.update');
            Route::post('/update/visibility/{id}', [VehicleController::class, 'updateVisibilityVehicleBrand'])->middleware('feature_control:7')->name('vehicle.brand.visibility.update');
            Route::post('/type/update/{id}', [VehicleController::class, 'updateVehicleType'])->middleware('feature_control:7')->name('vehicle.type.update');
            Route::post('/type/update/visibility/{id}', [VehicleController::class, 'updateVisibilityVehicleType'])->middleware('feature_control:7')->name('vehicle.type.visibility.update');
            Route::get('/delete/{id}', [VehicleController::class, 'deleteVehicleBrand'])->middleware('feature_control:8')->name('vehicle.brand.delete');
            Route::get('/type/delete/{id}', [VehicleController::class, 'deleteVehicleType'])->middleware('feature_control:8')->name('vehicle.type.delete');
        });
    });

    Route::prefix('location')->group(function () {
        Route::get('/', [LocationController::class, 'getLocationList'])->middleware('feature_control:2');
        Route::get('/add', [LocationController::class, 'addLocation'])->middleware('feature_control:1');
        Route::get('/search', [LocationController::class, 'searchLocation']);
        Route::get('/{id}', [LocationController::class, 'getLocationDetail'])->middleware('feature_control:2');

        Route::middleware('log:activity')->group(function () {
            Route::post('/add', [LocationController::class, 'createLocation'])->name('location.add')->middleware('feature_control:1');
            Route::post('/{id}', [LocationController::class, 'updateLocation'])->name('location.update')->middleware('feature_control:3');
            Route::get('/delete/{id}', [LocationController::class, 'deleteLocation'])->name('location.delete')->middleware('feature_control:4');
            Route::get('/delete/device/{location_id}/{id}', [LocationController::class, 'deleteDevice'])->name('location.device.delete')->middleware('feature_control:4');
            Route::post('/update/status/{id}', [LocationController::class, 'updateStatusLocation'])->name('location.status.update')->middleware('feature_control:3');

            Route::post('/developer/add', [LocationController::class, 'createLocationDeveloper'])->name('location.developer.add')->middleware('feature_control:1');
            Route::get('/developer/delete/{id}', [LocationController::class, 'deleteLocationDeveloper'])->name('location.developer.delete')->middleware('feature_control:4');
        });
    });

    Route::prefix('apply-casion')->group(function () {
        Route::get('/', [ApplyForCasionController::class, 'getApplyCasionList'])->middleware('feature_control:9');
        Route::get('/export', [ApplyForCasionController::class, 'exportApplyCasion'])->middleware('feature_control:9');
        Route::get('/{id}', [ApplyForCasionController::class, 'getApplyCasionDetail'])->middleware('feature_control:9');

        Route::middleware('log:activity')->group(function () {
            Route::post('/{id}', [ApplyForCasionController::class, 'updateDetailApplyCasion'])->name('apply.for.casion.detail.update')->middleware('feature_control:10');
            Route::post('/update/status/{id}', [ApplyForCasionController::class, 'updateStatusApplyCasion'])->name('apply.for.casion.status.update')->middleware('feature_control:10');
        });
    });

    Route::prefix('mobile-user')->group(function (){
        Route::get('/', [MobileUserController::class, 'getMobileUserList'])->middleware('feature_control:11');
        Route::get('/{id}', [MobileUserController::class, 'getMobileUserDetail'])->middleware('feature_control:11');
        Route::get('/{user_id}/transaction/{trx_id}', [MobileUserController::class, 'getMobileUserTransactionDetail'])->middleware('feature_control:11');

        Route::post('/update/status/{id}', [MobileUserController::class, 'updateStatusMobileUser'])->name('mobile.user.status.update')->middleware('feature_control:12');
    });

    Route::prefix('cms-user')->middleware('super.admin')->group(function () {
        Route::get('/', [CMSUserController::class, 'getUserList']);
        Route::get('/add', [CMSUserController::class, 'addUser']);
        Route::get('/{id}', [CMSUserController::class, 'getUserDetail']);

        Route::middleware('log:activity')->group(function () {
            Route::post('/add', [CMSUserController::class, 'createUser'])->name('cms.user.add');
            Route::post('/update/role/{id}', [CMSUserController::class, 'updateRoleUser'])->name('cms.user.role.update');
            Route::post('/update/detail/{id}', [CMSUserController::class, 'updateUserDetail'])->name('cms.user.detail.update');
            Route::post('/update/password/{id}', [CMSUserController::class, 'updatePasswordDetail'])->name('cms.user.password.update');
        });
    });

    Route::prefix('role')->middleware('super.admin')->group(function () {
        Route::get('/', [RoleController::class, 'getRoleList']);
        Route::get('/{id}', [RoleController::class, 'getRoleDetail']);

        Route::middleware('log:activity')->group(function () {
            Route::post('/add', [RoleController::class, 'createRole'])->name('role.add');
            Route::post('/{id}', [RoleController::class, 'updateRole'])->name('role.update');
            Route::get('/delete/{id}', [RoleController::class, 'deleteRole'])->name('role.delete');
        });
    });

    Route::prefix('ecs')->group(function () {
        Route::get('/', [ECSController::class, 'getECSList'])->middleware('feature_control:28');
        Route::get('/search', [ECSController::class, 'searchECS']);
        Route::get('/pricing', [ECSController::class, 'listPricingECS'])->middleware('feature_control:28');
        Route::get('/{id}', [ECSController::class, 'getEcsDetail'])->middleware('feature_control:28');
        Route::get('/qrcode/{ecs_id}/{connector_id?}', [ECSController::class, 'generateQRCode'])->middleware('feature_control:28');

        Route::middleware('log:activity')->group(function () {
            Route::get('/start-charging/{ecs_id}/{connector_id}', [ECSController::class, 'startCharging'])->name('ecs.start.charging'); //->middleware('feature_control:29');
            Route::get('/stop-charging/{ecs_id}/{connector_id}/{reference}', [ECSController::class, 'stopCharging'])->name('ecs.stop.charging'); //->middleware('feature_control:29');
            Route::post('assign-location', [ECSController::class, 'assignECSLocation'])->name('ecs.assign.to.location')->middleware('feature_control:29');
            Route::post('/{id}/pricing/update', [ECSController::class, 'updateECSPricing'])->name('ecs.pricing.update')->middleware('feature_control:29');
        });

        Route::prefix('ajax')->group(function () {
            Route::get('/connectors/{id}', [ECSController::class, 'ajaxGetConnectors']);
        });

        //TODO Development only, will remove soon!
        Route::get('/dev/register-ecs/{ecs_id}', [ECSController::class, 'registerECS']);
    });
});
