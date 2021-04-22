app.questions = {
    index : {
        initPrototype : () => {
            $('.question-collection').collection({
                add: `<a class="btn btn-primary m-3" style="max-width: 190px;" href="#"><i class="fa fa-plus"></i> Adicionar Pregunta</a>`,
                remove: `<a class="btn btn-link pull-right cortex-collection-icon" href="#"><i class="fa fa-trash text-red"></i></a>`,
                allow_up: false,
                allow_down: false,
                allow_duplicate: false,
                add_at_the_end: true,
                position_field_selector: '.question-counter',
                after_add: function (collection, element) {
                    app.questions.index.initPrototype();
                    app.questions.index.initFormEvents();
                },
                children: [{
                    selector: '.choice-collection',
                    add: `<a class="btn btn-warning m-3" href="#" style="max-width: 160px;"><i class="fa fa-plus"></i>Adicionar opción</a>`,
                    remove: `<a href="#" class="btn btn-danger mt-collection pull-right"><i class="fa fa-trash"></i></a>`,
                    allow_up: false,
                    allow_down: false,
                    allow_duplicate: false,
                    add_at_the_end: true,
                    position_field_selector: '.option-counter',
                    after_add: function (collection, element) {
                        app.questions.index.initFormEvents();
                    },
                }]
            });
        },
        initFormEvents : () => {
            // When sport gets selected ...
                $('.selector').change(function() {
                // ... retrieve the corresponding form.
                var $form = $(this).closest('form');
                // Simulate form data, but only include the selected sport value.
                var data = {};
                data[$(this).attr('name')] = $(this).val();
                // Submit data via AJAX to the form's action path.
                $.ajax({
                    url : $form.attr('action'),
                    type: $form.attr('method'),
                    data : data,
                    success: function(html) {
                        // Replace current position field ...
                        $('form[name="evaluationForm"]').replaceWith(
                            // ... with the returned one from the AJAX response.
                            $(html).find('form[name="evaluationForm"]')
                        );
                        // Position field now displays the appropriate positions.
                    }
                });
            });
        }
    },
};

$(() => {
    app.questions.index.initPrototype();
});