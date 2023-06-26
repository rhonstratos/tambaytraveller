<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        try {
            $request->authenticate();
        } catch (ValidationException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong.'
            ], 500);
        }

        return response()->json([
            'access_token' => $request->user()
                ->createToken($request->device())
                ->plainTextToken,
        ]);
    }
}
