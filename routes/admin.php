<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminBoardController;
use App\Http\Controllers\AdminBoardLibrary;
use App\Http\Controllers\AdminVideoController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminProductController;




Route::get('/admin/', [ AdminController::class, 'index' ])->name("admin.index" )->middleware("auth.check", "admin.access");

/* 회원관리 */
Route::get('/admin/user/', [ AdminUserController::class, 'index' ])->name("admin.user" )->middleware("auth.check", "admin.access");
Route::get('/admin/user/register', [ AdminUserController::class, 'create' ])->name("admin.user.create" )->middleware("auth.check", "admin.access");
Route::post('/admin/user/', [ AdminUserController::class, 'store' ])->name("admin.user.store" )->middleware("auth.check", "admin.access");
Route::get('/admin/user/{id}/edit', [ AdminUserController::class, 'edit' ])->name("admin.user.edit" )->middleware("auth.check", "admin.access");
Route::put('/admin/user/{id}', [ AdminUserController::class, 'update' ])->name("admin.user.update" )->middleware("auth.check", "admin.access");
Route::delete('/admin/user/{id}', [ AdminUserController::class, 'destroy' ])->name("admin.user.delete" )->middleware("auth.check", "admin.access");

Route::post("/admin/user/goods", [ AdminUserController::class, "goods" ])->name("admin.user.goods")->middleware("auth.check", "admin.access");


/*게시판 관리*/
Route::get('/admin/board', [ AdminBoardController::class, 'index' ])->name("admin.board" )->middleware("auth.check", "admin.access");
Route::get('/admin/board/modify', [AdminBoardController::class, 'boardManagementModify'])->name('admin.board.modify')->middleware("auth.check", "admin.access");


/* 자료실 */
Route::get('/admin/board/archives', [ AdminBoardLibrary::class, 'index' ])->name("admin.board.archives" )->middleware("auth.check", "admin.access");
Route::get('/admin/board/archives/write', [ AdminBoardLibrary::class, 'create' ])->name("admin.board.archives.create" )->middleware("auth.check", "admin.access");
Route::post('/admin/board/archives', [ AdminBoardLibrary::class, 'store' ])->name("admin.board.archives.store" )->middleware("auth.check", "admin.access");
Route::get('/admin/board/archives/view/{id}', [ AdminBoardLibrary::class, 'show' ])->name("admin.board.archives.view" )->middleware("auth.check", "admin.access");
Route::get('/admin/board/archives/edit/{id}', [ AdminBoardLibrary::class, 'edit' ])->name("admin.board.archives.edit" )->middleware("auth.check", "admin.access");
Route::put('/admin/board/archives', [ AdminBoardLibrary::class, 'update' ])->name("admin.board.archives.update" )->middleware("auth.check", "admin.access");
Route::delete('/admin/board/archives', [ AdminBoardLibrary::class, 'destroy' ])->name("admin.board.archives.destroy" )->middleware("auth.check", "admin.access");
Route::delete('/admin/board/archives/all', [ AdminBoardLibrary::class, 'destroyAll' ])->name("admin.board.archives.destroyAll" )->middleware("auth.check", "admin.access");


/* 동영상 */
Route::get('/admin/board/video', [ AdminVideoController::class, 'index' ])->name("admin.board.video" )->middleware("auth.check", "admin.access");
Route::get('/admin/board/video/show/{id}', [ AdminVideoController::class, 'show' ])->name("admin.board.video.show" )->middleware("auth.check", "admin.access");
Route::get('/admin/board/video/write', [ AdminVideoController::class, 'create' ])->name("admin.board.video.write" )->middleware("auth.check", "admin.access");
Route::post('/admin/board/video', [ AdminVideoController::class, 'store' ])->name("admin.board.video.store" )->middleware("auth.check", "admin.access");
Route::get('/admin/board/video/edit/{id}', [ AdminVideoController::class, 'edit' ])->name("admin.board.video.edit" )->middleware("auth.check", "admin.access");
Route::put('/admin/board/video/', [ AdminVideoController::class, 'update' ])->name("admin.board.video.update" )->middleware("auth.check", "admin.access");
Route::delete('/admin/board/video/{id}', [ AdminVideoController::class, 'destroy' ])->name("admin.board.video.destroy" )->middleware("auth.check", "admin.access");
Route::delete('/admin/board/video', [ AdminVideoController::class, 'deleteAll' ])->name("admin.board.video.deleteAll" )->middleware("auth.check", "admin.access");



Route::get('/admin/board/inquiry', [ AdminBoardController::class, 'inquiry' ])->name("admin.board.inquiry" )->middleware("auth.check", "admin.access");
Route::get('/admin/board/inquiry/view/{id}', [AdminBoardController::class, 'boardOnlineInquiryView'])->name('admin.board.inquiry.view')->middleware("auth.check", "admin.access");
Route::get('/admin/board/inquiry/modify/{id}', [AdminBoardController::class, 'boardOnlineInquiryModify'])->name('admin.board.inquiry.modify')->middleware("auth.check", "admin.access");
Route::post('/admin/board/inquiry/modify/{id}', [AdminBoardController::class, 'boardOnlineInquiryStore'])->name('admin.board.inquiry.store')->middleware("auth.check", "admin.access");
Route::delete('/admin/board/inquiry/destroy/{id}', [AdminBoardController::class, 'boardOnlineInquiryDestroy'])->name('admin.board.inquiry.destroy')->middleware("auth.check", "admin.access");

Route::get('/admin/board/faq', [ AdminBoardController::class, 'boardFAQManagement' ])->name("admin.board.faq" )->middleware("auth.check", "admin.access");
Route::get('/admin/board/faq/reply', [AdminBoardController::class, 'boardFAQManagementReply'])->name('admin.board.faq.reply')->middleware("auth.check", "admin.access");
Route::get('/admin/board/faq/view/{id}', [AdminBoardController::class, 'boardFAQManagementView'])->name('admin.board.faq.view')->middleware("auth.check", "admin.access");
Route::get('/admin/board/faq/write', [AdminBoardController::class, 'boardFAQManagementWrite'])->name('admin.board.faq.write')->middleware("auth.check", "admin.access");
Route::post('/admin/board/faq', [ AdminBoardController::class, 'boardFAQManagementStore' ])->name("admin.board.faq.store" )->middleware("auth.check", "admin.access");
Route::get('/admin/board/faq/edit/{id}', [ AdminBoardController::class, 'boardFAQManagementEdit' ])->name("admin.board.faq.edit" )->middleware("auth.check", "admin.access");
Route::put('/admin/board/faq', [ AdminBoardController::class, 'boardFAQManagementUpdate' ])->name("admin.board.faq.update" )->middleware("auth.check", "admin.access");
Route::delete('/admin/board/faq/destroy', [ AdminBoardController::class, 'boardFAQManagementDestroy' ])->name("admin.board.faq.destroy" )->middleware("auth.check", "admin.access");
Route::delete('/admin/board/faq/destroy/all', [ AdminBoardController::class, 'boardFAQManagementDestroyAll' ])->name("admin.board.faq.destroy.all" )->middleware("auth.check", "admin.access");



/*결제 관리*/
Route::get('/admin/payment', [ AdminPaymentController::class, 'index' ])->name("admin.product" )->middleware("auth.check", "admin.access");
Route::get('/admin/product', [AdminProductController::class, 'index'])->name('admin.product.manage')->middleware("auth.check", "admin.access");
Route::get('/admin/product/write', [AdminBoardController::class, 'boardPaymentProductWrite'])->name('admin.product.board')->middleware("auth.check", "admin.access");

Route::put('/admin/product/{id}', [ AdminProductController::class, 'updateOne' ])->name('admin.product.updateOne')->middleware("auth.check", "admin.access");
Route::put('/admin/product', [AdminProductController::class, 'update'])->name('admin.product.update')->middleware("auth.check", "admin.access");
