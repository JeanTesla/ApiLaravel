<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function betaSystems(){
        return response(['status'=>'Codando']);
    }

    public function register(Request $request)
    {
        try {
            $resgisterData = $request->all();
            $validatedData = Validator::make($resgisterData, [
                'name' => 'required|max:55',
                'email' => 'email|required',
                'password' => 'required|confirmed'
            ]);
            if ($validatedData->fails()) {
                return response(['message' => 'Credenciais Invalidas'], 422);
            }
            $resgisterData['password'] = Hash::make($resgisterData['password']);
            $user = User::create($resgisterData);
            $accessToken = $user->createToken('Laravel Password Grant Client')->accessToken;
            return response(['user' => $user, 'token' => $accessToken]);
        } catch (\Exception $e) {
            return response()->json(['erro' => $e], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $loginData = $request->all();
            $validatedData = Validator::make($loginData, [
                'email' => 'email|required',
                'password' => 'required'
            ]);
            if ($validatedData->fails()) {
                return response(['message' => 'Credenciais Invalidas'], 422);
            }
            $existsUser =  $this->user->query()->where('email', '=', $loginData['email'])->first();
            if ($existsUser && Hash::check($loginData['password'], $existsUser->password)) {
                $accessToken = $this->user->createToken('Laravel Password Grant Client')->accessToken;
                return response(['autorized' => $loginData, 'token' => $accessToken]);
            } else {
                return response(['message' => 'User not Found!']);
            }
        } catch (\Exception $e) {
            return response($e);
        }
    }

    public function logout (Request $request) {
        //return response(['request'=> $request->headers]);
        $token = Auth::user()->token_get_all;
        //$token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'Seu login foi destruido.','token'];
        return response($response, 200);
    }
}
