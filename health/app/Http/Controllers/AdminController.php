<?php

namespace App\Http\Controllers;

use App\AboutDoctor;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Change users group from admin panel
     */
    public function changeGroupUser(Request $request){
        $user = User::find($request->user_id);
        $user->detachRole($user->roles()->first()->name);
        $user->attachRole($request->role);

        if ($request->role == "doctor"){
            AboutDoctor::create([
                "about" => "",
                "doctor_id" => $request->user_id,
                "img" => ""
            ]);
        } else {
            AboutDoctor::where("doctor_id", $request->user_id)->delete();
        }
        return redirect()->back();
    }
}
