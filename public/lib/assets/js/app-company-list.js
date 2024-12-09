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
    var dt_company_table = $('.datatables-company'),
        select2 = $('.select2'),
        userView = 'app-user-view-account.html',
        statusObj = {
            0: {title: 'Pending', class: 'bg-label-warning'},
            1: {title: 'Active', class: 'bg-label-success'}
        };

    // Company datatable
    if (dt_company_table.length) {
        var dataFilePath = '';
        var companyRoute = document.getElementById('fetchCompanyRoute').getAttribute('data-url');
        var dt_company = dt_company_table.DataTable({
            ajax: companyRoute, // JSON file to add data
            columns: [
                // columns according to JSON
                {data: ''},
                {data: 'company_name'},
                {data: 'parent_company'},
                {data: 'company_code'},
                {data: 'company_address'},
                {data: 'company_logo'},
                {data: 'is_active'},
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
                        var $name = full['company_name'],
                            $image = full['avatar'];
                        if ($image) {
                            // For Avatar image
                            var $output =
                                '<img src="' + assetsPath + 'img/avatars/' + $image + '" alt="Avatar" class="rounded-circle">';
                        } else {
                            // For Avatar badge
                            var stateNum = Math.floor(Math.random() * 6);
                            var states = ['success', 'danger', 'warning', 'info', 'primary', 'secondary'];
                            var $state = states[stateNum],
                                $name = full['company_name'],
                                $initials = $name.match(/\b\w/g) || [];
                            $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                            $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
                        }
                        // Creates full output for row
                        var $row_output =
                            '<div class="d-flex justify-content-start align-items-center user-name">' +
                            '<div class="avatar-wrapper">' +
                            '<div class="avatar avatar-sm me-3">' +
                            $output +
                            '</div>' +
                            '</div>' +
                            '<div class="d-flex flex-column">' +
                            '<a href="' +
                            userView +
                            '" class="text-body text-truncate"><span class="fw-semibold">' +
                            $name +
                            '</span></a>' +
                            '</div>' +
                            '</div>';
                        return $row_output;
                    }
                },
                {
                    // parent company
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var $parent_company = full['parent_company'];
                        return "<span class='text-truncate d-flex align-items-center'>" + $parent_company + '</span>';
                    }
                },
                {
                    // company code
                    targets: 3,
                    render: function (data, type, full, meta) {
                        var $company_code = full['company_code'];
                        return "<span class='text-truncate d-flex align-items-center'>" + $company_code + '</span>';
                    }
                },
                {
                    // address
                    targets: 4,
                    render: function (data, type, full, meta) {
                        var $company_address = full['company_address'];

                        return '<span class="fw-semibold">' + $company_address + '</span>';
                    }
                },
                {
                    // logo
                    targets: 5,
                    render: function (data, type, full, meta) {
                        var $company_logo = full['company_logo'];

                        return '<span class="fw-semibold">' + $company_logo + '</span>';
                    }
                },
                {
                    // Company Status
                    targets: 6,
                    render: function (data, type, full, meta) {
                        var $is_active = full['is_active'];

                        return (
                            '<span class="badge ' +
                            statusObj[$is_active].class +
                            '" text-capitalized>' +
                            statusObj[$is_active].title +
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
                        var $companyId = full['id'];
                        var $deleted = full['is_deleted'];
                        var $status = full['is_active'];
                        var statusText = $status === 1 ? 'Suspend' : 'Activate';
                        var deletedText = $deleted === 0 ? '<i class="ti ti-trash ti-sm mx-2"></i>' : '<i class="fas fa-trash-restore-alt ti-sm mx-2"></i>';
                        var $action = $deleted === 1 ? 'restore' : 'delete';
                        var deletedView = $deleted === 0 ? 'block' : 'none';
                        return (
                            '<div class="d-flex align-items-center">' +
                            '<a href="javascript:void(0)" style="display: '+deletedView+'" id="update_company' + $companyId + '" data-company_id="' + $companyId + '" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddCompany" class="text-body"><i class="ti ti-edit ti-sm me-2"></i></a>' +
                            '<a href="javascript:;" id="delete_company' + $companyId + '" data-company_id="' + $companyId + '" data-action_type="'+$action+'" class="text-body delete-record">'+deletedText+'</a>' +
                            '<a href="javascript:;" style="display: '+deletedView+'" data-company_id="' + $companyId + '" class="update-status" id="status' + $companyId + '" >' + statusText + '</a>' +
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
                    text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add New Company</span>',
                    className: 'add-new btn btn-primary',
                    attr: {
                        'data-bs-toggle': 'offcanvas',
                        'data-bs-target': '#offcanvasAddCompany'
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
                    .columns(6)
                    .every(function () {
                        var column = this;
                        var select = $(
                            '<select id="FilterCompany" class="form-select text-capitalize"><option value=""> Select Status </option></select>'
                        )
                            .appendTo('.company_status')
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
                                    statusObj[d].title +
                                    '" class="text-capitalize">' +
                                    statusObj[d].title +
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
            //             fetch('/company/save', {
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
