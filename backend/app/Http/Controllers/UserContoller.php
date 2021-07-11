<?php

namespace App\Http\Controllers;

use App\Services\UserServices;
use Illuminate\Http\Request;

class UserContoller extends Controller
{
    public function __construct(
        public UserServices $userServices
    ) {
    }
    public function login(Request $request)
    {
        return $this->userServices->checkCredentials($request->all());
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
