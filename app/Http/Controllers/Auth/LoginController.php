<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

/**
 * @OA\PathItem(
 *     path="/api/login"
 * )
 */
class LoginController extends Controller
{
    use HasApiTokens;
    
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     description="Authenticates the user and returns an access token.",
     *     operationId="login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="your-access-token"),
     *             @OA\Property(property="user", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="user@example.com")
     *             ),
     *             @OA\Property(property="message", type="string", example="Login successful")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid email and/or password")
     *         )
     *     )
     * )
     */
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