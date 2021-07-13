<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UserContoller extends Controller
{
    public function __construct(
        public UserServices $userServices
    ) {
    }
    public function login(LoginRequest $request)
    {
        return $this->userServices->checkCredentials($request->validated());
    }

    public function refresh()
    {
        return $this->userServices->refresh();
    }

    public function logout()
    {
        $this->userServices->logout();
    }
}
