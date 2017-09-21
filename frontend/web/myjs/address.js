$().ready(function() {
    //城市的3级联动,省市先查询
    var province=$('#province');
    var city=$('#city');
    var town=$('#town');
    //一个返回异步ajax的返回,用来使用done的延迟对象
    function getArea(pid,Area){
        return $.getJSON(getinfo,{'parend_id':pid},function(data){
            var html='<option value="-1">请选择</option>';
            for(var i=0;i<data.length;i++){
                html+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                Area.html(html);
            }
        });
    }
    getArea(0,province);
    province.on('change',function (event) {
        var self=$(this);
        var pid=self.val();
        getArea(pid,city);
        town.html('<option value="-1">请选择</option>');
    });
    city.on('change',function (event) {
        var self=$(this);
        var pid=self.val();
        getArea(pid,town);
    });
    var hd=$('.address_hd');
    hd.find('dl:last').addClass('last');
    hd.on('click','.default_addr',function () {
        var dl=$(this).closest('dl');
        var id=dl.data('id');
        console.log(id);
        $.getJSON(de,{id:id},function (data){
            if(data.error==0){
                $('.address_hd').find('#default').text('设置为默认地址').attr('href','javascript:void(0)').removeAttr('id').addClass('default_addr');
                dl.find('a:last').text('默认地址').removeAttr('href').attr('id','default');
            }
        })
    });
    hd.on('click','.del',function () {
        var dl=$(this).closest('dl');
        var id=dl.data('id');
        $.getJSON(del,{id:id},function (data){
            if(data.error==0){
                dl.remove();
            }
        })
    });
    hd.on('click','.edit',function () {
        var dl=$(this).closest('dl');
        var id=dl.data('id');
        $.getJSON(edit,{id:id},function (data){
            if(!data.error){
                var address=data.address;
                $('input[name=name]').val(address.name);
                $('input[name=address]').val(address.address);
                $('input[name=tel]').val(address.tel);
                $('input[name=id]').val(address.id);
                if(address.status==1){
                    $('input[name=status]:last').val([1]);
                }else{
                    $('input[name=status]:last').val([]);
                }
                province.val([address.province]);
                //这里是done的延迟方法,属于一个异步状态,会检查执行的状态,成功会执行done,失败会执行fair,done只能是deferred对象才能调用,ajax就是一个子类,更多的看收藏
                getArea(address.province,city).done(function(){
                    city.val([address.city]);
                    getArea(address.city,town).done(function () {
                        town.val([address.town]);
                    })
                });
            }
        })
    });
    $("#address_form").validate({
        rules:{
            name:{
                required:true,
                minlength:2,
                maxlength:7
            },
            province:{
                validateArea:true
            },
            city:{
                validateArea:true
            },
            town:{
                validateArea:true
            },
            address:{
                required:true,
                minlength:4
            },
            tel:{
                minlength:11,
                maxlength:11
            }
        },
        messages:{
            name:{
                required:'请填写收件人',
                minlength:'收件人名称过短',
                maxlength:'收件人名称过长'
            },
            address:{
                required:'请填写详细地址',
                minlength:'地址不详细'
            },
            tel:{
                minlength:'请输入正确的手机号',
                maxlength:'请输入正确的手机号'
            }
        },
        errorElement:'em',
        errorPlacement:function (error,element) {
            var p=element.closest('li');
            if(p.find('em')){
                p.find('em').remove();
            }
            error.appendTo(p);
        }
    });
    $.validator.addMethod('validateArea',function (value, element, param) {
        return this.optional(element)||(value!=-1)
    },'请确认收货地址');
});