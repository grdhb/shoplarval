<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Model\Cart;

class PayController extends Controller
{
     //加入购物车
     public  function paysadd(Request $request)
     {
          $user = session('user');
          $post = $request->all();
          $post['id'] = $user;
          $models = new Cart;
          foreach ($post as $k => $v) {
               $models->$k = $v;
          }
          $where = [
               'goods_id' => $models['goods_id'],
               'id' => $models['id'],
               'is_del' => 1,
          ];
          $cartInfo = Cart::where($where)->first();
          if ($cartInfo != '') {
               //累加
               //根据商品id去查询库存
               $res = $this->cartKC($models->goods_id, $models->buy_number, $cartInfo->buy_number);
               if ($res) {
                    $where1 = [
                         'buy_number' => $models->buy_number + $cartInfo->buy_number
                    ];
                    $cartInfos = $models->where($where)->update($where1);
                    $models->buy_number =$models->buy_number+$cartInfo->buy_number;
                    if ($cartInfos) {
                         return ['code' => 1, 'msg' => '加入购物车成功'];
                    } else {
                         return ['code' => 0, 'msg' => '加入购物车失败'];
                    }
               } else {
                    return ['code' => 2, 'msg' => '超出库存'];
               }
          } else {
               //根据商品id去查询库存
               $res = $this->cartKC($models->goods_id, $models->buy_number, 0);
               if ($res == true) {
                    $cartInfos = $models->save();
                    if ($cartInfos) {
                         return ['code' => 1, 'msg' => '加入购物车成功'];
                    } else {
                         return ['code' => 0, 'msg' => '加入购物车失败'];
                    }
               } else {
                    return ['code' => 2, 'msg' => '超出库存'];
               }
          }
     }
     //检测库存
     public function cartKC($goods_id, $buy_number, $num)
     {
          $where = [
               'goods_id' => $goods_id
          ];
          $goods_number = DB::table('goods')->where($where)->value('goods_number');
          if ($buy_number + $num > $goods_number) {
               return false;
          } else {
               return true;
          }
     }
     //购物车页面
     public function index()
     {
          //用户id
          $id = session('user');
          $where = [
               'id' => $id,
          ];
          //查询商品表
          $data = DB::table('cart as c')
               ->leftJoin('goods as g', 'c.goods_id', '=', 'g.goods_id')
               ->where($where)
               ->where('is_del', 1)
               ->get();
          if ($data) {
               foreach ($data as $k => $v) {
                    $total = $v->shop_price * $v->buy_number;
                    $data[$k]->total = $total;
               }
          }

          $res = DB::table('cart')->where($where)->count();
          return view('pay/car', ['data' => $data, 'res' => $res]);
     }
     //购物车页面极点既改
     public function changeBuyNumber(Request $request)
     {
          $goods_id = $request->goods_id;
          $buy_number = $request->buy_number;
          $where = [
               'goods_id' => $goods_id
          ];
          $updateInfo = [
               'buy_number' => $buy_number
          ];
          //检测库存
          $cartInfo = $this->cartKC($goods_id, $buy_number, 0);
          if ($cartInfo) {
               $res = DB::table('cart')->where($where)->update($updateInfo);
          } else {
               return ['code' => 1];
          }
     }

     //购物车总价
     public function countTotal(Request $request)
     {
          $goods_id = $request->goods_id;
          $goods_id = explode(',', $goods_id);
          //提取用户id
          $id = session('user');
          $where = [
               'id' => $id,
          ];
          //根据用户id去商品表查询价格和购买数量
          $data = DB::table('cart as c')
               ->select('buy_number', 'shop_price', 'c.goods_id')
               ->join('goods as g', 'c.goods_id', '=', 'g.goods_id')
               ->where($where)
               ->whereIn('c.goods_id', $goods_id)
               ->get();
          $count = 0;
          foreach ($data as $k => $v) {
               $count += $v->buy_number * $v->shop_price;
          }
          echo  $count;
     }
     //购物车小计
     public function xj(Request $request)
     {
          $goods_id = $request->goods_id;
          $where = [
               'goods_id' => $goods_id
          ];
          $shop_price = DB::table('goods')->where($where)->value('shop_price');
          $user = session('user');
          $userWhere = [
               'id' => $user,
               'goods_id' => $goods_id
          ];
          $buy_number = DB::table('cart')->where($userWhere)->value('buy_number');
          $xj = $buy_number * $shop_price;
          echo $xj;
     }
     //删除
     public function del(Request $request)
     {

          $goods_id = $request->goods_id;
          if ($goods_id == '') {
               return ['code' => 1, 'msg' => '请选择一个商品'];
               exit();
          }
          $goods_id = explode(',', $goods_id);
          $res = DB::table('cart')
               ->whereIn('goods_id', $goods_id)
               ->delete();
          if ($res) {
               return ['code' => 1, 'msg' => '删除成功'];
          }
     }
     //购物车结算
     public function pay(Request $request)
     {
          $goods_id = $request->goods_id;
          if($goods_id==''){

          }
          $goods_id = explode(',', $goods_id);
          $id = session('user');
          $where = [
               'id' => $id,
               'is_del'=>1
          ];
          //根据用户id去商品表查询价格和购买数量
          $data = DB::table('cart as c')
               ->join('goods as g', 'c.goods_id', '=', 'g.goods_id')
               ->where($where)
               ->whereIn('c.goods_id', $goods_id)
               ->get();
          //总价
          $count = 0;
          foreach ($data as $k => $v) {
               $count += $v->buy_number * $v->shop_price;
          }
          //查询默认地址
          $resInfo = DB::table('address')->where($where)->get()->toArray();
          foreach ($resInfo as $k => $v) {
               $resInfo[$k]->province = DB::table('area')->where('id', $v->province)->value('name');
               $resInfo[$k]->city = DB::table('area')->where('id', $v->city)->value('name');
               $resInfo[$k]->area = DB::table('area')->where('id', $v->area)->value('name');
          }
          return view('pay/pays', ['data' => $data, 'count' => $count, 'resInfo' => $resInfo]);
     }
}
