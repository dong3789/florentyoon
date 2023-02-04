<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::group(['prefix' => 'api'], function(){
    Route::group(['middleware' => 'login'], function(){

        Route::get('/users', [\App\Http\Controllers\CatUsersController::class, 'getUsersData']); //# 사용자 정보

        //# 질문
        Route::group(['prefix' => 'board'], function() {
            Route::post('/',        [\App\Http\Controllers\CatBoardController::class, 'setBoardCreate']); //# 질문 등록하기
            Route::delete('/{id}',  [\App\Http\Controllers\CatBoardController::class, 'removeBoardData']); //# 질문 삭제하기
            Route::put('/{id}',     [\App\Http\Controllers\CatBoardController::class, 'updateBoardData']); //# 질문 수정하기

            Route::post('/{boardId}/reply', [\App\Http\Controllers\CatBoardReplyController::class, 'createBoardReplyData']); //# 답변 작성하기
        });

        //# 답글
        Route::group(['prefix' => 'reply'], function() {
            Route::post('/{id}',    [\App\Http\Controllers\CatBoardReplyController::class, 'setChooseReplyData']); //# 답변 채택
            Route::put('/{id}',     [\App\Http\Controllers\CatBoardReplyController::class, 'updateBoardReplyData']); //# 답변 수정하기
            Route::delete('/{id}',  [\App\Http\Controllers\CatBoardReplyController::class, 'removeBoardReplyData']); //# 답변 삭제하기
        });
    });

    Route::get('/board', [\App\Http\Controllers\CatBoardController::class, 'getBoardList']);
    Route::get('/board/{id}', [\App\Http\Controllers\CatBoardController::class, 'getBoardDetail']);
});



