/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

/* From Sylius Bootstrap Theme */
// Main scripts file
import './js/index';

// Main styles file
import './scss/index.scss';

// Images
import './media/sylius-logo.png';

// Font awesome icons
import './js/fontawesome';
/* From Sylius Bootstrap Theme */


// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css'; // Now using syliys scss index file
import './styles/static.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

// loads the jquery package from node_modules
var $ = require('jquery');
// create global $ and jQuery variables
global.$ = global.jQuery = $;

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');


require('./js/product-list');
require('./js/front');

// import the function from greet.js (the .js extension is optional)
// ./ (or ../) means to look for a local file
var greet = require('./js/greet');

$(document).ready(function() {

    $('[data-toggle="popover"]').popover();

    var isAuthenticated = $('.js-user-name').data('isAuthenticated');
    var userName = '';//'guest';
    if (isAuthenticated) {
        userName = $('.js-user-name').data('userProfile');
    }
    $('.js-user-name').html(greet(userName));
});