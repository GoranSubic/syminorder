/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/app.js');

// loads the jquery package from node_modules
var $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// import the function from greet.js (the .js extension is optional)
// ./ (or ../) means to look for a local file
var greet = require('./greet');

$(document).ready(function() {

    $('[data-toggle="popover"]').popover();

    var isAuthenticated = $('.js-user-rating').data('isAuthenticated');
    var userName = 'Guest';
    if (isAuthenticated) {
        userName = $('.js-user-rating').data('userProfile');
    }
    $('.js-user-rating').html(greet(userName));
});