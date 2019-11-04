<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\API\ApiMessage;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function index(){

        $data = ['data' => $this->user->paginate(20)];

        return response()->json($data);

    }

    // public function store(Request $request){
    //     try {

    //         $this->validate($request,[
    //             'fullName' => ['required', 'string', 'max:191'],
    //             'userName' => ['required', 'string', 'max:191', 'unique:users'],
    //             'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
    //             'password' => ['required', 'string', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
    //         ]);
    
    //         $user = new User;
    //         $user->fullName = $request->json('fullName');
    //         $user->userName = $request->json('userName');
    //         $user->email = $request->json('email');
    //         $user->password = Hash::make($request->json('password'));
    //         $user->active = 0;
    //         $user->save();
            
    //        return response()->json(ApiMessage::successMessage('Verify you email!', 201));

    //     } catch(\Exception $e){

    //         if(config('app.debug')){
    //             return response()->json(ApiMessage::errorMessage($e->getMessage(), 1010));
    //         }
    //         return response()->json(ApiMessage::errorMessage('Error to create user!', 1010));

    //     }
    // }

    public function update(Request $request, $id){
        try {

            $this->validate($request,[
                'fullName' => ['required', 'string', 'max:191'],
                'userName' => ['required', 'string', 'max:191', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
                'password' => ['required', 'string', 'min:6'],
            ]);
    
            $user = User::find($id);
            $user->fullName = $request->json('fullName');
            $user->userName = $request->json('userName');
            $user->email = $request->json('email');
            $user->password = Hash::make($request->json('password'));
            $user->save();

        } catch(\Exception $e){

            if(config('app.debug')){
                return response()->json(ApiMessage::errorMessage($e->getMessage(), 1011));
            }
                return response()->json(ApiMessage::errorMessage('Error to update user!', 1011));

        }
        
    }

    public function destroy($id){
        try{
            $user = User::find($id);
            $user->delete();            

            return response()->json(ApiMessage::successMessage('User '. $user->fullName .' deleted!', 202));

        } catch(\Exception $e) {

            if(config('app.debug')){
                return response()->json(ApiMessage::errorMessage($e->getMessage(), 1012));
            }
                return response()->json(ApiMessage::errorMessage('Error to delete user!', 1012));

        }
        

    }

    public function show(User $id){
        $data = ['data' => $this->user->find($id)];

        return response()->json($data);
    }

    public function activeUser(){

    }
}
