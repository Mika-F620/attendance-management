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
        @foreach ($staffs as $staff)
          <tr class="requestList__line">
            <td class="requestList__detail">{{ $staff->name }}</td>
            <td class="requestList__detail">{{ $staff->email }}</td>
            <td class="requestList__detail"><a href="{{ route('admin.attendance.staff', $staff->id) }}">詳細</a></td>
          </tr>
        @endforeach
      </table>
    </div>
  </section>
@endsection