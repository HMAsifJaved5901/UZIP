<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 expense_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-expense table border-top custom-table-design">
                    <thead class="bg-custom-black">
                    <tr>
                        <th></th>
                        <th>Station</th>
                        <th>Date</th>
                        {{--<th>Service</th>--}}
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new expense -->
            <div
                    {{--class="offcanvas offcanvas-end"--}}
                    class="offcanvas custom-centered-modal"
                    tabindex="-1"
                    id="offcanvasAddExpense"
                    aria-labelledby="offcanvasAddExpenseLabel"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddExpenseLabel" class="offcanvas-title">Add Expense</h5>
                    <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-expense pt-0" id="addNewExpenseFormInPro" enctype="multipart/form-data"
                          action="{{ route('expense.save') }}" method="POST">
                        @csrf
                        <input type="hidden" value="" name="id" id="expense_id">
                        <div class="mb-3">
                            <label class="form-label" for="add-expense-date">Expense Date</label>
                            <input
                                    type="date"
                                    class="form-control"
                                    id="add-expense-date"
                                    name="expense_date"
                                    aria-label="Expense Date"
                            />
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-expense-station">Station</label>
                            <select id="add-expense-station" class="form-select" name="station_id">
                                <option value="basic">Select Station</option>
                                @foreach($stations as $station)
                                    <option value="{{$station->id}}">{{$station->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-expense-station-service">Service</label>
                            <select id="add-expense-station-service" class="form-select" name="service_id">
                                <option value="0">Select Service</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-expense-category">Category</label>
                            <select id="add-expense-category" class="form-select" name="category_id">
                                <option value="0">Select Category</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="add-cheque-no">Cheque No</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-cheque-no"
                                    placeholder="Cheque No"
                                    name="cheque_no"
                                    aria-label="cheque_no"
                                    step="0.01"
                            />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="add-exp-si-uni">Quantity/Unit</label>
                            <div class="input-group">
                            <span class="input-group-text">
                                <input type="text" class="form-control" placeholder="Value" id="add-category-exp_quantity"
                                       name="exp_quantity" aria-label="Expense Quantity">
                            </span>
                                <select id="add-exp-si-unit" class="form-select" name="exp_si_unit">
                                    <option value="ton">ton</option>
                                    <option value="lb">lb</option>
                                    <option value="gal">gal</option>
                                    <option value="bu">bu</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-expense-amount">Amount</label>
                            <input
                                    type="number"
                                    class="form-control"
                                    id="add-expense-amount"
                                    placeholder="Amount"
                                    name="amount"
                                    aria-label="Amount"
                                    step="0.01"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-expense-image">Image/File</label>
                            <input
                                    type="file"
                                    class="form-control"
                                    id="add-expense-image"
                                    name="image_file"
                                    aria-label="Image File"
                            />
                        </div>
                        <input type="hidden" name="existing_image_file" value="" id="existing_expense_image">
                        <div class="mb-3">
                            <label class="form-label" for="add-expense-description">description</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-expense-description"
                                    placeholder="Description"
                                    name="description"
                                    aria-label="Description"
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
        <script src="{{ asset('lib/assets/js/app-expense-list.js') }}"></script>
        <div id="fetchExpenseRoute" data-url="{{ route('expense.list') }}"></div>
        <div id="fetchExpenseViewRoute" data-url="{{ route('expense.view',':id') }}"></div>

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
                var expenseId = $(this).data('expense_id'); // Get the company ID from the data attribute
                if (expenseId) {
                    $.ajax({
                        url: '{{url("/")}}/expense/' + expenseId, // Endpoint to fetch company data
                        method: 'GET',
                        success: function (response) {
                            if (response.data) {
                                var expense = response.data;
                                // Populate the form fields
                                $('#expense_id').val(expense.id);
                                $('#add-expense-date').val(expense.expense_date);
                                $('#add-expense-station').val(expense.station_id).trigger('change');
                                $('#add-expense-amount').val(expense.amount);
                                $('#add-category-exp_quantity').val(expense.exp_quantity);
                                $('#add-exp-si-unit').val(expense.exp_si_unit);

                                // Fetch services and set service
                                populateServices(expense.station_id,expense.service_id, function () {
                                    $('#add-expense-station-service').val(expense.service_id).trigger('change');
                                    populateCategories(expense.service_id,expense.category_id, function () {
                                        $('#add-expense-category').val(expense.category_id);
                                    });
                                });
                            }
                        },
                        error: function (xhr) {
                            alert('Error fetching Expense details');
                        }
                    });
                } else {
                    // Clear the form for a new Company
                    $('#addNewExpenseFormInPro')[0].reset();
                }
            });
        </script>



        <script>
            $(document).on('click', '.delete-record', function () {
                const expenseId = $(this).data('expense_id');
                const action = $(this).data('action_type');

                var btnTitle = (action === 'restore') ? "Sure to Proceed?" : "Are you sure?";
                var btnText = (action === 'restore') ? "Please proceed to Activate Suspended data!" : "This Expense won't be used in any case!";
                var confirmBtnText = (action === 'restore') ? "Yes, Activate it!" : "Yes, Suspend it!";
                var SuccessTitle = (action === 'restore') ? "Activate!" : "Suspended!";
                var SuccessText = (action === 'restore') ? "The Data has been Activated.!" : "The Expense has been Suspended!";

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
                            url: '{{ route("expense.destroy") }}', // Adjust the route name
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                expense_id: expenseId
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
            // On station selection, fetch services
            $('#add-expense-station').on('change', function () {
                var stationId = $(this).val();

                if (stationId) {
                    populateServices(stationId,0); // Fetch services for the selected station
                } else {
                    $('#add-expense-station-service').empty().append('<option value="0">Select Service</option>');
                    $('#add-expense-category').empty().append('<option value="0">Select Category</option>');
                }
            });

            // On service selection, fetch categories
            $('#add-expense-station-service').on('change', function () {
                var serviceId = $(this).val();

                if (serviceId) {
                    populateCategories(serviceId,0); // Fetch categories for the selected service
                } else {
                    $('#add-expense-category').empty().append('<option value="0">Select Category</option>');
                }
            });

            // Populate services dynamically
            function populateServices(stationId, selectedServiceId, callback) {
                $.ajax({
                    url: '{{url("/")}}/get-services/' + stationId,
                    type: 'GET',
                    success: function (data) {
                        var serviceDropdown = $('#add-expense-station-service');
                        serviceDropdown.empty();
                        serviceDropdown.append('<option value="0">Select Service</option>');
                        $.each(data, function (index, service) {
                            serviceDropdown.append('<option value="' + service.id + '">' + service.name + '</option>');
                        });

                        // Set the selected service ID if provided
                        if (selectedServiceId) {
                            serviceDropdown.val(selectedServiceId).trigger('change');
                        }

                        if (typeof callback === 'function') {
                            callback(); // Trigger callback after services are loaded
                        }
                    }
                });
            }

            // Populate categories dynamically
            function populateCategories(serviceId, selectedCategoryId) {
                var category = 'expense_category';
                $.ajax({
                    url: '{{url("/")}}/get-categories/' + category + '/' + serviceId,
                    type: 'GET',
                    success: function (data) {
                        var categoryDropdown = $('#add-expense-category');
                        categoryDropdown.empty();
                        categoryDropdown.append('<option value="0">Select Category</option>');
                        $.each(data, function (index, category) {
                            categoryDropdown.append('<option value="' + category.id + '">' + category.value + '</option>');
                        });

                        // Set the selected service ID if provided
                        if (selectedCategoryId) {
                            categoryDropdown.val(selectedCategoryId).trigger('change');
                        }
                    }
                });
            }
        </script>

    </x-slot>

</x-app-layout>