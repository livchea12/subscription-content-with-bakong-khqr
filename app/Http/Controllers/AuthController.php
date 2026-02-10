<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\AuthRequest\LoginRequest;
use App\Http\Resources\AuthResource\LoginResource;
class AuthController extends Controller
{
    public function __construct(private AuthService $authService){}
    //Login
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $token = $this->authService->login($credentials);

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        return LoginResource::make($token);
    }

    //Register
    public function register(Request $request){
        $credentials = $request->only('name', 'email', 'password');
        $token = $this->authService->register($credentials);
        return LoginResource::make($token);
    }

    //Logout
    public function logout(Request $request){
        $jti = JWTAuth::parseToken()->getPayload()->get('jti');
        $this->authService->logout($jti);
        return response()->json("Logout successfully");
    }

    //reset Password
    public function resetPassword(Request $request){
        $user = Auth::guard('api')->user();
        $credentials = $request->only('old_password', 'new_password');
        
        $success = $this->authService->resetPassword($user, $credentials['old_password'], $credentials['new_password']);
        
        if (!$success) {
            return response()->json([
                'status' => false,
                'message' => 'Old password does not match'
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully'
        ]);
    }
}
