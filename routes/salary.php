<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\PaybookController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\PaymentDeductionController;

/* 비포괄임금제계산 */
Route::get('/salary/non/{page?}', [ SalaryController::class, 'non_inclusive' ])->name("salary.non" )->middleware("auth.check");
Route::get('/salary/inclusive/{page?}', [ SalaryController::class, 'inclusive' ])->name("salary.inclusive" )->middleware("auth.check");

Route::get('/salary/calc', [ SalaryController::class, 'calc' ])->name("salary.calc" )->middleware("auth.check");
Route::post('/salary/calc', [ SalaryController::class, 'calc_action' ])->name("salary.calc.action" )->middleware("auth.check");

Route::get('/salary/paybook', [ PaybookController::class, 'index' ])->name("paybook.index" )->middleware("auth.check");


Route::get('/salary/deduction', [ PaymentDeductionController::class, 'index' ])->name("deduction.index" )->middleware("auth.check");


// 계산한거 저장하기
Route::post('/salary/calc/save', [ SalaryController::class, "save" ])->name("salary.calc.save")->middleware("auth.check");





Route::get("/salary/payslip", [ PayslipController::class, "index" ])->name("payslip.index")->middleware("auth.check");


