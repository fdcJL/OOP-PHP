<?php

namespace Src\Controller;

use App\Core\Request;
use App\Database\DB;

class RegisteredController {
    public function index() {
        // $users = DB::table('users')->get();
        // header('Content-Type: application/json');
        // return $users;
        echo "Invalid input!";
    }
    public function store(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        if ($username && $password) {
            // Handle the registration logic
            echo "User registered with username: $username and password: $password";
        } else {
            echo "Invalid input!";
        }
    }
}
