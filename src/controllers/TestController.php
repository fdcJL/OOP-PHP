<?php

namespace Src\Controllers;

use App\Database\DB;
use Src\Controllers\Controller;

class TestController extends Controller{
    public function index(){        
        $rs = DB::table('test as a')
                ->leftJoin('sample as b', 'a.id = b.idacct')
                ->select('a.id,a.fname,b.fname')
                ->where('a.id>0')
                ->get();
        
        return response()->json($rs)->send();
    }
}
