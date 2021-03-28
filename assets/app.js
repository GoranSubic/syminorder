/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

/* From Sylius Bootstrap Theme */
// Font awesome icons
import './js/fontawesome';
/* From Sylius Bootstrap Theme */

import './styles/global.scss';

// any CSS you import will output into a single css file (app.css in this case)
import 'vue-multiselect/dist/vue-multiselect.min.css'

import './styles/app.css';
import './styles/offer.css';
import './styles/static.css';

import './fonts/style.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

// loads the jquery package from node_modules
var $ = require('jquery');
// create global $ and jQuery variables
global.$ = global.jQuery = $;

// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');


// Vue.js app.js file
require('./js/main');

require('./js/front');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});