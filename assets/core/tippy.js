import swal from "sweetalert2";

window.Tippy = require('tippy.js').default;
import 'tippy.js/dist/tippy.css';
import 'tippy.js/animations/scale.css';
import 'tippy.js/animations/scale-subtle.css';
import 'tippy.js/animations/scale-extreme.css';
function initTippy(){
    new Tippy('[data-tippy-content]',{
        allowHTML: true,
        animation: 'scale',
        arrow: true
    });
}
$(document).ready(function () {
    initTippy();
    $.extend(true, $.fn.dataTable.defaults, {
        "drawCallback": function(settings) {
            initTippy();
            $(this).on("contextmenu",function(){
                swal.fire({
                    title: 'Lo sentimos',
                    text: "¡ACCIÓN NO PERMITIDA!",
                    icon: "warning",
                    showConfirmButton: true,
                });
                return false;
            });
        }
    });
});

