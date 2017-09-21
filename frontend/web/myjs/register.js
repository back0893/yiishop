$().ready(function() {
// 在键盘按下并释放及提交后验证提交表单
    $("#signupForm").validate({
        rules: {
            username: {
                required: true,
                minlength: 3,
                maxlength:20,
                remote:{
                    url:'validate.html',
                    type:'post',
                    dataType:'json',
                    data:{
                        username:function () {
                            return $('[name=username]').val()
                        },
                        '_csrf-frontend':token
                    }
                }
            },
            checkcode:{
                validateCheckCode:true
            },
            password:{
                required:true,
                minlength:6,
                maxlength:20
            },
            repassword:{
                required:true,
                equalTo:'#password'
            },
            email:{
                required:true,
                email:true,
                remote:{
                    url:'validate.html',
                    type:'post',
                    dataType:'json',
                    data:{
                        '_csrf-frontend':token
                    }
                }
            },
            code:{
              required:true,
              remote:{
                  url:check_sms,
                  type:'post',
                  dataType:'json',
                  data:{
                      tel:function () {
                          return  $('[name=tel]').val()
                      },
                      '_csrf-frontend':token
                  }
              }
            },
            tel:{
                required:true,
                validateTel:true,
                remote:{
                    url:'validate.html',
                    type:'post',
                    dataType:'json',
                    data:{
                        '_csrf-frontend':token
                    }
                }
            }
        },
        messages:{
            username:{
                requried:'注册的用户名是必须填写的',
                minlength:'用户名长度最少为3位字符',
                maxlength:'用户名长度最多为20位字符',
                remote:'这个用户名已经被使用了'
            },
            password:{
                required:'请输入密码',
                minlength:"密码最少长度为6位",
                maxlength:"密码最长长度为20位"
            },
            repassword:{
                equalTo:'2次密码不一样'
            },
            tel:{
                remote:'手机号已经被使用了'
            },
            email:{
                remote:'邮箱已经被使用了'
            },
            code:{
                remote:"验证码错误"
            }
        },
        errorElement:'em'
    });
    showCaptcha();
    function showCaptcha() {
        $.getJSON(get_captcha,{refresh:1},function(data){
            $('#captcha_img').attr('src',data.url).data('hash',data.hash1);
        });
    }
    $('#change_captcha').click(function () {
        showCaptcha();
    });

    $.validator.addMethod('validateCheckCode',function (value,element,param) {
        for(var i=value.length-1,h=0;i>=0;i--){
            h+=value[i].charCodeAt();
        }
        return this.optional(element) || (h==$('#captcha_img').data('hash'));
    },"请输入正确的验证码");

    $.validator.addMethod('validateTel',function (value,element,param) {
        return this.optional(element) || (value.length===11);
    },"请输入正确的手机号");
    $('#get_captcha').click(function (event) {
        //启动ajax,发动tel到到后台发送短信
        $.post(send_sms,{tel:$('input[name=tel]').val(),'_csrf-frontend':token},function (data) {
                data=JSON.parse(data);
                if(data.error){
                    // $('input[name=tel]').
                }
        });
        var self=$(this);
        //禁止点击按钮
        self.prop('disabled',true);
        //开启输入框
        var time=10;
        //启动一个计时事件
        var interval = setInterval(function(){
            time--;
            if(time<=0){
                //时间小于0,就结束事件并还原初始
                clearInterval(interval);
                var html = '获取验证码';
                self.prop('disabled',false);
            } else{
                //显示倒计时
                var html = time + ' 秒后再次获取';
            }
            self.val(html);
        },1000);
    });
});