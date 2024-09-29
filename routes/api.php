<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApprenantController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReferentielController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/logout', [AuthController::class, 'logout']);


Route::prefix('/v1')->group(function () { 

    // Authentification
Route::post('/login', [AuthController::class, 'login']);

    // users 
 Route::prefix('/')->middleware('auth:api')->group(function () {  
Route::post('/user', [UserController::class, 'store']);
Route::get('/users', [UserController::class, 'index']);
Route::patch('/users/{id}', [UserController::class, 'update']);
    
});
Route::prefix('/')->middleware('auth:api')->group(function () {  
// referentiels
Route::get('/referentiels', [ReferentielController::class, 'getActiveReferentiels']);
Route::post('/referentiel', [ReferentielController::class, 'createReferentiel']);
Route::get('/referentiels/etat', [ReferentielController::class, 'getReferentielsByEtat']);
Route::get('/referentiels/{id}/competences', [ReferentielController::class, 'getReferentielCompetences']);
Route::get('/referentiels/{id}/modules', [ReferentielController::class, 'getReferentielModules']);
Route::patch('/referentiels/{id}', [ReferentielController::class, 'updateReferentiel']);
Route::delete('/referentiel/{id}', [ReferentielController::class,'softDeleteReferentiel']);
Route::get('/referentiels/deleted', [ReferentielController::class, 'getDeletedReferentiels']);
});

// Promotions
Route::prefix('/')->group(function () { 
    Route::group(['middleware' => 'promotion.closed'], function () {  
 Route::post('/promotion', [PromotionController::class, 'createPromotion']);
 Route::patch('promotions/{id}', [PromotionController::class, 'updatePromotion']);
 Route::patch('promotions/{id}/referentiels', [PromotionController::class, 'updatePromotionReferentiels']);

 });
 Route::get('/promotions/{id}/close', [PromotionController::class, 'closePromotion']);
 Route::get('/promotions/{id}/referentiel', [PromotionController::class, 'getActiveReferentiels']);
 Route::get('/promotions', [PromotionController::class, 'getAllPromotions']);
 Route::get('/promotions/current', [PromotionController::class, 'getCurrentPromotion']);
}); 
//   appreants
Route::prefix('/')->middleware('auth:api')->group(function () { 
    
 Route::post('/apprenants', [ApprenantController::class, 'createApprenant']);
 Route::get('/apprenant/{id}', [ApprenantController::class, 'show']);
 Route::get('/apprenants', [ApprenantController::class, 'listApprenants']);
 Route::post('/apprenants/import', [ApprenantController::class, 'importApprenants']);


}); 

 }); 









