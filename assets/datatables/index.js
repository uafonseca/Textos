/* Configurando el idioma español para todas las tablas */
$.extend(true, $.fn.dataTable.defaults, {
  language: {
    sProcessing:
      '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><h3 style="display:inline;">Procesando...</h3>',
    sLengthMenu: "Mostrar _MENU_",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sInfo: "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
    sInfoEmpty: "Mostrando del 0 al 0 de un total de 0 registros",
    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
    sInfoPostFix: "",
    sSearch: "Buscar:",
    sUrl: "",
    sInfoThousands: ",",
    sLoadingRecords: "Cargando...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "Siguiente",
      sPrevious: "Anterior",
    },
    oAria: {
      sSortAscending: ": Activar para ordenar la columna de manera ascendente",
      sSortDescending:
        ": Activar para ordenar la columna de manera descendente",
    },
  },
});

$(function () {
  $(document).on("click", ".confirm", function (event) {
    const scope = $(this);
    app.dialogs.confirm({
      event: event,
      onAccept: function () {
        const mode = scope.attr("data-mode");
        const href = scope.attr("href");

        if (mode !== undefined && mode === "reload") {
          window.location.replace(href);
        } else {
          $.get(href, function (response) {
            if (response.type === "success") {
              toastr.success(response.message);

              if (response.no_reload === true) {
                location.reload();
              }else {
                if (response["datatableId"]) {
                  $(document).trigger("cortex.delete.complete", {
                    table: scope.data("table"),
                    datatableId: response["datatableId"],
                  });
                } else {
                  const table = scope.parents("table").get(0);
                  if (table) {
                    $(table).DataTable().ajax.reload(null, false);
                  }
                }
              }  

              $(document).trigger("confirm-response.complete", {
                response: response,
              });
            } else if (response.type === "error") {
              toastr.error(response.message);
            }
          });
        }
      },
    });
  });
});


app.datatable = {
  handleSingleSelect: function (table) {
      const scope = this;

      $(table).find('tbody').find('input[type="checkbox"]').on('ifChanged', function () {
          scope.triggerDatatableSelecion(table);
      });
  },

  handleMultipleSelect: function (table) {
      const scope = this;

      table.find('thead tr:first').find('input[name="select_all"]').on('ifChanged', function () {
          if (this.checked) {
              table.find('tbody input[type="checkbox"]:not(:checked)').iCheck('check');
          } else {
              table.find('tbody input[type="checkbox"]:checked').iCheck('uncheck');
          }

          scope.triggerDatatableSelecion(table);
      });
  },

  handleDeleteButton: function (table, deleteButton) {
      table.on('cortex.datatable.selection', function (event, count) {
          event.preventDefault();
          if (count > 0) {
              deleteButton.removeClass('disabled');
          } else {
              deleteButton.addClass('disabled');
          }
      });
  },

  handleDeleteMultipleAction: function (deleteButton, form) {
      deleteButton.on('click', function (event) {
          event.preventDefault();
          cortex.dialogs.confirm({
              'onAccept': function () {
                  form.submit();
              }
          });
      });
  },

  handleEditAction: function (table, editButtonClass, routeName) {
      $(table).on('click', editButtonClass, function () {
          event.preventDefault();

          const editBtn = $(this);
          const path = Routing.generate(routeName, {'id': editBtn.data('url')});
          // cortex.dialogs.create({url:path})
          CrearNuevoModalAjax(path, null, {use_routing: false, modal_width: '50%'})
      });
  },

  handleAddAction: function (addBtn, path) {
      addBtn.on('click', function (event) {
          event.preventDefault();
          // cortex.dialog.create({url:path})
          CrearNuevoModalAjax(path, null, {use_routing: false, modal_width: '50%'})
      });
  },

  handleDeleteAction: function (table, deleteBtnClass, routeName) {
      $(table).on('click', deleteBtnClass, function () {
          event.preventDefault();
          const deleteBtn = $(this);

          let path = $(this).attr('href');

          if (routeName) {
              path = Routing.generate(path, {'id': deleteBtn.data('url')});
          }

          cortex.dialogs.confirm({
              'onAccept': function () {
                  window.location.href = path;
              }
          });
      });
  },

  triggerDatatableSelecion: function (table) {
      $(table).trigger('cortex.datatable.selection', this.selectedCount(table));
  },

  selectedCount: function (table) {
      return table.find('tbody input[type="checkbox"]:checked').length;
  },

  initInputSorting: function () {
      $.fn.dataTable.ext.order['dom-text'] = function (settings, col) {
          return this.api().column(col, {order: 'index'}).nodes().map(function (td, i) {
              return parseFloat($('input', td).val());
          });
      };
  },

  handleDetails: function (scope, options) {
      const tr = $(scope).closest('tr');
      const icon = $(scope).find('span');
      if (tr.attr('data-status') === undefined) {
          icon.removeClass('fa-plus-circle').addClass('fa-spinner fa-spin');
          app.dom.lock($(scope).closest('table'));
          $.get(options['path'], function (response) {
              tr.after(`<tr><td colspan="${tr.find('td').length}">${response}</td></tr>`);

              const loadTables = options['loadTables'] ? options['loadTables'] : false;
              if (loadTables) {
                  $(tr).next().find('table').DataTable();
              }

              tr.attr('data-status', 'loaded');
              icon.removeClass('fa-spinner fa-spin').addClass('fa-minus-circle');
              icon.removeClass('text-success').addClass('text-danger');
              app.dom.unlock($(scope).closest('table'));
          });
      } else {
          if (tr.attr('data-status') === 'loaded') {
              tr.next().hide();
              tr.attr('data-status', '');
              icon.removeClass('fa-minus-circle').addClass('fa-plus-circle');
              icon.removeClass('text-danger').addClass('text-success');
          } else {
              tr.next().show();
              tr.attr('data-status', 'loaded');
              icon.removeClass('fa-plus-circle').addClass('fa-minus-circle');
              icon.removeClass('text-success').addClass('text-danger');
          }
      }
  },

  handleBulkAction: function (options, actions) {
      // TODO: Refactor this code to handle datatable init in another way
      setTimeout(function () {
          actions.forEach((action) => {

              $(document).on("click", '[data-table]', function (e) {
                  e.preventDefault();

                  const scope = $(this);
                  const tableId = scope.attr('data-table');
                  const oTable = $(`#sg-datatables-${tableId}`).DataTable();

                  if (tableId.includes(scope.attr('data-table')) && scope.attr('title') === action.button) {
                      if (oTable && oTable.rows(".selected").data().length > 0) {
                          if (scope.data("message")) {
                              action.onAction(e).done(function (formData) {
                                  const items = $.map(oTable.rows(".selected").data(), function (i) {
                                      return i;
                                  });

                                  const url = scope.attr("href");

                                  if (url != null) {
                                      $.ajax({
                                          url: url,
                                          type: "POST",
                                          cache: false,
                                          data: {
                                              'data': items,
                                              'formData': formData
                                          },
                                          success: function (response) {
                                              action.onSuccess(response);
                                          },
                                          error: function (XMLHttpRequest, textStatus, errorThrown) {
                                              console.log(XMLHttpRequest + ' ' + textStatus + ' ' + errorThrown);
                                          }
                                      })
                                  }
                              });
                          }
                      } else {
                          alert("Debes seleccionar al menos 1 elemento");
                      }
                  }
              });
          });
      }, 1500)
  }
};
