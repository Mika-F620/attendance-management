<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Facades\Fortify;
use App\Http\Controllers\AuthController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\StampCorrectionRequestController;

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

// メール認証用
Route::get('email/verify', function () {
    return view('auth.verify'); // メール認証画面
})->name('verification.notice');

Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

// 認証後、アクセスするページ
Route::get('home', function () {
    return redirect()->route('attendance'); // 直接attendanceページにリダイレクト
})->middleware('verified')->name('home');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// ログインフォームの表示
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware(['guest'])
    ->name('login');

// ログイン処理
Route::post('/login', [AuthController::class, 'login'])->name('login');

// ログアウト処理
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware(['auth'])
    ->name('logout');

Route::middleware(['auth', 'verified'])->get('/attendance', function () {
    return view('attendance');
})->name('attendance');


Route::get('attendance/list', function () {
    return view('attendance.list');
});

Route::get('stamp_correction_request/list', function () {
    // 申請一覧データを取得する処理
    return view('stamp_correction_request.list');  // ビューを表示
});

Route::get('admin/staff/list', function () {
    // 申請一覧データを取得する処理
    return view('admin.staff.list');  // ビューを表示
});

Route::get('admin/attendance/staff/{id}', function ($id) {
    // ユーザーID（$id）に基づいてスタッフの勤怠情報を取得する処理
    return view('admin.attendance.staff.show', compact('id'));  // ビューにIDを渡す
});

// 出勤画面の表示
Route::middleware(['auth', 'verified'])->get('/attendance', [AttendanceController::class, 'show'])->name('attendance');

Route::post('/start-work', [AttendanceController::class, 'startWork'])->name('start-work');
Route::post('/start-rest', [AttendanceController::class, 'startRest'])->name('start-rest');
Route::post('/end-work', [AttendanceController::class, 'endWork'])->name('end-work');
// 休憩戻処理
Route::post('/end-rest', [AttendanceController::class, 'endRest'])->name('end-rest');

Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');

// 詳細ページ用ルートを追加
Route::get('/attendance/{id}', [AttendanceController::class, 'showDetail'])->name('attendance.show');
Route::put('attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');

// 管理者ログインルート
Route::get('admin/login', [AdminAuthController::class, 'loginForm'])->name('admin.login');
Route::post('admin/login', [AdminAuthController::class, 'login']);

// 管理者用の勤怠一覧
Route::middleware('auth:admin')->prefix('admin')->group(function() {
    Route::get('attendance/list', [AdminAttendanceController::class, 'index'])->name('admin.attendance.list');
});

// ログアウトルート
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('staff/list', [StaffController::class, 'index'])->name('staff.list');
});

Route::middleware('auth:admin')->prefix('admin')->group(function () {
    // スタッフ別勤怠詳細表示ページ
    Route::get('attendance/staff/{id}', [AdminAttendanceController::class, 'staffAttendance'])->name('admin.attendance.staff');
});

// 一般ユーザー用の申請一覧
Route::middleware(['auth'])->get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])->name('stamp_correction_request.list');

// 管理者用の申請一覧（管理者専用）
Route::middleware('auth:admin')->group(function() {
    Route::get('admin/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])->name('admin.stamp_correction_request.list');
});

// 勤怠詳細ページ用ルート
Route::get('attendance/{id}', [AttendanceController::class, 'showDetail'])->name('attendance.show');

// 管理者用の勤怠詳細ページルート
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('attendance/{id}', [AttendanceController::class, 'showDetail'])->name('admin.attendance.show');
});

// 一般ユーザー用の勤怠詳細ページルート
Route::middleware('auth')->get('/attendance/{id}', [AttendanceController::class, 'showDetail'])->name('attendance.show');

// 管理者用の承認申請ページ
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('stamp_correction_request/approve/{id}', [StampCorrectionRequestController::class, 'approve'])
        ->name('admin.stamp_correction_request.approve');
});

// 承認の処理を行うためのPATCHメソッド
Route::patch('/admin/stamp_correction_request/approve/{id}', [StampCorrectionRequestController::class, 'approveSubmit'])
    ->name('stamp_correction_request.approve.submit');

// スタッフの勤怠情報CSV出力
Route::get('admin/staff/{staffId}/export-csv', [StaffController::class, 'exportCsv'])->name('admin.staff.exportCsv');