<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    public function store(Request $request){
        try {

            $this->validate($request,[
                'fullName' => ['required', 'string', 'max:191'],
                'userName' => ['required', 'string', 'max:191', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            ]);
    
            $user = new User;
            $user->fullName = $request->json('fullName');
            $user->userName = $request->json('userName');
            $user->email = $request->json('email');
            $user->password = Hash::make($request->json('password'));
            $user->active = 0;
            $user->save();
            
           return response()->json(ApiMessage::successMessage('Verify you email!', 201));

        } catch(\Exception $e){

            if(config('app.debug')){
                return response()->json(ApiMessage::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiMessage::errorMessage('Error to create user!', 1010));

        }
    }
}
