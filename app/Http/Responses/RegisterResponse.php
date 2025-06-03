<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        $roleRoutes = [
            0 => 'admin.overview',
            1 => 'admin.overview',
            2 => 'business.publish',
            3 => 'customer.shop',
        ];

        if (!isset($roleRoutes[$user->role_id])) {
            auth()->logout();
            abort(403, 'Unauthorized role.');
        }

        return redirect()->intended(route($roleRoutes[$user->role_id]));
    }
}
