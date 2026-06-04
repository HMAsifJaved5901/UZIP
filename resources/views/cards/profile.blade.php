<x-app-layout>
    <x-slot name="links">
        <style>
            .form-check-input.hourly-wage-checkbox {
                accent-color: #4caf50; /* Changes checkbox color */
                width: 20px;
                height: 20px;
                border: 2px solid #007bff;
                border-radius: 5px;
            }

            .form-check-input.hourly-wage-checkbox:checked {
                background-color: #ff5722; /* Checked state */
                border-color: #ff9800;
                box-shadow: 0 0 5px #ffc107;
            }

            .table:not(.table-dark) thead:not(.table-dark) th {
                color: #5d596c !important;
            }

            /* Ensure custom radio icons update when the hidden radio is checked */
            .payroll-trigger[type="radio"]:checked + .emp-radio-box .ra-icon {
                background-color: #fff !important;
                border: 2px solid #007bff !important;
                display: flex !important;
                align-items: center;
                justify-content: center;
            }

            /* Create the inner dot for the selected radio button */
            .payroll-trigger[type="radio"]:checked + .emp-radio-box .ra-icon::after {
                content: "";
                width: 8px;
                height: 8px;
                background-color: #007bff;
                border-radius: 50%;
                display: block;
            }

        </style>
    </x-slot>
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!--<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Employee / Station & Services </span></h4>-->
        <div class="row">
            <div class="card station-ser-view-info-main mb-4 p-0">
                <div class="card-body station-ser-view-info-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row" style="border-bottom: solid 1px #e7e7e7;">
                                <div class="col-md-5">
                                    <h3 class=""> {{$user->first_name}} {{$user->last_name}}</h3>
                                    <p class="service-code">Status: <span
                                                class="badge @if($user->status == 1) bg-label-success @else bg-label-warning @endif"
                                                style="font-size: 13px;">
                                            @if($user->status == 1) Active @else Suspended @endif
                                        </span></p>
                                </div>
                                <div class="col-md-7">
                                    <div class="emp-email-no">
                                        <div style="color: #333333;">
                                            <span style="color: #7b7788;">Email:</span> {{$user->email}}
                                        </div>
                                        <div class="station-ser-view-info-box-phone">
                                            <img src="/lib/assets/img/phone-icon.png" alt="Icon">
                                            <p>{{$user->contact_no}}</p>
                                            <button type="button">
                                                <img src="/lib/assets/img/copy-icon.png" alt="Icon">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 25px;">
                                <div class="col-md-4">
                                    <div class="station-ser-view-info-box">
                                        <img src="/lib/assets/img/company-icon.png" alt="Icon">
                                        <div>
                                            <p>Company:</p>
                                            <span>{{$user->company->company_name}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="station-ser-view-info-box">
                                        <img src="/lib/assets/img/role-icon.png" alt="Icon">
                                        <div>
                                            <p>Role:</p>
                                            <span>{{$user->role->name}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="station-ser-view-info-box">
                                        <img src="/lib/assets/img/ssn-icon.png" alt="Icon">
                                        <div>
                                            <p>SSN:</p>
                                            <span>{{$user->ssn}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <form id="payrollForm" action="{{ route('profile.save-payroll') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="card mb-4 emp-card-main">
                    <h2>Payroll Type</h2>
                    @foreach($UserStationList as $station)
                        @if(!empty($station->station->name))
                            <div class="card-body emp-card-body p-0 payroll-card">
                                {{-- Hidden station reference for JavaScript --}}
                                <input type="hidden" class="station-id-ref" value="{{$station->station->id}}">

                                <div class="payroll-header">
                                    <div class="icon"><img src="/lib/assets/img/loc-icon.png" alt="Icon"></div>
                                    <div>
                                        <p>Site Name:</p>
                                        <h3>{{$station->station->name}}</h3>
                                    </div>
                                </div>
                                <div class="payroll-body">
                                    <h4>Payroll Type:</h4>
                                    <div>
                                        <!-- Salary -->
                                        <div class="emp-salary-box">
                                            <div class="emp-salary-checkbox-main">
                                                <p>Salary</p>
                                                <div class="emp-salary-checkbox">
                                                    <input type="checkbox" class="payroll-trigger" data-id="42"
                                                           id="salaryCheck_{{$station->id}}">
                                                    <label for="salaryCheck_{{$station->id}}">
                                                        <img src="/lib/assets/img/checkbox-icon.png" alt="Icon">
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="salaryField" class="dynamic-container hidden"></div>
                                        </div>

                                        <!-- Wages / Commission -->
                                        <div class="emp-radio-box-main">
                                            <input type="radio" name="payroll_{{$station->id}}" value="41"
                                                   class="payroll-trigger" id="wages_{{$station->id}}">
                                            <label class="emp-radio-box" for="wages_{{$station->id}}">
                                                Wages
                                                <div class="ra-icon"></div>
                                            </label>
                                            <input type="radio" name="payroll_{{$station->id}}" value="43"
                                                   class="payroll-trigger" id="commission_{{$station->id}}">
                                            <label class="emp-radio-box" for="commission_{{$station->id}}">
                                                Commission
                                                <div class="ra-icon"></div>
                                            </label>
                                        </div>
                                        <div id="wagesContainer" class="dynamic-container hidden"></div>
                                        <div id="commissionContainer" class="dynamic-container hidden"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
            </form>


            <!-- User Content -->
            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1" style="display: none;">
                <div class="card mb-4">
                    <div class="container mt-2 mb-2">
                        <h5 class="mt-3">Assign Site Shift</h5>
                        <form action="{{ url('/employee-shift/store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $user->id }}">

                            <div id="dynamic-rows">
                                @if (!$employeeServices->isEmpty())

                                    @foreach ($employeeServices as $index => $service)
                                        @php
                                            $shift = $employeeShifts->where('station_id', $service->station_id)->first();
                                            if(!empty($shift) && $shift!=null){
                                            $start = $shift->shift_start_time;
                                            $end = $shift->shift_end_time;
                                            }else{
                                            $start = '';
                                            $end = '';
                                            }
                                        @endphp
                                        <div class="row mb-3 dynamic-row">
                                            <div class="col-md-3">
                                                <label for="station-{{ $service->id }}">Site</label>
                                                <select name="employee_services[{{ $index }}][station_id]"
                                                        class="form-control station-select"
                                                        id="station-{{ $service->id }}">
                                                    {{--<option value="">Select Site</option>--}}
                                                    @foreach ($stations as $station)
                                                        @if($station->id==$service->station_id)
                                                            <option value="{{ $station->id }}" {{ $station->id == $service->station_id ? 'selected' : '' }}>
                                                                {{ $station->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="service-{{ $service->id }}">Shift Type</label>
                                                <select name="employee_services[{{ $index }}][shift_id]"
                                                        class="form-control service-select"
                                                        id="shift_id-{{ $service->id }}">
                                                    <option value="">Select Type</option>
                                                    @if(!empty($ShiftConfiguration))
                                                        @foreach ($ShiftConfiguration as $Shift)
                                                            <option value="{{ $Shift->id }}"
                                                                    @if(isset($shift) && $shift->shift_id == $Shift->id) selected @endif>
                                                                {{ $Shift->label }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @if($user->role->r_code != '0003')
                                                <div class="col-md-3">
                                                    <label for="sst-{{ $service->id }}">Shift start TIme</label>
                                                    <div class="input-group input-group-merge">
                                                        <input
                                                                name="employee_services[{{ $index }}][shift_start_time]"
                                                                value="{{$start}}"
                                                                id="shift_start_time-{{ $service->id }}"
                                                                type="time"
                                                                class="form-control"
                                                                placeholder="100"
                                                                aria-label="Amount (to the nearest dollar)"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="set-{{ $service->id }}">Shift End Time</label>
                                                    <div class="input-group input-group-merge">
                                                        <input
                                                                name="employee_services[{{ $index }}][shift_end_time]"
                                                                value="{{$end}}"
                                                                id="wage-{{ $service->id }}"
                                                                type="time"
                                                                class="form-control"
                                                                placeholder="100"
                                                                aria-label="Amount (to the nearest dollar)"
                                                        />
                                                    </div>
                                                </div>
                                            @endif
                                            {{--<div class="col-md-1">--}}
                                            {{--<button type="button" class="btn btn-danger remove-row mt-4">Remove--}}
                                            {{--</button>--}}
                                            {{--</div>--}}
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            {{--<button type="button" class="btn btn-success add-row">Add Row</button>--}}
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
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
            var payrollMethodsCache = {};
            var savedPayrollData = @json($existingPayrollMethods) || [];

            // 1. Dynamic Row Generator
            function getdynamicRow(method, typeId, stationId) {
                if (!method) return '';
                var typeLabel = (typeId == 42) ? 'Salary' : (typeId == 41 ? 'Wages' : 'Commission');

                var savedRate = "";
                if (savedPayrollData && savedPayrollData.length > 0) {
                    for (var i = 0; i < savedPayrollData.length; i++) {
                        var saved = savedPayrollData[i];
                        if (saved.station_id == stationId &&
                            (saved.method_id == (method.id || method.method_id)) &&
                            saved.payroll_type_id == typeId) {
                            savedRate = saved.rate;
                            break;
                        }
                    }
                }

                var mId = method.id || method.method_id || '';
                var mName = method.name || method.method_name || 'Method';

                return '<div class="method-row mt-2">' +
                    '<input type="hidden" name="station_id[]" value="' + stationId + '">' +
                    '<input type="hidden" name="payroll_type_id[]" value="' + typeId + '">' +
                    '<input type="hidden" name="method_id[]" value="' + mId + '">' +
                    '<div class="method-label">' +
                    '<span class="font-weight-bold" style="font-size: 14px;">' + mName + '</span>' +
                    '</div>' +
                    '<div class="method-col flex-grow-1">' +
                    '<input type="number" class="form-control" name="amount[]" value="' + savedRate + '" placeholder="Enter ' + typeLabel + ' Amount" step="any" min="0">' +
                    '<button type="button" class="delete-btn btn p-0 border-0 bg-transparent">' +
                    '<img src="/lib/assets/img/delete-icon.png" alt="Delete" style="width:20px; height:20px; cursor:pointer;">' +
                    '</button>' +
                    '</div>' +
                    '</div>';
            }

            function renderRows(methods, $target, typeId, stationId) {
                if (!$target || $target.length === 0) return;
                $target.empty().removeClass('hidden').show();
                $.each(methods, function (index, method) {
                    $target.append(getdynamicRow(method, typeId, stationId));
                });
            }

            $(document).ready(function () {
                // 2. Click/Change Listener (Delegated)
                $(document).on('change', '.payroll-trigger', function () {
                    var $this = $(this);
                    var isCheckbox = $this.is(':checkbox');
                    var typeId = isCheckbox ? parseInt($this.data('id')) : parseInt($this.val());
                    var $card = $this.closest('.payroll-card');
                    var stationId = $card.find('.station-id-ref').val();

                    var $target = (typeId == 42) ? $card.find('#salaryField') :
                        (typeId == 41 ? $card.find('#wagesContainer') : $card.find('#commissionContainer'));

                    if (!isCheckbox) $card.find('#wagesContainer, #commissionContainer').empty().addClass('hidden');
                    if (isCheckbox && !$this.is(':checked')) {
                        $card.find('#salaryField').empty().addClass('hidden');
                        return;
                    }

                    if (payrollMethodsCache[typeId]) {
                        renderRows(payrollMethodsCache[typeId], $target, typeId, stationId);
                    } else {
                        $.ajax({
                            url: '/profile/get-methods/' + typeId,
                            type: 'GET',
                            success: function (data) {
                                payrollMethodsCache[typeId] = data;
                                renderRows(data, $target, typeId, stationId);
                            }
                        });
                    }
                });

                // 3. HYDRATION: Use a small delay to ensure DOM and logic are ready
                setTimeout(function() {
                    if (typeof savedPayrollData !== 'undefined' && savedPayrollData.length > 0) {
                        var processedKeys = [];
                        $.each(savedPayrollData, function(index, item) {
                            var uniqueKey = item.station_id + '_' + item.payroll_type_id;
                            if (processedKeys.indexOf(uniqueKey) === -1) {
                                processedKeys.push(uniqueKey);

                                var $card = $('.payroll-card').filter(function() {
                                    return $(this).find('.station-id-ref').val() == item.station_id;
                                });

                                if ($card.length > 0) {
                                    var typeId = item.payroll_type_id;
                                    var $input;

                                    if (typeId == 42) {
                                        // Find the checkbox
                                        $input = $card.find('.emp-salary-checkbox input[type="checkbox"]');

                                        if ($input.length && !$input.is(':checked')) {
                                            // 1. Set the property
                                            $input.prop('checked', true);

                                            // 2. Trigger the AJAX/logic
                                            $input.trigger('change');

                                            // 3. FORCE VISUAL REFRESH (Common for custom themes)
                                            if (typeof $input.checkboxradio === "function") {
                                                $input.checkboxradio("refresh");
                                            }

                                            // 4. Manual class toggle (for standard Bootstrap/Custom CSS themes)
                                            $input.closest('.emp-salary-checkbox').addClass('checked');
                                        }
                                    } else {
                                        // Wages/Commission Radio
                                        $input = $card.find('input[type="radio"][value="' + typeId + '"]');
                                        if ($input.length) {
                                            $input.prop('checked', true).trigger('change');
                                            if (typeof $input.checkboxradio === "function") {
                                                $input.checkboxradio("refresh");
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }, 900);

                // 4. Form Submission
                $('#payrollForm').on('submit', function (e) {
                    e.preventDefault();
                    var $form = $(this);

                    $form.find(':input').prop('disabled', false);

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: $form.serialize(), // Captures all indices to keep arrays aligned
                        success: function (response) {
                            if (response.status === 'success') {
                                window.location.href = response.redirect;
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText); // Check here for the exact PHP error
                            alert("Save failed. Check console for details.");
                        }
                    });
                });

                $(document).on('click', '.delete-btn', function () {
                    if (confirm("Remove method?")) $(this).closest('.method-row').remove();
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var rowIndex = {{ count($employeeServices) }}; // Start index based on existing rows

                // Add new row
                document.querySelector('.add-row').addEventListener('click', function () {
                    var rowTemplate =
                        '<div class="row mb-3 dynamic-row">' +
                        '<div class="col-md-3">' +
                        '<select name="employee_services[' + rowIndex + '][station_id]" class="form-control station-select">' +
                        '<option value="">Select Site</option>' +
                        '@foreach ($stations as $station)' +
                        '<option value="{{ $station->id }}">{{ $station->name }}</option>' +
                        '@endforeach' +
                        '</select>' +
                        '</div>' +
                        '<div class="col-md-3">' +
                        '<select name="employee_services[' + rowIndex + '][wage_id]" class="form-control">' +
                        '@foreach ($wages as $wage)' +
                        '<option value="{{ $wage->id }}">{{ $wage->label }} - {{ $wage->value }}{{ $wage->value_unit }}</option>' +
                        '@endforeach' +
                        '</select>' +
                        '</div>' +
                        '<div class="col-md-3">' +
                        '<button type="button" class="btn btn-danger remove-row mt-4">Remove</button>' +
                        '</div>' +
                        '</div>';
                    document.getElementById('dynamic-rows').insertAdjacentHTML('beforeend', rowTemplate);
                    rowIndex++;
                });

                // Remove row
                document.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-row')) {
                        e.target.closest('.dynamic-row').remove();
                    }
                });
            });
        </script>

        <script>
            var existingPayrollData = @json($existingPayrollMethods);
        </script>

    </x-slot>

</x-app-layout>
