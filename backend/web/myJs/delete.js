$('tbody').on('click','.del',function(){
    var self=$(this);
    var id=self.data('id');
    console.log(id);
    $.ajax('del',{
            type:'get',
            dataType:'json',
            data:{
                'id':id
            },
            success:function(){
               self.closest('tr').remove();
            }
        }
    )
});