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

Route::get('register', function () {
    return view('auth.register');
});

Route::get('login', function () {
    return view('auth.login');
});

Route::get('attendance', function () {
    return view('attendance');
});

Route::get('attendance/list', function () {
    return view('attendance.list');
});

Route::get('attendance/{id}', function ($id) {
    // ユーザーID（$id）に基づいて勤怠情報を取得する処理（例えば、DBから取得）
    // 例: $attendance = Attendance::find($id);

    return view('attendance.show', compact('id'));  // ビューにIDを渡す
});

Route::get('stamp_correction_request/list', function () {
    // 申請一覧データを取得する処理（例: データベースから申請情報を取得）
    // 例: $requests = StampCorrectionRequest::all();

    return view('stamp_correction_request.list');  // ビューを表示
});

Route::get('admin/login', function () {
    return view('auth.admin.login');
});