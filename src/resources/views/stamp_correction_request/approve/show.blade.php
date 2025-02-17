@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin/approve-show.css') }}">
@endsection
@section('menu')
  <nav class="header__nav">
    <ul class="header__menu">
      <li class="header__list"><a class="header__link" href="#">勤怠</a></li>
      <li class="header__list"><a class="header__link" href="#">勤怠一覧</a></li>
      <li class="header__list"><a class="header__link" href="#">申請</a></li>
      <li class="header__list"><a class="header__link" href="#">ログアウト</a></li>
    </ul>
  </nav>
@endsection
@section('content')
  <section class="attendanceShow grayBg">
    <div class="attendanceShow__contents wrapper">
      <h2 class="pageTitle">勤怠詳細</h2>
      <form class="attendanceShow__details" method="POST" action="{{ route('stamp_correction_request.approve', $attendance->id) }}">
        @csrf
        @method('PATCH') <!-- PATCHメソッドを指定 -->
        <div class="attendanceShow__formContents">
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">名前</label>
            <p class="attendanceShow__content attendanceShow__name">{{ $attendance->user->name }}</p>
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">日付</label>
            <div class="attendanceShow__content">
              <p class="attendanceShow__contentInput attendanceShow__date">{{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}</p>
              <p class="attendanceShow__contentInput">{{ \Carbon\Carbon::parse($attendance->date)->format('m月d日') }}</p>
            </div>
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">出勤・退勤</label>
            <div class="attendanceShow__content">
              <p class="attendanceShow__contentInput">{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}</p>
              <span class="attendanceShow__contentCenter">〜</span>
              <p class="attendanceShow__contentInput">{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}</p>
            </div>
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">休憩</label>
            <div class="attendanceShow__content">
              <p class="attendanceShow__contentInput">{{ \Carbon\Carbon::parse($attendance->break_start_time)->format('H:i') }}</p>
              <span class="attendanceShow__contentCenter">〜</span>
              <p class="attendanceShow__contentInput">{{ \Carbon\Carbon::parse($attendance->break_end_time)->format('H:i') }}</p>
            </div>
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">備考</label>
            <p class="attendanceShow__content attendanceShow__contentTextarea">{{ $attendance->remarks }}</p>
          </div>
        </div>
        <!-- 承認ボタンの表示を変更 -->
        @if ($attendance->approval_status == '承認済み')
          <input class="blackBtn attendanceShow__btn disabledBtn" type="button" value="承認済み" disabled />
        @else
          <input class="blackBtn attendanceShow__btn" type="submit" value="承認" />
        @endif
      </form>
    </div>
  </section>
@endsection