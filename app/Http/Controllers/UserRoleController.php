<?php

namespace App\Http\Controllers;

use App\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function getRoles()
    {
        return UserRole::all();
    }
}
