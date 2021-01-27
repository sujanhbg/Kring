page('/', index);
page('/level/:id', viewlevel);
page();

function index() {


}

function viewlevel(ctx) {
    loadurl('/?app=home&opt=level&fd=fd&id=' + ctx.params.id, 'mainbody');
}