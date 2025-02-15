<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StampCorrectionRequestController extends Controller
{
    public function index()
    {
        // 管理者なら全ての申請を取得
        if (Auth::guard('admin')->check()) {
            $attendances = Attendance::where('approval_status', '承認待ち')->orWhere('approval_status', '承認済み')->get(); // 承認待ち、承認済み両方を取得
        } else {
            // 一般ユーザーなら自分の申請のみを取得
            $attendances = Attendance::where('user_id', Auth::id())
                                      ->whereIn('approval_status', ['承認待ち', '承認済み'])
                                      ->get();  // 自分の承認待ち・承認済みデータを取得
        }

        // ビューにデータを渡す
        return view('stamp_correction_request.list', compact('attendances'));
    }
}
