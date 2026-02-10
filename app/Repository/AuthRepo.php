<?php

namespace App\Repository;
use App\Models\UserToken;
use App\Models\User;
use App\Repository\Interface\AuthRepoInterface;

class AuthRepo implements AuthRepoInterface
{
    public function storeToken($userId, $token ,$expiredAt, $isUsed ,$jti){
        return UserToken::create([
            'user_id'=>$userId,
            'token' => $token,
            'expired_at'=> $expiredAt,
            'is_used'=>$isUsed,
            'jti'=>$jti
        ]);
    }

    public function register(string $name, string $email, string $password){
        return User::create([
            'name'=>$name,
            'email'=>$email,
            'password'=>$password,
        ]);
    }

    public function invalidateToken(string $jti){
        return UserToken::where('jti', $jti)->update([
            'is_used'=>true
        ]);
    }
}

