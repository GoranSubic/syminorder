var welcome;
var meal;
var date = new Date();
var hour = date.getHours();
if (hour < 10) {
    welcome = "Dobro jutro";
    meal = "želimo Vam prijatno jutro, pogledajte našu ponudu za doručak";
} else if (hour < 12) {
    welcome = "Dobar dan";
    meal = "odaberite najbolji ručak";
} else if (hour < 17) {
    welcome = "Prijatno poslepodne";
    meal = "odaberite obrok za kasni ručak ili ranu večeru";
} else {
    welcome = "Dobro veče";
    meal = "želimo Vam prijatno veče, pogledajte našu ponudu za večeru";
}

module.exports = function(el) {
    return `<h3><font color=\'#f08080\'> ${welcome} ${el} </font> - ${meal}</h3>`;
};