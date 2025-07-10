<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function store(RegistrationRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::create($validated);
            $token = $user->createToken('authToken')->plainTextToken;

            $this->addLog(action: 'USER_REGISTRATION', user: $user->id);

            return response()->json([
                'success' => true,
                'message' => __('auth.ACCOUNT_CREATED'),
                'data'    => [
                    'user'  => $user,
                    'token' => $token,
                ],
            ], 201);
        } catch (\Throwable $th) {
            return $this->throwError($th);
        }
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'data'    => [
                'user' => [
                    ...$user->toArray(),
                    'name' => $user->first_name . ' ' . $user->last_name,
                ],
            ],
        ]);
    }

    public function editProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'specialist' => 'nullable|in:dentist,cardiologist,dermatologist,pediatrician',
            'mobile'     => 'nullable|string|max:20|unique:users,mobile,' . $user->id,
            // 'password'   => 'nullable|string|confirmed|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data'    => [
                'user' => $user,
            ],
        ]);
    }

    public function deleteProfile(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated',
                'data'    => null,
            ], 401);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully',
            'data'    => null,
        ]);
    }

    // غير مستخدمة حالياً
    public function index() {}
    public function show(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}