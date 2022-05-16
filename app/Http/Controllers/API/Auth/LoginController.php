<?php


namespace App\Http\Controllers\API\Auth;


use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController
{

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
            'device_name' =>'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'The provided credentials are incorrect.',
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'token' => $token,
        ]);
    }
}
