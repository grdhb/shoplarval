@extends('layouts.shop')
@section('title', '阿栋公司首页')
@section('content')
<div class="head-top">
     <img src="/index/images/head.jpg" />
     <dl>
          <dt><a href="user.html"><img src="/index/images/touxiang.jpg" /></a></dt>
          <dd>
               <h1 class="username">24K金赫闪光双眼阿栋公司的专属会员</h1>
               <ul>
                    <li><a href="prolist.html"><strong>34</strong>
                              <p>全部商品</p>
                         </a></li>
                    <li><a href="javascript:;"><span class="glyphicon glyphicon-star-empty"></span>
                              <p>收藏本店</p>
                         </a></li>
                    <li style="background:none;"><a href="javascript:;"><span class="glyphicon glyphicon-picture"></span>
                              <p>二维码</p>
                         </a></li>
                    <div class="clearfix"></div>
               </ul>
          </dd>
          <div class="clearfix"></div>
     </dl>
</div>
<!--head-top/-->
<form class="search">
     <input type="text" class="seaText fl" name="goods_name" value="{{$goods_name}}" />
     <input type="submit" value="搜索" class="seaSub fr" />
</form>
<!--search/-->
@if (session('emailname'))
<ul class="reg-login-click">
     <li><a href="{{url('User/index')}}">欢迎.{{session('emailname')}}.登入</a></li>
     <li><a href="{{url('Reg/index')}}" class="rlbg">注册</a></li>
     <div class="clearfix"></div>
</ul>
<!--reg-login-click/-->
@else
<ul class="reg-login-click">
     <li><a href="{{url('Login/index')}}">登录</a></li>
     <li><a href="{{url('Reg/index')}}" class="rlbg">注册</a></li>
     <div class="clearfix"></div>
</ul>
<!--reg-login-click/-->
@endif
<div id="sliderA" class="slider">
     <img src="/index/images/image1.jpg" />
     <img src="/index/images/image2.jpg" />
     <img src="/index/images/image3.jpg" />
     <img src="/index/images/image4.jpg" />
     <img src="/index/images/image5.jpg" />
</div>
<!--sliderA/-->
<ul class="pronav">
     <li><a href="prolist.html">晋恩干红</a></li>
     <li><a href="prolist.html">万能手链</a></li>
     <li><a href="prolist.html">高级手镯</a></li>
     <li><a href="prolist.html">特异戒指</a></li>
     <div class="clearfix"></div>
</ul>
<!--pronav/-->
<div class="index-pro1">
     @foreach ($res as $v)
     <div class="index-pro1-list">
          <dl>
               <dt><a href="{{url('Goods/proinfo')}}/{{$v->goods_id}}"><img src="{{config('app.goods_img')}}{{$v->goods_img}}" /></a></dt>
               <dd class="ip-text"><a href="{{url('Goods/proinfo')}}/{{$v->goods_id}}">{{$v->goods_name}}</a><span>已售：488</span></dd>
               <dd class="ip-price"><strong>¥{{$v->shop_price}}</strong> <span>¥{{$v->market_price}}</span></dd>
          </dl>
     </div>
     @endforeach
     <div class="clearfix"></div>
</div>
<!--index-pro1/-->
<div class="prolist">
     <dl>
          <dt><a href="{{url('Goods/proinfo')}}"><img src="/index/images/prolist1.jpg" width="100" height="100" /></a></dt>
          <dd>
               <h3><a href="{{url('Goods/proinfo')}}">四叶草</a></h3>
               <div class="prolist-price"><strong>¥299</strong> <span>¥599</span></div>
               <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
          </dd>
          <div class="clearfix"></div>
     </dl>
</div>
<!--prolist/-->
<div class="joins"><a href="fenxiao.html"><img src="/index/images/jrwm.jpg" /></a></div>
<div class="copyright">Copyright &copy; <span class="blue">这是就是阿栋滑板有限公司底部信息</span></div>

@include('public/foot')
<div class="clearfix"></div>
@endsection