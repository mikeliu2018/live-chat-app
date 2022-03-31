<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function profile()
    {
        $userProfile = auth()->user();
        if ($userProfile !== NULL)            
            User::where('id', $userProfile->id)->update([
                'updated_at' => now()
            ]);          

            return response()->json([
                'status' => true,
                'message' => __('Operation complete'),
                'userProfile' => $userProfile
            ]);
    }    

}
