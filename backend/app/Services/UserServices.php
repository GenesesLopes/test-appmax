<?php

namespace App\Services;

use App\Exceptions\UnauthorizedException;

class UserServices
{
    
    public function encryptPassword(string $password): string
    {
        return \Hash::make($password);
    }

    public function checkCredentials(array $data): array
    {
        if (!$token = \Auth::attempt($data))
            throw new UnauthorizedException();
        return $this->respondWithToken($token);
    }

    public function refresh(): array
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function logout()
    {
        auth()->logout();
    }


    protected function respondWithToken(string $token): array
    {
        dump($token);
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'name' => \Auth::user()->name
        ];
    }
}