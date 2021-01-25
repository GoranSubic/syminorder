$("#categories .row.category").click(function () {
    $(this).siblings(".container").children(".row.category").toggle();

    if($(this).siblings(".container").children(".row.category").is(':hidden')) {
        $(this).siblings(".container").children(".row.category").siblings(".row.products").hide();
        $(this).siblings(".container").children(".row.category").siblings(".container").children(".row.category").hide();
        $(this).siblings(".container").children(".row.category").siblings(".container").children(".row.category").siblings(".row.products").hide();
    }
});

$(".row.category").click(function(){
    $(this).siblings(".row.products").toggle();
});

/*
$("#categories .first.row.category").click(function (){
    if($(this).hasClass("animated")) {
        $(this).removeClass("animated");
        $(this).animate({ width: "99%" }, 1000 );
    } else {
        $(this).addClass("animated");
        $(this).animate({ width: "95%" }, 1000 );
    }
});*/
