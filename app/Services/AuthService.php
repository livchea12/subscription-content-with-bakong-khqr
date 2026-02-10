<?php

namespace App\Services;
use App\Repository\Interface\AuthRepoInterface;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class AuthService {
    public function __construct(private AuthRepoInterface $authRepo){}

    public function login($credentials){
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return false;
        }
        
        $user = Auth::guard('api')->user();
        return $this->generateTokens($user, $token);
    }

    public function register($credentials){
        $user = $this->authRepo->register($credentials['name'], $credentials['email'], $credentials['password']);
        
        // Attempt to login/generate tokens for the new user
        $token = Auth::guard('api')->login($user);
        
        return $this->generateTokens($user, $token);
    }

    private function generateTokens($user, $accessToken) {
        $jti = Str::random(40);
        
        $expiry = now()->addDays(15);

        $refreshPayload = [
            'sub' => $user->id,
            'exp' => $expiry->timestamp,
            'jti' => $jti,
            'type' => 'refresh'
        ];

        $refreshToken = JWTAuth::customClaims($refreshPayload)->fromUser($user);    
       
        $this->authRepo->storeToken($user->id, $refreshToken, $expiry, false, $jti);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }

    public function logout($jti){
        Auth::guard('api')->logout();
        return $this->authRepo->invalidateToken($jti);
    }
}
