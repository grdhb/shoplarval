<?php

namespace App\Http\Controllers\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class ProlistController extends Controller
{
    //所有商品
    public function index(){
        $res = cache('resd');
        if (!$res) {
            $res = DB::table('goods')->get();
            cache(['resd' => $res], 5);
        }
      return  view('goods/prolist',compact('res'));
    }
}
