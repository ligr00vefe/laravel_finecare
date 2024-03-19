<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\QNABoardController;
use App\Http\Controllers\BoardLibraryController;
use App\Http\Controllers\BoardVideoController;

/* 온라인문의  */
Route::get('/support/qna/list', [ QNABoardController::class, 'index' ])->name("support.qna" )->middleware("auth.check");
Route::get('/support/qna/write', [ QNABoardController::class, 'create' ])->name("support.qna.create" )->middleware("auth.check");
Route::post('/support/qna', [ QNABoardController::class, 'store' ])->name("support.qna.store" )->middleware("auth.check");
Route::get('/support/qna/view/{id?}', [ QNABoardController::class, 'show' ])->name("support.qna.show" )->middleware("auth.check");
Route::get('/support/qna/write/{id?}', [ QNABoardController::class, 'edit' ])->name("support.qna.edit" )->middleware("auth.check");
Route::put('/support/qna/write/{id?}', [ QNABoardController::class, 'update' ])->name("support.qna.update" )->middleware("auth.check");
Route::delete('/support/qna/{id}', [ QNABoardController::class, 'destroy' ])->name("support.qna.destroy" )->middleware("auth.check");


/* 자료실 */
Route::get('/support/lib/list', [ BoardLibraryController::class, 'index' ])->name("support.lib" )->middleware("auth.check");
Route::get('/support/lib/write', [ BoardLibraryController::class, 'create' ])->name("support.lib.create" )->middleware("auth.check");
Route::post('/support/lib', [ BoardLibraryController::class, 'store' ])->name("support.lib.store" )->middleware("auth.check");
Route::get('/support/lib/view/{id?}', [ BoardLibraryController::class, 'show' ])->name("support.lib.show" )->middleware("auth.check");
Route::get('/support/lib/write/{id?}', [ BoardLibraryController::class, 'edit' ])->name("support.lib.edit" )->middleware("auth.check");
Route::put('/support/lib/write/{id?}', [ BoardLibraryController::class, 'update' ])->name("support.lib.update" )->middleware("auth.check");
Route::delete('/support/lib/{id}', [ BoardLibraryController::class, 'destroy' ])->name("support.lib.destroy" )->middleware("auth.check");


/* 동영상 */
Route::get('/support/video/list', [ BoardVideoController::class, 'index' ])->name("support.video" )->middleware("auth.check");
Route::get('/support/video/write', [ BoardVideoController::class, 'create' ])->name("support.video.create" )->middleware("auth.check");
Route::post('/support/video', [ BoardVideoController::class, 'store' ])->name("support.video.store" )->middleware("auth.check");
Route::get('/support/video/view/{id?}', [ BoardVideoController::class, 'show' ])->name("support.video.show" )->middleware("auth.check");
Route::get('/support/video/write/{id?}', [ BoardVideoController::class, 'edit' ])->name("support.video.edit" )->middleware("auth.check");
Route::put('/support/video/write/{id?}', [ BoardVideoController::class, 'update' ])->name("support.video.update" )->middleware("auth.check");
Route::delete('/support/video/{id}', [ BoardVideoController::class, 'destroy' ])->name("support.video.destroy" )->middleware("auth.check");




Route::get('/support/qna/answer/{id?}', [ SupportController::class, 'qna_answer' ])->name("support.qna.answer" )->middleware("auth.check");



/* FAQ */
Route::get('/support/faq/list/{page?}', [ SupportController::class, 'faq_list' ])->name("support.faq" )->middleware("auth.check");

