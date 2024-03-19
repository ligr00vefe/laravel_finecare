<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MemberController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DevCustomController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\PublicDataController;
use App\Http\Controllers\CrawlingController;
use App\Http\Controllers\LocationUploadController;
use App\Http\Controllers\LocationAjaxController;
use App\Http\Controllers\PaymentRecordController;
use App\Http\Controllers\WorkerPaymentRecordController;
use App\Http\Controllers\RecalculateAnnualController;
use App\Http\Controllers\RecalculateAnnualApplyController;
use App\Http\Controllers\RecalcSaveController;
use App\Http\Controllers\HelperWorkOffController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\DashboardController;
use App\Executes\DayOffReCalculateRecordsSave;

Route::get('/', function() {
    if (session()->has("user")) {
        return redirect("/member/main/all");
    }else {
        return redirect("/login");
    }
})->name("user.index");
//Route::get('/', function() {
//    return redirect()->route('member.index', [ "type" => "all", "page"=>1 ]);
//});

Route::get("/login", [ AuthController::class, "index"])->name("login");
Route::post("/loginAction", [ AuthController::class, "login"])->name("login.action");

Route::get("/logout", [ AuthController::class, "logout"])->name("auth.logout");

Route::get("/test", function() {
    return view("test");
});

Route::post("/test/action", [ DevCustomController::class, "simplified_tax_table" ]);

// 엑셀
Route::post('/export/excel/payments', [ ExcelController::class, "payment" ])->name("excel.export.payment")->middleware("auth.check");
Route::post('/export/excel/helpers', [ ExcelController::class, "helpers" ])->name("excel.export.helpers")->middleware("auth.check");
Route::post('/export/excel/schedule', [ ExcelController::class, "schedule" ])->name("excel.export.schedule")->middleware("auth.check");
Route::post('/export/excel/payslip', [ExcelController::class, "payslip"])->name("excel.export.payslip")->middleware("auth.check");

// PDF 다운로드
Route::post('/pdf/payslip', [PDFController::class, "payslip"])->name("pdf.payslip")->middleware("auth.check");


// 대시보드
Route::get('/dashboard', [DashboardController::class, "index"])->name("dashboard")->middleware("auth.check");




Route::post("/ajax/gabgeunse", [ AjaxController::class, "gabgeunse" ])->name("ajax.get.gabgeunse")->middleware("auth.check");


Route::get("/holiday/get", [ PublicDataController::class, "special" ])->name("public.day.special")->middleware("admin.access");
Route::get("/crawling/location", [ CrawlingController::class, "location" ])->name("crawling.location")->middleware("admin.access");



// 지역정보 업로드
//Route::get("/location/upload", [ LocationUploadController::class, "upload" ])->name("location.upload")->middleware("admin.access");
Route::get("/location/get", [ LocationAjaxController::class, "get" ])->name("location.get")->middleware("auth.check");


Route::prefix("record")->group(function () {
    Route::resource("/payment", PaymentRecordController::class)->middleware("auth.check");;
    Route::resource("/worker", WorkerPaymentRecordController::class)->middleware("auth.check");;
});



Route::resource("/recalc", \App\Http\Controllers\RecalculateController::class)->middleware("auth.check");

Route::resource("/recalcDiff", \App\Http\Controllers\RecalculateDiffController::class)->middleware("auth.check");;
Route::resource("/recalcAnnual", RecalculateAnnualController::class)->middleware("auth.check");
Route::resource("/recalcAnnualApply", RecalculateAnnualApplyController::class)->middleware("auth.check");

Route::post("/recalc/save", [ RecalcSaveController::class, "index" ])->middleware("auth.check")->middleware("auth.check");


Route::get("/work/off", [ HelperWorkOffController::class, "index" ])->middleware("auth.check");
Route::post("/work/off/store", [ HelperWorkOffController::class, "store" ])->middleware("auth.check");
Route::post("/work/off/delete", [ HelperWorkOffController::class, "destroy" ])->middleware("auth.check");


Route::post("/recalculate/offday", [ DayOffReCalculateRecordsSave::class, "store" ])->middleware("auth.check");
Route::post('/export/excel/recalcoffdaypay', [ ExcelController::class, "recalcOffDayPay" ])->name("excel.export.recalc.offday")->middleware("auth.check");
Route::post('/export/excel/client/list', [ ExcelController::class, "clients" ])->name("excel.export.client")->middleware("auth.check");

// 추가 서비스 내역 엑셀 내려 받기
Route::post('/export/excel/service/extra', [ ExcelController::class, "serviceExtra" ])->name("excel.export.service.extra")->middleware("auth.check");



include_once("member.php");
include_once("worker.php");
include_once("counseling.php");
include_once("service.php");
include_once("etcincome.php");
include_once("social.php");
include_once("salary.php");
include_once("addon.php");
include_once("support.php");
include_once("admin.php");
include_once("video.php");
