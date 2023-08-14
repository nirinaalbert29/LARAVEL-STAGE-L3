<?php

use App\Http\Controllers\actionController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\categorieController;
use App\Http\Controllers\compteController;
use App\Http\Controllers\exportController;
use App\Http\Controllers\IntervenantController;
use App\Http\Controllers\menuController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\statistiqueController;
use App\Http\Controllers\suiteController;
use App\Http\Controllers\testController;
use App\Http\Controllers\ticketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});





Route::get('/newcompte',[compteController::class,'index']);
Route::post('/compte-create',[compteController::class,'create']);
Route::get('/mdpoublie',[compteController::class,'mdpindex']);
Route::post('/mdpoublie',[compteController::class,'oublie']);

Route::get('/',[compteController::class,'connection']);
Route::post('/',[compteController::class,'login']);
Route::get('/principale',[menuController::class,'menu']);

Route::get('/ticket',[ticketController::class,'index']);
Route::post('/ticket',[ticketController::class,'store']);
Route::put('/ticket-update/{id}',[ticketController::class,'update']);
Route::get('/ticketok',[ticketController::class,'ticketok']);
Route::get('/ticketsuivre',[ticketController::class,'ticketsuivre']);
Route::get('/ticketglobal',[ticketController::class,'global']);
Route::get('/ticketInaccessible',[ticketController::class,'inaccess']);

Route::get('/continue-ticket/{id}',[suiteController::class,'continue']);
Route::get('/suite-ticket',[suiteController::class,'suite']);
Route::put('/suite-update/{id}',[suiteController::class,'update']);

Route::get('/termine-pause/{id}',[ticketController::class,'termine']);

Route::post('/modif-reunion/{id}',[ticketController::class,'reunionUpdate']);

Route::get('/statmensuel',[statistiqueController::class,'index']);
Route::post('/statmensuel',[statistiqueController::class,'stat']);

Route::get('/statglobalmensuel',[adminController::class,'stat']);
Route::post('/statglobalmensuel',[adminController::class,'globalmensuel']);

Route::get('/statglobalhebdo',[adminController::class,'statis']);
Route::post('/statglobalhebdo',[adminController::class,'globalhebdo']);

Route::get('/stathebdo',[statistiqueController::class,'hebdo']);
Route::post('/stathebdo',[statistiqueController::class,'stathebdo']);

Route::get('/stathebdoAjourd',[statistiqueController::class,'hebdoAujourd']);
Route::get('/statglobalAjourd',[statistiqueController::class,'globalAujourd']);



//Email
Route::get('/envoyer', [MessageController::class, 'create'])->name('envoyer.form');
Route::post('/envoyer', [MessageController::class, 'send'])->name('envoyer.message');

//test elemt
Route::get('/test',[testController::class,'index']);


Route::get('/dash-admin', [adminController::class, 'dash']);
Route::get('/login-admin', [adminController::class, 'indexlogin']);
Route::post('/login-admin', [adminController::class, 'connex']);
Route::get('/create-admin', [adminController::class, 'indexcreate']);
Route::post('/create-admin', [adminController::class, 'create']);
Route::get('/mdpoublie-admin',[adminController::class,'mdpindex']);
Route::post('/mdpoublie-admin', [adminController::class, 'oublie']);


Route::get('/change-mdp-inter',[compteController::class,'changemdp']);
Route::put('changemdp-inter/{id}', [compteController::class, 'update_mdp_inter']);



Route::get('deconnect', [adminController::class,'deconnect']);


Route::put('/intervenant-update-compte/{id}', [IntervenantController::class, 'updatecompte']);
Route::middleware(['web', 'admin.auth'])->group(function () {
    // Routes protégées ici
    Route::get('/export',[exportController::class,'index']);
    Route::post('/export',[exportController::class,'export']);
    Route::get('/changemdp-admin',[adminController::class,'changemdp']);
    Route::put('changemdp-admin/{id}', [adminController::class, 'update_mdp']);
    Route::put('/admin-update/{id}', [adminController::class, 'update_admin']);
    Route::get('/intervenants', [IntervenantController::class, 'index']);
    Route::post('/intervenant-ajout', [IntervenantController::class, 'ajout']);
    Route::post('/intervenant-ajout-login', [IntervenantController::class, 'ajout_login']);
    Route::put('/intervenant-update/{id}', [IntervenantController::class, 'update']);
    Route::get('/principale-admin', [adminController::class, 'menu']);
    Route::get('/stathebdo-admin',[adminController::class,'hebdo']);
    Route::post('/stathebdo-admin',[adminController::class,'stathebdo']);

    Route::get('/statmensuel-admin',[adminController::class,'index']);
    Route::post('/statmensuel-admin',[adminController::class,'statmensuel']);

    //import categ
    Route::post('categorie/import', [categorieController::class,'importExcel'])->name('categorie.import');
    Route::get('/intervenant-delete/{id}',[IntervenantController::class,'supprimer']);
    // Route::get('/intervenants/{id}/photo', [IntervenantController::class, 'showPhoto'])->name('intervenants.photo');
    Route::get('/action-liste',[actionController::class,'index']);
    Route::post('/action-ajout',[actionController::class,'store']);
    Route::put('/action-update/{id}', [actionController::class, 'update']);
    Route::get('/action-delete/{id}',[actionController::class,'supprimer']);

    Route::get('/categorie',[categorieController::class,'form']);
    Route::post('/categorie-ajout',[categorieController::class,'store']);
    Route::put('/categorie-update/{id}', [categorieController::class, 'update']);
    Route::get('/categorie-delete/{id}',[categorieController::class,'supprimer']);
});


