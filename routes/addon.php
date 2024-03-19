<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddonsController;

/* 비포괄임금제계산 */
Route::get('/addon/statistics', [ AddonsController::class, 'index' ])->name("addon.index" )->middleware("auth.check");;
Route::get('/addon/other/{type}', [ AddonsController::class, 'other' ])->name("addon.other" )->middleware("auth.check");;
Route::get('/addon/tax/{type}', [ AddonsController::class, 'tax' ])->name("addon.tax.year" )->middleware("auth.check");;
Route::get('/addon/tax/data/year', [ AddonsController::class, 'year_end_tax' ])->name("addon.tax.data.year" )->middleware("auth.check");;
Route::get('/addon/rsnumber/batch/{page?}', [ AddonsController::class, 'rsnumber_batch' ])->name("addon.rsnumber.batch" )->middleware("auth.check");;




