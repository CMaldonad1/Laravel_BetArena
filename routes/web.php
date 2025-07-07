<?php
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use \App\Http\Controllers\LoginController;
use \App\Http\Controllers\JornadesController;
use \App\Http\Controllers\ApostesController;
use \App\Http\Controllers\FPDFController;

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
Route::get("/log", [LoginController::class, "index"])->name("log");
Route::post("/log/verification", [LoginController::class, "verification"])->name("/log/verification")->middleware('logs');
Route::get("/log/logout", [LoginController::class, "logout"])->name("/log/logout");
Route::post("/log/selection", [LoginController::class, "selectJornada"])->name("/log/selection");

Route::get("/signup", [LoginController::class, "registre"])->name("/signup");
Route::post("/signup/registre", [LoginController::class, "registrarUsuari"])->name("/signup/registre")->middleware('userReg');
Route::get("/valida/{email}", [LoginController::class, "validaUsuari"])->name("/valida")->middleware('validat');

Route::get("/recover", [LoginController::class, "recupera"])->name("/recover");
Route::post("/recover/update", [LoginController::class, "recuperarContrasenya"])->name("/recover/update")->middleware('emailExist');

Route::get("/activa/{email}", [LoginController::class, "activaCompte"])->name("/activa");

Route::get("/changepswrd", [LoginController::class, "canviPswrd"])->name("/changepswrd")->middleware('userlog');
Route::post("/changepswrd/update", [LoginController::class, "updateNewPswrd"])->name("/changepswrd/update")->middleware('userlog');

Route::get("/uploadJornada", [JornadesController::class, "uploadJSON"])->name("/uploadJornada")->middleware('adminLoged');
Route::post("/uploadJornada/upload", [JornadesController::class, "pujarJoranda"])->name("/uploadJornada/upload")->middleware('JSONvalid');

Route::get("/resultats", [JornadesController::class, "resultsView"])->name("/resultats")->middleware('adminLoged');
Route::post("/resultats/upload", [JornadesController::class, "updateResults"])->name("/resultats/upload");

Route::get("/aposta", [ApostesController::class, "apostesView"])->name("/aposta")->middleware('userlog');
Route::post("/aposta/upload", [ApostesController::class, "ferAposta"])->name("/aposta/upload")->middleware('userlog');

Route::get("/pdf", [FPDFController::class, "createPDF"])->name("/pdf");
