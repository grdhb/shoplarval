@extends('layouts.shop')

@section('title', '阿栋滑板公司用户注册')
@section('content')
<a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
<div class="head-mid">
      <h1>会员注册</h1>
</div>
</header>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="head-top">
      <img src="/index/images/head.jpg" />
</div>
<!--head-top/-->

<h3>已经有账号了？点此<a class="orange" href="login.html">登陆</a></h3>
<div class="lrBox">
      <div class="lrList"><input type="text" placeholder="输入手机号码或者邮箱号" name='email' id='email' /></div>
      <div class="lrList2"><input type="text" placeholder="输入短信验证码" name='yzm' id='yzm' /> <button class='yzm'>获取验证码</button></div>
      <div class="lrList"><input type="text" placeholder="设置新密码（6-18位数字或字母）" name='pwd' id='pwd' /></div>
      <div class="lrList"><input type="text" placeholder="再次输入密码" name='pwds' id='pwds' /></div>
</div>
<!--lrBox/-->
<div class="lrSub">
      <input type="button" value="立即注册" class='button'>
</div>

<!--reg-login/-->
@include('public/foot')
<div class="clearfix"></div>

<script>
      $(function() {
            $('.yzm').click(function() {
                  //秒数倒计时
                  var email = $('#email').val();
                  var _this = $(this);
                  tel();
                  _this.text(60 + 's');
                  setI = setInterval(timeLess, 1000);
                  $.ajaxSetup({
                        headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });
                  $.post("yzm", {
                              email: email,
                        },
                        function(data) {
                              if (data['code'] == 1) {
                                    $('#pwd').prev('font').remove();
                                    $('#pwd').before("<font>" + data['msg'] + "</font>");
                              }
                        });


            })
            //时间
            function timeLess() {
                  var _time = parseInt($('.yzm').text());
                  if (_time <= 0) {
                        $('.yzm').text("获取验证码");
                        $('.yzm').css('pointerEvents', 'auto');
                  } else {
                        _time = _time - 1;
                        $('.yzm').text(_time + 's');
                        $('.yzm').css('pointerEvents', 'none');


                  }
            }
            $('.button').click(function() {
                  //手机号
                  var tel_flag = tel();;
                  if (!tel_flag) {
                        return tel_flag;
                  }

                  //判断验证码
                  var yzm_flag = yzms();
                  if (!yzm_flag) {
                        return yzm_flag;
                  }
                  //密码
                  var pwdd = pwds();
                  if (!pwdd) {
                        return pwdd;
                  }
                  //确认密码
                  var dess = desc();
                  if (!dess) {
                        return pwdds;
                  }
                  //确认密码
                  var email = $('#email').val();
                  var yzm = $('#yzm').val();
                  var pwd = $('#pwd').val();
                  $.post("addo", {
                              email: email,
                              yzm: yzm,
                              pwd: pwd,
                        },
                        function(data) {
                              if (data['code'] == 1) {
                                    alert(data['msg']), location.href = '/Login/index';
                              } else {
                                    alert(data['msg']);
                              }
                        })
            })
            //验证手机号格式
            function tel() {
                  var email = $('#email').val();
                  //验证手机号或者邮箱
                  var tel = /^\d{11}$/;
                  var _email = /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/;
                  if (!email) {
                        $('#email').next('font').remove();
                        $('#email').after('<font>用户名不能为空</font>');
                        return false;
                  }
                  if (!tel.test(email) && !_email.test(email)) {
                        $('#email').next('font').remove();
                        $('#email').after('<font>用户名不符合规则请输入正确的手机号或邮箱</font>');
                        return false;
                  }
                  $.ajaxSetup({
                        headers: {
                              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });
                  var name_file = false;
                  $.ajax({
                        method: "POST",
                        url: "ajax",
                        async: false,
                        data: {
                              email: email
                        }
                  }).done(function(msg) {
                        if (msg['code'] == 1) {
                              $('#email').next('font').remove();
                              $('#email').after("<font>" + msg['msg'] + "</font>");
                              name_file = false;
                        } else {
                              $('#email').next('font').remove();
                              $('#email').after("<font>" + msg['msg'] + "</font>");
                              name_file = true;
                        }
                  });
                  if (name_file != true) {
                        return name_file;
                  } else {
                        return true;
                  }
            }

            function yzms() {
                  var yzm = $('#yzm').val();
                  var yzm_file = false;
                  if (!yzm) {
                        $('#pwd').prev('font').remove();
                        $('#pwd').before("<font>验证码不能为空</font>");
                        yzm_file = false
                  } else {
                        yzm_file = true;
                  }
                  if (yzm_file != true) {
                        return yzm_file;
                  } else {
                        return true;
                  }
            }
            //验证密码
            function pwds() {
                  var pwd_file = false;
                  var pwd = $('#pwd').val();
                  if (!pwd) {
                        $('#pwd').next('font').remove();
                        $('#pwd').after('<font>密码不能为空</font>');
                        pwd_file = false;
                  } else {
                        pwd_file = true;
                  }
                  if (pwd_file != true) {
                        return pwd_file;
                  } else {
                        return true;
                  }
            }
            //确认密码
            function desc() {
                  var pwds_file = false;
                  var pwd = $('#pwd').val();
                  var pwds = $('#pwds').val();
                  if (!pwds) {
                        $('#pwds').next('font').remove();
                        $('#pwds').after('<font>确认密码不能为空</font>');
                        pwds_file = false;
                  } else if (pwds != pwd) {
                        $('#pwds').next('font').remove();
                        $('#pwds').after('<font>俩次密码不一致</font>');
                        pwds_file = false;
                  } else {
                        pwds_file = true;
                  }
                  if (pwds_file != true) {
                        return pwds_file;
                  } else {
                        return true;
                  }

            }

      })
</script>
@endsection