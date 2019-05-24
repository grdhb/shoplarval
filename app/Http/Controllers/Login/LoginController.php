<?php

namespace App\Http\Controllers\Login;

use App\Model\Login;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //用户登入
    public function index(Request $request)
    {
        return view('login/login');
    }
    //登入判断
    public function login(Request $request)
    {
        $email = $request->email;
        $pwd = $request->pwd;
        $pwd = md5($pwd);
        $where = [
            'email' => $email,
            'pwd' => $pwd,
        ];
        $res = Login::where($where)->first();

        if ($res) {
            if ($request->ajax()) {

                session(['user' => $res->id]);
                session(['emailname' => $res->email]);
                return ['code' => 1, 'msg' => '登入成功'];
            } else {
                return view('login/login');
            }
        } else {
            if ($request->ajax()) {
                return ['code' => 0, 'msg' => '账号密码错误'];
            } else{
                return view('login/login');

            }
        }
    }
}
