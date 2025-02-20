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

    // 申請承認ページ
    // public function approve($id)
    // {
    //     // // IDに基づいて勤怠申請を取得
    //     // $attendance = Attendance::findOrFail($id);

    //     // // 申請情報をビューに渡す
    //     // return view('stamp_correction_request.approve.show', compact('attendance'));

    //     // 該当する勤怠申請を取得
    //     $attendance = Attendance::findOrFail($id);

    //     // 承認ステータスを「承認済み」に更新
    //     $attendance->approval_status = '承認済み';
    //     $attendance->save();

    //     // 承認後、リダイレクト
    //     return redirect()->route('stamp_correction_request.list')->with('status', '申請が承認されました');
    // }

    public function approve($id)
    {
        // 勤怠申請をIDで取得
        $attendance = Attendance::findOrFail($id);
    
        // 承認済みの場合も詳細を表示したい場合
        return view('stamp_correction_request.approve.show', compact('attendance'));
    }
    


public function approveSubmit(Request $request, $id)
{
    // 勤怠申請をIDで取得
    $attendance = Attendance::findOrFail($id);

    // 承認処理を行い、状態を「承認済み」に変更
    $attendance->approval_status = '承認済み';
    $attendance->save();

    // 承認後、一覧ページにリダイレクト
    return redirect()->route('stamp_correction_request.list')->with('status', '申請が承認されました');
}

    
}
