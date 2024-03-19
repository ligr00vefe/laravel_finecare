<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CounselingController;




Route::get('/counseling/users/{type?}/{page?}', [ CounselingController::class, 'index' ])->name("counseling.index" )->middleware("auth.check");;
Route::get('/counseling/log/create/{type}/{id}', [ CounselingController::class, 'create' ])->name("counseling.create" )->middleware("auth.check");;
Route::get('/counseling/log/view/{id?}', [ CounselingController::class, 'show' ])->name("counseling.show" )->middleware("auth.check");;
Route::get('/counseling/log/list', [ CounselingController::class, 'log' ])->name("counseling.log" )->middleware("auth.check");;

Route::post('/counseling/log/store', [ CounselingController::class, 'store' ])->name("counseling.store" )->middleware("auth.check");
Route::get('/counseling/log/edit/{id}', [ CounselingController::class, 'edit' ])->name("counseling.edit" )->middleware("auth.check");
Route::put('/counseling/log/edit', [ CounselingController::class, 'update' ])->name("counseling.update" )->middleware("auth.check");
Route::delete('/counseling/log/{id}', [ CounselingController::class, 'delete' ])->name("counseling.delete" )->middleware("auth.check");


