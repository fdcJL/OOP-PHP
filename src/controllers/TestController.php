<?php

namespace Src\Controllers;

use App\Database\DB;
use Src\Controllers\Controller;

class TestController extends Controller{
    public function index(){
        $test = DB::table('test')->get();
        
        return response()->json($test)->send();
    }
}
