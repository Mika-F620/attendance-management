@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">
@endsection
@section('content')
  <section class="login wrapper">
    <h2 class="sectionTitle">管理者ログイン</h2>
    <form class="login__form" method="POST" action="{{ url('/admin/login') }}">
      @csrf
      <div class="login__formContents">
        <div class="login__item">
          <label class="login__label" for="login_identifier">メールアドレス</label>
          <input class="login__input" type="text" name="email" id="email" value="{{ old('email') }}">
          @error('email')
            <p class="form__error">{{ $message }}</p>
          @enderror
        </div>
        <div class="login__item">
          <label class="login__label" for="pass">パスワード</label>
          <input class="login__input" type="password" name="password" id="pass">
          @error('password')
            <p class="form__error">{{ $message }}</p>
          @enderror
        </div>
        @if (session('errors') && session('errors')->has('login_error'))
          <div class="form__error">
            {{ session('errors')->first('login_error') }}
          </div>
        @endif
      </div>
      <div class="login__btn">
        <input class="blackBtn login__btnBlack" type="submit" value="管理者ログインする" />
      </div>
    </form>
  </section>
@endsection