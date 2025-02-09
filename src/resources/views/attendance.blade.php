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
        <p class="checkIn__label">{{ $status === '勤務外' ? '勤務外' : ($status === '出勤中' ? '出勤中' : $status) }}</p>
        <p class="checkIn__date">{{ $today->format('Y年m月d日') }} ({{ $weekday }})</p>
        <p class="checkIn__time">{{ $today->format('H:i') }}</p>
      </div>
      <!-- 出勤フォーム -->
      <form action="{{ route('start-work') }}" method="POST" id="attendance-form" style="display: {{ $status === '勤務外' ? 'block' : 'none' }}">
        @csrf
        <button class="blackBtn checkIn__btn" type="submit" id="attendance-btn">
          出勤
        </button>
      </form>

      <div class="checkIn__work" style="display: {{ $status === '出勤中' && $status !== '休憩中' ? 'flex' : 'none' }}">
        <!-- 退勤フォーム（休憩中のみ表示しない） -->
        <form action="{{ route('end-work') }}" method="POST" id="end-form" style="display: {{ $status === '出勤中' && $status !== '休憩中' ? 'inline-block' : 'none' }}">
          @csrf
          <button class="blackBtn checkIn__btn" type="submit" id="end-btn">
            退勤
          </button>
        </form>

        <!-- 休憩入フォーム -->
        <form action="{{ route('start-rest') }}" method="POST" id="rest-form" style="display: {{ $status === '出勤中' ? 'inline-block' : 'none' }}">
          @csrf
          <button class="whiteBtn checkIn__btn" type="submit" id="rest-btn">
            休憩入
          </button>
        </form>
      </div>

      <!-- 休憩戻フォーム（休憩中のみ表示） -->
      <form action="{{ route('end-rest') }}" method="POST" id="end-rest-form" style="display: {{ $status === '休憩中' ? 'inline-block' : 'none' }}">
        @csrf
        <button class="blackBtn checkIn__btn" type="submit" id="end-rest-btn">
          休憩戻
        </button>
      </form>

      <!-- 退勤後のメッセージ -->
      @if ($status === '退勤済')
        <p class="checkIn__thanks">お疲れ様でした。</p>
      @endif
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // サーバーからの返答に基づいてボタンの表示を更新
      @if ($status === '出勤中')
        document.getElementById('attendance-btn').style.display = 'none';
        document.getElementById('rest-btn').style.display = 'inline-block';
        document.getElementById('end-btn').style.display = 'inline-block';
      @elseif ($status === '休憩中')
        document.getElementById('rest-btn').style.display = 'none';
        document.getElementById('end-rest-btn').style.display = 'inline-block';
      @elseif ($status === '退勤済')
        document.getElementById('end-btn').style.display = 'none';
      @endif
    });
  </script>
@endsection

