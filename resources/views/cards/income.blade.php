<x-app-layout>
    <x-slot name="links">
        <link rel="stylesheet" href="{{ asset('lib/assets/vendor/css/pages/ui-carousel.css') }}"/>
    </x-slot>
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row invoice-edit">
            <!-- Invoice Edit-->
            <div class="col-lg-12 col-12 mb-lg-0 mb-4">
                <div class="card invoice-preview-card">
                    <div class="card-body">
                        @if(isset($record) && $record->transaction_adjustment_id)
                            @php
                                $adjustmentRecord = \App\Models\TransactionAdjustment::find($record->transaction_adjustment_id);
                            @endphp

                            @if($adjustmentRecord)
                                <div class="alert alert-light-danger d-flex align-items-center justify-content-between border border-danger border-opacity-25 rounded-3 p-3 mt-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-label-danger me-3 p-2">
                                            <i class="ti ti-alert-triangle fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-danger fw-bold">Rejected Transaction</h6>
                                            <small class="text-muted">View detail of rejected transaction record.
                                            </small>
                                        </div>
                                    </div>
                                    <a href="{{ route('income.view', ['id' => $adjustmentRecord->original_record_id]) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="btn btn-danger btn-sm px-4 shadow-sm">
                                        <i class="ti ti-external-link me-1"></i> View Details
                                    </a>
                                </div>
                            @endif
                        @endif

                        <div class="row m-sm-4 m-0">
                            <div class="col-md-6 mb-md-0 mb-4 ps-0">
                                <div class="card-block">
                                    <div class="c-store-view">
                                        <div class="c-store-view-content">
                                            <h3>C-Store</h3>
                                            <ul>
                                                @if(isset($record->station_name))
                                                    <li>Station Name:<span>{{$record->station_name}}</span></li>
                                                @endif

                                                @if(isset($record->category_name))
                                                    <li>Service Type:<span>{{$record->category_name}}</span></li>
                                                @endif

                                                @if(isset($record->service_name))
                                                    <li>Service:<span>{{$record->service_name}}</span></li>
                                                @endif

                                                @if(isset($record->sl_quantity))
                                                    <li>Sale
                                                        Quantity:<span>${{ number_format($record->sl_quantity, 2, '.', ',') }}</span>
                                                    </li>
                                                @endif

                                                @if(isset($record->sl_si_unit))
                                                    <li>Sale Unit:<span>{{$record->sl_si_unit}}</span></li>
                                                @endif

                                                @if(isset($record->fuel_sales))
                                                    <li>Fuel
                                                        Sales:<span>${{ number_format($record->fuel_sales, 2, '.', ',') }}</span>
                                                    </li>
                                                @endif

                                                @if(isset($record->fuel_discount))
                                                    <li>Fuel
                                                        Discount:<span>${{ number_format($record->fuel_discount, 2, '.', ',') }}</span>
                                                    </li>
                                                @endif

                                                @if(isset($record->total_fuel_sale))
                                                    <li>Total Fuel
                                                        Sale:<span>${{ number_format($record->total_fuel_sale, 2, '.', ',') }}</span>
                                                    </li>
                                                @endif

                                                @if(isset($record->other_sale))
                                                    <li>Other
                                                        Sale:<span>${{ number_format($record->other_sale, 2, '.', ',') }}</span>
                                                    </li>
                                                @endif

                                                @if(isset($record->other_discount))
                                                    <li>Other Discount:<span>{{$record->other_discount}}</span></li>
                                                @endif

                                                @if(isset($record->total_other_sale))
                                                    <li>Total Other
                                                        Sale:<span>${{ number_format($record->total_other_sale, 2, '.', ',') }}</span>
                                                    </li>
                                                @endif

                                                @if(isset($record->net_tax))
                                                    <li>Net Tax:<span>{{$record->net_tax}}</span></li>
                                                @endif

                                                @if(isset($record->credit_card_sale))
                                                    <li>Credit Card
                                                        Sale:<span>${{ number_format($record->credit_card_sale, 2, '.', ',') }}</span>
                                                    </li>
                                                @endif

                                                @if(isset($record->cash_sale))
                                                    <li>Cash
                                                        Sale:<span>${{ number_format($record->cash_sale, 2, '.', ',') }}</span>
                                                    </li>
                                                @endif

                                                @if(isset($record->net_sale))
                                                    <li>Net
                                                        Sale:<span>${{ number_format($record->net_sale, 2, '.', ',') }}</span>
                                                    </li>
                                                @endif
                                                @if(!empty($record->rejected_reason))
                                                    <li>Rejected Reason:<span>{{ $record->rejected_reason }}</span></li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="c-store-total">
                                            <div class="row">
                                                <div class="col-md-{{ $adjustmentAmount > 0 ? '6 text-left' : '12 text-center' }}">
                                                    <h3>Total Cash</h3>
                                                    <h1>${{ number_format($record->cash_sale ?? 0, 2, '.', ',') }}</h1>
                                                </div>

                                                @if($adjustmentAmount != 0)
                                                    <div class="col-md-6 text-right">
                                                        <h3>Rejected Resubmission Difference:</h3>
                                                        <h1>${{ number_format($adjustmentAmount, 2, '.', ',') }}</h1>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <div class="mt-4 text-center">
                                        @if($record->status == 0)
                                            <form action="{{ route('cstore.sale.approve', $record->id) }}" method="POST"
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-info">Approve</button>
                                            </form>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#rejectModalIncome">
                                                Reject
                                            </button>
                                        @endif

                                        @if($record->status == 1)
                                            <form action="{{ route('cstore.sale.pending', $record->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">Pending</button>
                                            </form>
                                        @endif

                                        <a href="{{ url()->previous() }}" class="btn btn-primary">
                                            Go Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-md-0 mb-4 ps-0">
                                <!--<h6 class="text-muted mt-3">Income Images</h6>-->
                                <div class="swiper" id="swiper-with-progress">
                                    @if($images && count($images) > 0)
                                        <div class="swiper-wrapper">
                                            @foreach($images as $image)
                                                <div class="swiper-slide">
                                                    <img src="{{ asset($image->image_path) }}"
                                                         class="img-thumbnail img-clickable" data-bs-toggle="modal"
                                                         data-bs-target="#imageModal"
                                                         data-bs-src="{{ asset($image->image_path) }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="swiper-pagination"></div>
                                        <div class="swiper-button-next swiper-button-white custom-icon"></div>
                                        <div class="swiper-button-prev swiper-button-white custom-icon"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Offcanvas -->
    </div>
    <!-- / Content -->
    <!-- Bootstrap Modal for Image Enlargement -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Expanded Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModalIncome" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject C-Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('cstore.sale.reject', $record->id) }}" method="POST" class="d-inline ms-2">
                        @csrf
                        <input type="text" class="form-control" id="rejected_reason"
                               placeholder="Please add reject reason" maxlength="255" required="required"
                               name="rejected_reason">
                        <br>
                        <button type="submit" class="btn btn-primary">Reject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <x-slot name="scripts">
        <script src="{{ asset('lib/assets/js/ui-carousel.js') }}"></script>
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

    <!-- JavaScript to Handle Image Click and Expansion -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const modalImage = document.getElementById("modalImage");
                const imageModal = document.getElementById("imageModal");

                document.querySelectorAll(".img-clickable").forEach(function (img) {
                    img.addEventListener("click", function () {
                        modalImage.src = this.getAttribute("data-bs-src");
                    });
                });
            });
        </script>

    </x-slot>

</x-app-layout>