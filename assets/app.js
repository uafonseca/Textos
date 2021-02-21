/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './custon-theme/css/app.css'

// start the Stimulus application


import './custon-theme/js/app'
import 'bootstrap/dist/css/bootstrap-grid.css';
import 'bootstrap/dist/css/bootstrap-reboot.css';
import 'bootstrap/dist/css/bootstrap-utilities.css';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-editable'
import 'datatables.net-buttons-bs4'
import 'popper.js'
import './core/core'
import './datatables/index'

require('webpack-jquery-ui');
require('webpack-jquery-ui/css');

import '@fortawesome/fontawesome-free'

