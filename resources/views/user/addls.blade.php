@extends('layouts.shop')
@section('title', '购物车')
@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>收货地址</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/index/images/head.jpg" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><a href="{{url('User/address')}}" class="hui"><strong class="">+</strong> 新增收货地址</a></td>
       <td width="25%" align="center" style="background:#fff url(/index/images/xian.jpg) left center no-repeat;"><a href="javascript:;" class="orange">删除信息</a></td>
      </tr>
     </table>

     <div class="dingdanlist" onClick="window.location.href='proinfo.html'">
     @foreach ($res as $v)
      <table>
       
       <tr>
        <td width="50%">
         <h3>{{$v->address_name}}</h3>
         <time>{{$v->province}}/{{$v->city}}/{{$v->area}}</time>
        </td>
        <td align="right"><a href="address.html" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
       </tr>
      </table>
      @endforeach
     </div><!--dingdanlist/-->
      
     @include('public/foot')
      <div class="clearfix"></div>
      @endsection