<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 lookup_name"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-lookup table border-top">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Expense Type</th>
                        <th>Expense Description</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new lookup -->
            <div
                    class="offcanvas offcanvas-end"
                    tabindex="-1"
                    id="offcanvasAddLookup"
                    aria-labelledby="offcanvasAddExpenseCategory"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddExpenseCategory" class="offcanvas-title">Add Expense category</h5>
                    <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-expense-category pt-0" id="addNewExpenseSaveFormInPro" method="POST"
                          action="{{ route('lookup.save') }}">
                        @csrf
                        <input type="hidden" value="" name="id" id="lookup_expense_id">
                        <input type="hidden" value="expense_category" name="type" id="lookup_expense_type">
                        <div class="mb-3">
                            <label class="form-label" for="add-expense-category-name">Expense Category Name</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-expense-category-name"
                                    placeholder="Expense Name"
                                    name="value"
                                    aria-label="Expense Category Name"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-expense-category-description">Expense Category Description</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-expense-category-description"
                                    placeholder="Expense Description"
                                    name="description"
                                    aria-label="Expense Category Description"
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
        <script src="{{ asset('lib/assets/js/app-lookup-list.js') }}"></script>
        <div id="fetchLookupRoute" data-url="{{ url('lookup/expense_category') }}"></div>

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
                var type = $(this).data('lookupid'); // Get the lookup type from the data attribute
                if (type) {
                    $.ajax({
                        url: '{{url("/")}}/fetch/lookup/' + type, // Endpoint to fetch lookup data
                        method: 'GET',
                        success: function (response) {
                            if (response.data) {
                                var lookup_value = response.data;

                                // Populate the form fields
                                $('#lookup_expense_id').val(lookup_value.id);
                                $('#lookup_expense_type').val(lookup_value.type);
                                $('#add-expense-category-name').val(lookup_value.value);
                                $('#add-expense-category-description').val(lookup_value.description);
                            }
                        },
                        error: function (xhr) {
                            alert('Error fetching Expense details');
                        }
                    });
                } else {
                    // Clear the form for a new lookup
                    $('#addNewExpenseSaveFormInPro')[0].reset();
                }
            });
        </script>



        <script>
            $(document).on('click', '.delete-record', function () {
                var lookupId = $(this).data('lookupid');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This Expense won't be able to be used!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then(function(result) {
                    if (result.isConfirmed) { // Check if the lookup clicked "Yes"
                        $.ajax({
                            url: '{{ route("lookup.destroy", ":id") }}'.replace(':id', lookupId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'The expense category has been deleted.',
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