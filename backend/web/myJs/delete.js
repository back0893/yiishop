var p=$('#required');
var url=p.data('del');
var _csrf=p.data('csrf');
$('tbody').on('click','.del',function(){
    var self=$(this);
    var id=self.data('id');
    console.log(id);
    $.ajax(url,{
            type:'post',
            dataType:'json',
            data:{
                'id':id,
                '_csrf':_csrf
            },
            success:function(){
               self.closest('tr').remove();
            }
        }
    )
});
