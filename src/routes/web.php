<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Facades\Fortify;
use App\Http\Controllers\AuthController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\AdminAttendanceController;

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

// Route::get('register', function () {
//     return view('auth.register');
// });

// Route::middleware(['auth', 'verified'])->get('/attendance', function () {
//     return view('attendance');
// })->name('attendance');

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

// Route::get('attendance/{id}', function ($id) {
//     // ユーザーID（$id）に基づいて勤怠情報を取得する処理（例えば、DBから取得）
//     // 例: $attendance = Attendance::find($id);

//     return view('attendance.show', compact('id'));  // ビューにIDを渡す
// });

Route::get('stamp_correction_request/list', function () {
    // 申請一覧データを取得する処理（例: データベースから申請情報を取得）
    // 例: $requests = StampCorrectionRequest::all();

    return view('stamp_correction_request.list');  // ビューを表示
});

// Route::get('admin/login', function () {
//     return view('auth.admin.login');
// });

// Route::get('admin/attendance/list', function () {
//     // 申請一覧データを取得する処理（例: データベースから申請情報を取得）
//     // 例: $requests = StampCorrectionRequest::all();

//     return view('admin.attendance.list');  // ビューを表示
// });

Route::get('admin/staff/list', function () {
    // 申請一覧データを取得する処理（例: データベースから申請情報を取得）
    // 例: $requests = StampCorrectionRequest::all();

    return view('admin.staff.list');  // ビューを表示
});

Route::get('admin/attendance/staff/{id}', function ($id) {
    // ユーザーID（$id）に基づいてスタッフの勤怠情報を取得する処理
    // 例: $attendance = Attendance::where('user_id', $id)->get();

    return view('admin.attendance.staff.show', compact('id'));  // ビューにIDを渡す
});

Route::get('stamp_correction_request/approve/{attendance_correct_request}', function ($attendance_correct_request) {
    // このIDを使って、関連するデータ（例：申請情報など）を取得
    // 例: $request = AttendanceCorrectRequest::findOrFail($attendance_correct_request);

    return view('stamp_correction_request.approve.show', compact('attendance_correct_request'));  // ビューにIDを渡す
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
Route::middleware('auth')->prefix('admin')->group(function() {
    Route::get('attendance/list', [AdminAttendanceController::class, 'index'])->name('admin.attendance.list');
});

Route::prefix('admin')->name('admin.')->group(function() {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

