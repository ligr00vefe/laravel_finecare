<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceExtraController;
use App\Http\Controllers\ServiceAddLogController;



/* 서비스내역조회 */
Route::get('/service/list/{type}', [ ServiceController::class, 'index' ])->name("service.index" )->middleware("auth.check");

/* 전자바우처 이용내역 검색 */
Route::get('/service/voucher/list/{page?}', [ ServiceController::class, 'voucher' ])->name("service.voucher" )->middleware("auth.check");
Route::post('/service/voucher/list/{page?}', [ ServiceController::class, 'voucher' ])->name("service.voucher.store" )->middleware("auth.check");

/* 전자바우처내역 등록 */
Route::get('/service/voucher/upload', [ ServiceController::class, 'upload' ])->name("service.voucher.upload" )->middleware("auth.check");
Route::get('/service/voucher/download/excel', [ ServiceController::class, 'download' ])->name("voucher.excel.download" )->middleware("auth.check");

Route::post('/service/voucher/upload', [ ServiceController::class, 'excel_upload' ])->name("service.voucher.upload.excel" )->middleware("auth.check", "excel.upload");




/* 추가 서비스내역 관리 */
//Route::get('/service/manage/list/{page?}', [ ServiceController::class, 'manage' ])->name("service.manage.list" )->middleware("auth.check");
//Route::get('/service/manage/write', [ ServiceController::class, 'manage_create' ])->name("service.manage.write" )->middleware("auth.check");


Route::get('/service/extra/list', [ ServiceExtraController::class, 'index' ])->name("service.extra.list" )->middleware("auth.check");
Route::get('/service/extra/create', [ ServiceExtraController::class, 'create' ])->name("service.extra.create" )->middleware("auth.check");
Route::post('/service/extra', [ ServiceExtraController::class, 'store' ])->name("service.extra.store" )->middleware("auth.check");
Route::put('/service/extra/update', [ ServiceExtraController::class, 'update' ])->name("service.extra.update" )->middleware("auth.check");
Route::delete('/service/extra/delete', [ ServiceExtraController::class, 'delete' ])->name("service.extra.delete" )->middleware("auth.check");




Route::post('/service/add/upload', [ ServiceAddLogController::class, 'import' ])->name("service.extra.import" )->middleware("auth.check");
