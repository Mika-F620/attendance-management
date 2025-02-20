@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/user/attendance-show.css') }}">
@endsection
@section('menu')
  <nav class="header__nav">
    <ul class="header__menu">
    @if (Auth::guard('admin')->check()) <!-- 管理者ログインの判定 -->
    <!-- 管理者用のメニュー -->
    <li class="header__list"><a class="header__link" href="{{ route('admin.attendance.list') }}">勤怠一覧</a></li>
    <li class="header__list"><a class="header__link" href="{{ route('admin.staff.list') }}">スタッフ一覧</a></li>
    <li class="header__list"><a class="header__link" href="{{ route('admin.stamp_correction_request.list') }}">申請一覧</a></li>
  @elseif (Auth::check()) <!-- 一般ユーザーログインの判定 -->
    <!-- 一般ユーザー用のメニュー -->
    <li class="header__list"><a class="header__link" href="{{ route('attendance') }}">勤怠</a></li>
    <li class="header__list"><a class="header__link" href="{{ route('attendance.list') }}">勤怠一覧</a></li>
    <li class="header__list"><a class="header__link" href="{{ route('stamp_correction_request.list') }}">申請</a></li>
  @endif
  <!-- ログインしている場合はログアウトボタンを表示 -->
  @if (Auth::guard('admin')->check() || Auth::check())
    <li class="header__list">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="header__link">ログアウト</button>
      </form>
    </li>
  @else
    <!-- ログインしていない場合はログインボタンを表示 -->
    <li class="header__list"><a class="header__link" href="{{ route('login') }}">ログイン</a></li>
  @endif
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
        <!-- 修正ボタンの表示条件 -->
        @if (Auth::guard('admin')->check())
          <!-- 管理者の場合、承認待ちでも承認済みでも修正可能 -->
          <input class="blackBtn attendanceShow__btn" type="submit" value="修正" />
        @elseif (Auth::check() && $attendance->approval_status == '承認済み')
          <!-- 一般ユーザーで承認済みの場合のみ修正可能 -->
          <input class="blackBtn attendanceShow__btn" type="submit" value="修正" />
        @else
          <!-- 承認待ちの場合は修正不可 -->
          <p class="attendanceShow__error" style="color: red;">*承認待ちのため修正はできません。</p>
        @endif
      </form>
    </div>
  </section>

@endsection