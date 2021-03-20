$(".header .navbar-brand").hover(function(){
    $(this).addClass('nav-opacity');
},function(){
    $(this).removeClass('nav-opacity');
});

/* Set active navbar item */
$(document).ready(function (){
    $(".navbar-nav .nav-item").removeClass('active');

    if ($("#homepage").length) {
        $(".navbar-nav .nav-item.homepage").addClass('active');
    }
    if ($("#category-show").length) {
        $(".navbar-nav .nav-item.categories").addClass('active');
    }
    if ($("#offer-indications").length) {
        $(".navbar-nav .nav-item.indications").addClass('active');
    }
    if ($("#about").length) {
        $(".navbar-nav .nav-item.about").addClass('active');
    }
});

/* Category long-description ShowMore/ShowLess */
$("#category-show .learnmore-btn .moreless.more").click(function () {
    if ($(this).hasClass('more')) {
        $("#moreless.truncate-desc").show();
    }
    $(this).hide();
    $("#category-show .learnmore-btn .moreless.less").show();
});
$("#category-show .learnmore-btn .moreless.less").click(function () {
    if ($(this).hasClass('less')) {
        $("#moreless.truncate-desc").hide();
    }
    $(this).hide();
    $("#category-show .learnmore-btn .moreless.more").show();
});
