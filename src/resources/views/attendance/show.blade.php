@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/user/attendance-show.css') }}">
@endsection
@section('menu')
  <nav class="header__nav">
    <ul class="header__menu">
      <li class="header__list"><a class="header__link" href="#">勤怠</a></li>
      <li class="header__list"><a class="header__link" href="#">勤怠一覧</a></li>
      <li class="header__list"><a class="header__link" href="#">申請</a></li>
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
  <section class="attendanceShow grayBg">
    <div class="attendanceShow__contents wrapper">
      <h2 class="pageTitle">勤怠詳細</h2>
      <form class="attendanceShow__details" action="{{ route('attendance.update', $attendance->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- 更新の場合はPUTメソッドを明示 -->
        <div class="attendanceShow__formContents">
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">名前</label>
            <p class="attendanceShow__content attendanceShow__name">{{ $attendance->user->name }}</p>
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">日付</label>
            <div class="attendanceShow__content">
              <input type="text" class="attendanceShow__contentInput attendanceShow__date" value="{{ \Carbon\Carbon::parse($attendance->date)->format('Y年') }}" name="date_year">
              <input type="text" class="attendanceShow__contentInput" value="{{ \Carbon\Carbon::parse($attendance->date)->format('m月d日') }}" name="date_day">
            </div>
            @error('date_year')
              <p class="form__error">{{ $message }}</p>
            @enderror
            @error('date_day')
              <p class="form__error">{{ $message }}</p>
            @enderror
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">出勤・退勤</label>
            <div class="attendanceShow__content">
              <input type="text" class="attendanceShow__contentInput" value="{{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }}" name="start_time">
              <span class="attendanceShow__contentCenter">〜</span>
              <input type="text" class="attendanceShow__contentInput" value="{{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}" name="end_time">
            </div>
            @error('start_time')
              <p class="form__error">{{ $message }}</p>
            @enderror
            @error('end_time')
              <p class="form__error">{{ $message }}</p>
            @enderror
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">休憩</label>
            <div class="attendanceShow__content">
              <input type="text" class="attendanceShow__contentInput" value="{{ \Carbon\Carbon::parse($attendance->break_start_time)->format('H:i') }}" name="break_start_time">
              <span class="attendanceShow__contentCenter">〜</span>
              <input type="text" class="attendanceShow__contentInput" value="{{ \Carbon\Carbon::parse($attendance->break_end_time)->format('H:i') }}" name="break_end_time">
            </div>
            @error('break_start_time')
              <p class="form__error">{{ $message }}</p>
            @enderror
            @error('break_end_time')
              <p class="form__error">{{ $message }}</p>
            @enderror
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">備考</label>
            <textarea class="attendanceShow__content attendanceShow__contentTextarea" name="remarks">{{ $attendance->remarks }}</textarea>
            @error('remarks')
              <p class="form__error">{{ $message }}</p>
            @enderror
          </div>
        </div>
        @if ($attendance->approval_status == '承認待ち')
          <!-- 承認待ちの場合 -->
          <p class="attendanceShow__error" style="color: red;">*承認待ちのため修正はできません。</p>
        @else
          <!-- 承認済みの場合 -->
          <input class="blackBtn attendanceShow__btn" type="submit" value="修正" />
        @endif
      </form>
    </div>
  </section>

@endsection