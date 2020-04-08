<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-04-02
 * Time: 00:22
 */

namespace App\Http\Controllers;

class MessageController extends Controller
{
    public function index() {
        return view('messages');
    }
}