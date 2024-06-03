<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    public function GetUserByType($id)
    {
        $members = collect();
        if ($id === '1') {
            $members = DB::table('teachers')->get();
        } elseif ($id === '2') {
            $members = DB::table('students')->get();
        } elseif ($id === '3' || $id === '4') {
            $members = DB::table('employees')->get();
        } 
        
        if ($members->isNotEmpty()) {
            return response()->json([
                'status' => 200,
                'data' => $members
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No User found for this Type."
            ], 404);
        }
    }

    public function index()
    {
        $users = DB::table('users')->get();
        foreach ($users as $user) {

            switch ($user->member_type) {
                case 'employees':
                    $user->member_info = DB::table('employees')->where('id', $user->member_id)->first();
                    break;
                case 'admins':
                    $user->member_info = DB::table('employees')->where('id', $user->member_id)->first();
                    break;
                case 'teachers':
                    $user->member_info = DB::table('teachers')->where('id', $user->member_id)->first();
                    break;
                case 'students':
                    $user->member_info = DB::table('students')->where('id', $user->member_id)->first();
                    break;
                default:
                    break;
            }
        }

        if ($users->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No Record Found.'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'users' => $users
        ], 200);
    }

    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation Failed'], 400);
        }
        // return $id;
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'status' => 200,
            'user' => $user,
        ], 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'member_id' => 'required|integer',
            'member_type' => 'required|string|max:255',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $user = User::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'member_id' => $request->input('member_id'),
            'member_type' => $request->input('member_type'),
            'role_id' => $request->input('role_id'),
        ]);

        if ($user) {
            return response()->json([
                'status' => 200,
                'message' => 'User created successfully.',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to create user.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'member_id' => 'required|integer',
            'member_type' => 'required|string|max:255',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found'
            ], 404);
        }

        $user->email = $request->input('email');
        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->member_id = $request->input('member_id');
        $user->member_type = $request->input('member_type');
        $user->role_id = $request->input('role_id');
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully.',
            'user' => $user,
        ], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 200,
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function GetUserByRole(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['role_id' => 'required|integer|exists:roles,id',]
        );
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
    
        $role_id = $request->input('role_id');
        $users = User::where('role_id', $role_id)->with('role')->get();
    
        if ($users->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => "No User found for this Role."
            ], 404);
        }
    
        foreach ($users as $user) {
            switch ($user->member_type) {
                case 'employees':
                    $user->member_info = DB::table('employees')->where('id', $user->member_id)->first();
                    break;
                case 'admins':
                    $user->member_info = DB::table('employees')->where('id', $user->member_id)->first();
                    break;
                case 'teachers':
                    $user->member_info = DB::table('teachers')->where('id', $user->member_id)->first();
                    break;
                case 'students':
                    $user->member_info = DB::table('students')->where('id', $user->member_id)->first();
                    break;
                default:
                    $user->member_info = null;
                    break;
            }
        }
    
        return response()->json([
            'status' => 200,
            'users' => $users
        ], 200);
    }
    

}
