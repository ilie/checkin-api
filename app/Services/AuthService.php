<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Create login token
     * @param Request $request
     * @return response
     */
    public static function login($request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        if(Auth::attempt($credentials)) {
            $user = Auth::user();
            $abilities = $user->is_admin ? ['isAdmin'] : ['isUser'];
            $deviceName = 'Checkin App - '.$request->header('User-Agent');
            $token = $user->createToken($deviceName, $abilities)->plainTextToken;

            return response()->json([
            'type' => 'user',
            'id' => (string) $user->id,
            'attributes' => [
                'token' => $token,
                'name' => $user->name,
                'nif' => $user->nif,
                'email' => $user->email,
                'social_sec_num' => $user->social_sec_num,
                'hours_on_contract' => $user->hours_on_contract,
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at->toDateTimeString(),
                'updated_at' => $user->updated_at->toDateTimeString(),
            ],
            'links' => [
                'self' => route('users.show', $user->id)
            ]
        ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);

    }

    /**
     *  logout
     *  @param $request
     *  @return \Illuminate\Http\JsonResponse
     */

    public static function logout($request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out'], 204);
    }

    /**
     *  forgot password
     *  @param $request
     *  @return \Illuminate\Http\JsonResponse
     */
    public static function forgotPassword($request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        if($status == Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset link was sent by email'], 200);
        }
        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    /**
     *  reset password
     *  @param $request
     *  @return \Illuminate\Http\JsonResponse
     */
    public static function resetPassword($request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        });

        if($status === Password::PASSWORD_RESET) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Password was reset successfuly'], 200);
        }
        throw ValidationException::withMessages([
            'message' => [__($status)],
        ], 500);

    }

}
