<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 service_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-service table border-top  custom-table-design">
                    <thead class="bg-custom-black">
                    <tr>
                        <th></th>
                        <th>Name</th>
                        {{--<th>Code</th>--}}
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new service -->
            <div
                    {{--class="offcanvas offcanvas-end"--}}
                    class="offcanvas custom-centered-modal"
                    tabindex="-1"
                    id="offcanvasAddService"
                    aria-labelledby="offcanvasAddServiceLabel"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddServiceLabel" class="offcanvas-title">Add Service</h5>
                    <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-service pt-0" id="addNewServiceFormInPro" enctype="multipart/form-data"
                          action="{{ route('service.save') }}" method="POST">
                        @csrf
                        <input type="hidden" value="" name="id" id="service_id">
                        <input type="hidden" value="" name="category_id" id="add-service-category">
                        <div class="mb-3">
                            <label class="form-label" for="add-service-name">Service Name</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-service-name"
                                    placeholder="Tesla"
                                    name="name"
                                    aria-label="Tesla"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-service-description">Service Description</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-service-description"
                                    placeholder="Tesla description"
                                    name="description"
                                    aria-label="Tesla description"
                            />
                        </div>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('lib/assets/js/app-service-list.js') }}"></script>
        <div id="fetchServiceRoute" data-url="{{ route('service.list') }}"></div>

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
                var serviceId = $(this).data('service_id'); // Get the service ID from the data attribute
                if (serviceId) {
                    $.ajax({
                        url: '{{url("/")}}/service/' + serviceId, // Endpoint to fetch service data
                        method: 'GET',
                        success: function (response) {
                            if (response.data) {
                                var service = response.data;
                                // Populate the form fields
                                $('#service_id').val(service.id);
                                $('#add-service-name').val(service.name);
                                $('#add-service-description').val(service.description);
                                $('#add-service-category').val(service.category_id);
                            }
                        },
                        error: function (xhr) {
                            alert('Error fetching Service details');
                        }
                    });
                } else {
                    // Clear the form for a new Service
                    $('#addNewServiceFormInPro')[0].reset();
                }
            });
        </script>

        <script>
            $(document).on('click', '.delete-record', function () {
                const serviceId = $(this).data('service_id');
                const action = $(this).data('action_type');

                var btnTitle = (action === 'restore') ? "Sure to Proceed?" : "Are you sure?";
                var btnText = (action === 'restore') ? "Please proceed to Activate Suspended data!" : "This Service won't be able to perform any action!";
                var confirmBtnText = (action === 'restore') ? "Yes, Activate it!" : "Yes, Suspend it!";
                var SuccessTitle = (action === 'restore') ? "Activate!" : "Suspended!";
                var SuccessText = (action === 'restore') ? "The Service has been Activated.!" : "The Service has been Suspended!";

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
                    if (result.isConfirmed) { // Check if the service clicked "Yes"
                        $.ajax({
                            url: '{{ route("service.destroy") }}', // Adjust the route name
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                service_id: serviceId
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
                const serviceId = $(this).data('service_id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "The Service Status will be changed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Update it!',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) { // Check if the service clicked "Yes"
                        $.ajax({
                            url: '{{ route("service.status.update") }}', // Adjust the route name
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                service_id: serviceId
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Updated!',
                                    'The service status has been updated.',
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