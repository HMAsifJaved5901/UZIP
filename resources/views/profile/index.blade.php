<x-app-layout>
    <x-slot name="links">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"
              rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
    </x-slot>
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Employee List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 user_role"></div>
                    {{--<div class="col-md-4 user_plan"></div>--}}
                    {{--<div class="col-md-4 user_status"></div>--}}
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-table-design">
                    <thead class="bg-custom-black">
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new Employee -->
            <div
                    {{--class="offcanvas offcanvas-end"--}}
                    class="offcanvas custom-centered-modal"
                    tabindex="-1"
                    id="offcanvasAddUser"
                    aria-labelledby="offcanvasAddUserLabel"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Employee</h5>
                    <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-user pt-0" id="addNewUserFormInPro" method="POST"
                          action="{{ route('create.user') }}">
                        @csrf
                        <input type="hidden" value="" name="user_id" id="user_id">
                        <div class="mb-3">
                            <label class="form-label" for="add-user-first-name">First name</label>
                            <input
                                    type="text"
                                    id="add-user-first-name"
                                    class="form-control"
                                    aria-label="john"
                                    name="first_name"
                                    required
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-user-last-name">Last name</label>
                            <input
                                    type="text"
                                    id="add-user-last-name"
                                    class="form-control"
                                    aria-label="Doe"
                                    name="last_name"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="user-role">Designation</label>
                            <select id="user-role" class="form-select" name="default_role_id">
                                <option value="">Select Designation</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}" data-code="{{$role->r_code}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="user-role">Login Authorization</label>
                            <div class="d-flex gap-3 align-items-center mt-2">
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" id="login_mobile"
                                           name="login_mobile" value="1">
                                    <label class="form-check-label" for="login_mobile">Mobile</label>
                                </div>
                                <div class="form-check form-check-success">
                                    <input class="form-check-input" type="checkbox" id="login_web" name="login_web"
                                           value="1">
                                    <label class="form-check-label" for="login_web">Web</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 d-none" id="add-user-email-div">
                            <label class="form-label" for="add-user-email">Email</label>
                            <input
                                    type="text"
                                    id="add-user-email"
                                    class="form-control"
                                    placeholder="john.doe@example.com"
                                    aria-label="john.doe@example.com"
                                    name="email"
                                    autocomplete="off"
                            />
                        </div>
                        <div class="mb-4 d-none" id="add-user-email-div">
                            <label class="form-label" for="add-user-email">Password</label>
                            <input
                                    type="password"
                                    id="add-user-password"
                                    class="form-control"
                                    placeholder="**********"
                                    aria-label="***********"
                                    name="password"
                                    autocomplete="off"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="station-select">Sites</label>
                            <input type="text"
                                   id="station-select"
                                   name="station_id"
                                   class="form-control"
                                   placeholder="Add Sites">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-user-contact">Contact</label>
                            <input
                                    type="text"
                                    id="add-user-contact"
                                    class="form-control phone-mask"
                                    aria-label="john.doe@example.com"
                                    name="contact_no"
                            />
                        </div>

                        <br>
                        <br>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('lib/assets/js/app-user-list.js') }}"></script>

        <div id="fetchUsersRoute" data-url="{{ route('get.users') }}"></div>
        <div id="fetchUserViewRoute" data-url="{{ route('user.view', ':id') }}"></div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: '{!! implode("<br>", $errors->all()) !!}'
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
            function toggleLoginFields() {
                const on = $("#login_mobile").is(":checked") || $("#login_web").is(":checked");
                $("#add-user-email-div, #add-user-password-div").toggleClass("d-none", !on);
            }

            $('#offcanvasAddUser').on('shown.bs.modal', function () {
                toggleLoginFields();
            });

            $("#login_mobile, #login_web").on("change", toggleLoginFields);

            $(document).on('change', '#country', function () {
                var selectedOption = $(this).find(':selected'); // Get the selected <option>
                var code = selectedOption.data('code'); // Get the 'data-code' attribute
                var placeholder = selectedOption.data('placeholder'); // Get the 'data-placeholder' attribute

                $('#add-user-contact').val(placeholder); // Update the input with the placeholder
            });

            const offcanvasEl = document.getElementById('offcanvasAddUser');
            offcanvasEl.addEventListener('show.bs.offcanvas', function (event) {
                const trigger = event.relatedTarget || document.activeElement;
                const mode = trigger ? trigger.getAttribute('data-mode') : null;
                const form = document.getElementById('addNewUserFormInPro');
                if (mode === 'add') {
                    form.reset();
                    var addMethodBtn = document.getElementById('addMethodBtn');
                    addMethodBtn.classList.add('d-none');
                    var payrollMethodLabel = document.getElementById('payroll_method_label');
                    payrollMethodLabel.classList.add('d-none');
                    document.getElementById('methodRateContainer').innerHTML = '';
                    document.getElementById('user_id').value = '';
                    toggleLoginFields();
                }
            });
            document.addEventListener('DOMContentLoaded', function () {
                var input = document.querySelector('#station-select');
                new Tagify(input, {
                    whitelist: @json($stations->pluck('name')->toArray()), // Prepopulate with station names
                    dropdown: {
                        enabled: 1, // Show suggestions after 1 character
                        maxItems: 10 // Limit dropdown items
                    }
                });
            });
        </script>

        <script>
            $(document).on('click', '[data-bs-toggle="offcanvas"]', function () {
                var userId = $(this).data('userid'); // Get the user ID from the data attribute
                if (userId) {
                    $.ajax({
                        url: '{{url("/")}}/profile/' + userId, // Endpoint to fetch user data
                        method: 'GET',
                        success: function (response) {
                            if (response.success) {
                                var user = response.data.user;
                                var stations = response.data.stations; // Array of station names
                                var PayRollTypeId = response.data.payrollTypeId;
                                // Populate the form fields
                                $('#user_id').val(user.id);
                                $('#add-user-first-name').val(user.first_name);
                                $('#add-user-last-name').val(user.last_name);
                                $('#add-user-dob').val(user.dob);
                                $('#add-user-ssn').val(user.ssn);
                                $('#add-user-address').val(user.address);
                                $('#add-user-email').val(user.email);
                                $('#add-user-password').val(''); // Clear password field for security
                                $('#country').val(user.country_id).trigger('change.select2');
                                $('#add-user-contact').val(user.contact_no);

                                //$('#user-role').val(user.default_role_id).trigger('change.select2');
                                $('#user-role')
                                    .val(user.default_role_id)
                                    .trigger('change', [true, PayRollTypeId]);

                                // For the 'login_web' checkbox
                                if (user.login_web === 1) {
                                    $('#login_web').prop('checked', true); // Check the checkbox with ID 'login_web'
                                } else {
                                    $('#login_web').prop('checked', false); // Uncheck the checkbox with ID 'login_web'
                                }

                                if (user.login_mobile === 1) {
                                    $('#login_mobile').prop('checked', true); // Check the checkbox with ID 'login_mobile'
                                } else {
                                    $('#login_mobile').prop('checked', false); // Uncheck the checkbox with ID 'login_mobile'
                                }

                                toggleLoginFields();

                                // Populate stations into the Tagify input
                                var tagify = new Tagify(document.querySelector('#station-select'));
                                tagify.removeAllTags(); // Clear existing tags
                                tagify.addTags(stations); // Add stations as tags
                            }
                        },
                        error: function (xhr) {
                            alert('Error fetching user details');
                        }
                    });
                } else {
                    // Clear the form for a new user
                    $('#addNewUserFormInPro')[0].reset();
                }
            });

        </script>

        <script>
            $(document).on('click', '.delete-record', function () {
                const userId = $(this).data('user_id');
                const action = $(this).data('action_type');

                var btnTitle = (action === 'restore') ? "Sure to Proceed?" : "Are you sure?";
                var btnText = (action === 'restore') ? "Please proceed to Activate Suspended data!" : "This Employee won't be able to perform any action!";
                var confirmBtnText = (action === 'restore') ? "Yes, Activate it!" : "Yes, Suspend it!";
                var SuccessTitle = (action === 'restore') ? "Activate!" : "Suspended!";
                var SuccessText = (action === 'restore') ? "The Data has been Activated.!" : "The Role has been Suspended!";

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
                    if (result.isConfirmed) { // Check if the company clicked "Yes"
                        $.ajax({
                            url: '{{ route("profile.destroy") }}', // Adjust the route name
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                user_id: userId
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
            $(document).on('click', '.update-status', function () {
                const userId = $(this).data('user_id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This user won't be able to Use Some Services!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Update it!',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) { // Check if the user clicked "Yes"
                        $.ajax({
                            url: '{{ route("profile.status.update") }}', // Adjust the route name
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                user_id: userId
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Updated!',
                                    'The profile status has been updated.',
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
    </x-slot>

</x-app-layout>