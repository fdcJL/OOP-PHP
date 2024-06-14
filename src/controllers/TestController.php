<?php

namespace Src\Controllers;

use App\Database\DB;
use Src\Controllers\Controller;

class TestController extends Controller{
    public function index(){        
        $rs = DB::table('test as a')
                ->leftJoin('sample as b', 'a.id = b.idacct')
                ->select('a.id,a.fname as a,b.fname as b')
                ->where('a.id>0')
                ->groupBy('a.id')
                ->orderBy('a.id DESC')
                ->get();
        
        return response()->json($rs)->send();
    }
}
