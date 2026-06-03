<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Lib\SocialLogin;

class SocialiteController extends Controller
{
    public function socialLogin(string $provider)
    {
        $socialLogin = new SocialLogin('user', $provider);

        return $socialLogin->redirectDriver();
    }

    public function callback($provider)
    {
        $socialLogin = new SocialLogin($provider);

        try {
            $socialLogin->login();
        } catch (\Exception $exp) {
            $notify[] = ['error', $exp->getMessage()];
            return to_route('home')->withNotify($notify);
        }
    }
}
