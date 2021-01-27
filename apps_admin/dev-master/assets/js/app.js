page('/', function () {});

page('/admin/switchtheme', toggleTheme);

page('/admin/profile/my', function () {
    lr('/admin/?app=profile&opt=my&fd=fd', 'mainbody');
    document.title = "Profile";
});

page('/admin/profile/changepassword', function () {
    lr('/admin/?app=profile&opt=changepassword&fd=fd', 'mainbody');
});

page('/admin/users', function () {
    lr('/admin/?app=users&opt=index&fd=fd', 'mainbody');
    document.title = "All users Data";

});

page('/admin/blog/edit/:id', function (ctx) {
    lr('/admin/?app=blog&opt=edit&fd=fd&id=' + ctx.params.id, 'mainbody');
});



page('/admin/eng_level', function () {
    lr('/admin/?app=Eng_level&opt=index&fd=fd', 'mainbody');
    document.title = "Eng_level";
});
page('/admin/eng_level/new', function () {
    lr('/admin/?app=Eng_level&opt=new&fd=fd', 'mainbody');
    document.title = "Eng_level";
});

page('/admin/eng_level/edit/:id', function (ctx) {
    lr('/admin/?app=Eng_level&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
    document.title = "Eng_level";
});
page('/admin/eng_level/delete/:id', function (ctx) {
    lr('/admin/?app=Eng_level&opt=eng_level_delete&fd=fd&ID=' + ctx.params.id, 'mainbody');
    document.title = "Eng_level";
});




page('/admin/configs', function () {
    lr('/admin/?app=configs&opt=index&fd=fd', 'mainbody');
    document.title = "configs";
});
page('/admin/configs/new', function () {
    lr('/admin/?app=configs&opt=new&fd=fd', 'mainbody');
    document.title = "Add configs";
});

page('/admin/configs/edit/:id', function (ctx) {
    lr('/admin/?app=configs&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
    document.title = "Edit configs";
});
page('/admin/configs/delete/:id', function (ctx) {
    lr('/admin/?app=configs&opt=eng_level_delete&fd=fd&ID=' + ctx.params.id, 'mainbody');
    document.title = "Delete configs";
});

function profile() {
    lr('/admin/?app=profile&opt=my&fd=fd', 'mainbody');
    document.title = "Profile";
}





page('/admin/user', function () {
    lr('/admin/?app=user&opt=index&fd=fd', 'mainbody');
    document.title = "user";
});
page('/admin/user/new', function () {
    lr('/admin/?app=user&opt=new&fd=fd', 'mainbody');
    document.title = "Add user";
});

page('/admin/user/edit/:id', function (ctx) {
    lr('/admin/?app=user&opt=edit&fd=fd&ID=' + ctx.params.id, 'mainbody');
    document.title = "Edit user";
});
page('/admin/user/delete/:id', function (ctx) {
    lr('/admin/?app=user&opt=user_delete&fd=fd&ID=' + ctx.params.id, 'mainbody');
    document.title = "Delete user";
});


page();