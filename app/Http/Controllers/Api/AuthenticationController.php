<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function register(Request $request){
    	$request->validate([
    		'first_name' => 'required|max:45',
    		'last_name' => 'required|max:45',
    		'email' => 'required|email|unique:users|max:191',
    		'password' => 'required'
    	]);

    	$existingUsers = User::all();

    	$registeredUser = User::create([
    		'first_name' => $request->first_name,
    		'last_name' => $request->last_name,
    		'email' => $request->email,
    		'password' => Hash::make($request->password)
    	]);

    	//automatically tag newly registered user as friends with the existing users
    	foreach($existingUsers as $existingUser){
    		$registeredUser->users()->attach($existingUser->id);
    	}

        return response()->json([
            'message' => 'Successfully registered!'
        ]);
    }

    public function authenticate(Request $request){
    	$request->validate([
			'email' => 'required|email',
			'password' => 'required'
		]);

		$user = User::where('email', $request->email)->first();
		if (!$user || !Hash::check($request->password, $user->password)) {
	        throw ValidationException::withMessages([
	            'message' => 'The provided credentials are incorrect.',
	        ]);
	    }

	    return response()->json([
	    	'token' => $user->createToken($request->email, User::$userAbilities)->plainTextToken,
            'user_id' => $user->id
		]);
    }

    public function logout(){
        Auth::user()->currentAccessToken()->delete(); //delete current token
        return response()->json(['message' => 'Logged out Successfully!']);
    }

}
