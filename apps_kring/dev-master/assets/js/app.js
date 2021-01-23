page('/', index);
page('/kring/kringcoder/showtables/:table', showtabledtail);
page('/kring/kringcoder/formmaker/:table', formmaker);
page();

function index() {
    //no action defined

}
function showtabledtail(ctx) {
    loadurl('{{baseurl}}/kringcoder/showtables/' + ctx.params.table + '/fd', 'mainbody');
}
function formmaker(ctx) {
    loadurl('{{baseurl}}/kringcoder/formmaker/' + ctx.params.table + '/fd', 'mainbody');
}