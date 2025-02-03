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
      <form class="attendanceShow__details">
        @csrf
        @method('PUT') <!-- 更新の場合はPUTメソッドを明示 -->
        <div class="attendanceShow__formContents">
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">名前</label>
            <p class="attendanceShow__content attendanceShow__name">西　伶奈</p>
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">日付</label>
            <div class="attendanceShow__content">
              <input type="text" class="attendanceShow__contentInput attendanceShow__date" value="2023年">
              <input type="text" class="attendanceShow__contentInput" value="6月1日">
            </div>
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">出勤・退勤</label>
            <div class="attendanceShow__content">
              <input type="text" class="attendanceShow__contentInput" value="09:00">
              <span class="attendanceShow__contentCenter">〜</span>
              <input type="text" class="attendanceShow__contentInput" value="18:00">
            </div>
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">休憩</label>
            <div class="attendanceShow__content">
              <input type="text" class="attendanceShow__contentInput" value="12:00">
              <span class="attendanceShow__contentCenter">〜</span>
              <input type="text" class="attendanceShow__contentInput" value="13:00">
            </div>
          </div>
          <div class="attendanceShow__line">
            <label class="attendanceShow__title">備考</label>
            <textarea class="attendanceShow__content attendanceShow__contentTextarea">電車遅延のため</textarea>
          </div>
        </div>
        <input class="blackBtn attendanceShow__btn" type="submit" value="承認" />
      </form>
    </div>
  </section>
@endsection