/**
 * App user list
 */

'use strict';

// Datatable (jquery)
$(function () {
    var dtUserTable = $('.datatables-users-role'),
        statusObj = {
            0: {title: 'Pending', class: 'bg-label-warning'},
            1: {title: 'Active', class: 'bg-label-success'},
            2: {title: 'Inactive', class: 'bg-label-secondary'}
        };

    var userView = 'app-user-view-account.html';

    // Users List datatable
    if (dtUserTable.length) {
        var RolesRoute = document.getElementById('fetchRoles').getAttribute('data-url');
        // var filePath = assetsPath + '/json/user-list.json';
        var dtUser = dtUserTable.DataTable({
            ajax: RolesRoute, // JSON file to add data
            columns: [
                // columns according to JSON
                {data: ''},
                {data: 'name'},
                {data: 'guard_name'},
                {data: 'status'},
                {data: 'created_at'},
                {data: ''}
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    // Plans
                    targets: 1,
                    render: function (data, type, full, meta) {
                        var $name = full['name'];

                        return '<span class="fw-semibold">' + $name + '</span>';
                    }
                },
                {
                    // Plans
                    targets: 2,
                    render: function (data, type, full, meta) {
                        var $guard_name = full['guard_name'];

                        return '<span class="fw-semibold">' + $guard_name + '</span>';
                    }
                },
                {
                    // User Status
                    targets: 3,
                    render: function (data, type, full, meta) {
                        var $status = full['status'];

                        return (
                            '<span class="badge ' +
                            statusObj[$status].class +
                            '" text-capitalized>' +
                            statusObj[$status].title +
                            '</span>'
                        );
                    }
                },
                {
                    // remove ordering from Name
                    targets: 4,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var $date = full['created_at'];
                        return '<span class="text-nowrap">' + $date + '</span>';
                    }
                },
                {
                    // Actions
                    targets: -1,
                    title: 'Actions',
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        var $roleId = full['id'];
                        var $deleted = full['deleted'];
                        var $status = full['status'];
                        var statusText = deletedText === 0 ?  $status === 1 ? 'Suspend' : 'Activate' : '';
                        var deletedText = $deleted === 0 ? '<i class="ti ti-trash ti-sm mx-2"></i>' : '<i class="fas fa-trash-restore-alt ti-sm mx-2"></i>';
                        var $action = $deleted === 1 ? 'restore' : 'delete';
                        return (
                            '<div class="d-flex align-items-center">' +
                            // '<a href="' + userView + '" class="btn btn-sm btn-icon"><i class="ti ti-eye"></i></a>' +
                            '<a href="javascript:;"  id="delete_role'+$roleId+'" data-role_id="'+$roleId+'" data-action_type="'+$action+'" class="text-body delete-record">'+deletedText+'</a>' +
                            '<a href="javascript:;" id="status_role'+$roleId+'" data-role_id="'+$roleId+'" class="dropdown-item">'+statusText+'</a>' +
                            // '<a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-sm mx-1"></i></a>' +
                            // '<div class="dropdown-menu dropdown-menu-end m-0">' +
                            // '<a href="javascript:;"" class="dropdown-item">Edit</a>' +
                            // '<a href="javascript:;" class="dropdown-item">Suspend</a>' +
                            '</div>' +
                            '</div>'
                        );
                    }
                }
            ],
            order: [[1, 'desc']],
            dom:
            '<"row mx-2"' +
            '<"col-sm-12 col-md-4 col-lg-6" l>' +
            '<"col-sm-12 col-md-8 col-lg-6"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end justify-content-center align-items-center flex-sm-nowrap flex-wrap me-1"<"me-3"f><"user_role w-px-200 pb-3 pb-sm-0">>>' +
            '>t' +
            '<"row mx-2"' +
            '<"col-sm-12 col-md-6"i>' +
            '<"col-sm-12 col-md-6"p>' +
            '>',
            language: {
                sLengthMenu: 'Show _MENU_',
                search: 'Search',
                searchPlaceholder: 'Search..'
            },
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
                // Adding role filter once table initialized
                this.api()
                    .columns(2)
                    .every(function () {
                        var column = this;
                        var select = $(
                            '<select id="UserRole" class="form-select text-capitalize"><option value=""> Select Role </option></select>'
                        )
                            .appendTo('.user_role')
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
                            });
                    });
            }
        });
    }
    // Delete Record
    $('.datatables-users-role tbody').on('click', '.delete-record', function () {
        dtUser.row($(this).parents('tr')).remove().draw();
    });

//     setTimeout(() = > { $('.dataTables_filter .form-control'
// ).removeClass('form-control-sm');
//     $('.dataTables_length .form-select').removeClass('form-select-sm');}, 300 );
});

(function () {
    // On edit role click, update text
    var roleEditList = document.querySelectorAll('.role-edit-modal'),
        roleAdd = document.querySelector('.add-new-role'),
        roleTitle = document.querySelector('.role-title');

    roleAdd.onclick = function () {
        roleTitle.innerHTML = 'Add New Role'; // reset text
    };
    if (roleEditList) {
        roleEditList.forEach(function (roleEditEl) {
            roleEditEl.onclick = function () {
                roleTitle.innerHTML = 'Edit Role'; // reset text
            };
        });
    }
})();
