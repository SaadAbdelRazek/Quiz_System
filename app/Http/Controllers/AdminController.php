<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('admin.admin-dashboard');
    }
    public function viewUsers()
    {
        $users=User::all();
        return view('admin.users',compact('users'));
    }
}