<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class AddressController extends Controller
{
    //用户中心
    public function index()
    {
        return view('user/user');
    }
    //收货地址
    public function addls()
    {
        $id=session('user');
        $where=[
            'id'=>$id,
            'is_del'=>1
        ];
        $res=DB::table('address')->where($where)->get();
        foreach($res as $k=>$v){
            $res[$k]->province=DB::table('area')->where('id',$v->province)->value('name');
            $res[$k]->city=DB::table('area')->where('id',$v->city)->value('name');
            $res[$k]->area=DB::table('area')->where('id',$v->area)->value('name');
 
        }
        return view('user/addls',['res'=>$res]);
    }
    //添加收货地址收货页面
    public function address()
    {
        //查询收货地址信息
        $addressInfo = $this->getAddressInfo(0);

        return view('user/address', compact('addressInfo'));
    }
    //查询第一个收货地址
    public function getAddressInfo($id)
    {
        $where = [
            'pid' => $id
        ];
        //查询地址表,pid为0的
        $res = DB::table('area')->where($where)->get();
        // dd($res);
        return $res;
    }
    //三级联动
    public function getArea()
    {
        $id = request()->id;
        if (!$id) {
            return ['code' => 1, 'msg' => '请至少选择一个'];
        }
        $areaInfo = $this->getAddressInfo($id);

        return $areaInfo;
    }
    //添加收货地址执行
    public function addressDo(Request $request)
    {
        $post = $request->all();
        //用户id
        $id = session('user');
        $post['id']=$id;
        $resInfo = DB::table('address')->where(['id' => $id])->first();
        if ($resInfo) {
            if ($post['is_defaut'] == 1) {
                $where = [
                    'id' => $id
                ];
                $res1 = DB::table('address')->where($where)->update(['is_defaut'=>2]);
                if($res1){
                    $res=DB::table('address')->insert($post);
                    if($res){
                        return ['code'=>1,'msg'=>'添加成功'];
                    }
                }
            }
        }

    }
    //退出
    public function session(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}
