<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

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

Route::middleware( 'auth' )->group( function () {
    Route::get( '/uploads', [ UploadController::class, 'listUploads' ] );
    Route::post( '/uploads', [ UploadController::class, 'handleUpload' ] );

    Route::get( '/saved', [ UploadController::class, 'listSaved' ] );
    Route::post( '/saved', [ UploadController::class, 'moveUpload' ] );
} );
