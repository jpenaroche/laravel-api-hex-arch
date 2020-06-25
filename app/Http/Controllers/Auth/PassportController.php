<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Src\Domain\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PassportController extends Controller
{
    /**
     * PassportController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest:api')->except('logout', 'details', 'renewToken');
        $this->middleware('auth:api')->only('logout', 'renewToken', 'details');
    }

    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken(str_random(10))->accessToken;

        return response([
            'status' => 'success',
            'token' => $token
        ]);
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'active' => 1
        ];

        if (auth()->attempt($credentials)) {

            $token = auth()->user()->createToken(Str::random(10))->accessToken;

            /*Guardo el token en Session*/
            $this->saveToken($token);

            return response([
                'status' => 'success',
                'token' => $token
            ]);
        } else {
            return response([
                'status' => 'error',
                'message' => __('auth.failed')
            ], 401);
        }
    }

    /**
     * Handles Logout Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response([
            'message' => __('messages.user_logout'),
            'token' => null
        ]);
    }

    public function renewToken()
    {
        $token = auth()->user()->createToken(str_random(10))->accessToken;
        $this->saveToken($token);
        return response([
            'status' => 'success',
            'token' => $token
        ]);
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response([
            'status' => 'success',
            'user' => auth()->user()->load('profile', 'roles', 'permissions')
        ]);
    }

    /**
     * Returns User Email Existence
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmail($email)
    {
        $user = User::whereEmail($email)->whereActive(1)->first();
        if ($user) {
            $user->media = $user->getMediaMatchAll(['profile', 'img', 'icon']);
            return response([
                'status' => 'success',
                'user' => $user->load('profile')
            ]);
        }

        abort(404, __('messages.email_doesn\'t_found'));
    }

    private function saveToken($token)
    {
        Session::put('auth_token', $token);
        Session::save();
    }
}
