@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/user/request-list.css') }}">
@endsection
@section('menu')
  <nav class="header__nav">
    <ul class="header__menu">
      <li class="header__list"><a class="header__link" href="{{ route('attendance') }}">勤怠</a></li>
      <li class="header__list"><a class="header__link" href="{{ route('attendance.list') }}">勤怠一覧</a></li>
      <li class="header__list"><a class="header__link" href="{{ route('stamp_correction_request.list') }}">申請</a></li>
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
  <section class="grayBg requestList">
    <div class="wrapper requestList__contents">
      <h2 class="pageTitle">申請一覧</h2>
      <!-- タブ部分 -->
      <ul class="requestList__tab">
        <li class="requestList__tabList active" id="tab-waiting">承認待ち</li>
        <li class="requestList__tabList" id="tab-approved">承認済み</li>
      </ul>
      <!-- 承認待ちのテーブル -->
      <div id="waiting-requests" class="requestList__table-container">
        <table class="requestList__table">
          <tr class="requestList__line">
            <th class="requestList__title">状態</th>
            <th class="requestList__title">名前</th>
            <th class="requestList__title">対象日時</th>
            <th class="requestList__title">申請理由</th>
            <th class="requestList__title">申請日時</th>
            <th class="requestList__title">詳細</th>
          </tr>
          @foreach ($attendances->where('approval_status', '承認待ち') as $attendance)
            <tr class="requestList__line">
              <td class="requestList__detail">{{ $attendance->approval_status }}</td>
              <td class="requestList__detail">{{ $attendance->user->name }}</td>
              <td class="requestList__detail">{{ \Carbon\Carbon::parse($attendance->date)->format('Y/m/d') }}</td>
              <td class="requestList__detail">{{ $attendance->remarks }}</td>
              <td class="requestList__detail">{{ $attendance->created_at ? $attendance->created_at->format('Y/m/d') : '-' }}</td>
              <td class="requestList__detail"><a href="{{ route('attendance.show', $attendance->id) }}">詳細</a></td>
            </tr>
          @endforeach
        </table>
      </div>

      <!-- 承認済みのテーブル -->
      <div id="approved-requests" class="requestList__table-container" style="display: none;">
        <table class="requestList__table">
          <tr class="requestList__line">
            <th class="requestList__title">状態</th>
            <th class="requestList__title">名前</th>
            <th class="requestList__title">対象日時</th>
            <th class="requestList__title">申請理由</th>
            <th class="requestList__title">申請日時</th>
            <th class="requestList__title">詳細</th>
          </tr>
          @foreach ($attendances->where('approval_status', '承認済み') as $attendance)
            <tr class="requestList__line">
              <td class="requestList__detail">{{ $attendance->approval_status }}</td>
              <td class="requestList__detail">{{ $attendance->user->name }}</td>
              <td class="requestList__detail">{{ \Carbon\Carbon::parse($attendance->date)->format('Y/m/d') }}</td>
              <td class="requestList__detail">{{ $attendance->remarks }}</td>
              <td class="requestList__detail">{{ $attendance->created_at ? $attendance->created_at->format('Y/m/d') : '-' }}</td>
              <td class="requestList__detail"><a href="{{ route('attendance.show', $attendance->id) }}">詳細</a></td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>
  </section>
  <script>
    // タブをクリックした時に切り替える処理
    document.getElementById('tab-waiting').addEventListener('click', function() {
      document.getElementById('waiting-requests').style.display = 'block';
      document.getElementById('approved-requests').style.display = 'none';
      document.getElementById('tab-waiting').classList.add('active');
      document.getElementById('tab-approved').classList.remove('active');
    });

    document.getElementById('tab-approved').addEventListener('click', function() {
      document.getElementById('approved-requests').style.display = 'block';
      document.getElementById('waiting-requests').style.display = 'none';
      document.getElementById('tab-approved').classList.add('active');
      document.getElementById('tab-waiting').classList.remove('active');
    });
  </script>
@endsection