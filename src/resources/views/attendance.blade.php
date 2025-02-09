@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/user/attendance.css') }}">
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
  <div class="grayBg">
    <div class="wrapper checkIn">
      <div class="checkIn__contents">
        <p class="checkIn__label">勤務外</p>
        <p class="checkIn__date">{{ $today->format('Y年m月d日') }} ({{ $weekday }})</p>
        <p class="checkIn__time">{{ $today->format('H:i') }}</p>
      </div>
      <button class="blackBtn checkIn__btn">出勤</button>
    </div>
  </div>
@endsection