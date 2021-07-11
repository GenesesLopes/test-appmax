<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class UnauthorizedException extends Exception
{
    //
    public function render(Request $request)
    {
        return response("Usuário não Autorizado", 401);
    }
}