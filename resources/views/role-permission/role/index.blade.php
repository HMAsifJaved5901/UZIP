<x-app-layout>
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">Designation List</h4>
        <div class="row g-4">

            <div class="col-12">
                <!-- Role Table -->
                <div class="card">
                    <div class="card-datatable table-responsive">
                        <table class="datatables-users-role table border-top custom-table-design">
                            <thead class="bg-custom-black">
                            <tr>
                                <th></th>
                                <th>Designation</th>
                                <th>Status</th>
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
                            <h3 class="role-title mb-2">Add/Update Designation</h3>
                            <p class="text-muted"></p>
                        </div>
                        <!-- Add role form -->
                        <form id="addRolePermissionForm" class="row g-3" action="{{route('roles.save')}}"
                              method="POST">
                            <input type="hidden" name="id" value="" id="role_id">
                            {{csrf_field()}}
                            <div class="col-12 mb-4">
                                <label class="form-label" for="modalRoleName">Name</label>
                                <input
                                        type="text"
                                        id="modalRoleName"
                                        name="RoleName"
                                        class="form-control"
                                        placeholder="Enter A Designation Name"
                                        tabindex="-1"
                                />
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
                var btnText = (action === 'restore') ? "Please proceed to restore Suspended data!" : "This Role won't be able to perform any action!";
                var confirmBtnText = (action === 'restore') ? "Yes, restore it!" : "Yes, Suspend it!";
                var SuccessTitle = (action === 'restore') ? "Restore!" : "Suspended!";
                var SuccessText = (action === 'restore') ? "The Role has been restored.!" : "The Role has been Suspended!";

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