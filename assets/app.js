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
import './core/jQuery'
require('webpack-jquery-ui');
require('webpack-jquery-ui/css');

import './plugins/jquery-confirm-v3.3.4/dist/jquery-confirm.min'
import './plugins/jquery-confirm-v3.3.4/dist/jquery-confirm.min.css'
import './custon-theme/js/app'
import 'bootstrap/dist/css/bootstrap-grid.css';
import 'bootstrap/dist/css/bootstrap-reboot.css';
import 'bootstrap/dist/css/bootstrap-utilities.css';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-editable'
import 'datatables.net-buttons-bs4'
import 'datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css'
import 'popper.js'
import 'select2'
import 'bootstrap-fileinput'
import 'bootstrap-fileinput/css/fileinput.css'
import 'symfony-collection'
import './core/core'
import './datatables/index'
import './core/plugins'
import './core/dialogs'
import './core/forms'



import '@fortawesome/fontawesome-free'

