<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


 Route::any('/', 'Goods\IndexController@index');

//用户登入
Route::prefix('Login')->group(function () {
    //用户登入
    Route::any('index', 'Login\LoginController@index');
    //登入
    Route::any('login', 'Login\LoginController@login');
});
//用户注册
Route::prefix('Reg')->group(function () {
    //用户注册
    Route::any('index', 'Login\RegController@index');
    //用户唯一性
    Route::any('ajax', 'Login\RegController@ajax');
    //验证码邮箱手机号
    Route::any('yzm', 'Login\RegController@yzm');
    //自己写的发送邮箱
    Route::any('email','Login\RegController@email');
    //验证码判断
    Route::any('yzmadd','Login\RegController@yzmadd');
    //添加
    Route::any('addo','Login\RegController@addo');

});
//商品
Route::prefix('Goods')->group(function () {
    //商品首页
    Route::any('index', 'Goods\IndexController@index');
    //所有商品
    Route::any('prolis', 'Goods\ProlistController@index');
    //商品详情
    Route::any('proinfo/{id}', 'Goods\ProinfoController@index');
    //商品评论
    Route::any('add/{id}', 'Goods\ProinfoController@add');
    //评论展示
    Route::any('list', 'Goods\ProinfoController@list');
});
// //购物车
Route::prefix('Pay')->group(function () {
    //购物车
    Route::any('index', 'Pay\PayController@index');
    //加入购物车
    Route::any('paysadd', 'Pay\PayController@paysadd');
    //极点既改
    Route::any('changeBuyNumber', 'Pay\PayController@changeBuyNumber');
    //购物车总价
    Route::any('countTotal', 'Pay\PayController@countTotal');
    //小计
    Route::any('xj', 'Pay\PayController@xj');
    //删除
    Route::any('del', 'Pay\PayController@del');
    //购物车结算
    Route::any('pays', 'Pay\PayController@pay');
    //点击更改默认地址
    Route::any('order', 'Pay\PayController@order');
});
//个人中心收货地址
Route::prefix('User')->middleware('CheckLogin')->group(function () {
    //个人中心
    Route::any('index', 'User\AddressController@index');
    //退出
    Route::any('session', 'User\AddressController@session');
    //收货地址
    Route::any('addls', 'User\AddressController@addls');
    //添加收货地址
    Route::any('address', 'User\AddressController@address');
    //三级联动
    Route::any('getArea', 'User\AddressController@getArea');
    //添加收货地址
    Route::any('addressDo', 'User\AddressController@addressDo');

});
//订单
Route::prefix('Success')->middleware('CheckLogin')->group(function () {
    //提交订单执行
    Route::any('tjdt', 'Success\SuccessController@tjdt');
    //订单成功页面
    Route::any('index', 'Success\SuccessController@index');
    //支付页面
    Route::any( 'pcpay', 'Success\SuccessController@pcpay');
    //同步
    Route::any( 'returnurl', 'Success\SuccessController@returnurl');
    //异步
    Route::any( 'notifyurl', 'Success\SuccessController@notifyurl');
    //事物
    Route::any( 'swInfo', 'Success\SuccessController@swInfo');



});


