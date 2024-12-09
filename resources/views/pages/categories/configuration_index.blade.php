<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-configuration table border-top">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Configuration Key</th>
                        <th>Configuration Name</th>
                        <th>Configuration Value</th>
                        <th>Configuration Description</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new configuration -->
            <div
                    class="offcanvas offcanvas-end"
                    tabindex="-1"
                    id="offcanvasAddConfiguration"
                    aria-labelledby="offcanvasAddConfiguration"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddConfiguration" class="offcanvas-title">Add Configuration</h5>
                    <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-configuration pt-0" id="addNewConfigurationSaveFormInPro" method="POST"
                          action="{{ route('configuration.save') }}">
                        @csrf
                        <input type="hidden" value="" name="id" id="configuration_id">
                        <div class="mb-3">
                            <label class="form-label" for="add-config-key">Configuration Key</label>
                            <select id="add-config-key" class="form-select" name="config_key">
                                @foreach($configuration_list as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-config-label">Configuration Name</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-config-label"
                                    placeholder="Configuration Name"
                                    name="label"
                                    aria-label="Configuration Name"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-config-value">Configuration value</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-config-value"
                                    placeholder="Configuration Value"
                                    name="value"
                                    aria-label="Configuration Value"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-config-description">Configuration Description</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-config-description"
                                    placeholder="Description"
                                    name="description"
                                    aria-label="Configuration Description"
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
        <script src="{{ asset('lib/assets/js/app-configuration-list.js') }}"></script>
        <div id="fetchConfigurationRoute" data-url="{{ route('configuration.list') }}"></div>

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
                var configurationId = $(this).data('configurationid');
                if (configurationId) {
                    $.ajax({
                        url: '{{url("/")}}/configuration/' + configurationId, // Endpoint to fetch Configuration data
                        method: 'GET',
                        success: function (response) {
                            if (response.data) {
                                var model = response.data;
                                // Populate the form fields\
                                $('#configuration_id').val(model.id);
                                $('#add-config-key').val(model.config_key);
                                $('#add-config-label').val(model.label);
                                $('#add-config-value').val(model.value);
                                $('#add-config-description').val(model.description);
                            }
                        },
                        error: function (xhr) {
                            alert('Error fetching Data!');
                        }
                    });
                } else {
                    // Clear the form for a new Configuration
                    $('#addNewConfigurationSaveFormInPro')[0].reset();
                }
            });
        </script>



        <script>
            $(document).on('click', '.delete-record', function () {
                const configurationId = $(this).data('configurationid');
                const action = $(this).data('action_type');
                var btnTitle = (action === 'restore') ? "Sure to Proceed?" : "Are you sure?";
                var btnText = (action === 'restore') ? "Please proceed to restore deleted data!" : "This Configuration won't be able to perform any action!";
                var confirmBtnText = (action === 'restore') ? "Yes, restore it!" : "Yes, delete it!";
                var SuccessTitle = (action === 'restore') ? "Restore!" : "Deleted!";
                var SuccessText = (action === 'restore') ? "The Data has been restored.!" : "The Role has been deleted!";

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
                            url: '{{ route("configuration.destroy") }}', // Adjust the route name
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: configurationId
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