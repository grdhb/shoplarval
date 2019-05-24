<style>
      .a {
            border: 1px solid #F00;
            height: 13vh;

      }

      .b {
            background-color: gray;
            color: #636b6f;
            height: 13vh;
            margin: 0;
            border: 1px solid #F00
      }
</style>
@extends('layouts.shop')
@section('title', '滑板商品')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
<div class="head-mid">
      <h1>产品详情</h1>
</div>
</header>
<div id="sliderA" class="slider">
      <img src="{{config('app.goods_img')}}{{$res->goods_img}}" />
      <img src="{{config('app.goods_img')}}{{$res->goods_img}}" />
      <img src="{{config('app.goods_img')}}{{$res->goods_img}}" />
      <img src="{{config('app.goods_img')}}{{$res->goods_img}}" />
      <img src="{{config('app.goods_img')}}{{$res->goods_img}}" />
</div>
<!--sliderA/-->
<table class="jia-len">
      <tr goods_number="{{$res->goods_number}}" goods_id="{{$res->goods_id}}" id="cc">
            <th><strong class="orange">价格:{{$res->shop_price}}</strong></th>
            <th><strong class="orange"></strong></th>
            <td>
            <td align="center">
                  <div class="c_num">
                        <input type="button" value="-" class="car_btn_1 " />
                        <input type="text" value="1" style="width:40px;" name="" class="car_ipt buy_number" />
                        <input type="button" value="＋" class="car_btn_2" />
                  </div>
            </td>
            </td>
            </td>
      </tr>
      <tr>
            <td>
                  <strong>{{$res->goods_name}}</strong>
                  <p class="hui">{{$res->description}}</p>
            </td>
            <td align="right">
                  <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty"></span></a>
            </td>
      </tr>
</table>
<div class="height2"></div>
<h3 class="proTitle">商品规格</h3>
<ul class="guige">
      <li class="guigeCur"><a href="javascript:;">50ML</a></li>
      <li><a href="javascript:;">100ML</a></li>
      <li><a href="javascript:;">150ML</a></li>
      <li><a href="javascript:;">200ML</a></li>
      <li><a href="javascript:;">300ML</a></li>
      <div class="clearfix"></div>
</ul>
<!--guige/-->
<div class="height2"></div>
<div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style="background:none;">订购列表</a>
      <div class="clearfix"></div>
</div>
<!--zhaieq/-->
<div class="proinfoList">
      <img src="{{config('app.goods_img')}}{{$res->goods_img}}" width="600" height="200" />
</div>
<!--proinfoList/-->
<div class="proinfoList">
      暂无信息....
</div>
<!--proinfoList/-->
<div class="proinfoList">
      暂无信息......
</div>
<div id='con'>
      @foreach ($data as $v)
      <div class='b'>
            <p>用户评论</p>
            <table>
                  <tr>
                        <td>{{$v->name}}|</td>
                        <td>几星|</td>
                        <td>时间:</td>
                  </tr>
                  <tr>
                        <td>{{$v->nr}}</td>
                        <td>{{$v->is_pj}}星</td>

                        <td style="float:right">{{$v->created_at}}</td>

                  </tr>
            </table>
      </div>

      @endforeach
      {{ $data->appends(['data'=>$data])->links() }}
</div>
<div class='a'>
      <form action="{{url('Goods/add')}}/{{$res->goods_id}}" metch='post'>
            @csrf
            <table>
                  <tr>
                        <td>用户名:</td>
                        <td>E-mail:</td>
                        <td>评价等级:</td>
                        <td>评论内容</td>
                  </tr>
                  <tr>
                        <td>{{session('emailname')}}</td>
                        <td><input type="email" name='email'></td>
                        <td>
                              <input type="radio" value='1' name='is_pj'>1级
                              <input type="radio" value='2' name='is_pj'>2级
                              <input type="radio" value='3' name='is_pj'>3级
                              <input type="radio" value='4' name='is_pj'>4级
                              <input type="radio" value='5' name='is_pj'>5级

                        </td>
                        <td><textarea name="nr" id="" width='500'></textarea>
                              <input type="submit" value='提交评论'>

                        </td>
                  </tr>

            </table>
      </form>
</div>
<!--proinfoList/-->
<table class="jrgwc">
      <tr>
            <th>
                  <a href="index.html"><span class="glyphicon glyphicon-home"></span></a>
            </th>
            <td><a href="#" id='dd'>加入购物车</a></td>
      </tr>
</table>
</div>
<!--maincont-->
<script>
      $(function() {
            //无刷新分页
            $(document).on('click', '.pagination a', function() {
                  var url = $(this).attr('href');
                  $.get(
                        url,
                        function(data) {
                              $('#con').html(data);
                        }
                  )
                  return false;
            })
            //点击加号
            $('.car_btn_2').click(function() {
                  var _this = $(this);
                  //获取输入框的值，用parseInt转化为整型
                  var buy_number = parseInt(_this.prev('input').val());
                  //获取库存
                  var goods_number = _this.parents('tr').attr('goods_number');
                  if (buy_number >= goods_number) {
                        _this.prop('disabled', true);
                  } else {
                        buy_number += 1;
                        _this.prev('input').val(buy_number);
                        $('.car_btn_1').prop('disabled', false);

                  }

            })
            //点击减号
            $('.car_btn_1').click(function() {
                  var _this = $(this);
                  //获取输入框的值，用parseInt转化为整型
                  var buy_number = parseInt(_this.next('input').val());
                  //获取库存
                  var goods_number = _this.parents('tr').attr('goods_number');
                  if (buy_number <= 1) {
                        _this.prop('disabled', true);
                  } else {
                        buy_number -= 1;
                        _this.next('input').val(buy_number);
                        $('.car_btn_2').prop('disabled', false);
                  }



            })
            //失去焦点
            $('.buy_number').blur(function() {
                  var _this = $(this);
                  var buy_number = _this.val();
                  var goods_number = _this.parents('tr').attr('goods_number');
                  var res = /^\d{1,}$/;
                  if (buy_number == '' || buy_number <= 1 || !res.test(buy_number)) {
                        _this.val(1);
                  } else if (parseInt(buy_number) >= parseInt(goods_number)) {
                        _this.val(goods_number);
                  } else {
                        _this.val(buy_number);
                  }

            })
            $('#dd').click(function() {
                  var _this = $(this);
                  var goods_id = $('#cc').attr('goods_id');
                  var buy_number = $('.buy_number').val();
                  $.ajaxSetup({
                        headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });
                  $.post(
                        "/Pay/paysadd", {
                              goods_id: goods_id,
                              buy_number: buy_number
                        },
                        function(msg) {
                              if (msg.code == 1) {
                                    alert('加入成功'), location.href = "/Pay/index";
                              } else if (msg.code == 2) {
                                    alert('超出库存');
                              } else {
                                    alert('加入购物车失败');
                              }
                        }
                  )
            })
      })
</script>
@endsection