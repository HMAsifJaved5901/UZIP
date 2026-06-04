<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 company_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-company table border-top custom-table-design">
                    <thead class="bg-custom-black">
                    <tr>
                        <th></th>
                        <th >Company Name</th>
                        {{--<th>Business Name</th>--}}
                        {{--<th>Company Code</th>--}}
                        {{--<th>Company Address</th>--}}
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new company -->
            <div
                    {{--class="offcanvas offcanvas-end custom-centered-modal"--}}
                    class="offcanvas custom-centered-modal"
                    tabindex="-1"
                    id="offcanvasAddCompany"
                    aria-labelledby="offcanvasAddCompanyLabel"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddCompanyLabel" class="offcanvas-title">Add Company</h5>
                    <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-company pt-0" id="addNewCompanyFormInPro" enctype="multipart/form-data"
                          action="{{ route('company.save') }}" method="POST">
                        @csrf
                        <input type="hidden" value="" name="id" id="company_id">
                        <div class="mb-3">
                            <label class="form-label" for="add-company-name">Company Name</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-company-name"
                                    placeholder="Name"
                                    name="company_name"
                                    aria-label="Name"
                            />
                        </div>
                        <div class="mb-3" style="display: none">
                            <div class="mb-4">
                                <label class="form-label" for="add-station-parent-company">Business</label>
                                <input id="add-station-parent-company" name="business_id" value="1" type="hidden">
                                {{--<select id="add-station-parent-company" class="form-select" name="business_id">--}}
                                    {{--@foreach($companies as $company)--}}
                                        {{--<option value="{{$company->id}}">{{$company->name}}</option>--}}
                                    {{--@endforeach--}}
                                {{--</select>--}}
                            </div>
                        </div>
                        <div class="mb-3" style="display: none">
                            <label class="form-label" for="add-company-address">Company Address</label>
                            <input
                                    type="hidden"
                                    value="ABC"
                                    id="add-company-address"
                                    class="form-control"
                                    placeholder="abc street"
                                    aria-label="abc street"
                                    name="company_address"
                                    autocomplete="off"
                            />
                        </div>
                        <div class="mb-3" style="display: none">
                            <label class="form-label" for="add-company-code">Company Code </label>
                            <input
                                    type="hidden"
                                    value="ABC123"
                                    id="add-company-code"
                                    class="form-control"
                                    placeholder="123QWE"
                                    aria-label="123QWE"
                                    name="company_code"
                                    autocomplete="off"
                            />
                        </div>
                        <div class="mb-4" style="display: none">
                            <label class="form-label" for="add-company-logo">Company Logo</label>
                            <input
                                    type="file"
                                    id="add-company-logo"
                                    class="form-control"
                                    name="company_logo"
                                    autocomplete="off"
                            />
                        </div>
                        <input type="hidden" name="existing_company_logo" value="" id="existing_company_logo">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('lib/assets/js/app-company-list.js') }}"></script>
        <div id="fetchCompanyRoute" data-url="{{ route('company.list') }}"></div>

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
                var companyId = $(this).data('company_id'); // Get the company ID from the data attribute
                if (companyId) {
                    $.ajax({
                        url: '{{url("/")}}/company/' + companyId, // Endpoint to fetch company data
                        method: 'GET',
                        success: function (response) {
                            if (response.data) {
                                var company = response.data;
                                // Populate the form fields
                                $('#company_id').val(company.id);
                                $('#add-station-parent-company').val(company.business_id).change();
                                $('#add-company-name').val(company.company_name);
                                $('#add-company-address').val(company.company_address);
                                $('#add-company-code').val(company.company_code );
                            }
                        },
                        error: function (xhr) {
                            alert('Error fetching Company details');
                        }
                    });
                } else {
                    // Clear the form for a new Company
                    $('#addNewCompanyFormInPro')[0].reset();
                }
            });
        </script>



        <script>
            $(document).on('click', '.delete-record', function () {
                const companyId = $(this).data('company_id');
                const action = $(this).data('action_type');

                var btnTitle = (action === 'restore') ? "Sure to Proceed?" : "Are you sure?";
                var btnText = (action === 'restore') ? "Please proceed to Activate Suspended data!" : "This Company won't be able to perform any action!";
                var confirmBtnText = (action === 'restore') ? "Yes, Activate it!" : "Yes, Suspend it!";
                var SuccessTitle = (action === 'restore') ? "Activate!" : "Suspended!";
                var SuccessText = (action === 'restore') ? "The Company has been Activated.!" : "The Company has been Suspended!";

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
                            url: '{{ route("company.destroy") }}', // Adjust the route name
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                company_id: companyId
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
                const companyId = $(this).data('company_id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "The Company Status will be changed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Update it!',
                    cancelButtonText: 'Cancel'
                }).then(function(result) {
                    if (result.isConfirmed) { // Check if the company clicked "Yes"
                        $.ajax({
                            url: '{{ route("company.status.update") }}', // Adjust the route name
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                company_id: companyId
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Updated!',
                                    'The company status has been updated.',
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