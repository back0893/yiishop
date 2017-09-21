$().ready(function() {
    $("#loginForm").validate({
        rules:{
            username:{
                required:true,
                minlength:3,
                maxlength:20
            },
            checkcode:{
                required:true,
                validateCaptcha:true
            }
        },
        messages:{
            username:{
                required:'请输入帐号',
                minlength:'帐号不能少于3位',
                maxlength:'帐号不能多于20位'
            }
        },
        errorElement:'em'
    });
    getCaptcha();
    function getCaptcha(){
        $.getJSON('/site/captcha.html',{refresh:1},function(data){
            $('#captcha').attr('src',data.url).data('hash',data.hash1);
        })
    }
    $.validator.addMethod('validateCaptcha',function (value,element,param) {
        for(var i=value.length-1,h=0;i>=0;i--){
            h+=value[i].charCodeAt();
        }
        return this.optional(element) || (h==$('#captcha').data('hash'))
    },'请输入正确的验证码');
    $('#change_captcha').click(function () {
        getCaptcha();
    });
    $('#captcha').click(function(){
        getCaptcha();
    })
});