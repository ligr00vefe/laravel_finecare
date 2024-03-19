<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoBoardController;



Route::get('/support/video', [ VideoBoardController::class, 'index' ])->name("video.index" )->middleware("auth.check");
Route::get('/support/video/create', [ VideoBoardController::class, 'create' ])->name("video.create" )->middleware("auth.check");
Route::post('/support/video', [ VideoBoardController::class, 'store' ])->name("video.store" )->middleware("auth.check");
Route::get('/support/video/{id}/edit', [ VideoBoardController::class, 'edit' ])->name("video.edit" )->middleware("auth.check");
Route::put('/support/video/{id}', [ VideoBoardController::class, 'update' ])->name("video.update" )->middleware("auth.check");
Route::delete('/support/video/{id}', [ VideoBoardController::class, 'destroy' ])->name("video.destroy" )->middleware("auth.check");
