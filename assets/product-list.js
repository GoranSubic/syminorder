$(".first .row.category").click(function () {
    $(this).siblings(".container").children(".row.category").toggle();

    if($(this).siblings(".container").children(".row.category").is(':hidden')) {
        $(this).siblings(".container").children(".row.category").siblings(".row.product").hide();
        $(this).siblings(".container").children(".row.category").siblings(".container").children(".row.category").hide();
        $(this).siblings(".container").children(".row.category").siblings(".container").children(".row.category").siblings(".row.product").hide();
    }
});

$(".row.category").click(function(){
    $(this).siblings(".row.product").toggle();
});