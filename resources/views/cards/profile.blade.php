<x-app-layout>

    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Employee / Services </span></h4>
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body pt-0">
                        <h5 class="mt-4 small text-uppercase text-muted">{{$user->name}}</h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Status:</span>
                                    <span class="badge @if($user->status == 1) bg-label-success @else bg-label-warning @endif">
                                        @if($user->status == 1) Active @else Suspended @endif
                                    </span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Role:</span>
                                    <span>{{$user->role_name}}</span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Email :</span>
                                    <span><b>{{$user->email}}</b></span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Company:</span>
                                    <span>{{$user->company_name}}</span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Station:</span>
                                    <span>{{$user->station_name}}</span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Country:</span>
                                    <span>{{$user->country_name}}</span>
                                </li>
                                <li class="mb-2 pt-1 d-flex justify-content-between">
                                    <span class="fw-semibold me-1">Contact:</span>
                                    <span>{{$user->contact_no}}</span>
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
                                    <img src="<?php echo e(asset('lib/assets/img/icons/brands/asana.png')); ?>"
                                         alt="google" class="me-3" height="38">
                                </div>
                                <div class="flex-grow-1 row">
                                    <div class="col-8 mb-sm-0 mb-2">
                                        <h6 class="mb-0">{{$service->name}}</h6>
                                        <small class="text-muted">{{$service->description}}</small>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="form-check form-switch">
                                            <input
                                                    id="service_checkbox_{{$service->id}}"
                                                    data-user_id="{{$user->id}}"
                                                    data-service_id="{{$service->id}}"
                                                    class="form-check-input float-end add-station-service"
                                                    type="checkbox"
                                                    @if($service->checked == 1) checked @endif
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-sm-0 mb-2">
                                    <!-- Hourly Wage Rate Checkboxes -->
                                    @php
                                        $sn = $service->name;
                                        $exists = $wages_rates->contains(function ($item) use ($sn) {
                                            return str_contains($item->config_key, $sn);
                                        });
                                    @endphp
                                    @if($exists)
                                        <h6>Hourly Wages:</h6>
                                    @endif
                                    <div class="d-flex flex-wrap">
                                        @foreach($wages_rates as $rate)
                                            @if($rate->config_key == $service->name)
                                                <div class="d-flex align-items-center me-3 mt-2">
                                                    {{$rate->label}} &nbsp;&nbsp;
                                                    <input
                                                            name="wage_id"
                                                            data-user_id="{{$user->id}}"
                                                            data-service_id="{{$service->id}}"
                                                            data-wage_id="{{$rate->id}}"
                                                            class="form-check-input hourly-wage-checkbox me-2"
                                                            type="checkbox"
                                                            id="service_hourly_rate_{{$service->id}}{{$rate->id}}"
                                                            data-rate="10"
                                                            @if($rate->id == $service->wage_id && $service->wage_checked > 0) checked @endif
                                                    >
                                                    <label class="form-check-label" for="service_hourly_rate_{{$service->id}}{{$rate->id}}">
                                                        $ {{$rate->value}}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <br>
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
                const userId = $(this).data('user_id');
                const serviceId = $(this).data('service_id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are assigning this services to following User!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Update it!',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) { // Check if the user clicked "Yes"
                        $.ajax({
                            url: '{{ route("profile.service.save") }}', // Adjust the route name
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                user_id: userId,
                                service_id: serviceId
                            },
                            success: function (response) {
                                console.log(response);
                                Swal.fire(
                                    'Confirmed!',
                                     response.message,
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
            $(document).on('click', '.hourly-wage-checkbox', function () {
                const userId = $(this).data('user_id');
                const serviceId = $(this).data('service_id');
                const wageId = $(this).data('wage_id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to assign wage rate!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Update it!',
                    cancelButtonText: 'Cancel'
                }).then(function (result) {
                    if (result.isConfirmed) { // Check if the user clicked "Yes"
                        $.ajax({
                            url: '{{ route("profile.wage.save") }}', // Adjust the route name
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                user_id: userId,
                                service_id: serviceId,
                                wage_id: wageId
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Confirmed!',
                                    'Service Wage recorded successfully.',
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
