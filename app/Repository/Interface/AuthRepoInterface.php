<?php

namespace App\Repository\Interface;

use Carbon\Traits\Timestamp;
use DateTime;

interface AuthRepoInterface
{
    public function storeToken(int $userId,string $token,DateTime $expiredAt,bool $isUsed, string $jti);
    public function register(string $name, string $email, string $password);
    public function invalidateToken(string $jti);
}
