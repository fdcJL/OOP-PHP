<?php

namespace Src\Controllers;

use App\Database\DB;
use Src\Controllers\Controller;

class TestController extends Controller{
    public function index(){        
        $Qry = DB::table('users');
        $Qry->select('fname, lname');
        $Qry->where('id>0');
        $result = $Qry->get();

        return response()->json($result);
    }
}
