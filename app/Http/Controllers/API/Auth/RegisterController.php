<?php


namespace App\Http\Controllers\API\Auth;


use App\Enums\ApiResponseEnum;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController
{

    private array $rules = [
        'firstname' => ['required', 'string', 'min:3', 'max:20'],
        'lastname' => ['required', 'string', 'min:3', 'max:20'],
        'username' => ['required', 'string', 'unique:users'],
        'email' => ['required', 'email:rfc', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ];

    public function __invoke(Request $request)
    {
        $request->validate($this->rules);
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        event(new Registered($user));

        $user->wallet()->create([]);

        return response()->json([
            'status' => ApiResponseEnum::success(),
            'message' => 'Account created successfully',
            'token' => $user->createToken($request->email)->plainTextToken,
        ]);
    }
}
