<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class testController extends Controller
{
    public function debug(Request $request) 
    {
        $args = $request->all();
        $Request = User::where('email', $args['email'])->first();
        
        if(! $Request || ! Hash::check($args['password'], $Request->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentils are incorrect.'],
            ]);
        }
        return $Request->createToken($args['device'])->plainTextToken;
    }
}
