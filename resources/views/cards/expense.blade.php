<x-app-layout>
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row invoice-edit">
            <!-- Invoice Edit-->
            <div class="col-lg-9 col-12 mb-lg-0 mb-4">
                <div class="card invoice-preview-card">
                    <div class="card-body">
                        <div class="row m-sm-4 m-0">
                            <div class="col-md-6 mb-md-0 mb-4 ps-0">
                                <div class="d-flex svg-illustration mb-4 gap-2 align-items-center">
                                    <svg
                                            width="32"
                                            height="22"
                                            viewBox="0 0 32 22"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                                fill="#7367F0"
                                        />
                                        <path
                                                opacity="0.06"
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                                                fill="#161616"
                                        />
                                        <path
                                                opacity="0.06"
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                                                fill="#161616"
                                        />
                                        <path
                                                fill-rule="evenodd"
                                                clip-rule="evenodd"
                                                d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                                fill="#7367F0"
                                        />
                                    </svg>

                                    <span class="app-brand-text fw-bold fs-4"> UZIP Expense</span>
                                </div>
                                <div class="card-block">
                                    <table class="table table-striped">
                                        <tbody>
                                        <tr>
                                            <th>Station Name:</th>
                                            <td><a class="justify-content-right" href="javascript:void(0)">{{$record->station_name}}</a></td>
                                        </tr>
                                        <tr>
                                            <th>Service Type:</th>
                                            <td><a class="justify-content-right" href="javascript:void(0)">{{$record->category_name}}</a></td>
                                        </tr>
                                        <tr>
                                            <th>Service:</th>
                                            <td><a class="justify-content-right" href="javascript:void(0)">{{$record->service_name}}</a></td>
                                        </tr>
                                        <tr>
                                            <th>Amount::</th>
                                            <td><a class="justify-content-right" href="javascript:void(0)">{{number_format($record->amount)}})</a></td>
                                        </tr>
                                        <tr>
                                            <th>Description:</th>
                                            <td><a class="justify-content-right" href="javascript:void(0)">{{$record->description}}</a></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <dl class="row mb-2">
                                    <dt class="col-sm-5 mb-2 mb-sm-0 text-md-end ps-0">
                                        <span class="h4 text-capitalize mb-0 text-nowrap">Invoice</span>
                                    </dt>
                                    <dd class="col-sm-7 d-flex justify-content-md-end pe-0 ps-0 ps-sm-2">
                                        <div class="input-group input-group-merge disabled w-px-350">
                                            <span class="input-group-text">#</span>
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    disabled
                                                    placeholder="74909"
                                                    value="{{$record->invoice_no}}"
                                                    id="invoiceId"
                                            />
                                        </div>
                                    </dd>
                                    <dt class="col-sm-5 mb-2 mb-sm-0 text-md-end ps-0 mt-2">
                                        <span class="fw-normal">Expense Date:</span>
                                    </dt>
                                    <dd class="col-sm-7 d-flex justify-content-md-end pe-0 ps-0 ps-sm-2">
                                        <input type="text" class="form-control w-px-450 invoice-date"
                                               value="{{\Carbon\Carbon::parse($record->expense_date)->format('F j, Y')}}" placeholder="YYYY-MM-DD"/>
                                    </dd>
                                </dl>
                                <dl class="row mb-2">
                                    <dt class="col-sm-12 mb-2 mb-sm-0 text-md-end ps-0">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title"></h5>
                                                <img class="img-fluid d-flex mx-auto my-4 rounded" src="{{asset('/storage/'.$record->image_file)}}" alt="Card image cap">
                                            </div>
                                        </div>
                                    </dt>
                                </dl>
                            </div>
                        </div>

                        <hr class="my-3 mx-n4"/>
                    </div>
                </div>
            </div>
            <!-- /Invoice Edit-->

            <!-- Invoice Actions -->
            <div class="col-lg-3 col-12 invoice-actions">
                <div class="card mb-4">
                    <div class="card-body">
                        <button
                                class="btn btn-primary d-grid w-100"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#sendInvoiceOffcanvas"
                        >
                        <span class="d-flex align-items-center justify-content-center text-nowrap">
                            <i class="ti ti-send ti-xs me-1"></i>Send Invoice</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- /Invoice Actions -->
        </div>

        <!-- Offcanvas -->
        <!-- Send Invoice Sidebar -->
        <div class="offcanvas offcanvas-end" id="sendInvoiceOffcanvas" aria-hidden="true">
            <div class="offcanvas-header my-1">
                <h5 class="offcanvas-title">Send Invoice</h5>
                <button
                        type="button"
                        class="btn-close text-reset"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close"
                ></button>
            </div>
            <div class="offcanvas-body pt-0 flex-grow-1">
                <form>
                    <div class="mb-3">
                        <label for="invoice-from" class="form-label">From</label>
                        <input
                                type="text"
                                class="form-control"
                                id="invoice-from"
                                value="shelbyComapny@email.com"
                                placeholder="company@email.com"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="invoice-to" class="form-label">To</label>
                        <input
                                type="text"
                                class="form-control"
                                id="invoice-to"
                                value="qConsolidated@email.com"
                                placeholder="company@email.com"
                        />
                    </div>
                    <div class="mb-3">
                        <label for="invoice-message" class="form-label">Message</label>
                        <textarea class="form-control" name="invoice-message" id="invoice-message" cols="3" rows="8">
                        </textarea
                        >
                    </div>
                    <div class="mb-3 d-flex flex-wrap">
                        <button type="button" class="btn btn-primary me-3" data-bs-dismiss="offcanvas">Send</button>
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Send Invoice Sidebar -->

        <!-- /Offcanvas -->
    </div>
    <!-- / Content -->

    <x-slot name="scripts">

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

    </x-slot>

</x-app-layout>