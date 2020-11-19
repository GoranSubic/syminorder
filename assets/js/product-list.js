$("#categories .row.category").click(function () {
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

$("#categories .first.row.category").click(function (){
    if($(this).hasClass("animated")) {
        $(this).removeClass("animated");
        $(this).animate({ width: "100%" }, 1000 );
    } else {
        $(this).addClass("animated");
        $(this).animate({ width: "98%" }, 1000 );
    }
});