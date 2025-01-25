@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/user/attendance-list.css') }}">
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
  <section class="grayBg">
    <div class="attendanceList wrapper">
      <h2 class="pageTitle">勤怠一覧</h2>
      <div class="attendanceList__pagination">
        <p class="attendanceList__paginationLeft">
          <img class="" src="{{ asset('img/left-arrow.svg') }}" alt="左矢印">
          前月
        </p>
        <p class="attendanceList__paginationCenter">
          <img class="" src="{{ asset('img/calendar.svg') }}" alt="カレンダー">
          2023/06
        </p>
        <p class="attendanceList__paginationRight">
          翌月
          <img class="" src="{{ asset('img/right-arrow.svg') }}" alt="右矢印">
        </p>
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
        <tr class="attendanceList__line">
          <td class="attendanceList__detail">06/01(木)</td>
          <td class="attendanceList__detail">09:00</td>
          <td class="attendanceList__detail">18:00</td>
          <td class="attendanceList__detail">1:00</td>
          <td class="attendanceList__detail">8:00</td>
          <td class="attendanceList__detail">詳細</td>
        </tr>
        <tr class="attendanceList__line">
          <td class="attendanceList__detail">06/01(木)</td>
          <td class="attendanceList__detail">09:00</td>
          <td class="attendanceList__detail">18:00</td>
          <td class="attendanceList__detail">1:00</td>
          <td class="attendanceList__detail">8:00</td>
          <td class="attendanceList__detail">詳細</td>
        </tr>
      </table>
    </div>
  </section>
@endsection