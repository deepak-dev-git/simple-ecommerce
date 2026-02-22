<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';
    protected $recaptcha;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RecaptchaService $recaptcha)
    {
        $this->middleware('guest');
        $this->recaptcha = $recaptcha;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required'],
            'building_no' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ]);

        $validator->after(function ($validator) use ($data) {

            if (!$this->recaptcha->verify($data['g-recaptcha-response'] ?? null)) {
                $validator->errors()->add('captcha', 'Captcha verification failed.');
            }
        });

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Address::create([
            'user_id'    => $user->id,
            'building_no' => $data['building_no'],
            'street'     => $data['street'],
            'city'       => $data['city'],
            'district'   => $data['district'],
            'state'      => $data['state'],
            'postal_code' => $data['postal_code'],
            'country'    => 'India',
            'is_default' => true,
        ]);

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard.index');
        }

        return redirect()->intended('/');
    }
}
