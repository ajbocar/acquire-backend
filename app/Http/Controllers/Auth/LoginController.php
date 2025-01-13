<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class LoginController extends Controller
{
    use HasApiTokens;

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!password_verify($request->password, $user->password) || !$user) {
            return response()->json(['error' => 'Invalid email and/or password'], 401);
        }

        return response()->json([
            'token' => $user->createToken('accessToken')->accessToken,
            'user' => $user,
            'message' => 'Login successful'
        ]);
    }
}