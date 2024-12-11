<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // echo "get api is working";

        $users = User::where('status', 1)->get();
        if(count($users) > 0) {
            //users exits
            return response()->json([
                'message' => count($users) . 'users found',
                'status' => 1,
                'data' => $users
            ], 200);
        } else {
            //users does not exit
            return response()->json([
                'message' => count($users) . 'users not found',
                'status' => 0
            ], 404);        
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";

        $validator = validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages(), 400);
        } else {
            try {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
                return response()->json(['message' => 'Record stored successfully'], 200);
            } catch(\Exception $e) {
                return response()->json($e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if(is_null($user)) {
            return response()->json([
                'message' => 'User not found',
                'status' => 0
            ], 404);
        } else {
            return response()->json([
                'message' => 'User found',
                'status' => 1,
                'data' => $user
            ], 200);
        }
        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if(is_null($user)) {
            return response()->json([
                'message' => 'User does not exit',
                'status' => 0
            ], 404);
        } else {
            try {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();

                return response()->json([
                    'message' => 'User updated successfully',
                    'status' => 1,
                    'data' => $user
                ], 200);
 
            } catch (\Exception $e) {
                return response()->json($e->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if(is_null($user)) {
            return response()->json([
                'message' => 'User does not exit',
                'status' => 0
            ], 404);
        } else {
            try {
                $user->delete();
                return response()->json([
                    'message' => 'User deleted successfully',
                    'status' => 1
                ], 200);
            } catch(\Exception $e) {
                return response()->json($e->getMessage());
            }
        }
    }

    public function changePassword(Request $request, $id) {

        $user = User::find($id);
        if(is_null($user)) {
            return response()->json([
                'message' => 'User does not exit',
                'status' => 0
            ], 404);
        } else {
            if($user->password == $request->old_password) {
                if($request->new_password == $request->confirm_password) {
                    try {
                        $user->password = Hash::make($request->new_password);
                        $user->save();

                        return response()->json([
                            'message' => 'Password updated successfully',
                            'status' => 1
                        ], 200);

                    } catch(\Exception $e) {
                        return response()->json([
                            'message' => 'An error occurred while updating the password',
                            'error' => $e->getMessage(),
                            'status' => 0
                        ], 500);                    }

                } else {
                    return response()->json([
                        'message' => 'New password & confirm password does not match',
                        'status' => 0
                    ], 400);
                }

            } else {
                return response()->json([
                    'message' => 'Old password does not match',
                    'status' => 0
                ], 400);
            }
        }

    }
}