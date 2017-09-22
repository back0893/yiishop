$('.reduce_num').click(function(event){
    var self=$(this);
    $.post(editGoods,{
        goods_id:self.closest('tr').data('id'),
        '_csrf-frontend':token,
        amount:parseInt(self.next('input[name=amount]').val())-1
    });
});
$('.add_num').click(function(event){
    var self=$(this);
    $.post(editGoods,{
        goods_id:self.closest('tr').data('id'),
        '_csrf-frontend':token,
        amount:parseInt(self.prev('input[name=amount]').val())+1
    });
});
$('input[name=amount]').blur(function(event){
    var self=$(this);
    $.post(editGoods,{
        goods_id:self.closest('tr').data('id'),
        '_csrf-frontend':token,
        amount:self.val()
    });
});
$('.del').click(function (event) {
    var self=$(this);
    $.post(delGoods,{
        goods_id:self.closest('tr').data('id'),
        '_csrf-frontend':token
    },function(){
        self.closest('tr').remove();
       total();
    });
});
total();
function total() {
    var total = 0;
    $(".col5 span").each(function(){
        total += parseFloat($(this).text());
    });
    $("#total").text(total.toFixed(2));
}