var welcome;
var meal;
var date = new Date();
var hour = date.getHours();
if (hour < 10) {
    welcome = "good morning";
    meal = "have a great breakfast with us";
} else if (hour < 12) {
    welcome = "good day";
    meal = "have a great lunch with us";
} else if (hour < 17) {
    welcome = "good afternoon";
    meal = "have a great late lunch or make a plan for dinner with us";
} else {
    welcome = "good evening";
    meal = "have a great dinner with us";
}

module.exports = function(el) {
    return `<h3>Dear visitor, <font color=\'#f08080\'> ${welcome} ${el} </font> - ${meal}</h3>`;
};