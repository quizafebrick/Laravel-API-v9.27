<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $requests = $request->validated();

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match!', 401);
        }

        $user = User::where('email', $requests['email'])->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of '. $user->name)->plainTextToken
        ]);
    }

    public function register(StoreUserRequest $request)
    {
        $requests = $request->validated();
        $requests['password'] = Hash::make($requests['password']);

        $user = User::create($requests);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of '. $user->name)->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        // GET BEARER TOKEN FROM THE REQUEST
        $accessToken = $request->bearerToken();

        // GET ACCESS TOKEN FROM DATABASE
        $token = PersonalAccessToken::findToken($accessToken);

        $token->delete();

        return $this->success([
           'message' => 'You have successfully logged out and your token has been deleted.'
        ]);
    }
}
