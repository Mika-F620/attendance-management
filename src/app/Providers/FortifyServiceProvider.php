<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Redirect;
use Laravel\Fortify\Fortify;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ユーザー作成処理のカスタムクラスを指定
        Fortify::createUsersUsing(CreateNewUser::class);
        // ユーザー登録後のリダイレクト先を設定
        Fortify::loginView(fn() => view('auth.login')); // ログイン画面ビュー

        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    
        Fortify::authenticateUsing(function (Request $request) {
            $credentials = $request->only(['login_identifier', 'password']);
            
            // メールアドレスかユーザー名で判定
            $loginField = filter_var($credentials['login_identifier'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        
            // ユーザーを取得
            $user = User::where($loginField, $credentials['login_identifier'])->first();
        
            // パスワードチェック
            if ($user && Hash::check($credentials['password'], $user->password)) {
                return $user;
            }
        
            return null; // 認証失敗
        });
    }
}
