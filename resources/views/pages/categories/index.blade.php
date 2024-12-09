<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">Search Filter</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-category table border-top">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Category name</th>
                        <th>Category Description</th>
                        <th>Parent Category</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new category -->
            <div
                    class="offcanvas offcanvas-end"
                    tabindex="-1"
                    id="offcanvasAddCategory"
                    aria-labelledby="offcanvasAddCategory"
            >
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddCategory" class="offcanvas-title">Add Category</h5>
                    <button
                            type="button"
                            class="btn-close text-reset"
                            data-bs-dismiss="offcanvas"
                            aria-label="Close"
                    ></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="add-new-category pt-0" id="addNewCategorySaveFormInPro" method="POST"
                          action="{{ route('category.save') }}">
                        @csrf
                        <input type="hidden" value="" name="id" id="category_id">
                        <div class="mb-3">
                            <label class="form-label" for="add-category-name">Category Name</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-category-name"
                                    placeholder="Category Name"
                                    name="value"
                                    aria-label="Category Name"
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="add-category-description">Category Description</label>
                            <input
                                    type="text"
                                    class="form-control"
                                    id="add-category-description"
                                    placeholder="Description"
                                    name="description"
                                    aria-label="Category Description"
                            />
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="add-parent-category">Parent Category</label>
                            <select id="add-parent-category" class="form-select" name="parent_id ">
                                <option value="">Select Parent</option>
                                @foreach($parentCategories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
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
        <script src="{{ asset('lib/assets/js/app-category-list.js') }}"></script>
        <div id="fetchCategoryRoute" data-url="{{ route('category.list') }}"></div>

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
                var categoryId = $(this).data('categoryid');
                if (categoryId) {
                    $.ajax({
                        url: '{{url("/")}}/category/' + categoryId, // Endpoint to fetch Category data
                        method: 'GET',
                        success: function (response) {
                            if (response.data) {
                                var model = response.data;
                                // Populate the form fields\
                                $('#category_id').val(model.id);
                                $('#add-category-name').val(model.name);
                                $('#add-category-description').val(model.description);
                                $('#add-parent-category').val(model.parent_id);
                            }
                        },
                        error: function (xhr) {
                            alert('Error fetching Data!');
                        }
                    });
                } else {
                    // Clear the form for a new Category
                    $('#addNewCategorySaveFormInPro')[0].reset();
                }
            });
        </script>



        <script>
            $(document).on('click', '.delete-record', function () {
                var categoryId = $(this).data('categoryid');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This category won't be able to be used!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("category.destroy", ":id") }}'.replace(':id', categoryId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire(
                                    'Deleted!',
                                    'The Category has been deleted.',
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