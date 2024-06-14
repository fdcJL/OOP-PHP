<?php

namespace Src\Controllers;

use App\Database\DB;
use Src\Controllers\Controller;

class TestController extends Controller{
    public function index(){        
        $Qry = DB::table('test')->select('id,fname')->get();
        return response()->json($Qry)->send();
    }
}
