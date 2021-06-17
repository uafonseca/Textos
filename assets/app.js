/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/adminlte.css';
import './custon-theme/css/app.css' 

// start the Stimulus application
import './core/jQuery'
require('webpack-jquery-ui');
require('webpack-jquery-ui/css');
import ('toastr');
import('toastr/build/toastr.css');
window.toastr = toastr;
import './plugins/jquery-confirm-v3.3.4/dist/jquery-confirm.min'
import './plugins/jquery-confirm-v3.3.4/dist/jquery-confirm.min.css'
import './custon-theme/js/app'
import 'bootstrap/dist/css/bootstrap-grid.css';
import 'bootstrap/dist/css/bootstrap-reboot.css';
import 'bootstrap/dist/css/bootstrap-utilities.css';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-editable'
import 'bootstrap-timepicker/js/bootstrap-timepicker'
import 'bootstrap-timepicker/css/bootstrap-timepicker.min.css'
import 'datatables.net-buttons-bs4'
import 'datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css'
import 'datatables.net-buttons/js/buttons.print.min';
import 'popper.js'
import 'select2'
import 'bootstrap-fileinput'
import 'bootstrap-fileinput/css/fileinput.css'
import 'symfony-collection'
import 'jquery-blockui'
import './core/core'
import './datatables/index'
import './core/plugins'
import './core/dialogs'
import './core/forms'
import './core/tippy'
import './core/dom'

import '@fortawesome/fontawesome-free'
import 'jquery-validation'

$(()=>{
    $('.terms-dialog').on('click', function(event){
        event.preventDefault();
        const scope = $(this);
        app.dialogs.create({
            url: scope.attr('href'),
            containerFluid: true,
            columnClass:'col-md-10'
        });
    });
})