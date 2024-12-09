<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Session</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">21,459</h4>
                                    <span class="text-success">(+29%)</span>
                                </div>
                                <span>Total Users</span>
                            </div>
                            <span class="badge bg-label-primary rounded p-2">
                          <i class="ti ti-user ti-sm"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Paid Users</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">4,567</h4>
                                    <span class="text-success">(+18%)</span>
                                </div>
                                <span>Last week analytics </span>
                            </div>
                            <span class="badge bg-label-danger rounded p-2">
                          <i class="ti ti-user-plus ti-sm"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Active Users</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">19,860</h4>
                                    <span class="text-danger">(-14%)</span>
                                </div>
                                <span>Last week analytics</span>
                            </div>
                            <span class="badge bg-label-success rounded p-2">
                          <i class="ti ti-user-check ti-sm"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Pending Users</span>
                                <div class="d-flex align-items-center my-1">
                                    <h4 class="mb-0 me-2">237</h4>
                                    <span class="text-success">(+42%)</span>
                                </div>
                                <span>Last week analytics</span>
                            </div>
                            <span class="badge bg-label-warning rounded p-2">
                          <i class="ti ti-user-exclamation ti-sm"></i>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 user_role"></div>
                    <div class="col-md-4 user_plan"></div>
                    <div class="col-md-4 user_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top">
                    <thead>
                    <tr>
                        <th></th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>SSN</th>
                        <th>Role</th>
                        <th>Station</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new user -->
            <div
                    class="offcanvas offcanvas-end"
                    tabindex="-1"
                    id="offcanvasAddUser"
                    aria-labelledby="offcanvasAddUserLabel"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
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
                            <label class="form-label" for="add-user-name">User Name</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-user-name"
                                    placeholder="John Doe"
                                    name="name"
                                    aria-label="John Doe"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-user-first-name">First name</label>
                            <input
                                    type="text"
                                    id="add-user-first-name"
                                    class="form-control"
                                    aria-label="john"
                                    name="first_name"
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
                            <label class="form-label" for="add-user-dob">DOB</label>
                            <input
                                    type="date"
                                    id="add-user-dob"
                                    class="form-control"
                                    name="dob"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-user-ssn">Social Security No</label>
                            <input
                                    type="text"
                                    id="add-user-ssn"
                                    class="form-control"
                                    aria-label="Social Security Number"
                                    name="ssn"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-user-address">Address</label>
                            <input
                                    type="text"
                                    id="add-user-address"
                                    class="form-control"
                                    aria-label="martin street 121-D"
                                    name="address"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="user-role">User Role</label>
                            <select id="user-role" class="form-select" name="default_role_id">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>

                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-user-company">Company</label>
                            <select id="add-user-company" class="form-select" name="company_id">
                                <option value="0">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{$company->id}}">{{$company->company_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-user-station">Station</label>
                            <select id="add-user-station" class="form-select" name="station_id">
                                <option value="basic">Select Station</option>
                                @foreach($stations as $station)
                                    <option value="{{$station->id}}">{{$station->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
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
                        <div class="mb-4">
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
                            <label class="form-label" for="country">Country</label>
                            <select id="country" class="select2 form-select" name="country_id">
                                @foreach($countries as $country)
                                    <option data-code="{{$country->code}}" data-placeholder="{{$country->placeholder}}"
                                            value="{{$country->id}}">{{$country->name}}</option>

                                @endforeach
                            </select>
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
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--<x-slot name="header">--}}
    {{--<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">--}}
    {{--{{ __('Profile') }}--}}
    {{--</h2>--}}
    {{--</x-slot>--}}

    {{--<div class="py-12">--}}
    {{--<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">--}}
    {{--<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">--}}
    {{--<div class="max-w-xl">--}}
    {{--@include('profile.partials.update-profile-information-form')--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">--}}
    {{--<div class="max-w-xl">--}}
    {{--@include('profile.partials.update-password-form')--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">--}}
    {{--<div class="max-w-xl">--}}
    {{--@include('profile.partials.delete-user-form')--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    <x-slot name="scripts">
        <script src="{{ asset('lib/assets/js/app-user-list.js') }}"></script>
        <div id="fetchUsersRoute" data-url="{{ route('get.users') }}"></div>
        <div id="fetchUserViewRoute" data-url="{{ route('user.view', ':id') }}"></div>
        <script>
            $(document).on('change', '#country', function () {
                var selectedOption = $(this).find(':selected'); // Get the selected <option>
                var code = selectedOption.data('code'); // Get the 'data-code' attribute
                var placeholder = selectedOption.data('placeholder'); // Get the 'data-placeholder' attribute

                $('#add-user-contact').val(placeholder); // Update the input with the placeholder
            });
        </script>

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
            $(document).on('click', '[data-bs-toggle="offcanvas"]', function () {
                var userId = $(this).data('userid'); // Get the user ID from the data attribute
                if (userId) {
                    $.ajax({
                        url: '{{url("/")}}/profile/' + userId, // Endpoint to fetch user data
                        method: 'GET',
                        success: function (response) {
                            if (response.success) {
                                var user = response.data;

                                // Populate the form fields
                                $('#user_id').val(user.id);
                                $('#add-user-name').val(user.name);
                                $('#add-user-first-name').val(user.first_name);
                                $('#add-user-last-name').val(user.last_name);
                                $('#add-user-dob').val(user.dob);
                                $('#add-user-ssn').val(user.ssn);
                                $('#add-user-address').val(user.address);
                                $('#add-user-email').val(user.email);
                                $('#add-user-password').val(''); // Clear password field for security
                                $('#add-user-company').val(user.company_id).trigger('change.select2');
                                $('#add-user-station').val(user.station_id).trigger('change.select2');
                                $('#country').val(user.country_id).trigger('change.select2');
                                $('#add-user-contact').val(user.contact_no);
                                $('#user-role').val(user.default_role_id).trigger('change.select2');
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
                var btnText = (action === 'restore') ? "Please proceed to restore deleted data!" : "This User won't be able to perform any action!";
                var confirmBtnText = (action === 'restore') ? "Yes, restore it!" : "Yes, delete it!";
                var SuccessTitle = (action === 'restore') ? "Restore!" : "Deleted!";
                var SuccessText = (action === 'restore') ? "The Data has been restored.!" : "The Role has been deleted!";

                Swal.fire({
                    title: btnTitle,
                    text: btnText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmBtnText,
                    cancelButtonText: 'Cancel'
                }).then(function(result) {
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
                }).then(function(result) {
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
                                ).then(function() {
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