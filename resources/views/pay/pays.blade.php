@extends('layouts.shop')
@section('title', '商品结算')
@section('content')
<header>
     <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
     <div class="head-mid">
          <h1>购物车</h1>
     </div>
</header>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="head-top">
     <img src="/index/images/head.jpg" />
</div>
<!--head-top/-->

<div class="dingdanlist">
     @if($resInfo)
     <tr>
          <div style="border:1px solid gray;height:130px; overflow: auto">
               @foreach ($resInfo as $k => $v)
               <table border="0" class="peo_tab" style="width:1110px;" cellspacing="0" cellpadding="0">
                    <tr>
                         <td rowspan="2"><input type="radio" name="address_id" value="{{$v->address_id}}" class='cc' @if($v->is_defaut == 1) checked @endif ></td>
                         <td class="p_td" width="160">收货人姓名</td>
                         <td width="395">{{$v->address_name}}</td>
                         <td class="p_td" width="160">电话</td>
                         <td width="395">{{$v->address_tel}}</td>
                    </tr>
                    <tr>
                         <td class="p_td">详细信息</td>
                         <td>{{$v->province}}{{$v->city}}{{$v->address_detail}}</td>
                         <td class="p_td">邮政编码</td>
                         <td>{{$v->address_mail}}</td>
                    </tr>

               </table>
               @endforeach
          </div>
     </tr>
     @else
     <a href="{{url('User/addls')}}">没有货地址,请添加一个收货地址</a>
     @endif
     <a href="{{url('User/addls')}}">选择收货地址</a>
     <table>

          <tr>
               <td colspan="3" style="height:10px; background:#efefef;padding:0;"></td>
          </tr>
          <tr>
               <td class="dingimg" width="75%" colspan="2">选择收货时间</td>
               <td align="right"><img src="/index/images/jian-new.png" /></td>
          </tr>
          <tr>
               <td colspan="3" style="height:10px; background:#efefef;padding:0;"></td>
          </tr>
          <tr>
               <td class="dingimg" width="75%" colspan="2">支付方式</td>
               <td align="right"><span class="hui">网上支付</span></td>
          </tr>
          <tr>
               <td colspan="3" style="height:10px; background:#efefef;padding:0;"></td>
          </tr>
          <tr>
               <td class="dingimg" width="75%" colspan="2">优惠券</td>
               <td align="right"><span class="hui">无</span></td>
          </tr>
          <tr>
               <td colspan="3" style="height:10px; background:#efefef;padding:0;"></td>
          </tr>
          <tr>
               <td class="dingimg" width="75%" colspan="2">是否需要开发票</td>
               <td align="right"><a href="javascript:;" class="orange">是</a> &nbsp; <a href="javascript:;">否</a></td>
          </tr>
          <tr>
               <td class="dingimg" width="75%" colspan="2">发票抬头</td>
               <td align="right"><span class="hui">个人</span></td>
          </tr>
          <tr>
               <td class="dingimg" width="75%" colspan="2">发票内容</td>
               <td align="right"><a href="javascript:;" class="hui">请选择发票内容</a></td>
          </tr>
          <tr>
               <td colspan="3" style="height:10px; background:#fff;padding:0;"></td>
          </tr>
          <tr>
               <td class="dingimg" width="75%" colspan="3">商品清单</td>
          </tr>
          @foreach ($data as $v)
          <tr goods_id="{{$v->goods_id}}" class='id'>
               <td class="dingimg" width="15%"><img src="{{config('app.goods_img')}}{{$v->goods_img}}" /></td>
               <td width="50%">
                    <h3>{{$v->goods_name}}</h3>
                    <time>下单时间：{{$v->created_at}}</time>
               </td>
               <td align="right"><span class="qingdan">X {{$v->buy_number}}</span></td>
          </tr>
          <tr>
               <th colspan="3"><strong class="orange">¥{{$v->buy_number*$v->shop_price}}</strong></th>
          </tr>

          <tr>
               <td class="dingimg" width="75%" colspan="2">商品金额</td>
               <td align="right"><strong class="orange">¥{{$v->shop_price}}</strong></td>
          </tr>
          @endforeach
          <tr>
               <td class="dingimg" width="75%" colspan="2">折扣优惠</td>
               <td align="right"><strong class="green">¥0.00</strong></td>
          </tr>
          <tr>
               <td class="dingimg" width="75%" colspan="2">抵扣金额</td>
               <td align="right"><strong class="green">¥0.00</strong></td>
          </tr>
          <tr>

               <td class="dingimg" width="75%" colspan="2">总计：</td>
               <td align="right"><strong class="orange">¥{{$count}}</strong></td>
          </tr>
     </table>
</div>
<!--dingdanlist/-->


</div>
<!--content/-->

<div class="height1"></div>
<div class="gwcpiao">
     <table>
          <tr>
               <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>

               <td width="40%"><a href="#" class="jiesuan" id='dd'>提交订单</a></td>
          </tr>
     </table>
</div>
<!--gwcpiao/-->
</div>
<!--maincont-->

<script>
     $(function() {
          $('#dd').click(function() {
               //获取收货地址di
               var address_id = $('input[type=radio]:checked').val();
               //获取商品id
               var goods_id = '';
               $('.id').each(function(index) {
                    goods_id += $(this).attr('goods_id') + ',';
               })
               $.ajaxSetup({
                    headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
               });
               $.post(
                    "/Success/swInfo", {
                         address_id: address_id,
                         goods_id: goods_id
                    },
                    function(msg) {
                         if (msg['code'] == 1) {
                              alert(msg['msg']), location.href = "/Success/index";
                         } else {
                              alert(msg['msg']);
                         }
                    }
               )
          })
     })
</script>
@endsection