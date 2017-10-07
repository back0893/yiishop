var pregWord=/(keyWord=)([\w\u4e00-\u9fa5]+)/;
$('#skipsearch').click(function () {
    var self=$(this);
    var pregPage=/(page=)(\d+)/;
    var page_num=$('.page_num');
    var query=window.location.search.replace(pregPage,'$1'+page_num.val());
    query=window.location.search.replace(pregPage,'$1'+page_num.val());
    $(location).prop('href',query)
});
$('input[name=keyWord]').val(decodeURI(window.location.search).match(pregWord)[2]);
