(function ($) {
    $.fn.extend({
        wizeTable: function (options) {
            const settings = $.extend(
                {
                    target: this,
                    url: "",
                    columns: [],
                    columnData: undefined,
                    url_delete: false,
                    title: "List",
                    btn: false,
                    message_delete: "You won't be able to revert this!",
                    every_action: false,
                    multiple_action: false,
                    responsive: true,
                    ajaxData: function() { return {}; },
                },
                options
            );

            if (settings.columnData && settings.columns.length == 0) {
                if (!Array.isArray(settings.columnData))
                    throw new Error(
                        "columnData property must be an array of strings"
                    );
                settings.columnData.forEach((data) => {
                    settings.columns.push({
                        data,
                    });
                });
            }

            let hasResponsiveColumn = false;
            settings.columns = settings.columns.map((column) => {
                if (column.data === "actions") {
                    return { ...column, orderable: false };
                }
                if (column.data === "responsive") {
                    hasResponsiveColumn = true;
                }
                return column;
            });

            if (!hasResponsiveColumn && settings.responsive) {
                settings.columns.unshift({
                    data: "responsive",
                });
            }

            if (!settings.btn) {
                if (settings.url_delete) {
                    settings.btn = [
                        {
                            text: '<i class="fas fa-trash me-sm-1"></i> <span class="d-none d-sm-inline-block">Delete</span>',
                            className:
                                "delete-btn btn btn-danger me-2 btn-label-danger",
                        },
                    ];
                } else {
                    settings.btn = [
                        {
                            text: '<i class="fas fa-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New</span>',
                            className: "add-btn btn btn-primary me-2",
                        },
                    ];
                }
            }

            if (settings.multiple_action) {
                settings.columns.unshift({
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return (
                            '<div class="form-check"> <input class="form-check-input dt-checkboxes is-checkbox-delete" type="checkbox" data-id="' +
                            full.id +
                            '" /><label class="form-check-label"></label></div>'
                        );
                    },
                    checkboxes: {
                        selectAllRender:
                            '<div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="checkboxSelectAll" /><label class="form-check-label" for="checkboxSelectAll"></label></div>',
                    },
                });
            }

            const table = $(settings.target).DataTable({
                processing: false,
                serverSide: settings.url !== "",
                bDestroy: settings.every_action,
                order: [],
                ajax:
                    settings.url !== ""
                        ? {
                              url: settings.url,
                              beforeSend: function () {
                                  if (settings.every_action) {
                                      Swal.fire({
                                          html: '<div class="d-flex justify-content-center"><div class="sk-grid sk-secondary"><div class="sk-grid-cube"></div><div class="sk-grid-cube"></div><div class="sk-grid-cube"></div><div class="sk-grid-cube"></div><div class="sk-grid-cube"></div><div class="sk-grid-cube"></div><div class="sk-grid-cube"></div><div class="sk-grid-cube"></div><div class="sk-grid-cube"></div></div></div><br>Loading ...',
                                          allowOutsideClick: false,
                                          buttonsStyling: false,
                                          showConfirmButton: false,
                                      });
                                  }
                              },
                              data: function (d) {
                                  return $.extend({}, d, settings.ajaxData());
                              },
                              complete: function () {
                                  Swal.close();
                              },
                          }
                        : undefined,
                columns: settings.columns,
                // buttons: settings.btn,
                buttons: {
                    dom: {
                        button: {
                            className: 'btn',
                        },
                    },
                    buttons: settings.btn,
                },
                dom:
                    '<"row mb-3"<"col-sm-12 col-md-6 head-label text-start"><"col-sm-12 col-md-6 dt-action-buttons text-end"B>>' +
                    '<"row mb-3 "<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-end"f>>' +
                    // '<"row mb-3 "<"col-sm-12 col-md-6"<"d-flex"l>><"col-sm-12 col-md-6"<"d-flex justify-content-end"f>>>' +
                    '<"row mb-3"<"col-sm-12"t>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7 d-flex justify-content-end"p>>',
                // dom: "Bfrtip",
                displayLength: 10,
                columnDefs: [
                    {
                        className: "control",
                        orderable: false,
                        responsivePriority: 2,
                        targets: 0,
                    },
                ],
                responsive: {
                    details: {
                        type: "column",
                        target: "tr",
                    },
                },
                pagingType: "full_numbers",
                language: {
                    paginate: {
                        first: "<<",
                        last: ">>",
                        next: ">",
                        previous: "<",
                    },
                },
            });

            $(this)
                .closest(".row")
                .prev()
                .prev()
                .find("div.head-label")
                .html(
                    '<h5 class="card-title mb-0">' + settings.title + "</h5>"
                );

            table.reload = () => {
                table.ajax.reload();
            };
            return table;
        },
    });
})(jQuery);
