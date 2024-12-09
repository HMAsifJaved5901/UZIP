<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 income_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-income table border-top">
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
            <!-- Offcanvas to add new income -->
            <div
                    class="offcanvas offcanvas-end"
                    tabindex="-1"
                    id="offcanvasAddIncome"
                    aria-labelledby="offcanvasAddIncomeLabel"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddIncomeLabel" class="offcanvas-title">Add Income</h5>
                    <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-income pt-0" id="addNewIncomeFormInPro" enctype="multipart/form-data"
                          action="{{ route('income.save') }}" method="POST">
                        @csrf
                        <input type="hidden" value="" name="id" id="income_id">
                        <div class="mb-3">
                            <label class="form-label" for="add-income-date">Income Date</label>
                            <input
                                    type="date"
                                    class="form-control"
                                    id="add-income-date"
                                    name="income_date"
                                    aria-label="Income Date"
                            />
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-income-station">Station</label>
                            <select id="add-income-station" class="form-select" name="station_id">
                                <option value="basic">Select Station</option>
                                @foreach($stations as $station)
                                    <option value="{{$station->id}}">{{$station->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-income-station-service">Service</label>
                            <select id="add-income-station-service" class="form-select" name="service_id">
                                <option value="">Select Service</option>
                                @foreach($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-income-category">Category</label>
                            <select id="add-income-category" class="form-select" name="category_id">
                                <option value="basic">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-income-amount">Amount</label>
                            <input
                                    type="number"
                                    class="form-control"
                                    id="add-income-amount"
                                    placeholder="Amount"
                                    name="amount"
                                    aria-label="Amount"
                                    step="0.01"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-income-image">Image/File</label>
                            <input
                                    type="file"
                                    class="form-control"
                                    id="add-income-image"
                                    name="image_file"
                                    aria-label="Image File"
                            />
                        </div>
                        <input type="hidden" name="existing_image_file" value="" id="existing_image_file">
                        <div class="mb-3">
                            <label class="form-label" for="add-income-description">description</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-income-description"
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
        <script src="{{ asset('lib/assets/js/app-income-list.js') }}"></script>
        <div id="fetchIncomeRoute" data-url="{{ route('income.list') }}"></div>
        <div id="fetchIncomeViewRoute" data-url="{{ route('income.view',':id') }}"></div>

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
                var incomeId = $(this).data('income_id'); // Get the company ID from the data attribute
                if (incomeId) {
                    $.ajax({
                        url: '{{url("/")}}/income/' + incomeId, // Endpoint to fetch company data
                        method: 'GET',
                        success: function (response) {
                            if (response.data) {
                                var income = response.data;
                                // Populate the form fields
                                $('#income_id').val(income.id);
                                $('#add-income-date').val(income.income_date);
                                $('#add-income-station').val(income.station_id);
                                $('#add-income-station-service').val(income.service_id);
                                $('#add-income-category').val(income.category_id);
                                $('#add-income-amount').val(income.amount);
                                $('#add-income-description').val(income.description);
                            }
                        },
                        error: function (xhr) {
                            alert('Error fetching Income details');
                        }
                    });
                } else {
                    // Clear the form for a new Company
                    $('#addNewIncomeFormInPro')[0].reset();
                }
            });
        </script>



        <script>
            $(document).on('click', '.delete-record', function () {
                const incomeId = $(this).data('income_id');
                const action = $(this).data('action_type');

                var btnTitle = (action === 'restore') ? "Sure to Proceed?" : "Are you sure?";
                var btnText = (action === 'restore') ? "Please proceed to restore deleted data!" : "This Income won't be used in any case!";
                var confirmBtnText = (action === 'restore') ? "Yes, restore it!" : "Yes, delete it!";
                var SuccessTitle = (action === 'restore') ? "Restore!" : "Deleted!";
                var SuccessText = (action === 'restore') ? "The Data has been restored.!" : "The Income has been deleted!";

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
                            url: '{{ route("income.destroy") }}', // Adjust the route name
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                income_id: incomeId
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