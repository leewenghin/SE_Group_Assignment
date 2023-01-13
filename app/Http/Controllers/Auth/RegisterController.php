<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
    protected $redirectTo = '/administrator/users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'integer', 'exists:genders,id'],
            'department' => ['required', 'integer', 'exists:departments,id'],
            'file' => ['file', 'image', 'size:51200'],
            'role' => [
                'array',
                'max:3'
            ],
            'role.*' => [
                'int',
                'distinct',
                'exists:roles,id'
            ]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $file_path = null;
        $file_name = null;

        if ($data->hasFile('file') && $data->file('file')->isValid()) {
            $random_str = Str::random(32);
            $time_now = Carbon::now();
            $write_time = $time_now->format('YmdHis');
            // $write_time = str_replace("-", "", $time_now->toDateString()).str_replace(":", "", $time_now->toTimeString());
            $original_file_name = $data->file('file')->getClientOriginalName();
            $file_name = $random_str.'_'.$write_time.'_'.$original_file_name;
            $path = 'documents/avatar';
            $data->file('file')->move($path, $file_name);
            $file_path = '/'.$path.'/';
        }
        $create_user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender_id' => $data['gender'],
            'department_id' => $data['department'],
            'avatar_file_path' => $file_path,
            'avatar_file_name' => $file_name,
        ]);
        foreach ($data['role'] as $r) {
            if (!empty($r) && $r > 0) {
                $create_user->user_roles()->create([
                    'role_id' => $r
                ]);
            }
        }
        return $create_user;
    }
}
