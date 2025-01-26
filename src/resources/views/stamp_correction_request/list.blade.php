@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/user/request-list.css') }}">
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
      <h2 class="pageTitle">申請一覧</h2>
      <ul class="requestList__tab">
        <li class="requestList__tabList">承認待ち</li>
        <li class="requestList__tabList">承認済み</li>
      </ul>
      <table class="requestList__table">
        <tr class="requestList__line">
          <th class="requestList__title">状態</th>
          <th class="requestList__title">名前</th>
          <th class="requestList__title">対象日時</th>
          <th class="requestList__title">申請理由</th>
          <th class="requestList__title">申請日時</th>
          <th class="requestList__title">詳細</th>
        </tr>
        <tr class="requestList__line">
          <td class="requestList__detail">承認待ち</td>
          <td class="requestList__detail">西伶奈</td>
          <td class="requestList__detail">2023/06/01</td>
          <td class="requestList__detail">遅延のため</td>
          <td class="requestList__detail">2023/06/02</td>
          <td class="requestList__detail">詳細</td>
        </tr>
        <tr class="requestList__line">
          <td class="requestList__detail">承認待ち</td>
          <td class="requestList__detail">西伶奈</td>
          <td class="requestList__detail">2023/06/01</td>
          <td class="requestList__detail">遅延のため</td>
          <td class="requestList__detail">2023/06/02</td>
          <td class="requestList__detail">詳細</td>
        </tr>
      </table>
    </div>
  </section>
@endsection