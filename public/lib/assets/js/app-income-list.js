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

    var viewIncome = document.getElementById('fetchIncomeViewRoute').getAttribute('data-url');
    // Variable declaration for table
    var dt_income_table = $('.datatables-income'),
        select2 = $('.select2'),
        statusObj = {
            0: {is_deleted: 'Active', class: 'bg-label-success'},
            1: {is_deleted: 'Suspended', class: 'bg-label-success'}
        };

    // Company datatable
    if (dt_income_table.length) {
        var dataFilePath = '';
        var incomeRoute = document.getElementById('fetchIncomeRoute').getAttribute('data-url');
        var dt_company = dt_income_table.DataTable({
            ajax: incomeRoute, // JSON file to add data
            columns: [
                // columns according to JSON
                {data: ''},
                {data: 'income_date'},
                {data: 'station_name'},
                {data: 'service_name'},
                {data: 'category_name'},
                {data: 'amount'},
                {data: 'description'},
                {data: 'is_deleted'},
                {data: 'action'}
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
                },
                {
                    // User full name and email
                    targets: 1,
                    responsivePriority: 4,
                    render: function (data, type, full, meta) {

                        var $income_date = full['income_date'],
                            $image = full['avatar'],
                            $id = full['id'];
                        var userView = viewIncome.replace(':id', full['id']);
                        // Creates full output for row
                        var $row_output =
                            '<div class="d-flex justify-content-start align-items-center user-name">' +
                            '<div class="d-flex flex-column">' +
                            '<a href="' +
                            userView +
                            '" class="text-body text-truncate"><span class="fw-semibold">' +
                            $income_date +
                            '</span></a>' +
                            '</div>' +
                            '</div>';
                        return $row_output;
                    }
                },
                {
                    // Plans
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var $station_name = full['station_name'];

                        return '<span class="fw-semibold">' + $station_name + '</span>';
                    }
                },
                {
                    // Plans
                    targets: 3,
                    render: function (data, type, full, meta) {
                        var $service_name = full['service_name'];

                        return '<span class="fw-semibold">' + $service_name + '</span>';
                    }
                },
                {
                    // Plans
                    targets: 4,
                    render: function (data, type, full, meta) {
                        var $category_name = full['category_name'];

                        return '<span class="fw-semibold">' + $category_name + '</span>';
                    }
                },
                {
                    // Plans
                    targets: 5,
                    render: function (data, type, full, meta) {
                        var $amount = full['amount'];

                        return '<span class="fw-semibold">$ ' + $amount + '</span>';
                    }
                },
                {
                    // Plans
                    targets: 6,
                    render: function (data, type, full, meta) {
                        var $description = full['description'];

                        return '<span class="fw-semibold">' + $description + '</span>';
                    }
                },
                {
                    // User Status
                    targets: 7,
                    render: function (data, type, full, meta) {
                        var $is_deleted = full['is_deleted'];
                        return (
                            '<span class="badge ' +
                            statusObj[$is_deleted].class +
                            '" text-capitalized>' +
                            statusObj[$is_deleted].is_active +
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
                        var $incomeId = full['id'];
                        var $deleted = full['is_deleted'];
                        var $status = full['is_active'];
                        var statusText = $status === 1 ? 'Suspend' : 'Activate';
                        var deletedText = $deleted === 0 ? '<i class="ti ti-trash ti-sm mx-2"></i>' : '<i class="fas fa-trash-restore-alt ti-sm mx-2"></i>';
                        var $action = $deleted === 1 ? 'restore' : 'delete';
                        var deletedView = $deleted === 0 ? 'block' : 'none';
                        var userView = viewIncome.replace(':id', full['id']);
                        return (
                            '<div class="d-flex align-items-center">' +
                            '<a href="javascript:;" style="display: ' + deletedView + '" id="update_income' + full['id'] + '" data-income_id="' + full['id'] + '" tabindex="0" aria-controls="DataTables_Table_0" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddIncome" class="text-body"><i class="ti ti-edit ti-sm me-2"></i></a>' +
                            '<a href="javascript:;" id="delete_income' + full['id'] + '" data-income_id="' + full['id'] + '" data-action_type="' + $action + '" class="text-body delete-record">' + deletedText + '</a>' +
                            '<a href="' + userView + '" id="view_income' + full['id'] + '" class="text-body"><i class="ti ti-eye ti-sm me-2"></i></a>' +
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
                    className: 'btn btn-label-secondary dropdown-toggle mx-3',
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
                    text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add Income</span>',
                    className: 'add-new btn btn-primary',
                    attr: {
                        'data-bs-toggle': 'offcanvas',
                        'data-bs-target': '#offcanvasAddIncome'
                    }
                }
            ],
            // For responsive popup
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['full_name'];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>'
                                : '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                }
            },
            initComplete: function () {
                // Adding status filter once table initialized
                this.api()
                    .columns(5)
                    .every(function () {
                        var column = this;
                        var select = $(
                            '<select id="FilterIncome" class="form-select text-capitalize"><option value=""> Select Status </option></select>'
                        )
                            .appendTo('.income_status')
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
    // $('.datatables-company tbody').on('click', '.delete-record', function () {
    //     dt_company.row($(this).parents('tr')).remove().draw();
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
        addNewCompanyForm = document.getElementById('addNewCompanyFormInPro');

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
    const fv = FormValidation.formValidation(addNewCompanyForm, {
        fields: {
            company_name: {
                validators: {
                    notEmpty: {
                        message: 'Please enter company name!'
                    }
                }
            },
            company_code: {
                validators: {
                    notEmpty: {
                        message: 'Please enter company registration no!'
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
            // submitButton: new FormValidation.plugins.SubmitButton({
            //     onValid: function () {
            //         // Custom form submission logic
            //         var formData = new FormData(addNewCompanyForm);
            //             fetch('company/save', {
            //                 method: 'POST',
            //                 body: formData,
            //             })
            //             .then(function(response) {
            //                 return response.json(); // Ensure you return the result here
            //             })
            //             .then(function(result) {
            //                 if (result.success) {
            //                     Swal.fire('Success!', result.message, 'success');
            //                     $('#offcanvasAddCompany').modal('hide');
            //                     dt_company.ajax.reload();
            //                 } else {
            //                     Swal.fire('Error!', result.message, 'error');
            //                 }
            //             })
            //             .catch(function(error) {
            //             console.error('Form submission error:', error);
            //         Swal.fire('Error!', 'Could not add the company.', 'error');
            //     });
            //     }
            // }),

            // Submit the form when all fields are valid
            // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        }
    });
})();
