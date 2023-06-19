<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Retrieve a list of users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {

            $users = User::get();
            // dd(auth()->user());
            return response()->json([
                'status' => 200,
                'message' => 'Users fetched successfuly!',
                'data' => $users
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 400);
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
            'password' => 'required|unique:users|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validated->errors()
            ], 400);
        }
        try {

            $data = $validated->validated();

            $data['password'] = Hash::make($data['password']);

            // DB will rollback in case of any error occurs, useful when multiple
            // transactions are performed

            DB::beginTransaction();

            $user = User::create($data);

            // this function will use spatie media library and attach a new image if the photo is available in request
            if ($request->file('photo')) {
                Helpers::attachImage($user, $request->photo, 'photo');
            }

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
            ], 400);
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
        // dd($request->toArray(), $id);
        $validated = \Validator::make($request->toArray(), [
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id)
            ],
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validated->errors()
            ], 400);
        }

        try {

            // DB will rollback in case of any error occurs, useful when multiple
            // transactions are performed
            $data = $validated->validated();

            $data['password'] = Hash::make($data['password']);

            DB::beginTransaction();

            $user = User::find($id);

            if(!$user){
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found!',
                ], 404);
            }

            $user->update($data);

            // this function will use spatie media library and delete the previous attached image

            // this function will use spatie media library and attach a new image if the photo is available in request
            if ($request->file('photo')) {
                Helpers::deletePrevImage($user, 'photo');
                Helpers::attachImage($user, $request->photo, 'photo');
            }

            $user = User::find($id);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'User updated successfuly!',
                'data' => $user
            ], 200);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ], 404);
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

            $user = User::find($id);

            // if the :id is not correct throe err
            if (!$user)

                return response()->json([
                    'status' => 404,
                    'message' => 'User not found!',
                ]);

            // else delete the user
            if ($user)
                $user->delete();

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
            ], 400);
        }

    }
}
