<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * This method is responsible for authentication
     *
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        try {
            
            $credentials = $request->only('email', 'password');

            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return response()->json(compact('token'));

        }
        catch (JWTException $e) {
            return response()->json(['message' => 'Could not create token'], 500);
        }
        catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
    }

    /**
     * This method is responsible for logging out
     *
     * @return Response
     */
    public function logout()
    {
        try {
            
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Successfully logged out']);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
    }
}
