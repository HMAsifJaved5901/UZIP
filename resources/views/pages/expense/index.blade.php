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
                <table class="datatables-expense table border-top">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Station</th>
                        <th>Service</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new expense -->
            <div
                    class="offcanvas offcanvas-end"
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
                            <label class="form-label" for="add-expense-service-station">Service</label>
                            <select id="add-expense-service-station" class="form-select" name="service_id">
                                <option value="basic">Select Service</option>
                                @foreach($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="add-expense-category">Category</label>
                            <select id="add-expense-category" class="form-select" name="category_id">
                                <option value="basic">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->value}}</option>
                                @endforeach
                            </select>
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
                                $('#add-expense-station').val(expense.station_id);
                                $('#add-expense-service-station').val(expense.service_id);
                                $('#add-expense-category').val(expense.category_id);
                                $('#add-expense-amount').val(expense.amount);
                                $('#add-expense-description').val(expense.description);
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
                var btnText = (action === 'restore') ? "Please proceed to restore deleted data!" : "This Expense won't be used in any case!";
                var confirmBtnText = (action === 'restore') ? "Yes, restore it!" : "Yes, delete it!";
                var SuccessTitle = (action === 'restore') ? "Restore!" : "Deleted!";
                var SuccessText = (action === 'restore') ? "The Data has been restored.!" : "The Expense has been deleted!";

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

    </x-slot>

</x-app-layout>