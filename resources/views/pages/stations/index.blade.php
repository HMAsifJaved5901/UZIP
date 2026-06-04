<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 station_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-station table border-top custom-table-design">
                    <thead class="bg-custom-black">
                    <tr>
                        <th></th>
                        <th>Site</th>
                        <th>Code</th>
                        <th>Company</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Opening Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new station -->
            <div
                    {{--class="offcanvas offcanvas-end"--}}
                    class="offcanvas custom-centered-modal"
                    tabindex="-1"
                    id="offcanvasAddStation"
                    aria-labelledby="offcanvasAddStationLabel"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddStationLabel" class="offcanvas-title">Add New Site</h5>
                    <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-station pt-0" id="addNewStationFormInPro" enctype="multipart/form-data"
                          action="{{ route('station.save') }}" method="POST">
                        @csrf
                        <input type="hidden" value="" name="id" id="station_id">
                        <input type="hidden" value="1" name="category_id" id="category_id">
                        <div class="mb-3">
                            <label class="form-label" for="add-station-name">Name</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-station-name"
                                    placeholder="2230 new yark Eve"
                                    name="name"
                                    aria-label="2230 new yark Eve"
                                    required
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-station-code">Code</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-station-code"
                                    placeholder="123QWE"
                                    name="code"
                                    aria-label="123QWE"
                                    required
                            />
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-station-company">Select Company</label>
                            <select id="add-station-company" class="form-select" name="company_id" required>
                                <option value="basic">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{$company->id}}">{{$company->company_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-station-location">Postal Address</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-station-location"
                                    placeholder="Address"
                                    name="location"
                                    aria-label="Location"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-station-latitude">Latitude</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-station-latitude"
                                    placeholder="Latitude"
                                    name="latitude"
                                    aria-label="Latitude"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-station-longitude">Longitude</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-station-longitude"
                                    placeholder="Longitude"
                                    name="longitude"
                                    aria-label="Longitude"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-station-phone">Phone#</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-station-phone"
                                    placeholder="Phone"
                                    name="phone"
                                    aria-label="Phone"
                            />
                        </div>
                        {{--<div class="mb-3">--}}
                            {{--<label class="form-label" for="add-opening-hours">Point Of sale</label>--}}
                            {{--<select id="add-pos_id" class="form-select" name="pos_id">--}}
                                {{--@foreach($posCategories as $pos)--}}
                                    {{--<option value="{{ $pos->id }}">{{ ucfirst($pos->name) }}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        <div class="mb-3">
                            <label class="form-label" for="add-opening-hours">Select opening Hours</label>
                            <select id="add-opening-hours" class="form-select" name="opening_hours">
                                <option value="basic">Select Hours</option>
                                @foreach($businessHours as $day => $hours)
                                    <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script src="{{ asset('lib/assets/js/app-station-list.js') }}"></script>
        <div id="fetchStationRoute" data-url="{{ route('station.list') }}"></div>
        <div id="fetchStationViewRoute" data-url="{{ route('station.service.index', ':id') }}"></div>
        <div id="fetchStationCashFlowViewRoute" data-url="{{ route('station.cash.flow.index', ':id') }}"></div>

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
                var stationId = $(this).data('station_id'); // Get the company ID from the data attribute
                if (stationId) {
                    $.ajax({
                        url: '{{url("/")}}/station/' + stationId, // Endpoint to fetch company data
                        method: 'GET',
                        success: function (response) {
                            if (response.data) {
                                var station = response.data;
                                // Populate the form fields
                                $('#station_id').val(station.id);
                                $('#category_id').val(station.category_id);
                                $('#add-station-name').val(station.name);
                                $('#add-station-code').val(station.code);
                                $('#add-station-company').val(station.company_id);
                                $('#add-station-location').val(station.location);
                                $('#add-station-latitude').val(station.latitude);
                                $('#add-station-longitude').val(station.longitude);
                                $('#add-station-phone').val(station.phone);
                                $('#add-opening-hours').val(station.opening_hours);
                                $('#add-pos_id').val(station.pos_id);
                            }
                        },
                        error: function (xhr) {
                            alert('Error fetching Station details');
                        }
                    });
                } else {
                    // Clear the form for a new Company
                    $('#addNewStationFormInPro')[0].reset();
                }
            });
        </script>


        <script>
            $(document).on('click', '.delete-record', function () {
                const stationId = $(this).data('station_id');
                const action = $(this).data('action_type');

                var btnTitle = (action === 'restore') ? "Sure to Proceed?" : "Are you sure?";
                var btnText = (action === 'restore') ? "Please proceed to Activate Suspended data!" : "This Station won't be able to perform any action!";
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
                            url: '{{ route("station.destroy") }}', // Adjust the route name
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                station_id: stationId
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
                const stationId = $(this).data('station_id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "The Station Status will be changed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Update it!',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) { // Check if the station clicked "Yes"
                        $.ajax({
                            url: '{{ route("station.status.update") }}', // Adjust the route name
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                station_id: stationId
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Updated!',
                                    'The station status has been updated.',
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