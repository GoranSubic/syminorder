var welcome;
var date = new Date();
var hour = date.getHours();
if (hour < 10) {
    welcome = "good morning";
} else if (hour < 12) {
    welcome = "good day";
} else if (hour < 17) {
    welcome = "good afternoon";
} else {
    welcome = "good evening";
}

module.exports = function(el) {
    return `<h3>Yo yo <font color=\'#f08080\'> ${welcome} ${el} </font> - welcome to Encore!</h3>`;
};