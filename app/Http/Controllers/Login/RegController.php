<?php

namespace App\Http\Controllers\Login;

use Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Reg;

class RegController extends Controller
{
    //用户注册
    public function index()
    {
        return view('login/reg');
    }
    //验证用户唯一
    public function ajax(Request $request)
    {
        $email = $request->email;
        if ($email) {
            $res = Reg::where(['email' => $email])->count();
        }
        if ($res == 1) {
            return ['code' => 1, 'msg' => '用户名已存在'];
        } else {
            return ['code' => 0, 'msg' => '可以使用'];
        }
    }
    //手机或者邮箱发送验证码
    public function yzm(Request $request)
    {
        $email = $request->email;
        if (is_numeric($email)) {
            //手机
            $rand = rand(10000, 99999);
            $where = [
                'email' => $email,
                'rand' => $rand,
            ];
            $res = $this->tel($rand, $email);
            if ($res == 00000) {
                $request->session()->forget('name');
                session(['name' => $where]);
                return ['code' => 1, 'msg' => '手机验证码发送成功'];
            } else {
                return ['code' => 0, 'msg' => '手机验证码发送失败'];
            }
        } else {
        //邮箱
            $rand = rand(10000, 99999);
            $where = [
                'email' => $email,
                'rand' => $rand,
            ];
            $res = $this->send($email, $rand);
            if (!$res) {
                $request->session()->forget('email');
                session(['email' => $where]);
                return ['code' => 1, 'msg' => '邮箱验证码发送成功'];
            } else {
                return ['code' => 0, 'msg' => '邮箱验证码发送失败'];
            }
        }
    }
    //邮箱发送验证码
    public function send($email, $rand)
    {
        \Mail::raw('验证码为' . $rand, function ($message) use ($email) {
            //设置主题
            $message->subject("您的验证码为");
            //设置接收方
            $message->to($email);
        });
    }
    //手机号发送验证码
    public function tel($rand, $email)
    {
        $host = "http://dingxin.market.alicloudapi.com";
        $path = "/dx/sendSms";
        $method = "POST";
        $appcode = "855fdefe3e3e4f378d6d33ad706ba84c";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "mobile=$email&param=code%3A$rand&tpl_id=TP1711063";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $curl = json_decode(curl_exec($curl), true);
        return $curl['return_code'];
    }
    //  //验证验证码是否匹配
     public function yzmadd($request,$email,$yzm)
     {
         if (is_numeric($email)) {
             //手机号
             //取出手机号的session
             $email = $request->email;
             $yzm = $request->yzm;
             $name = session('name');
             if ($email == $name['email'] && $yzm == $name['rand']) {
                 return ['code' => 1];
             } else {
                 return ['code' => 0, 'msg' => '验证码不匹配'];
             }
         } else {
             //邮箱
             $email = $request->email;
             $yzm = $request->yzm;
             $name = session('email');
             if ($email == $name['email'] && $yzm == $name['rand']) {
                 return ['code' => 1];
             } else {
                 return ['code' => 0, 'msg' => '验证码不匹配'];
             }
         }
     }
    //添加
    public function addo(Request $request){

        $email=$request->email;
        $yzm=$request->yzm;
        if (is_numeric($email)) {
            //手机号
            //取出手机号的session
            $email = $request->email;
            $yzm = $request->yzm;
            $name = session('name');
            if ($email == $name['email'] && $yzm == $name['rand']) {
                $pwd=$request->pwd;
                $pwd=md5($pwd);
                $model = new Reg;
                $model->email = $email;
                $model->yzm = $yzm;
                $model->pwd = $pwd;
                $res=$model->save();
                if($res){
                    return ['code'=>1,'添加成功'];
                }
            } else {
                return ['code' => 0, 'msg' => '验证码不匹配'];
            }
        } else {
            //邮箱
            $email = $request->email;
            $yzm = $request->yzm;
            $name = session('email');
            if ($email == $name['email'] && $yzm == $name['rand']) {
                $pwd=$request->pwd;
                $pwd=md5($pwd);
                $model = new Reg;
                $model->email = $email;
                $model->yzm = $yzm;
                $model->pwd = $pwd;
                $res=$model->save();
                if($res){
                    return ['code'=>1,'msg'=>'添加成功'];
                }
            } else {
                return ['code' => 0, 'msg' => '验证码不匹配'];
            }
        }

    }
}
