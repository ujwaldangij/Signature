<?php

use App\Http\Controllers\SignatureController;
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

Route::get("/",[SignatureController::class,'index'])->name('home');
Route::post("/postSignature",[SignatureController::class,'postSignature'])->name('postSignature');
Route::get("/schedule_id_edit_frame/{id}", [SignatureController::class, 'schedule_id_edit_frame'])->name('schedule_id_edit_frame');


Route::fallback(function (){
    return view("WebsitePages.SuperAdmin.404");
});