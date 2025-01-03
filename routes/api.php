<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\MigrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleCotroller;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/auth/logOut', [AuthController::class, 'logOut']);

    Route::get('get-users-per-roles/{role}', [RoleCotroller::class, 'getUsersPerRoles']);
    Route::get('user-by-token', [AuthController::class, 'userByToken']);



    Route::get('get-users-per-roles/{role}', [RoleCotroller::class, 'getUsersPerRoles']);
    Route::get('get-roles', [RoleCotroller::class, 'getRoles']);
    Route::post('insertInfo', [InfoController::class, 'insertInfo']);
    Route::post('insertCategory', [InfoController::class, 'storeCategory']);
    Route::post('updateCategory/{id}', [InfoController::class, 'updateCategory']);
    // Route::get('current-user', [AuthController::class, 'currentUser']);

    Route::get('/run-migrations', [MigrationController::class, 'migrateAndSeed']);

    Route::get('/infos', [InfoController::class, 'index']);
    Route::post('/infos/filter', [InfoController::class, 'filter']);
    Route::get('/infos/{id}', [InfoController::class, 'show']);

    Route::post('/infos/toggle-info', [InfoController::class, 'saveDocumentForUser']);


    Route::get('/getCategories', [InfoController::class, 'getCategories']);

    // Route::middleware(['admin'])->group(function () {
    Route::post('/infos', [InfoController::class, 'store']);
    Route::post('/infos/{id}', [InfoController::class, 'update']);
    Route::delete('/infos/{id}', [InfoController::class, 'destroy']);
    Route::delete('/deleteCategory/{id}', [InfoController::class, 'deleteCategory']);
    // });
});

Route::post('/auth/signIn', [AuthController::class, 'signIn']);
