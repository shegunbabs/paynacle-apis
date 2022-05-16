<?php


namespace App\Http\Controllers\API\Auth;


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
    }
}
