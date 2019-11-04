<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\verifyUser;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\API\ApiMessage;
use Illuminate\Support\Str;
use Mail;
use App\Mail\VerifyMail;

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
            $user->fullName = $request['fullName'];
            $user->userName = $request['userName'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->save();

            $verifyUser = VerifyUser::create([
                'user_id' => $user->id,
                'token' => Str::random(40),//Test
                //'token' => Hash::make(Str::random(40)) --
            ]);
    
            Mail::to($user->email)->send(new VerifyMail($user));
            
           return response()->json(ApiMessage::successMessage('Verify your email!', 201));

        } catch(\Exception $e){

            if(config('app.debug')){
                return response()->json(ApiMessage::errorMessage($e->getMessage(), 1010));
            }
            return response()->json(ApiMessage::errorMessage('Error to create user!', 1010));

        }
    }

    // clear after
    public function getToken(){
        $token = VerifyUser::all();

        return response()->json($token);
    }

    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if(isset($verifyUser)){
            $user = $verifyUser->user;
            if(!$user->verified) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                $status = "Email verified. You can now login.";
            }else{
                $status = "Your e-mail is already verified. You can now login.";
            }
        }else{
            return response()->json(ApiMessage::errorMessage('Sorry your email cannot be identified.', 1014));
        }

        return response()->json(ApiMessage::successMessage($status, 1015));

    }

    protected function registered(Request $request, $user){

        $this->guard()->logout();
        return response()->json(ApiMessage::successMessage('We sent you an activation code. Check your email and click on the link to verify.', 1015));
        
    }
}
