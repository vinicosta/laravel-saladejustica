<?php

namespace App\Http\Controllers;

Use App\User;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // $user = User::find(1);
        // echo '[' . $user->password . ']<br>';
        // echo '[' . bcrypt('') . ']';
        // $user->password = bcrypt('');
        // $user->save();

        // exit;
        return view('dashboard');
    }
}
