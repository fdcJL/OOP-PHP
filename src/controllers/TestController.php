<?php

namespace Src\Controllers;

use App\Database\DB;
use Src\Controllers\Controller;

class TestController extends Controller{
    public function index(){        
        $rs = DB::table('users')->select('fname, lname')->get();
        
        return response()->json($rs);
    }
}
