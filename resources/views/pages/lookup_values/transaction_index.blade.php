<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                    <div class="col-md-4 lookup_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-lookup table border-top custom-table-design">
                    <thead class="bg-custom-black">
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new lookup -->
            <div
                    {{--class="offcanvas offcanvas-end"--}}
                    class="offcanvas custom-centered-modal"
                    tabindex="-1"
                    id="offcanvasAddLookup"
                    aria-labelledby="offcanvasAddTransactionCategory"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddTransactionCategory" class="offcanvas-title">Add Transaction category</h5>
                    <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-transaction-category pt-0" id="addNewTransactionSaveFormInPro" method="POST"
                          action="{{ route('lookup.save') }}">
                        @csrf
                        <input type="hidden" value="" name="id" id="lookup_transaction_id">
                        <input type="hidden" value="transaction_category" name="type" id="lookup_transaction_type">
                        <div class="mb-3">
                            <label class="form-label" for="add-transaction-category-name">Transaction Type</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-transaction-category-name"
                                    placeholder="Type"
                                    name="value"
                                    aria-label="Transaction Category Name"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-transaction-category-description">Transaction Description</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-transaction-category-description"
                                    placeholder="Description"
                                    name="description"
                                    aria-label="Transaction Category Description"
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
        <div id="fetchLookupRoute" data-url="{{ url('lookup/transaction_category') }}"></div>

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
                var lookupid = $(this).data('lookupid');
                if (lookupid) {
                    $.ajax({
                        url: '{{url("/")}}/fetch/lookup/' + lookupid, // Endpoint to fetch lookup data
                        method: 'GET',
                        success: function (response) {
                            if (response.data) {
                                var lookup_value = response.data;
                                // Populate the form fields\
                                $('#lookup_transaction_id').val(lookup_value.id);
                                $('#lookup_transaction_type').val(lookup_value.type);
                                $('#add-transaction-category-name').val(lookup_value.value);
                                $('#add-transaction-category-description').val(lookup_value.description);
                            }
                        },
                        error: function (xhr) {
                            alert('Error fetching Data!');
                        }
                    });
                } else {
                    // Clear the form for a new lookup
                    $('#addNewTransactionSaveFormInPro')[0].reset();
                }
            });
        </script>



        <script>
            $(document).on('click', '.delete-record', function () {
                var lookupId = $(this).data('lookupid');
                const action = $(this).data('action_type');

                var btnTitle = (action === 'restore') ? "Sure to Proceed?" : "Are you sure?";
                var btnText = (action === 'restore') ? "Please proceed to Activate Suspended data!" : "Transaction category won't be used !";
                var confirmBtnText = (action === 'restore') ? "Yes, Activate it!" : "Yes, Suspend it!";
                var SuccessTitle = (action === 'restore') ? "Activate!" : "Suspended!";
                var SuccessText = (action === 'restore') ? "The Transaction category has been Activated.!" : "The Transaction category has been Suspended!";

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
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("lookup.destroy", ":id") }}'.replace(':id', lookupId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire(
                                    SuccessTitle,
                                    SuccessText,
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