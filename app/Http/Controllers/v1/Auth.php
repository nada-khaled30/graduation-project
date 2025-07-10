<?php

namespace App\Http\Controllers\v1;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User as UserModel;

class Auth extends Controller
{
    public function login(Request $request)
    {
        try {
            if (!$this->isValidRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => __('auth.INVALID_LOGIN'),
                    'data' => null
                ], 401);
            }

            $user = UserModel::where($this->getUserIdType($request->userID), $request->userID)->first();

            if (!$user) {
                $this->addLog(action: 'USER_LOGIN_ERR_ID', data: ['user' => $request->userID]);
                return response()->json([
                    'success' => false,
                    'message' => __('auth.INVALID_LOGIN'),
                    'data' => null
                ], 401);
            }

            if (!$this->isPasswordValid($request, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => __('auth.PASSWORD_ERROR'),
                    'data' => null
                ], 401);
            }

            $this->addLog(action: 'USER_LOGIN', user: $user->id);
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ], 200);
        } catch (\Throwable $th) {
            return $this->throwError($th);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->addLog(action: 'USER_AUTH_END', user: auth()->user()->id);
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => __('auth.LOGGED_OUT'),
                'data' => null
            ], 200);
        } catch (\Throwable $th) {
            return $this->throwError($th);
        }
    }

    private function isValidRequest(Request $request): bool
    {
        if ($request->userID == "" || $request->password == "") {
            $this->addLog(action: 'AUTH_VALIDATION_ERR', data: ['user' => $request->userID ?? ""]);
            return false;
        }
        return true;
    }

    private function isPasswordValid(Request $request, string $password): bool
    {
        if (!Hash::check($request->password, $password)) {
            $this->addLog(action: 'USER_LOGIN_ERR_PASSWORD', data: ['user' => $request->userID]);
            return false;
        }
        return true;
    }
}
