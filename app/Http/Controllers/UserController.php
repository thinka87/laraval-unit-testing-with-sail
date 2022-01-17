<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {   

        // TODO 19: test_filter_users
        $users = User::has('projects')->get();
        return view('users.index', compact('users'));
    }

    public function show($name)
    {
        $user = User::where('name', $name)->first();
        if (!$user) {
            return view('users.notfound');
        }

        return view('users.show', compact('user'));
    }

    public function showRelationship($user_id)
    {      
        // TODO 14.2: test_show_users_comments
        // fix this by editing some code. Maybe there is too much?
        
        $user = User::with('comments')->where('id', $user_id)->first();
        return view('users.show', compact('user'));
    }
}
