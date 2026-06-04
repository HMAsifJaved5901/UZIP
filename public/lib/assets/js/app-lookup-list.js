/**
 * Page User List
 */

'use strict';

// Datatable (jquery)
$(function () {
    var borderColor, bodyBg, headingColor;

    if (isDarkStyle) {
        borderColor = config.colors_dark.borderColor;
        bodyBg = config.colors_dark.bodyBg;
        headingColor = config.colors_dark.headingColor;
    } else {
        borderColor = config.colors.borderColor;
        bodyBg = config.colors.bodyBg;
        headingColor = config.colors.headingColor;
    }

    // Variable declaration for table
    var dt_lookup_table = $('.datatables-lookup'),
        select2 = $('.select2'),
        userView = 'app-user-view-account.html',
        statusObj = {
            1: {is_deleted: 'Disabled', class: 'bg-label-warning'},
            0: {is_deleted: 'Active', class: 'bg-label-success'}
        };


    // Users datatable
    if (dt_lookup_table.length) {
        var dataFilePath = '';
        var lookup_type = '';
        var usersRoute = document.getElementById('fetchLookupRoute').getAttribute('data-url');
        var dt_user = dt_lookup_table.DataTable({
            ajax: usersRoute, // JSON file to add data
            columns: [
                // columns according to JSON
                {data: ''},
                {data: 'name'},
                {data: 'description'},
                {data: 'is_deleted'},
                {data: ''}
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: 'control',
                    searchable: false,
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },                {
                    // Lookup value
                    targets: 1,
                    render: function (data, type, full, meta) {
                        var $value = full['value'];
                        lookup_type = full['type'];
                        return "<span class='text-truncate d-flex align-items-center'>" + $value + '</span>';
                    }
                },
                {
                    // lookup Description
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var $description = full['description'];

                        return '<span class="fw-semibold">' + $description + '</span>';
                    }
                },
                {
                    // lookup Description
                    targets: 3,
                    render: function (data, type, full, meta) {
                        var $is_deleted = full['is_deleted'];
                        return (
                            '<span class="badge ' +
                            statusObj[$is_deleted].class +
                            '" text-capitalized>' +
                            statusObj[$is_deleted].is_deleted +
                            '</span>'
                        );
                    }
                },
                {
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var $id = full['id'];
                        var $deleted = full['is_deleted'];
                        var deletedText = $deleted === 0 ? '<i class="ti ti-trash ti-sm mx-2"></i>' : '<i class="fas fa-trash-restore-alt ti-sm mx-2"></i>';
                        var editText = $deleted === 0 ? '<i class="ti ti-edit ti-sm me-2"></i>' : '';
                        var $action = $deleted === 1 ? 'restore' : 'delete';
                        return (
                            '<div class="d-flex align-items-center justify-content-end">' +
                            '<a href="javascript:void(0)" id="update_lookup' + $id + '" data-lookupid="' + $id + '" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddLookup" class="text-body btn-edit">'+editText+'</a>' +
                            '<a href="javascript:;" data-action_type="' + $action + '" id="delete_lookup' + $id + '" data-lookupid="' + $id + '" class="text-body delete-record btn-delete">'+deletedText+'</a>' +
                            '</div>' +
                            '</div>'
                        );
                    }
                }
            ],
            order: [[1, 'desc']],
            dom:
            '<"row me-2"' +
            '<"col-md-2"<"me-3"l>>' +
            '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
            '>t' +
            '<"row mx-2"' +
            '<"col-sm-12 col-md-6"i>' +
            '<"col-sm-12 col-md-6"p>' +
            '>',
            language: {
                sLengthMenu: '_MENU_',
                search: '',
                searchPlaceholder: 'Search..'
            },
            // Buttons with Dropdown
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-label-secondary dropdown-toggle mx-3 bg-custom-black text-white',
                    text: '<i class="ti ti-screen-share me-1 ti-xs"></i>Export',
                    buttons: [
                        {
                            extend: 'print',
                            text: '<i class="ti ti-printer me-2" ></i>Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be print
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            },
                            customize: function (win) {
                                //customize print view for dark
                                $(win.document.body)
                                    .css('color', headingColor)
                                    .css('border-color', borderColor)
                                    .css('background-color', bodyBg);
                                $(win.document.body)
                                    .find('table')
                                    .addClass('compact')
                                    .css('color', 'inherit')
                                    .css('border-color', 'inherit')
                                    .css('background-color', 'inherit');
                            }
                        },
                        {
                            extend: 'csv',
                            text: '<i class="ti ti-file-text me-2" ></i>Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'copy',
                            text: '<i class="ti ti-copy me-2" ></i>Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5],
                                // prevent avatar to be display
                                format: {
                                    body: function (inner, coldex, rowdex) {
                                        if (inner.length <= 0) return inner;
                                        var el = $.parseHTML(inner);
                                        var result = '';
                                        $.each(el, function (index, item) {
                                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                                result = result + item.lastChild.firstChild.textContent;
                                            } else if (item.innerText === undefined) {
                                                result = result + item.textContent;
                                            } else result = result + item.innerText;
                                        });
                                        return result;
                                    }
                                }
                            }
                        }
                    ]
                },
                {
                    text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add new '+lookup_type+'</span>',
                    className: 'add-new btn btn-primary Rectangle_4',
                    attr: {
                        'data-bs-toggle': 'offcanvas',
                        'data-bs-target': '#offcanvasAddLookup'
                    }
                }
            ],
            // For responsive popup
            // responsive: {
            //     details: {
            //         display: $.fn.dataTable.Responsive.display.modal({
            //             header: function (row) {
            //                 var data = row.data();
            //                 return 'Details of ' + data['value'];
            //             }
            //         }),
            //         type: 'column',
            //         renderer: function (api, rowIdx, columns) {
            //             var data = $.map(columns, function (col, i) {
            //                 return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
            //                     ? '<tr data-dt-row="' +
            //                     col.rowIndex +
            //                     '" data-dt-column="' +
            //                     col.columnIndex +
            //                     '">' +
            //                     '<td>' +
            //                     col.title +
            //                     ':' +
            //                     '</td> ' +
            //                     '<td>' +
            //                     col.data +
            //                     '</td>' +
            //                     '</tr>'
            //                     : '';
            //             }).join('');

            //             return data ? $('<table class="table"/><tbody />').append(data) : false;
            //         }
            //     }
            // },
            initComplete: function () {
                // Adding status filter once table initialized
                this.api()
                    .columns(3)
                    .every(function () {
                        var column = this;
                        var select = $(
                            '<select id="FilterService" class="form-select text-capitalize"><option value=""> Select Status </option></select>'
                        )
                            .appendTo('.lookup_status')
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                select.append(
                                    '<option value="' +
                                    statusObj[d].is_deleted +
                                    '" class="text-capitalize">' +
                                    statusObj[d].is_deleted +
                                    '</option>'
                                );
                            });
                    });
            }
        });
    }

    // Delete Record
    // $('.datatables-users tbody').on('click', '.delete-record', function () {
    //     dt_user.row($(this).parents('tr')).remove().draw();
    //     Swal.fire(
    //         'Deleted!',
    //         response.message,
    //         'success'
    //     );
    // });

    // Filter form control to default size
    // ? setTimeout used for multilingual table initialization
    setTimeout(function () {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);
});

// Validation & Phone mask
(function () {
    const phoneMaskList = document.querySelectorAll('.phone-mask'),
        addNewUserForm = document.getElementById('addNewUserForm');

    // Phone Number
    if (phoneMaskList) {
        phoneMaskList.forEach(function (phoneMask) {
            new Cleave(phoneMask, {
                phone: true,
                phoneRegionCode: 'US'
            });
        });
    }
    // Add New User Form Validation
    const fv = FormValidation.formValidation(addNewUserForm, {
        fields: {
            userFullname: {
                validators: {
                    notEmpty: {
                        message: 'Please enter fullname '
                    }
                }
            },
            userEmail: {
                validators: {
                    notEmpty: {
                        message: 'Please enter your email'
                    },
                    emailAddress: {
                        message: 'The value is not a valid email address'
                    }
                }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                // Use this for enabling/changing valid/invalid class
                eleValidClass: '',
                rowSelector: function (field, ele) {
                    // field is the field name & ele is the field element
                    return '.mb-3';
                }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            // Submit the form when all fields are valid
            // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        }
    });
})();
