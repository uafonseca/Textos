let csc = require('country-state-city').default

app.registration = {
    index: {
        initComponents: () => {
            $('#user_student_brithday').datepicker({
                format: 'DD/MM/YYYY',
                changeYear: true,
                yearRange: "-100:+0"
            });
        },
        prepare: () => {
            $('.student').each(function (i, item) {
                $(item).prop('required', true)
                console.log(item)
            })
            $('.profesor').each(function (i, item) {
                $(item).prop('required', false)
            })
        },
        roleActions: () => {
            let student = $('#student');
            let teacher = $('#teacher');
            $('form[name="user"] input[id="user_roles_0"]').on('change', '', function (e) {
                const scope = $(this);
                if (scope.filter(':checked').val()) {

                    if (student.hasClass('d-none'))
                        $('#student').removeClass('d-none');
                    if (!teacher.hasClass('d-none'))
                        $('#teacher').addClass('d-none');

                    $('.student').each(function (i, item) {
                        $(item).prop('required', true)
                    })
                    $('.profesor').each(function (i, item) {
                        $(item).prop('required', false)
                    })
                }
            });

            $('form[name="user"] input[id="user_roles_1"]').on('change', '', function (e) {
                const scope = $(this);
                if (scope.filter(':checked').val()) {
                    if (!student.hasClass('d-none'))
                        $('#student').addClass('d-none');
                    if (teacher.hasClass('d-none'))
                        $('#teacher').removeClass('d-none');

                    $('.student').each(function (i, item) {
                        $(item).prop('required', false)
                    })
                    $('.profesor').each(function (i, item) {
                        $(item).prop('required', true)
                    })
                }
            });
        },
        loadAddress: () => {
            let $provinciaSelector = $('#user_provincia');
            let $cantonSelector = $('#user_canton');

            $provinciaSelector.change(function () {
                let data = {
                    id: $(this).val()
                };
                $.ajax({
                    type: 'post',
                    url: url_canton,
                    data: data,
                    beforeSend: function(){
                        $provinciaSelector.prop('disabled',true);
                        $cantonSelector.prop('disabled',true);
                    },
                    success: function (data) {
                        $provinciaSelector.prop('disabled',false);
                        $cantonSelector.prop('disabled',false);
                        $cantonSelector.html('<option>Seleccione un Canton...</option>');
                        for (let i = 0, total = data.length; i < total; i++){
                            $cantonSelector.append('<option value="'+ data[i].id + '">' + data[i].name + '</option>')
                        }
                    }
                })
            });
        }

    }
};

$(() => {
    app.registration.index.prepare();
    app.registration.index.roleActions();
    app.registration.index.initComponents();
    app.registration.index.loadAddress();
})