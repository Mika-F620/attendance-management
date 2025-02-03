@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin/staff-list.css') }}">
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
  <section class="grayBg requestList">
    <div class="wrapper requestList__contents">
      <h2 class="pageTitle">スタッフ一覧</h2>
      <table class="requestList__table">
        <tr class="requestList__line">
          <th class="requestList__title">名前</th>
          <th class="requestList__title">メールアドレス</th>
          <th class="requestList__title">月次勤怠</th>
        </tr>
        <tr class="requestList__line">
          <td class="requestList__detail">西 伶奈</td>
          <td class="requestList__detail">reina.n@coachtech.com</td>
          <td class="requestList__detail">詳細</td>
        </tr>
        <tr class="requestList__line">
          <td class="requestList__detail">山田 太郎</td>
          <td class="requestList__detail">taro.y@coachtech.com</td>
          <td class="requestList__detail">詳細</td>
        </tr>
      </table>
    </div>
  </section>
@endsection