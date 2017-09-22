var pregWord=/(keyWord=)(\w+)/;
$('#skipsearch').click(function () {
    var self=$(this);
    var pregPage=/(page=)(\d+)/;
    var page_num=$('.page_num');
    var query=window.location.search.replace(pregPage,'$1'+page_num.val());
    query=window.location.search.replace(pregPage,'$1'+page_num.val());
    $(location).prop('href',query)
});
$('input[name=keyWord]').val(window.location.search.match(pregWord)[2]);
