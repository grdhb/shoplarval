<?php

namespace App\Http\Controllers\Goods;
use DB;
use Illuminate\Http\Request;
use App\Model\Pj;
use App\Http\Controllers\Controller;

class ProinfoController extends Controller
{
    //商品详情
    public  function index(Request $request,$id)
    {

        $res = cache('dres'. $id);
        if(!$res){
            $res = DB::table('goods')->where(['goods_id' => $id])->first();
            cache([ 'dres'. $id=> $res],60);
        }
        $goods_img = cache( 'goods_img' . $id);
        if(!$goods_img){
            $goods_img= $res->goods_img;
            $goods_img=explode('|',rtrim( $goods_img,'|'));
            cache([ 'goods_img' . $id => $goods_img],60);
        }
         $data = DB::table('pj')->where(['goods_id'=> $id])->orderBy('created_at','desc')->paginate(2);
         if(request()->ajax()){
             return view( 'goods/ajaxproinfo',['res' => $res, 'goods_img' => $goods_img, 'data' => $data]);
         }
        return view('goods/proinfo',['res'=>$res, 'goods_img' => $goods_img,'data'=>$data]);
    }
    //商品评论
    public function add( Request $request,$id){
        //提取用户名
        $name=session('emailname');
        //提取用户id
        $user_id=session('user');
        //获取商品id
        $post= $request->except('_token');
        $post['name']=$name;
        $post[ 'user_id']= $user_id;
        $post['goods_id']= $id;
        $model=new Pj;
        foreach ($post as $k => $v) {
            $model->$k = $v;
        }
        $res = $model->save();

        if($res){
            return redirect( "Goods/proinfo/$id");
        }
    }


}
