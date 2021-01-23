page('/', index);
page('/kring/showtables/:table', showtabledtail);
page();

function index() {
    //no action defined

}
function showtabledtail(ctx) {
    loadurl('{{baseurl}}/kring/showtables/' + ctx.params.table + '/fd', 'mainbody');
}