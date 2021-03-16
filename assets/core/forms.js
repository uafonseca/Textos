app.forms = {
    submit: function (form, onSuccess, event) {
        const scope = $(form);

        if (event) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }

        // Pace.ignore(function () {
            $.ajax({
                url: scope.attr('action'),
                type: scope.attr('method'),
                data: new FormData(form.get(0) ? form.get(0) : form),
                processData: false,
                contentType: false,
                success: function (response) {
                    if (onSuccess !== undefined) onSuccess(response);
                }
            });
        // });
    }
};
// jQuery plugin to prevent double submission of forms
// jQuery.fn.preventDoubleSubmission = function () {
//     $(this).on('submit', function (e) {
//         var $form = $(this);

//         if ($form.data('submitted') === true) {
//             // Previously submitted - don't submit again
//             e.preventDefault();
//             // swal.fire({
//             //     title: "¡Espere!",
//             //     text: "¡Para enviar un formulario basta con solo un click!",
//             //     icon: "error",
//             // });
//         } else {
//             // Mark it so that the next submit can be ignored
//             // ADDED requirement that form be valid
//             if($form.valid()) {
//                 $form.data('submitted', true);
//             }
//         }
//     });

//     return this;
// };

$(function () {
    // $('form').preventDoubleSubmission();
});
