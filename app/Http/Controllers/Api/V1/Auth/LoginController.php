<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 *
 * @subGroup Login User
 */
class LoginController extends Controller
{
    /**
     * @bodyParam email string required The email of the user. Example: demo@user.test
     * @bodyParam password string required The password of the user. Example: password
     */
    public function __invoke(LoginRequest $request)
    {
        try {
            $request->authenticate();
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'access_token' => $request->user()
                ->createToken($request->device())
                ->plainTextToken,
        ]);
    }
}
