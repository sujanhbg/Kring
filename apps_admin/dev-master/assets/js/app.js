page('/', index);
page('/admin/blog', allblogs);
page('/admin/blog/new', newblog);
page('/admin/blog/edit/:id', editblog);
page();

function index() {
    location.reload();

}
function allblogs() {
    loadurl('/admin/?app=blog&opt=index&fd=fd', 'mainbody');
}
function newblog() {
    loadurl('/admin/?app=blog&opt=new&fd=fd', 'mainbody');
}
function editblog(ctx) {
    loadurl('/admin/?app=blog&opt=edit&fd=fd&id=' + ctx.params.id, 'mainbody');
}