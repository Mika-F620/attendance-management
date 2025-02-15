<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StampCorrectionRequestController extends Controller
{
    // 申請一覧ページ
    public function index()
    {
        // ログインユーザーの承認待ちと承認済みの勤怠を取得
        $attendances = Attendance::where('user_id', Auth::id())
                                ->whereIn('approval_status', ['承認待ち', '承認済み'])
                                ->get();

        // ビューにデータを渡して表示
        return view('stamp_correction_request.list', compact('attendances'));
    }
}
