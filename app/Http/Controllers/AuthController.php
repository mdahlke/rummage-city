<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller {
    /**
     * Create user
     *
     * @param Request request
     *  $request [
     *      string name
     *      string email
     *      string password
     *      string password_confirmation
     *  ]
     *
     * @return string message
     */
    public function signup(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param Request request
     *  $request [
     *      string email
     *      string password
     *      string remember_me
     *  ]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        $token->expires_at = Carbon::now()->addWeeks(1);


        $token->save();

        return response()->json([
            'user' => Auth::user(),
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return string message
     */
    public function logout(Request $request) {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request) {
        return response()->json($request->user());
    }
}
