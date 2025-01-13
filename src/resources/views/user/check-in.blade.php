@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/user/check-in.css') }}">
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
  <div class="wrapper checkIn">
    <div class="checkIn__contents">
      <p class="checkIn__label">勤務外</p>
      <p class="checkIn__date">2023年6月1日(木)</p>
      <p class="checkIn__time">08:00</p>
    </div>
    <button class="blackBtn checkIn__btn">出勤</button>
  </div>
@endsection