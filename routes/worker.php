<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkerFindController;
use App\Http\Controllers\WorkerWorkListController;
use App\Http\Controllers\WorkerServiceController;
use App\Http\Controllers\WorkerTimetableController;
use App\Http\Controllers\WorkerUploadController;
use App\Http\Controllers\HelperServiceCalendarController;




Route::get('/worker/main/{type}/{page?}', [ WorkerController::class, 'index' ])->name("worker.index" )->middleware("auth.check");;


/* 월별 급여 현황 */
Route::get('/worker/monthly/paid/{page?}', [ WorkerController::class, 'pay' ])->name("worker.monthly.pay" )->middleware("auth.check");;


/* 급여계산 근로시간 현황 */
Route::get('/worker/cal/{type}/{page?}', [ WorkerController::class, 'cal' ])->name("worker.cal.index" )->middleware("auth.check");;

/* 수당 지급내역 현황 */
Route::get('/worker/allowance/{page?}', [ WorkerController::class, 'allowance' ])->name("worker.allowance.index" )->middleware("auth.check");;

/* 퇴직금 누적적립 현황 */
Route::get('/worker/retiring/{type}/{page?}', [ WorkerController::class, 'retiring' ])->name("worker.retiring" )->middleware("auth.check");;

/* 근무현황 */
Route::get('/worker/work/list/', [ WorkerWorkListController::class, 'index' ])->name("worker.work.list" )->middleware("auth.check");;
Route::get('/worker/work/off/', [ WorkerController::class, 'off' ])->name("worker.work.off" )->middleware("auth.check");;
Route::get('/worker/work/date/{page?}', [ WorkerController::class, 'date' ])->name("worker.work.date" )->middleware("auth.check");;
Route::get('/worker/monthly/payment/', [ WorkerController::class, 'payment' ])->name("worker.monthly.payment" )->middleware("auth.check");;
Route::get('/worker/service/offer', [ WorkerController::class, 'service' ])->name("worker.service" )->middleware("auth.check");;
Route::get('/worker/service/list', [ WorkerServiceController::class, 'index' ])->name("worker.service.list" )->middleware("auth.check");;


/* 활동지원 명단 */
Route::get('/worker', [ WorkerController::class, 'workers' ])->name("worker.workers" )->middleware("auth.check");;
Route::get('/worker/add/{type}', [ WorkerController::class, 'add' ])->name("worker.add" )->middleware("auth.check");
Route::get('/worker/find', [ WorkerFindController::class, 'index' ])->name("worker.find" )->middleware("auth.check");

/* 활동지원사 계획표 */
Route::get('/worker/timetable', [ WorkerTimetableController::class, 'index' ])->name("worker.timetable" )->middleware("auth.check");
Route::get('/worker/timetable/upload', [ WorkerTimetableController::class, 'create' ])->name("worker.timetable.create" )->middleware("auth.check");
Route::post('/worker/timetable/upload', [ WorkerTimetableController::class, 'store' ])->name("worker.timetable.store" )->middleware("auth.check");
Route::delete('/worker/timetable/upload', [ WorkerTimetableController::class, 'delete' ])->name("worker.timetable.delete" )->middleware("auth.check");



Route::post('/worker/register', [ WorkerController::class, 'store' ])->name("worker.register" );
//Route::post('/worker/add/batch/basic', [ WorkerController::class, 'batch_basic' ])->name("worker.batch.basic" )->middleware("auth.check", "excel.upload");


Route::post('/worker/add/batch/basic', [ WorkerUploadController::class, 'store' ])->name("worker.upload.store" )->middleware("auth.check", "excel.upload");
Route::post('/worker/add/batch/detail', [ WorkerUploadController::class, 'detail' ])->name("worker.upload.detail" )->middleware("auth.check", "excel.upload");
Route::post('/worker/add/batch/detail2', [ WorkerUploadController::class, 'detail_second' ])->name("worker.upload.detail2" )->middleware("auth.check", "excel.upload");



Route::post("/worker/member/service/log", [ WorkerWorkListController::class, 'reload' ])->name("worker.member.service.log")->middleware("auth.check");


Route::post("/worker/service/calendar/time", [ HelperServiceCalendarController::class, "time" ])->name("helper.calendar.time")->middleware("auth.check");
Route::post("/worker/service/calendar/kind", [ HelperServiceCalendarController::class, "kind" ])->name("helper.calendar.kind")->middleware("auth.check");


Route::post("/worker/service/calendar/render/time", [ HelperServiceCalendarController::class, "timeRender" ])->name("helper.calendar.time.render")->middleware("auth.check");
Route::post("/worker/service/calendar/render/kind", [ HelperServiceCalendarController::class, "kindRender" ])->name("helper.calendar.kind.render")->middleware("auth.check");