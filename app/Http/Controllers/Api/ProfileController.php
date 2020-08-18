<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Profile as ProfileResource;

class ProfileController extends Controller
{
    public function getProfile($userId){
        if(Auth::user()->tokenCan('profile:view')){
            $profile = User::findOrFail($userId);
            if(Auth::user()->id == $userId){
                return new ProfileResource($profile);
            }
        }

        abort(403, 'Unauthorized access');
    }

    public function update(Request $request){
    	if(Auth::user()->tokenCan('profile:update')){
    		$user = User::findOrFail(Auth::user()->id);
			$user->first_name = $request->first_name;
			$user->last_name = $request->last_name;

    		if($user->isDirty()){
    			$user->save();
    			return new ProfileResource($user->fresh());
    		}

    		return response()->json([
    			'message' => 'No changes.'
    		]);
    	}

    	abort(403, 'Unauthorized access');
    }
}
