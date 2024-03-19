<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EtcIncomeController;



/* 기타수당 등록 */
Route::get('/etcIncome/{type}/registration', [ EtcIncomeController::class, 'create' ])->name("etcIncome.create" )->middleware("auth.check");

/* 기타수당 조회 */
Route::get('/etcIncome/{type}/view/{page?}', [ EtcIncomeController::class, 'index' ])->name("etcIncome.index" )->middleware("auth.check");;



Route::post("/etcIncome/transportation/register", [ EtcIncomeController::class, 'fare_upload' ])->name("etcIncome.fare.upload" )->middleware("auth.check", "excel.upload");

Route::post("/etcIncome/severe/register", [ EtcIncomeController::class, 'severe_upload' ])->name("etcIncome.severe.upload" )->middleware("auth.check", "excel.upload");
