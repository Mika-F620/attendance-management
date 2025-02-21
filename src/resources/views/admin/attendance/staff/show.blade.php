@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin/staff-show.css') }}">
@endsection
@section('menu')
  <nav class="header__nav">
    <ul class="header__menu">
      <li class="header__list"><a class="header__link" href="{{ route('admin.attendance.list') }}">勤怠一覧</a></li>
      <li class="header__list"><a class="header__link" href="{{ route('admin.staff.list') }}">スタッフ一覧</a></li>
      <li class="header__list"><a class="header__link" href="{{ route('admin.stamp_correction_request.list') }}">申請一覧</a></li>
      @if (Auth::check())  <!-- ユーザーか管理者がログインしているか確認 -->
        <li class="header__list">
          <form action="{{ route('admin.logout') }}" method="POST">
          @csrf
            <button class="header__link">ログアウト</button>
          </form>
        </li>
      @else
        <li class="header__list">
            <a class="header__link" href="{{ route('admin.login') }}">ログイン</a>
        </li>
      @endif
    </ul>
  </nav>
@endsection
@section('content')
  <section class="grayBg">
    <div class="attendanceList wrapper">
      <h2 class="pageTitle">{{ $staff->name }}さんの勤怠</h2>
      <!-- CSV出力ボタン -->
      <a class="attendanceList__csv" href="{{ route('admin.staff.exportCsv', ['staffId' => $staff->id]) }}?month={{ $month }}" class="btn btn-primary" style="margin-bottom: 15px;">CSV出力</a>
      <div class="attendanceList__pagination">
        <!-- 前月のリンク -->
        <form action="{{ route('attendance.list') }}" method="GET" style="display: inline;">
          <input type="hidden" name="month" value="{{ \Carbon\Carbon::parse($month)->subMonth()->format('Y-m') }}" />
          <button type="submit" name="previous" class="attendanceList__paginationLeft">
            <img src="{{ asset('img/left-arrow.svg') }}" alt="左矢印"> 前月
          </button>
        </form>
        <!-- 今月の年月の表示部分 -->
        <p class="attendanceList__paginationCenter">
          <img class="" src="{{ asset('img/calendar.svg') }}" alt="カレンダー">
          {{ \Carbon\Carbon::parse($month)->format('Y/m') }}
        </p>
        <!-- 翌月のリンク -->
        <form action="{{ route('attendance.list') }}" method="GET" style="display: inline;">
          <input type="hidden" name="month" value="{{ \Carbon\Carbon::parse($month)->addMonth()->format('Y-m') }}" />
          <button type="submit" name="next" class="attendanceList__paginationRight">
            翌月 <img class="" src="{{ asset('img/right-arrow.svg') }}" alt="右矢印">
          </button>
        </form>
      </div>
      <table class="attendanceList__table">
        <tr class="attendanceList__line">
          <th class="attendanceList__title">日付</th>
          <th class="attendanceList__title">出勤</th>
          <th class="attendanceList__title">退勤</th>
          <th class="attendanceList__title">休憩</th>
          <th class="attendanceList__title">合計</th>
          <th class="attendanceList__title">詳細</th>
        </tr>
        @foreach ($attendances as $attendance)
          <tr class="attendanceList__line">
            <td class="attendanceList__detail">{{ \Carbon\Carbon::parse($attendance->date)->format('m/d (D)') }}</td>
            <td class="attendanceList__detail">{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}</td>
            <td class="attendanceList__detail">{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}</td>
            <td class="attendanceList__detail">
              @if ($attendance->break_start_time && $attendance->break_end_time)
                {{ \Carbon\Carbon::parse($attendance->break_start_time)->diff(\Carbon\Carbon::parse($attendance->break_end_time))->format('%H:%I') }}
              @else
                -
              @endif
            </td>
            <td class="attendanceList__detail">
              @if ($attendance->start_time && $attendance->end_time)
                {{ \Carbon\Carbon::parse($attendance->start_time)->diff(\Carbon\Carbon::parse($attendance->end_time))->format('%H:%I') }}
              @else
                -
              @endif
            </td>
            <td class="attendanceList__detail"><a href="{{ route('attendance.show', $attendance->id) }}">詳細</a></td>
          </tr>
        @endforeach
      </table>

      <!-- ページネーション -->
      {{ $attendances->links() }}
    </div>
  </section>
@endsection