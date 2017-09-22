var priceGrep=/(?:￥)([\d\.]+)/;
var pay=parseFloat($('#pay').text().match(priceGrep)[1]);
var repay=parseFloat($('#repay').text().match(priceGrep)[1]);
var send=$('#send');
var totalyuan=$('#totalyuan');
var total=$('#total');
$('input[name=delivery]').click(function(){
    var self=$(this);
    var delivery_id=self.val();
    var delivery_price=parseFloat(self.closest('td').next('td').text().match(priceGrep)[1]);
    send.text('￥'+delivery_price.toFixed(2));
    var totalPrice=pay-repay+delivery_price;
    total.text('￥'+(totalPrice.toFixed(2)));
    totalyuan.text(totalPrice.toFixed(2)+'元');
});
$('#submit').click(function(){
   $('#order_form').submit();
});