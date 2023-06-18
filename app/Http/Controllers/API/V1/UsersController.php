<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    /**
     * Retrieve a list of users.
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
            if (!$user) {
                return response()->json([
                    'status' => 400,
                    'message' => 'User not found'
                ]);
            }

            if (Hash::check($data['password'], $user->password)) {

                \Auth::login($user);
                $token = $user->createToken(config('app.name'))->accessToken;
                // dd($token);

                return response()->json([
                    'status' => 200,
                    'message' => 'User loggedIn successfuly!',
                    'data' => [
                        'user' => $user,
                        'token' => $token,
                    ]
                ]);

            } else {

                return response()->json([
                    'status' => 401,
                    'message' => 'Password Incorrect!',
                ]);
            }


        } catch (\Exception $e) {

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }

    }
    /**
     * Retrieve a list of users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {

            $users = User::get();

            return response()->json([
                'status' => 200,
                'message' => 'Users fetched successfuly!',
                'data' => $users
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }

    }

    /**
     * Create a new user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        $validated = \Validator::make($request->toArray(), [
            'first_name' => 'required|string',
            'last_name' => 'string',
            'email' => 'required|unique:users',
            'password' => 'required|unique:users|confirmation',
            'photo' => 'sometimes',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validated->errors()
            ]);
        }
        try {

            $data = $validated->validated();
            $data['password'] = Hash::make($request->password);

            // DB will rollback in case of any error occurs, useful when multiple
            // transactions are performed

            DB::beginTransaction();

            $user = User::create($data);

            DB::commit();

            return response()->json([
                'status' => 201,
                'message' => 'Users created successfuly!',
                'data' => $user
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }

    }

    /**
     * Update a specific user.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(Request $request, $id)
    {
        $validated = \Validator::make($request->toArray(), [
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'required|unique:users',
            'password' => 'required|unique:users',
            'photo' => 'sometimes',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validated->errors()
            ]);
        }

        try {

            // DB will rollback in case of any error occurs, useful when multiple
            // transactions are performed

            DB::beginTransaction();

            $user = User::find($id)->update($validated);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'User updated successfuly!',
                'data' => $user
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }

    }

    /**
     * Delete a specific user.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function delete(Request $request, $id)
    {
        try {
            // DB will rollback in case of any error occurs, useful when multiple
            // transactions are performed

            DB::beginTransaction();

            User::find($id)->delete();

            DB::commit();

            return response()->json([
                'status' => 204,
                'message' => 'User deleted successfuly!',
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }

    }
    public function generateToken(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (\Auth::attempt($credentials)) {
            $user = \Auth::user();
            $token = $user->createToken('API Access Token')->accessToken;

            return response()->json(['access_token' => $token]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }
}
