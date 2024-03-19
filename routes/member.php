<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberListController;
use App\Http\Controllers\MemberServiceController;
use \App\Http\Controllers\MemberFindController;
use \App\Http\Controllers\MemberUploadController;
use \App\Http\Controllers\ClientDetailController;
use \App\Http\Controllers\ClientServiceUnusedController;


Route::get('/member/main/{type}/{page?}', [ MemberController::class, 'index' ])->name("member.index" )->middleware("auth.check");

/* 이용자명단 */
Route::get('/member/list', [ MemberListController::class, 'index' ])->name("member.list.index" );
Route::put('/member/list', [ MemberListController::class, 'update' ])->name("member.list.update" )->middleware("auth.check");
Route::get('/member/add/{type?}', [ MemberListController::class, 'create' ])->name("member.list.create" )->middleware("auth.check");

/*엑셀 다운로드*/
Route::get('/member/list/excel/download', [ MemberListController::class, 'excel_download' ])->name("member.excel.download" )->middleware("auth.check");

/* 이용자 1명 등록 */
Route::post('/member/register', [ MemberListController::class, 'store' ])->name("member.store" )->middleware("auth.check");

/* 이용자 일괄등록 */
//Route::post('/member/add/batch/basic', [ MemberListController::class, 'basic_upload' ])->middleware("auth.check", "excel.upload");
//Route::post('/member/add/batch/detail', [ MemberListController::class, 'detail_upload' ])->middleware("auth.check", "excel.upload");


/* 이용자 찾기 */
Route::get('/member/find/', [ MemberFindController::class, 'index' ])->name("member.find" )->middleware("auth.check");
//Route::post('/member/find/', [ MemberFindController::class, 'search' ])->name("member.find.action" )->middleware("auth.check");

/* 현황 */
Route::get('/member/sort/{type}/{page?}', [ MemberListController::class, 'sort' ])->name("member.sort" )->middleware("auth.check");
//Route::get('/member/sort/{type}/{page?}', [ MemberListController::class, 'sortMna' ])->name("member.sort" )->middleware("auth.check");


/* 서비스 이용 */
Route::get('/member/service/checklist/{page?}', [ MemberListController::class, 'service' ])->name("member.service.checklist" )->middleware("auth.check", "cors");
Route::get('/member/service/list/{page?}', [ MemberListController::class, 'service_list' ])->name("member.service.list" )->middleware("auth.check");


Route::get("/member/calendar/list", [ MemberListController::class, 'calendar_call' ])->name("member.calendar.list")->middleware("auth.check");


Route::post("/member/calendar/reload", [ MemberListController::class, 'calendar_reload' ])->name("member.calendar.reload")->middleware("auth.check");

Route::post("/member/service/log/worker", [ MemberServiceController::class, "ajax_get" ])->name("member.service.log.worker")->middleware("auth.check");

Route::get("/member/service/unused", [ ClientServiceUnusedController::class, 'index' ])->name("member.service.unused")->middleware("auth.check");





/* 변경되는 이용자 기본양식 */
Route::post("/member/upload/excel", [ MemberUploadController::class, "store" ])->name("member.upload.store")->middleware("auth.check");
Route::post("/member/upload/detail/excel", [ ClientDetailController::class, "store" ])->name("member.detail.upload.store")->middleware("auth.check");
