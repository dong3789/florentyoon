<?php

namespace App\Http\Controllers;

use App\Models\CatUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function App\responseData;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
           'email' => 'required',
           'password' => 'required'
        ]);

        $user = CatUsers::created([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken('catUsers')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        return $this->createToken($request->email, $request->password);

    }

    public function createToken ($userId, $password) {
        $credentials = array(
            'email' => $userId,
            'password' => $password
        );

        if(!Auth::guard('auth')->attempt($credentials, true)){
            return responseData(401, null, "Unauthenticated");
        }
        $user = Auth::guard('auth')->user();
        $data = [
            'grant_type' => 'password',
            'client_id' => '8',
            'client_secret' => 'ZkK2aPddhFTHgs5aKDOC1DwyOO0bPTblNy2axrDC',
            'username' => $user['email'],
            'password' => $password,
            'scope' => '*',
        ];

        //# 지난 로그인 삭제
        (new CatUsers())->removeToken($user->id);

        $request = Request::create('/oauth/token', 'POST', $data);
        $response = app()->handle($request);
        return $response;
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout()
    {
        $userId = Auth::user()->id;
        (new CatUsers())->removeToken($userId);

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
