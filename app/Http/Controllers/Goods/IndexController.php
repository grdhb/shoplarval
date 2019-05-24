<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class IndexController extends Controller
{
    //商品首页
    public  function index()
    {

        //走缓存
        $goods_name = request()->goods_name ?? '';
        $where1[] = ['goods_name', 'like', "%$goods_name%"];
        $res = cache('res'.$goods_name);
        if (!$res) {
            $where = [
                'tj' => 2
            ];
            $res = DB::table('goods')->where($where)->where($where1)->get();
            cache(['res'. $goods_name => $res], 1);
        }
        return view('goods/index', ['res' => $res,'goods_name'=>$goods_name]);
    }
}
