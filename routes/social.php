<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\InsuranceManagementController;



/* 사회보험 기입정보 관리 */
Route::get('/social/insurance/manage', [ InsuranceManagementController::class, 'index' ])->name("social.insurance" )->middleware("auth.check");
Route::post('/social/insurance/manage', [ InsuranceManagementController::class, 'store' ])->name("social.insurance.store" )->middleware("auth.check");

/* 통합징수포털 */
Route::get('/social/collect/upload/{type?}', [ SocialController::class, 'collect' ])->name("social.collect" )->middleware("auth.check");
Route::get('/social/collect/list/{type}/{page?}', [ SocialController::class, 'collect_list' ])->name("social.collect.list" )->middleware("auth.check");


/* 엑셀업로드 */
Route::post('/social/collect/upload/{type?}', [ SocialController::class, 'collect_excel' ])->name("social.collect.upload" )->middleware("auth.check", "excel.upload");


/* edi */
Route::get('/social/EDI/upload/{type}', [ SocialController::class, 'edi_upload' ])->name("social.edi.upload" )->middleware("auth.check");
Route::get('/social/EDI/list/{type}', [ SocialController::class, 'edi_list' ])->name("social.edi.list" )->middleware("auth.check");


/* edi 엑셀업로드 */
Route::post('/social/EDI/upload/{type}', [ SocialController::class, 'edi_upload_action' ])->name("social.edi.upload.action" )->middleware("auth.check", "excel.upload");




