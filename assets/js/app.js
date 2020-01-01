/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../scss/form.scss');
require('../scss/error/error.scss');
require('../scss/gcu/gcu.scss');
require('../scss/global/global.scss');
require('../scss/security/formFrame.scss');
require('../scss/trick/home.scss');
require('../scss/trick/modification.scss');
require('../scss/trick/new.scss');
require('../scss/trick/show.scss');
require('../scss/user/profilePicture.scss');

require('./collectionTypeNewTrick');
require('./collectionTypeTrickModification');
require('./homeAddTricks');
require('./menuButton');
require('./modalWindow');
require('./upButton');
require('./tricks/commentButton');
require('./tricks/modal');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
