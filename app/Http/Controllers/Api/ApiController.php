<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

abstract class ApiController extends Controller
{
    /**
     * Return the authenticated user or abort with a 401 JSON response.
     */
    protected function authUser()
    {
        $user = Auth::guard('web')->user();

        if (! $user) {
            throw new HttpResponseException(
                response()->json(['message' => 'Unauthenticated.'], 401)
            );
        }

        return $user;
    }

    /**
     * Ensure the authenticated user has one of the given roles.
     *
     * @param  string|array  $roles
     */
    protected function authorizeRole($roles)
    {
        $user = $this->authUser();
        $roles = (array) $roles;

        if (! in_array($user->role, $roles, true)) {
            throw new HttpResponseException(
                response()->json(['message' => 'Forbidden.'], 403)
            );
        }

        return $user;
    }
}
