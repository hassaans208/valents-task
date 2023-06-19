<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;

class AuthenticationController extends Controller
{
    /**
     * Login User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // $emails = User::get()->pluck('email')->toArray();

        $validated = \Validator::make($request->toArray(), [

            'email' => 'required',
            'password' => 'required',

        ], [

            'email.in' => 'Email not found'

        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validated->errors()
            ]);
        }

        try {

            $data = $validated->validated();

            $user = User::where('email', $data['email'])->first();

            // if email not found
            if (!$user) {
                return response()->json([
                    'status' => 400,
                    'message' => 'User not found'
                ]);
            }

            if (Hash::check($data['password'], $user->password)) {

                Auth::login($user);

                $token = $user->createToken(config('app.name'))->accessToken;

                return response()->json([
                    'status' => 200,
                    'message' => 'User loggedIn successfuly!',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ]
                ], 200);

            } else {

                return response()->json([
                    'status' => 401,
                    'message' => 'Password Incorrect!',
                ], 401);
            }


        } catch (\Exception $e) {

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }

    }
    public function logout(Request $request)
    {
        try {

            Auth::user()->token()->revoke();

            return response()->json([
                'status' => 204,
                'message' => 'Logged out successfully!',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
        }

    }


}
