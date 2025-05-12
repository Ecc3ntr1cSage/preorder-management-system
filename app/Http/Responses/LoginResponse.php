<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();
        // Define role-based route mapping
        $roleRoutes = [
            0 => 'admin.overview',
            1 => 'admin.overview',
            2 => 'business.dashboard',
            3 => 'customer.shop',
        ];

        if (!isset($roleRoutes[$user->role_id])) {
            auth()->logout();
            abort(403, 'Unauthorized role.');
        }

        $redirectRoute = $roleRoutes[$user->role_id];

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(route($redirectRoute));
    }
}
