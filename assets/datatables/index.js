 /* Configurando el idioma español para todas las tablas */
 $.extend(true, $.fn.dataTable.defaults, {
    "language": {
        "sProcessing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><h3 style="display:inline;">Procesando...</h3>',
        "sLengthMenu": "Mostrar _MENU_",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
 });

$(function () {
    $(document).on('click', '.confirm', function (event) {
        const scope = $(this);
        app.dialogs.confirm({
            'event': event,
            'onAccept': function () {
                const mode = scope.attr('data-mode');
                const href = scope.attr('href');

                if (mode !== undefined && mode === 'reload') {
                    window.location.replace(href);
                } else {
                    $.get(href, function (response) {
                        if (response.type === 'success') {
                            toastr.success(response.message);

                            if (response['datatableId']) {
                                $(document).trigger('cortex.delete.complete', {
                                    table: scope.data('table'),
                                    datatableId: response['datatableId']
                                });
                            } else {
                                const table = scope.parents('table').get(0);
                                if (table) {
                                    $(table).DataTable().ajax.reload(null, false);
                                }
                            }

                            $(document).trigger('confirm-response.complete', {
                                response: response
                            });
                        }
                    })
                }
            }
        });
    });
});