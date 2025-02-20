@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/user/attendance-list.css') }}">
@endsection
@section('menu')
  <nav class="header__nav">
    <ul class="header__menu">
      <li class="header__list"><a class="header__link" href="{{ route('attendance') }}">勤怠</a></li>
      <li class="header__list"><a class="header__link" href="{{ route('attendance.list') }}">勤怠一覧</a></li>
      <li class="header__list"><a class="header__link" href="{{ route('stamp_correction_request.list') }}">申請</a></li>
      <li class="header__list">
        @if (Auth::check())
          <form class="" action="/logout" method="post">
            @csrf
            <button class="header__link">ログアウト</button>
          </form>
        @else
          <!-- ログインしていない場合、ログインボタンを表示 -->
          <a class="header__link" href="{{ route('login') }}">ログイン</a>
        @endif
      </li>
    </ul>
  </nav>
@endsection
@section('content')
  <section class="grayBg">
    <div class="attendanceList wrapper">
      <h2 class="pageTitle">勤怠一覧</h2>
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
          <td class="attendanceList__detail">{{ \Carbon\Carbon::parse($attendance->date)->locale('ja')->isoFormat('MM/DD (ddd)') }}</td>
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
          <td class="attendanceList__detail">
            <a href="{{ route('attendance.show', $attendance->id) }}">詳細</a>
          </td>
        </tr>
        @endforeach
      </table>
      <!-- ページネーション -->
      {{ $attendances->links() }}
    </div>
  </section>
@endsection