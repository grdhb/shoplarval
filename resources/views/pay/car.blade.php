@extends('layouts.shop')
@section('title', '滑板公司购物车')
@section('content')
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
<table class="shoucangtab">
    <tr>
        <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$res}}</strong>件商品</span></td>
        <td width="25%" align="center" style="background:#fff url(/index/images/xian.jpg) left center no-repeat;">
            <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
        </td>
    </tr>
</table>

<div class="dingdanlist">
    <table>
        <tr>
            <td width="100%" colspan="4"><a href="javascript:;"><input type="checkbox" name="1" class='qx' /> 全选</a></td>
        </tr>
    </table>
</div>
<!--dingdanlist/-->
<div class="dingdanlist">
    <table>
        @foreach ($data as $v)
        <tr goods_number="{{$v->goods_number}}" goods_id="{{$v->goods_id}}" id="cc">
            <td width="4%"><input type="checkbox" name="1" class='_box' /></td>
            <td class="dingimg" width="15%"><img src="{{config('app.goods_img')}}{{$v->goods_img}}" /></td>
            <td width="50%">
                <h3>{{$v->goods_name}}</h3>
                <time>下单时间:{{$v->updated_at}}</time>
            </td>
            <td colspan="4"><strong class="orange" id='xj'>¥{{$v->total}}</strong></td>
            <td align="center">
                <div class="c_num">
                    <input type="button" value="-" class="car_btn_1 " />
                    <input type="text" style="width:25px;" value="{{$v->buy_number}}" class="car_ipt buy_number" />
                    <input type="button" value="＋" class="car_btn_2" />
                </div>
            </td>
        </tr>
        <tr>
        </tr>
        @endforeach
        <tr>
            <td width="100%" colspan="4"><a href="javascript:;" class='del'> 删除</a></td>
        </tr>


    </table>
</div>
<!--dingdanlist/-->
<div class="height1"></div>
<div class="gwcpiao">
    <table>
        <tr>
            <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
            <td width="50%">总计：<strong class="orange" id='zj'>¥0</strong></td>
            <td width="40%"><a href="javascript:;" class="jiesuan">去结算</a></td>
        </tr>
    </table>
</div>
<!--gwcpiao/-->
<script>
    $(function() {
        //点击加号
        $('.car_btn_2').click(function() {
            var _this = $(this);
            //获取当前购买的数量
            var buy_number = parseInt(_this.prev('input').val());
            //获取库存
            var goods_number = $('#cc').attr('goods_number');
            //获取商品id
            var goods_id = _this.parents('tr').attr('goods_id');
            if (buy_number >= goods_number) {
                _this.prop('disabled', true);
            } else {
                buy_number += 1;
                _this.prev('input').val(buy_number);
                $('.car_btn_1').prop('disabled', false);
            }
            changeBuyNumber(goods_id, buy_number)
            //默认选中
            changeMoRen(_this);
            //总价
            conut();
            //小计
            xj(_this, goods_id)
        });
        //点击减号
        $('.car_btn_1').click(function() {
            var _this = $(this);
            //获取当前购买的数量
            var goods_id = _this.parents('tr').attr('goods_id');
            var buy_number = parseInt(_this.next('input').val());
            if (buy_number <= 1) {
                _this.prop('disabled', true);
            } else {
                buy_number -= 1;
                _this.next('input').val(buy_number);
                $('.car_btn_2').prop('disabled', false);
            }
            changeBuyNumber(goods_id, buy_number)
            //默认选中
            changeMoRen(_this)
            //总价
            conut();
            //小计
            xj(_this, goods_id)
        });
        //失去焦点
        $('.buy_number').blur(function() {
            var _this = $(this);
            var buy_number = _this.val();
            var goods_id = _this.parents('tr').attr('goods_id');
            var goods_number = $('#cc').attr('goods_number');
            var res = /^\d{1,}$/;
            if (buy_number == '' || buy_number <= 1 || !res.test(buy_number)) {
                _this.val(1);
            } else if (parseInt(buy_number) >= parseInt(goods_number)) {
                _this.val(goods_number);
            } else {
                _this.val(buy_number);
            }
            changeBuyNumber(goods_id, buy_number)
            //默认选中
            changeMoRen(_this)
            //总价
            conut();
            //小计
            xj(_this, goods_id)
        })
        //全选
        $('.qx').click(function() {
            var _this = $(this);
            var status = _this.prop('checked');
            // alert(status);
            $('._box').prop('checked', status);
            conut();
        })
        //删除
        $('.del').click(function() {
            var _box = $('._box');
            var goods_id = '';
            _box.each(function(index) {
                if ($(this).prop('checked') == true) {
                    goods_id += $(this).parents('tr').attr('goods_id') + ',';
                }
            })
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "del", {
                    goods_id: goods_id
                },
                function(res) {
                    if (res['code'] == 1) {
                        alert(res['msg']), location.href = "index";
                    } else {
                        alert(res['msg']);
                    }
                }
            )

        })
        //极点既改
        function changeBuyNumber(goods_id, buy_number) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "changeBuyNumber",
                method: 'post',
                data: {
                    goods_id: goods_id,
                    buy_number: buy_number
                },
                async: false,
                //同步
                success: function(res) {
                    //错误给出提示，正确不提示
                    if (res.code == 1) {
                        alert('超过库存');
                    }
                }

            });
        }
        //默认选中
        function changeMoRen(_this) {
            _this.parents('tr').find("input[type='checkbox']").prop('checked', true);
        }
        //点击获取价格
        $(document).on('click', '._box', function() {
            conut();
        })
        //总价
        function conut() {
            var _box = $('._box');
            var goods_id = '';
            _box.each(function(index) {
                if ($(this).prop('checked') == true) {
                    goods_id += $(this).parents('tr').attr('goods_id') + ',';
                }
            })
            goods_id = goods_id.substr(0, goods_id.length - 1);
            //吧商品id传给控制器 获取商品总价
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "countTotal", {
                    goods_id: goods_id
                },
                function(res) {
                    $('#zj').text(res);
                }
            )
        }
        //小计
        function xj(_this, goods_id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "xj", {
                    goods_id: goods_id,
                },
                function(res) {
                    // alert(res);
                    _this.parents('td').prev('td').text('¥' + res);
                }
            );
        }
        //点击去结算
        $('.jiesuan').click(function() {
            var _box = $('._box');
            var goods_id = '';
            _box.each(function(index){
                if($(this).prop('checked')==true){
                    goods_id+=$(this).parents('tr').attr('goods_id')+',';
                }
            })
            goods_id=goods_id.substr(0,goods_id.length-1);
            location.href = "{{url('Pay/pays')}}?goods_id=" + goods_id;


        })

    })
</script>
@endsection