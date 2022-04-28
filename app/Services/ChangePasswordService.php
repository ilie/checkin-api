<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordService{

    public static function changePassword($request){
        $user= User::findOrFail($request->id);
        if(Hash::check($request->old_password, $user->password)){
            $user->update(['password' => Hash::make($request->password)]);
            return response()->json(['message' => 'Password updated successfully'], 200);
        }else{
            return response()->json(['error' => 'Old password does not match'], 400);
        }
    }
}