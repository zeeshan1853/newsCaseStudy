<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use \Tymon\JWTAuth\Facades\JWTAuth;

class UserPreferenceController extends Controller
{

    public function index()
    {
        return 'Index';
    }
    public function store(Request $request)
    {
        $user = JWTAuth::user();
        $pref = $user->preferences;
        if (!$pref) {
            $pref = new UserPreference();
            $pref->user_id = $user->id;
        }
        $pref->sources = $request->sources;
        $pref->categories = $request->categories;

        $pref->save();

        return response('Preferences updated.');
    }

}