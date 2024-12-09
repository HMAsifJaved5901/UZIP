<x-app-layout>

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{$station->category_name}} / Services </span></h4>
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body pt-0">
                        <h5 class="mt-4 small text-uppercase text-muted">{{$station->name}}</h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Status:</span>
                                    <span class="badge @if($station->is_active == 1) bg-label-success @else bg-label-warning @endif">
                                        @if($station->is_active == 1) Active @else Suspended @endif
                                    </span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Station Hourse:</span>
                                    <span><b>{{$station->opening_hours}}</b></span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Station Code:</span>
                                    <span>{{$station->name}}</span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Company:</span>
                                    <span>{{$station->company_name}}</span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Manager:</span>
                                    <span>{{$station->manager_name}}</span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Postal Address:</span>
                                    <span>{{$station->location}}</span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Latitude:</span>
                                    <span>{{$station->latitude}}</span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Longitude:</span>
                                    <span>{{$station->longitude}}</span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">phone:</span>
                                    <span>{{$station->phone}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <div class="card mb-4">
                    <h5 class="card-header pb-1">Services</h5>
                    <div class="card-body">
                        <p class="mb-4">Station services are listed below, for operation active/suspended</p>
                        @foreach($services as $service)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <img src="<?php echo e(asset('lib/assets/img/icons/brands/asana.png')); ?>" alt="google" class="me-3" height="38">
                                </div>
                                <div class="flex-grow-1 row">
                                    <div class="col-9 mb-sm-0 mb-2">
                                        <h6 class="mb-0">{{$service->name}}</h6>
                                        <small class="text-muted">{{$service->description}}</small>
                                    </div>
                                    <div class="col-3 text-end">
                                        <div class="form-check form-switch">
                                            <input data-station_id="{{$station->id}}" data-service_id="{{$service->id}}" class="form-check-input float-end add-station-service" type="checkbox" @if($service->checked == 1) checked @else @endif />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- /Social Accounts -->
        </div>
    </div>
    <!-- / Content -->

    <x-slot name="scripts">
        <script src="{{ asset('lib/assets/js/app-user-list.js') }}"></script>
        <div id="fetchUsersRoute" data-url="{{ route('get.users') }}"></div>
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
            $(document).on('click', '.add-station-service', function () {
                const stationId = $(this).data('station_id');
                const serviceId = $(this).data('service_id');

                console.log('stationId: '+stationId+'  '+'serviceId: '+serviceId)
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are assigning this services to following station!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Update it!',
                    cancelButtonText: 'Cancel'
                }).then(function(result) {
                    if (result.isConfirmed) { // Check if the user clicked "Yes"
                        $.ajax({
                            url: '{{ route("station.service.save") }}', // Adjust the route name
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                station_id: stationId,
                                service_id: serviceId
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Confirmed!',
                                    'Service recorded successfully.',
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
