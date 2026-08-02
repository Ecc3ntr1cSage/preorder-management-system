<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        return redirect()->intended(route($user->is_admin ? 'admin.overview' : 'dashboard'));
    }
}
