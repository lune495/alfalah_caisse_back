<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaisseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/vente/ticket-pdf-service/{id}', [CaisseController::class,'generatePDF']);
Route::get('/vente/ticket-pdf-pharmacie/{id}', [CaisseController::class,'generatePDF3']);
Route::get('/test', [CaisseController::class,'Notif']);
Route::get('/vente/situation-generale-pdf', [CaisseController::class,'generatePDF2']);
Route::get('/vente/situation-filtre-pdf/{start}', [CaisseController::class,'SituationParFiltreDate']);
