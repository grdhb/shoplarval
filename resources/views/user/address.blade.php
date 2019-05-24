@extends('layouts.shop')
@section('title', '购物车')
@section('content')
<header>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
  <div class="head-mid">
    <h1>收货地址</h1>
  </div>
</header>
<div class="head-top">
  <img src="/index/images/head.jpg" />
</div>
<!--head-top/-->

<div class="lrBox">
  <div class="lrList"><input type="text" placeholder="收货人" id='address_name' /></div>
  <div class="lrList"><input type="text" placeholder="详细地址" id='address_detail' /></div>
  <div class="lrList">
    <select class='dd' id='province'>
      <option>省份/直辖市</option>
      @foreach ($addressInfo as $k=>$v)
      <option value="{{$v->id}}">{{$v->name}}</option>
      @endforeach
    </select>

    <select class='dd' id='city'>
      <option value='0'>--请选择--</option>
    </select>

    <select class='dd' id='area'>
      <option value='0'>--请选择--</option>
    </select>
  </div>
  <div class="lrList"><input type="text" placeholder="手机" id='address_tel' /></div>
  <div class="lrList2"><input type="checkbox" id="is_defaut"> <button>设为默认</button></div>
</div>
<!--lrBox/-->
<div class="lrSub">
  <input type="button" id='submit' value="保存" />
</div>

<!--reg-login/-->
@include('public/foot')
<div class="clearfix"></div>
<script>
  //三级联动
  $(document).on('change', '.dd', function() {
    var _this = $(this);
    //获取id
    var id = _this.val();
    //选了第一个让下的变成_option
    var _option = "<option value='0'>--请选择--</option>";
    _this.nextAll('select').html(_option);
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.post(
      "getArea", {
        id: id
      },
      function(res) {
        //i代表有几个记录就循环几次。循环出来一个id和name
        for (var i in res) {
          _option += "<option value='" + res[i]['id'] + "'>" + res[i]['name'] + "</option>"
          _this.next('select').html(_option);
        }
      }
    )
  })
  //添加
  $('#submit').click(function() {
    var obj = {};
    //省
    obj.province = $('#province').val();
    //市
    obj.city = $('#city').val();
    //县
    obj.area = $('#area').val();
    //姓名
    obj.address_name = $('#address_name').val();
    //地址
    obj.address_detail = $('#address_detail').val();
    //手机号
    obj.address_tel = $('#address_tel').val();
    //默认选中
    obj.is_defaut = $('#is_defaut').prop('checked');
    if (obj.is_defaut == true) {
      obj.is_defaut = 1;
    } else {
      obj.is_defaut = 2;
    }
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.post(
      "addressDo",
      obj,
      function(res) {
        if(res.code==1){
          alert(res['msg']);
        }
      },
    )
  })
</script>
@endsection