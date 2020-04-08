<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-13
 * Time: 01:48
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.users');
    }
}