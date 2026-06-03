<?php

namespace App\Http\Controllers\User\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\Intended;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function showRegistrationForm()
    {
        $pageTitle = 'Register';
        Intended::identifyRoute();

        return view('Template::user.auth.register', compact('pageTitle'));
    }

    protected function validator(array $data)
    {
        $passwordValidation = gs('secure_password') ? Password::min(6)->mixedCase()->numbers()->symbols()->uncompromised() : Password::min(6);
        $agree              = gs('agree') ? 'required' : 'nullable';

        $validate = Validator::make($data, [
            'firstname'     => 'required',
            'lastname'      => 'required',
            'email'         => 'required|email|unique:users',
            'password'      => ['required', 'confirmed', $passwordValidation],
            'captcha'       => 'sometimes|required',
            'agree'         => $agree
        ], [
            'firstname.required' => 'The firstname field is required',
            'lastname.required'  => 'The lastname fiels is requires'
        ]);

        return $validate;
    }

    public function register(Request $request)
    {
        if (!gs('registration')) {
            $notify[] = ['error', 'Registration not allowed'];
            return back()->withNotify($notify);
        }

        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }

    protected function create(array $data)
    {
        $user                = new User();
        $user->email         = strtolower($data['email']);
        $user->firstname     = $data['firstname'];
        $user->lastname      = $data['lastname'];
        $user->password      = Hash::make($data['password']);
        $user->kv            = gs('kv') ? Status::NO : Status::YES;
        $user->ev            = gs('ev') ? Status::NO : Status::YES;
        $user->sv            = gs('sv') ? Status::NO : Status::YES;
        $user->ts            = Status::DISABLE;
        $user->tv            = Status::ENABLE;
        $user->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New rider registered';
        $adminNotification->click_url = urlPath('admin.rider.detail', $user->id);
        $adminNotification->save();

        $ip        = getRealIP();
        $exist     = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', $info['long']);
            $userLogin->latitude     = @implode(',', $info['lat']);
            $userLogin->city         = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country      = @implode(',', $info['country']);
        }

        $userAgent          = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();

        return $user;
    }

    public function checkUser(Request $request)
    {
        $exist['data'] = false;
        $exist['type'] = null;

        if ($request->email) {
            $exist['data']  = User::where('email', $request->email)->exists();
            $exist['type']  = 'email';
            $exist['field'] = 'Email';
        }

        if ($request->mobile) {
            $exist['data']  = User::where('mobile', $request->mobile)->where('dial_code', $request->mobile_code)->exists();
            $exist['type']  = 'mobile';
            $exist['field'] = 'Mobile';
        }

        if ($request->username) {
            $exist['data']  = User::where('username', $request->username)->exists();
            $exist['type']  = 'username';
            $exist['field'] = 'Username';
        }

        return response($exist);
    }

    public function registered()
    {
        return to_route('user.home');
    }
}
