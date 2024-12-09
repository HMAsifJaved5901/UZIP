<x-app-layout>
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">Roles List</h4>

        <p class="mb-4">
            A role provided access to predefined menus and features so that depending on <br/>
            assigned role an administrator can have access to what user needs.
        </p>
        <!-- Role cards -->
        <div class="row g-4">
            {{--Add New Role--}}
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card h-100">
                    <div class="row h-100">
                        <div class="col-sm-5">
                            <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                                <img
                                        src="{{ asset('lib//assets/img/illustrations/add-new-roles.png') }}"
                                        class="img-fluid mt-sm-4 mt-md-0"
                                        alt="add-new-roles"
                                        width="83"
                                />
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="card-body text-sm-end text-center ps-sm-0">
                                <button
                                        data-bs-target="#addRoleModal"
                                        data-bs-toggle="modal"
                                        class="btn btn-primary mb-2 text-nowrap add-new-role"
                                >
                                    Add New Role
                                </button>
                                <p class="mb-0 mt-1">Add role, if it does not exist</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($data as $role)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-normal mb-2">Total 4 users</h6>
                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                    <li
                                            data-bs-toggle="tooltip"
                                            data-popup="tooltip-custom"
                                            data-bs-placement="top"
                                            title="Vinnie Mostowy"
                                            class="avatar avatar-sm pull-up"
                                    >
                                        <img class="rounded-circle" src="{{ asset('lib//assets/img/avatars/5.png') }}"
                                             alt="Avatar"/>
                                    </li>
                                    <li
                                            data-bs-toggle="tooltip"
                                            data-popup="tooltip-custom"
                                            data-bs-placement="top"
                                            title="Allen Rieske"
                                            class="avatar avatar-sm pull-up"
                                    >
                                        <img class="rounded-circle" src="{{ asset('lib//assets/img/avatars/12.png') }}"
                                             alt="Avatar"/>
                                    </li>
                                    <li
                                            data-bs-toggle="tooltip"
                                            data-popup="tooltip-custom"
                                            data-bs-placement="top"
                                            title="Julee Rossignol"
                                            class="avatar avatar-sm pull-up"
                                    >
                                        <img class="rounded-circle" src="{{ asset('lib//assets/img/avatars/6.png') }}"
                                             alt="Avatar"/>
                                    </li>
                                    <li
                                            data-bs-toggle="tooltip"
                                            data-popup="tooltip-custom"
                                            data-bs-placement="top"
                                            title="Kaith D'souza"
                                            class="avatar avatar-sm pull-up"
                                    >
                                        <img class="rounded-circle" src="{{ asset('lib//assets/img/avatars/3.png') }}"
                                             alt="Avatar"/>
                                    </li>
                                    <li
                                            data-bs-toggle="tooltip"
                                            data-popup="tooltip-custom"
                                            data-bs-placement="top"
                                            title="John Doe"
                                            class="avatar avatar-sm pull-up"
                                    >
                                        <img class="rounded-circle" src="{{ asset('lib//assets/img/avatars/1.png') }}"
                                             alt="Avatar"/>
                                    </li>
                                </ul>
                            </div>
                            <div class="d-flex justify-content-between align-items-end mt-1">
                                <div class="role-heading">
                                    <h4 class="mb-1">{{$role->name}}</h4>
                                    <a
                                            id="{{$role->id}}"
                                            href="javascript:;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addRoleModal"
                                            data-role-id="{{$role->id}}"
                                            onclick="editRoleTrigger({{$role->id}});"
                                            class="role-edit-modal">
                                        <span>Edit/View Role</span>
                                    </a>
                                </div>
                                <a href="javascript:void(0);" onclick="copyToClipboard(this)" data-copy="{{$role->name}}" class="text-muted"><i class="ti ti-copy ti-md"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-12">
                <!-- Role Table -->
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-users-role table border-top">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Role</th>
                                <th>Guard</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!--/ Role Table -->
            </div>
        </div>
        <!--/ Role cards -->

        <!-- Add Role Modal -->
        <!-- Add Role Modal -->
        <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
                <div class="modal-content p-3 p-md-5">
                    <button
                            type="button"
                            class="btn-close btn-pinned"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                    ></button>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <h3 class="role-title mb-2">Add New Role</h3>
                            <p class="text-muted">Set role permissions</p>
                        </div>
                        <!-- Add role form -->
                        <form id="addRolePermissionForm" class="row g-3" action="{{route('roles.save')}}"
                              method="POST">
                            <input type="hidden" name="id" value="" id="role_id">
                            {{csrf_field()}}
                            <div class="col-12 mb-4">
                                <label class="form-label" for="modalRoleName">Role Name</label>
                                <input
                                        type="text"
                                        id="modalRoleName"
                                        name="RoleName"
                                        class="form-control"
                                        placeholder="Enter a role name"
                                        tabindex="-1"
                                />
                            </div>
                            <div class="col-12">
                                <h5>Role Permissions</h5>
                                <!-- Permission table -->
                                <div class="table-responsive">
                                    <table class="table table-flush-spacing">
                                        <tbody>
                                        <tr>
                                            <td class="text-nowrap fw-semibold">
                                                Administrator Access
                                                <i
                                                        class="ti ti-info-circle"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Allows a full access to the system"
                                                ></i>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="admin" type="checkbox"
                                                           id="selectAll"/>
                                                    <label class="form-check-label" for="selectAll"> Select All </label>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($permissions as $module=>$permission)
                                            <tr>
                                                <td class="text-nowrap fw-semibold">{{$module}}</td>
                                                <td>
                                                    <div class="row">
                                                        @foreach ($permission as $pem)
                                                            <div class="col-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" name="permissions[]"
                                                                           type="checkbox" value="{{$pem['id']}}"
                                                                           id="{{$pem['slug'].$pem['id']}}"/>
                                                                    <label class="form-check-label"
                                                                           for="{{$pem['slug'].$pem['id']}}">
                                                                        {{$pem['slug']}}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Permission table -->
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                                <button
                                        type="reset"
                                        class="btn btn-label-secondary"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                        <!--/ Add role form -->
                    </div>
                </div>
            </div>
        </div>
        <!--/ Add Role Modal -->
        <!-- / Add Role Modal -->
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('lib/assets/js/app-access-roles.js') }}"></script>
        <div id="fetchRoles" data-url="{{ route('roles.list') }}"></div>

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ $errors->first() }}'
                });
            </script>
        @endif

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}'
                });
            </script>
        @endif

        <script>
            document.getElementById('addRoleModal').addEventListener('shown.bs.modal', function (event) {
                const triggerElement = event.relatedTarget; // Element that triggered the modal
                const roleId = triggerElement.getAttribute('data-role-id'); // Get the role ID
                editRoleTrigger(roleId); // Call your function
            });

            function editRoleTrigger(roleId) {
                if (roleId) {
                    $.ajax({
                        url: '{{route('fetch.role')}}',
                        type: 'GET',
                        data: {id: roleId},
                        success: function (response) {
                            var roleId = response.data.id;
                            var roleName = response.data.name;
                            if (roleId && roleName) {
                                $('#modalRoleName').val(roleName);
                                $('#role_id').val(roleId);
                                if (roleId) {
                                    $.ajax({
                                        url: '{{route('permissions.by.role')}}',
                                        type: 'GET',
                                        data: {role_id: roleId},
                                        success: function (response) {
                                            if (response.permissions && Array.isArray(response.permissions)) {
                                                $('input[name="permissions[]"]').prop('checked', false);
                                                response.permissions.forEach(function (permissionId) {
                                                    const checkbox = $('input[value="' + permissionId + '"]');
                                                    if (checkbox.length) {
                                                        checkbox.prop('checked', true);
                                                    }
                                                });
                                            } else {
                                                console.error('Invalid permissions data:', response.permissions);
                                            }
                                        },
                                        error: function (xhr) {
                                            console.error('Error fetching permissions:', xhr.responseText);
                                        }
                                    });
                                }
                            } else {
                                console.error('Invalid role data:', response.dat);
                            }
                        },
                        error: function (xhr) {
                            console.error('Error fetching role:', xhr.responseText);
                        }
                    });
                }
            }
        </script>

        <script>
            $(document).on('click', '.delete-record', function () {
                const roleId = $(this).data('role_id');
                const action = $(this).data('action_type');

                var btnTitle = (action === 'restore') ? "Sure to Proceed?" : "Are you sure?";
                var btnText = (action === 'restore') ? "Please proceed to restore deleted data!" : "This Role won't be able to perform any action!";
                var confirmBtnText = (action === 'restore') ? "Yes, restore it!" : "Yes, delete it!";
                var SuccessTitle = (action === 'restore') ? "Restore!" : "Deleted!";
                var SuccessText = (action === 'restore') ? "The Role has been restored.!" : "The Role has been deleted!";

                Swal.fire({
                    title: btnTitle,
                    text: btnText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmBtnText,
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) { // Check if the user clicked "Yes"
                        $.ajax({
                            url: '{{ url('roles') }}/'+roleId , // Use template literals for dynamic URLs
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}' // CSRF token for Laravel
                            },
                            success: function (response) {
                                Swal.fire(
                                    SuccessTitle,
                                    SuccessText,
                                    'success'
                                ).then(function () {
                                    location.reload(); // Reload the page or handle UI updates
                                });
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    'Error!',
                                    xhr.responseJSON.message || 'Something went wrong.',
                                    'error'
                                );
                            }
                        });

                    }
                });

            });
        </script>

        <script>
            function copyToClipboard(element) {
                const textToCopy = element.getAttribute('data-copy');
                if (textToCopy) {
                    navigator.clipboard.writeText(textToCopy).then(function () {
                        // Success feedback
                        Swal.fire(
                            'Copied!',
                            'Text has been copied to the clipboard.',
                            'success'
                        );
                    }).catch(function (err) {
                        console.error('Failed to copy: ', err);
                        Swal.fire(
                            'Error!',
                            'Unable to copy text.',
                            'error'
                        );
                    });
                } else {
                    Swal.fire('Error!', 'No text to copy!', 'error');
                }
            }
        </script>

    </x-slot>

    <!-- / Content -->
</x-app-layout>