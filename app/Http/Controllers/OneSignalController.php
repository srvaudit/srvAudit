<?php

namespace App\Http\Controllers;

use Auth;
use User;
use Illuminate\Http\Request;

class OneSignalController extends Controller
{
    function addOneSignalID(Request $request)
    {
        $user = Auth::user();
        $user->onesignal_id = $request->userID;
        $user->save();
    }
}
