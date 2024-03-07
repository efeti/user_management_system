<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManageUsers extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'User registered successfully'], 201);

    }

    public function update(Request $request)
    {
        $user = Auth::user(); //retrieve the authenticated user

        // if ($request->user()->id !== $user->id){
        //     return response()->json(['error' => 'Unauthorized'])
        // }

        $validate_data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|min:8',
        ]);

        if(!filled($validate_data)) {
            return response()->json(['error' => 'atleast one field must be provided'], 422);
        }

        $user->update($validate_data);

        return response()->json(['message' => 'profile updated successfully'], 200);
    }

    public function update_role(Request $request){

        if (!Auth::user()->tokenCan('admin')){
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'email' => 'required|string|email|max:255',
            'role' => 'required|string|in:admin',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        
        if(!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->update(['role' => $request->input('role')]);

        return response()->json(['message' => 'User role updated successfully'], 200);

    }

    public function delete_user(Request $request){

        if (!Auth::user()->tokenCan('admin')){
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'email' => 'required|string|email|exists:users,email',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        
        if(!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['error' => 'User deleted successfully'], 200);
    }

    public function view_all_users(){
        if (!Auth::user()->tokenCan('admin')){
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $users = User::all();
        return response()->json(['users' => $users], 200);
    }


  
}
