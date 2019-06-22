<?php

namespace App\Http\Controllers;

use App\CommentsSiteLikes;
use App\Role;
use App\Treatment;
use App\User;
use App\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->hasRole('admin')){
            return $this->adminHome();
        } else if (Auth::user()->hasRole('doctor')){
            return $this->doctorHome();
        } else {
            return $this->userHome();
        }
    }

    /**
     * Home page for admin
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function adminHome(){
        $users = User::get();
        $roles = Role::get();
        return view('home.admin', [
            "users" => $users,
            "roles" => $roles
        ]);
    }

    /**
     * Home page for user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function userHome(){
        $treatments = Treatment::where("user_id", Auth::user()->id)->get();
        return view('home.user', [
            "treatments" => $treatments
        ]);
    }

    /**
     * Home page for doctor
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function doctorHome(){
        $treatments = Treatment::select(
            "treatments.id as id",
            "treatments.photo as photo",
            "treatments.disease as disease",
            "treatments.status as status",
            "treatments.comment as comment",
            "users.name as user_name",
            "treatments.user_id as user_id"
        )
            ->whereNull("comment_doctor")
            ->where("doctor_id", Auth::user()->id)
            ->join("users", "users.id", "=", "treatments.user_id")
            ->get();

        foreach($treatments as &$treatment){
            $treatment->documents = UserDocument::where("treatment_id", $treatment->id)->get();
        }
        return view('home.doctor', [
            "treatments" => $treatments
        ]);
    }

    public function welcome(){
        return view('layouts.app');
    }
}
