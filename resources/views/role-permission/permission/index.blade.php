<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">Permissions List</h4>
        <x-slot name="links">

        </x-slot>


    {{--<p class="mb-4">--}}
    {{--Each category (Basic, Professional, and Business) includes the four predefined roles shown below.--}}
    {{--</p>--}}

    <!-- Permission Table -->
        <div class="card">
            <form id="insertRolePermission" class="row g-3" action="{{route('roles.permission')}}"
                  method="POST">
                {{csrf_field()}}

                <div class="container mt-5">
                    <h2>Permissions</h2>
                    <p>Assign permission to roles.</p>
                    <h5 class="text-primary">ROLE — Has Permission.</h5>
                    <div class="col-6 form-check mb-2 p-0">
                        <label class="form-check-label" for="UserRole">Roles</label>
                        <select id="UserRole" name="role" class="form-select text-capitalize">
                        </select>
                    </div>
                    <hr>
                    <div class="form-check mb-2">
                        <input class="form-check-input" name="selectAllPermission" type="checkbox"
                               id="selectAllPermission"
                               onclick="selectAll(this, 'permissions')">
                        <label class="form-check-label" for="selectAllPermission">Select All Permissions</label>
                    </div>
                    @foreach ($data as $module => $permissions)
                        <div class="mb-3" id="permissions-list">
                            <h5>{{ $module }}</h5>
                            @foreach ($permissions as $key=>$permission)
                                <div id="permissions{{$key}}" class="row permissions">
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permission[]"
                                                   id="{{$module}}.'_'.{{ $permission['guard_name'] }}"
                                                   value="{{ $permission['id'] }}">
                                            <label class="form-check-label"
                                                   for="{{ $permission['guard_name'] }}">{{ $permission['name'] }}</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                    @endforeach
                </div>

                <div class="col-12 text-center mt-4 mb-4">
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

            <div class="card-datatable table-responsive" style="display:none">
                <table class="datatables-permissions table border-top">
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Name</th>
                        <th>Assigned To</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--/ Permission Table -->

        <!-- Modal -->
        <!-- Add Permission Modal -->
        <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <button
                            type="button"
                            class="btn-close btn-pinned"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                    ></button>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Add New Permission</h3>
                            <p class="text-muted">Permissions you may use and assign to your users.</p>
                        </div>
                        <form id="addPermissionForm" class="row" onsubmit="return false">
                            <div class="col-12 mb-3">
                                <label class="form-label" for="modalPermissionName">Permission Name</label>
                                <input
                                        type="text"
                                        id="modalPermissionName"
                                        name="modalPermissionName"
                                        class="form-control"
                                        placeholder="Permission Name"
                                        autofocus
                                />
                            </div>
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="corePermission"/>
                                    <label class="form-check-label" for="corePermission"> Set as core
                                        permission </label>
                                </div>
                            </div>
                            <div class="col-12 text-center demo-vertical-spacing">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Create Permission</button>
                                <button
                                        type="reset"
                                        class="btn btn-label-secondary"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                >
                                    Discard
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Add Permission Modal -->

        <!-- Edit Permission Modal -->
        <div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <button
                            type="button"
                            class="btn-close btn-pinned"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                    ></button>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Edit Permission</h3>
                            <p class="text-muted">Edit permission as per your requirements.</p>
                        </div>
                        <div class="alert alert-warning" role="alert">
                            <h6 class="alert-heading mb-2">Warning</h6>
                            <p class="mb-0">
                                By editing the permission name, you might break the system permissions functionality.
                                Please
                                ensure you're absolutely certain before proceeding.
                            </p>
                        </div>
                        <form id="editPermissionForm" class="row" onsubmit="return false">
                            <div class="col-sm-9">
                                <label class="form-label" for="editPermissionName">Permission Name</label>
                                <input
                                        type="text"
                                        id="editPermissionName"
                                        name="editPermissionName"
                                        class="form-control"
                                        placeholder="Permission Name"
                                        tabindex="-1"
                                />
                            </div>
                            <div class="col-sm-3 mb-3">
                                <label class="form-label invisible d-none d-sm-inline-block">Button</label>
                                <button type="submit" class="btn btn-primary mt-1 mt-sm-0">Update</button>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="editCorePermission"/>
                                    <label class="form-check-label" for="editCorePermission"> Set as core
                                        permission </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Edit Permission Modal -->

        <!-- /Modal -->
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('lib/assets/js/app-access-permission.js') }}"></script>
        <div id="fetchPermissions" data-url="{{ route('permissions.list') }}"></div>
        <script>
            function selectAll(selectAllCheckbox, sectionClass) {
                var checkboxes = document.querySelectorAll('.' + sectionClass + ' .form-check-input');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            }
        </script>

        <script>
            $.ajax({
                url: '{{route('roles.list')}}', // Your route to fetch roles
                type: 'GET',
                success: function (response) {
                    console.log(response)
                    $('#UserRole').empty().append('<option value="">Select Role</option>'); // Reset options

                    if (response.data && response.data.length > 0) {
                        // Populate role dropdown
                        response.data.forEach(function (role) {
                            $('#UserRole').append(
                                '<option value="' + role.id + '">' + role.name + '</option>'
                            );
                        });
                    } else {
                        $('#role-dropdown').append('<option value="">No roles available</option>');
                    }
                },
                error: function (xhr) {
                    console.error('Error fetching roles:', xhr.responseText);
                    $('#role-dropdown').empty().append('<option value="">Error loading roles</option>');
                }
            });
        </script>

        <script>
            $('#UserRole').on('change', function () {
                const selectedRoleId = $(this).val();
                $('#permissions-list input[type="checkbox"]').prop('checked', false);
                if (selectedRoleId) {
                    $.ajax({
                        url: '{{route('permissions.by.role')}}',
                        type: 'GET',
                        data: {role_id: selectedRoleId},
                        success: function (response) {
                            if (response.permissions && Array.isArray(response.permissions)) {
                                response.permissions.forEach(function (permissionId) {
                                    $('#permissions-list input[value="' + permissionId + '"]').prop('checked', true);
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
            });
        </script>

    </x-slot>

</x-app-layout>