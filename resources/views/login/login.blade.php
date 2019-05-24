@extends('layouts.shop')
@section('title', '滑板用户登入')
@section('content')
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>定陶0530会员登入</h1>
      </div>
     </header>
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <div class="head-top">
      <img src="/index/images/head.jpg" />
     </div><!--head-top/-->
     <form action="user.html" method="get" class="reg-login">
      <h3>还没有三级分销账号？点此<a class="orange" href="reg.html">注册</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" placeholder="输入手机号码或者邮箱号" name='email' id='email'/></div>
       <div class="lrList"><input type="password" placeholder="输入证码"  name='pwd'  id='pwd'/></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="button" value="立即登录" class='dd' />
      </div>
     </form><!--reg-login/-->
     @include('public/foot')
      <div class="clearfix"></div>

 <script>
      $('.dd').click(function(){
            var email=$('#email').val();
            var pwd=$('#pwd').val();
            if(!email){
                  $('#email').after('<font>用户名不能为空</font>');
                  return false;
            }
            if(!pwd){
                  $('#pwd').after('<font>密码不能为空</font>');
                  return false;
            }
            $.ajaxSetup({
                        headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });
            $.post(
                  "login",
                  {email:email,
                        pwd:pwd
                  },function(msg){
                        if(msg['code']==1){
                              alert(msg['msg']);
                              window.location.href="/";
                        }else{
                              alert(msg['msg']);
                        }
                  }
            ),"json"
            ;
      })


</script>
 @endsection