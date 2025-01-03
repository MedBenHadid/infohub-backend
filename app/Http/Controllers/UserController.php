<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function insertUser(Request $request, User $user)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'role_id' => 'required|integer',
            'file_id' => 'integer',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::create([
            'name' => $request->name,
            'password' => encrypt('azerty'),
            'email' => $request->email,
            'role_id' => $request->role_id,
            'application_id' => $request->application_id,
        ]);
        if ($request->has('file_ids')) {
            $user->files()->attach($request->input('file_ids'));
        }
    }
}
