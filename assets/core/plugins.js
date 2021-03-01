app.plugins = {
    // initDatatable: function (selector, options = {}) {
    //     return $(selector).DataTable(options);
    // },
    // initTimePicker: function (selector) {
    //     $(selector).timepicker({
    //         defaultTime: 'current',
    //         showInputs: false
    //     });
    // },
    // initCheck2: function (selector) {
    //     $(selector).iCheck({
    //         checkboxClass: 'icheckbox_square-blue',
    //         radioClass: 'iradio_square-blue'
    //     });
    // },
    initSelect2: function (selector, options = {}) {
        const config = {
            placeholder: 'Seleccionar'
        };

        if (options['closeOnSelect'] !== undefined) config.closeOnSelect = options['closeOnSelect'];
        if (options['readonly'] !== undefined) config.readonly = options['readonly'];
        if (options['ajax'] !== undefined) config.readonly = options['ajax'];

        return $(selector).select2(config);
    },
    initFileInput: function (selector) {
        $(selector).fileinput({
            showCaption: false,
            showPreview: false,
            showUploadedThumbs: false,
            showUpload: false,
            showCancel: false,
            showRemove: false,
            browseLabel: '&nbsp;Importar',
            browseIcon: '<i class="fa fa-upload"></i>&nbsp;'
        });
    },
    // initNewFileInput: function (selector, imageDefault, style = '') {

    //     let myStyle = style === '' ? 'width: "100px", height: "92px"' : 'width:' + style.width;
    //     let STYLE_SETTING = 'style="width:{width};height:{height};"',
    //         img_field = "<img src='value_replace' style='" + myStyle + "' alt='foto'>";


    //     $(selector).fileinput({
    //         'defaultPreviewContent': [
    //             img_field.replace('value_replace', imageDefault)
    //         ],
    //         'previewSettings': {
    //             image: style === '' ? {width: "100px", height: "92px"} : style
    //         },
    //         'initialCaption': '&nbsp; Foto Trabajador', // Muestro que esa foto es la original del usuario
    //         'overwriteInitial': false, // Cuando limpien el preview se vuelve a mostrar el initialCaption
    //         'showCaption': true,
    //         'showUpload': false,
    //         'browseLabel': '&nbsp;Buscar',
    //         'removeLabel': '&nbsp;Quitar',
    //         'allowedFileTypes': ['image'],
    //         'allowedPreviewTypes': ['image'],
    //         'allowedFileExtensions': ['jpg', 'jpeg', 'png', 'gif', 'bmp'],
    //         'maxFileSize': '150000',
    //         'maxImageWidth': '1000000',
    //         'layoutTemplates': {
    //             main1: '' +
    //                 '<div class="col-md-7 no-padding">' +
    //                 '{preview}' +
    //                 '<div class="kv-upload-progress hide"></div></div>\n' +
    //                 '<div class="col-md-5">' +
    //                 '       {browse}\n' +
    //                 '       {remove}\n' +
    //                 '       {cancel}\n' +
    //                 '       {upload}\n' +
    //                 '</div>',
    //             btnDefault: '<button type="{type}" tabindex="500" style="margin-top: 10px" title="{title}" class="file-input-btn {css}"{status}>{icon}{label}</button>',
    //             icon: '<i class="fa fa-camera-retro"></i>&nbsp;'
    //         },
    //         'previewTemplates': {
    //             image: '   <img src="{data}" class="file-preview-image" title="{caption}" alt="{caption}" ' + STYLE_SETTING + '>\n'
    //         }
    //     });
    // },

    // initializeEmpresaFileInput: function (field_selector, image_url_defaultPreview) {

    //     let STYLE_SETTING = 'style="width:{width};height:{height};"',
    //         img_field = "<img src='value_replace' style='width:190px;height:110px' alt='foto'>";

    //     $(field_selector).fileinput({
    //         'defaultPreviewContent': [
    //             img_field.replace('value_replace', image_url_defaultPreview)
    //         ],
    //         'previewSettings': {
    //             image: {width: "190px", height: "110px"}
    //         },
    //         'initialCaption': '&nbsp; Foto Trabajador', // Muestro que esa foto es la original del usuario
    //         'overwriteInitial': false, // Cuando limpien el preview se vuelve a mostrar el initialCaption
    //         'showCaption': true,
    //         'showUpload': false,
    //         'browseLabel': '&nbsp;Buscar',
    //         'removeLabel': '&nbsp;Quitar',
    //         'allowedFileTypes': ['image'],
    //         'allowedPreviewTypes': ['image'],
    //         'allowedFileExtensions': ['jpg', 'jpeg', 'png', 'gif', 'bmp'],
    //         'maxFileSize': '150000',
    //         'maxImageWidth': '1000000',
    //         'layoutTemplates': {
    //             main1: '' +
    //                 '{preview}' +
    //                 '<div class="kv-upload-progress hide"></div>\n' +
    //                 '       {browse}\n' +
    //                 '       {remove}\n' +
    //                 '       {cancel}\n' +
    //                 '       {upload}\n',
    //             btnDefault: '<button type="{type}" tabindex="500" title="{title}" class="file-input-btn {css}"{status}>{icon}{label}</button>',
    //             icon: '<i class="fa fa-camera-retro"></i>&nbsp;'
    //         },
    //         'previewTemplates': {
    //             image: '   <img src="{data}" class="file-preview-image" title="{caption}" alt="{caption}" ' + STYLE_SETTING + '>\n'
    //         }
    //     });
    // },

    // initDatepicker: function (selector, options) {
    //     const defaultOptions = {
    //         format: "dd-mm-yy",
    //         autoclose: true,
    //         changeYear: true,
    //         locale: 'es'
    //     };

    //     $(selector).datepicker(options === undefined ? defaultOptions : options);
    // },
    // initSingleDateRangePicker: function (selector) {
    //     return $(selector).daterangepicker({
    //         singleDatePicker: true,
    //         changeYear: true,
    //         locale: {
    //             format: 'DD-MM-YYYY',
    //             "separator": " - ",
    //             "applyLabel": "Aplicar",
    //             "cancelLabel": "Cancelar",
    //             "fromLabel": "Desde",
    //             "toLabel": "Hasta",
    //             "customRangeLabel": "Personalizado",
    //             "weekLabel": "S",
    //             "daysOfWeek": [
    //                 "Do",
    //                 "Lu",
    //                 "Ma",
    //                 "Mi",
    //                 "Ju",
    //                 "Vi",
    //                 "Sa"
    //             ],
    //             "monthNames": [
    //                 "Enero",
    //                 "Febrero",
    //                 "Marzo",
    //                 "Abril",
    //                 "Mayo",
    //                 "Junio",
    //                 "Julio",
    //                 "Agosto",
    //                 "Septiembre",
    //                 "Octubre",
    //                 "Noviembre",
    //                 "Diciembre"
    //             ],
    //             "firstDay": 1
    //         }
    //     });
    // },
    // initPrototype: function (selector, options = {}) {
    //     $(selector).collection({
    //         add: `<a class="btn btn-primary" href="#"><i class="fa fa-plus"></i> ${options.buttonLabel ? options.buttonLabel : 'Adicionar'}</a>`,
    //         remove: `<a class="btn btn-link pull-right cortex-collection-icon" href="#"><i class="fa fa-trash text-red"></i></a>`,
    //         allow_up: false,
    //         allow_down: false,
    //         allow_duplicate: false,
    //         add_at_the_end: true,
    //         after_add: options['afterAdd'],
    //         init_with_n_elements: options['init_with_n_elements'],
    //         position_field_selector: options['position_field_selector'],
    //         prefix: options['prefix'],
    //         children: options['children']
    //     });
    // },
    // initDateRangePicker: function (selector, startAt, endAt) {
    //     return $(selector).daterangepicker({
    //         timePicker: false,
    //         startDate: startAt,
    //         endDate: endAt,
    //         timePickerIncrement: 1,
    //         format: 'DD-MM-YYYY',
    //         locale: {
    //             applyLabel: 'Aplicar',
    //             cancelLabel: 'Cancelar',
    //             fromLabel: 'Fecha Inicio',
    //             toLabel: 'Fecha Fin',
    //             weekLabel: 'S'
    //         }
    //     });
    // },
    // initEditableCombodate: function (editable) {
    //     return $(editable).editable({
    //         type: 'combodate',
    //         combodate: {
    //             minuteStep: 1
    //         },
    //         template: 'h:mm a',
    //         format: 'h:mm a',
    //         viewformat: 'h:mm a',
    //         title: 'Tiempo',
    //         emptytext: 'Sin Marca',
    //         params: function (params) {
    //             params.pk = $(this).attr('data-pk');
    //             return params;
    //         },
    //         url: Routing.generate('biometric_activity_result_edit'),
    //         success: function (response) {
    //             const row = $('#activity-result-id-' + response.id);

    //             $('#sg-datatables-user_datatable').DataTable().ajax.reload(null,false);

    //             row.fadeOut("slow", function () {
    //                 row.replaceWith(response.row);
    //                 cortex.plugins.initEditableCombodate($('.editable-combodate'));
    //                 cortex.plugins.initEditableNumber($('.editable-number'));
    //                 $('.editable-combodate').removeClass('biometric-editable');
    //                 $('.editable-number').removeClass('biometric-editable');
    //                 row.fadeIn("slow");
    //             });
    //         }
    //     });
    // },
    // initEditableNumber: function (editable) {
    //     return $(editable).editable({
    //         type: 'text',
    //         title: 'Actualizar estadística',
    //         emptytext: 'Sin valor',
    //         validate: function (value) {
    //             value = $.trim(value);
    //             if (value == "") {
    //                 return "Campo requerido";
    //             }

    //             if (isNaN(value)) {
    //                 return "Solo valores numéricos permitidos";
    //             }
    //         },
    //         params: function (params) {
    //             params.pk = $(this).attr('data-pk');
    //             params.field = $(this).attr('data-field');
    //             return params;
    //         },
    //         url: Routing.generate('biometric_activity_result_edit_statistic_field'),
    //         success: function (response) {
    //             $('#sg-datatables-user_datatable').DataTable().ajax.reload(null,false);

    //             const row = $('#activity-result-id-' + response.id);

    //             row.fadeOut("slow", function () {
    //                 row.replaceWith(response.row);
    //                 cortex.plugins.initEditableCombodate($('.editable-combodate'));
    //                 cortex.plugins.initEditableNumber($('.editable-number'));
    //                 $('.editable-combodate').removeClass('biometric-editable');
    //                 $('.editable-number').removeClass('biometric-editable');
    //                 row.fadeIn("slow");
    //             });
    //         }
    //     })
    // },
    // initEditableMarkDescription: function (editable) {
    //     return $(editable).editable({
    //         type: 'textarea',
    //         title: 'Actualizar descripción',
    //         rows: 3,
    //         params: function (params) {
    //             params.pk = $(this).attr('data-pk');
    //             return params;
    //         },
    //         url: Routing.generate('biometric_activity_result_edit_description_field'),
    //         success: function (response) {
    //             toastrMessage(response.type, response.message);
    //         },
    //         emptytext: 'Sin descripción',
    //         emptyclass: '',
    //         display: function (value, response) {
    //             let text = response !== undefined ? value : $(this).html();
    //             $(this).html(value.trim());
    //         }
    //     });
    // },
    // initEditableStatisticDescription: function (editable) {
    //     return $(editable).editable({
    //         type: 'textarea',
    //         title: 'Actualizar descripción',
    //         rows: 3,
    //         params: function (params) {
    //             params.pk = $(this).attr('data-pk');
    //             params.field = $(this).attr('data-field');
    //             return params;
    //         },
    //         url: Routing.generate('biometric_activity_statistic_description_field'),
    //         success: function (response, newValue) {
    //             toastrMessage(response.type, response.message);
    //         },
    //         emptytext: 'Sin descripción',
    //         emptyclass: '',
    //         display: function (value, response) {
    //             let text = response !== undefined ? value : $(this).html();
    //             $(this).html(value.trim());
    //         }
    //     });
    // }
};