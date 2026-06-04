<x-app-layout>
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- User Sidebar -->
            <div class="row">
                <div class="card station-ser-view-info-main mb-4 p-0 ms-3">
                    <div class="card-body station-ser-view-info-body">
                        <div class="row">
                            <div class="col-7">
                                <h3 class=""> {{$station->name}}</h3>
                                <p class="service-code">Site Code: <span>{{$station->code}}</span></p>

                                <div class="station-ser-view-info">
                                    <div class="station-ser-view-info-box">
                                        <div>
                                            <p>Status:</p>
                                            <span class="badge @if($station->is_active == 1) bg-label-success @else bg-label-warning @endif">
                                                @if($station->is_active == 1) Active @else Suspended @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="station-ser-view-info-box">
                                        <img src="{{ asset('lib/assets/img/watch-icon.png') }}" alt="Icon"/>
                                        <div>
                                            <p>Site Hourse:</p>
                                            <span>{{$station->opening_hours}}</span>
                                        </div>
                                    </div>
                                    <div class="station-ser-view-info-box">
                                        <img src="{{ asset('lib/assets/img/company-icon.png') }}" alt="Icon"/>
                                        <div>
                                            <p>Company:</p>
                                            <span>{{$station->company_name}}</span>
                                        </div>
                                    </div>
                                    @if(!empty($station->phone))
                                        <div class="station-ser-view-info-box-phone">
                                            <img src="{{ asset('lib/assets/img/phone-icon.png') }}" alt="Icon"/>
                                            <p>{{$station->phone}}</p>
                                            <button type="button">
                                                <img src="{{ asset('lib/assets/img/copy-icon.png') }}" alt="Icon"/>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <div class="station-ser-view-address">
                                    <img src="{{ asset('lib/assets/img/map-icon.png') }}" alt="Icon"/>
                                    <div>
                                        <p>Postal Address:</p>
                                        <span>{{$station->location}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="card station-ser-view-box"
                                     style="height: 219px; background-image: url('{{ asset('lib/assets/img/map-image.png') }}'); background-size: cover; background-repeat: no-repeat; background-position: center;">
                                    <div class="card-body card-body-with-bg">
                                        <p class="card-text">Latitude: {{$station->latitude}}<br/>
                                            Longitude: {{$station->longitude}}</p>
                                        <a href="https://www.google.com/maps?q={{$station->latitude}},{{$station->longitude}}"
                                           target="_blank"
                                           class="btn btn-primary">View Map</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="row">
                <div class="card mb-4 p-0 ms-3 station-ser-view-switch">
                    <div class="card-body">
                        @foreach($services as $service)
                            @if($service->scode=='0002')
                                <input type="hidden" id="fuelServiceId" value="{{ $service->id }}">
                            @endif

                            <div class="row align-items-center">
                                <div class="col-md-1">
                                    <div class="form-check form-switch">
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input add-station-service"
                                                   data-station_id="{{$station->id}}" data-service_id="{{$service->id}}"
                                                   @if(\App\Models\StationService::where('station_id', $station->id)
                                                               ->where('service_id', $service->id)
                                                               ->where('is_active', 1)
                                                               ->exists()) checked @endif
                                            />
                                            <span class="switch-toggle-slider">
                                              <span class="switch-on">
                                                <i class="ti ti-check"></i>
                                              </span>
                                              <span class="switch-off">
                                                <i class="ti ti-x"></i>
                                              </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-0">{{$service->name}}</h6>
                                    <small class="text-muted">{{$service->description}}</small>
                                </div>

                                <div class="col-md-7">
                                    <div class="station-ser-view-switch-buttons-top">
                                        @if($service->scode=='0001')
                                            @if(\App\Models\StationService::where('station_id', $station->id)
                                                            ->where('service_id', $service->id)
                                                            ->where('is_active', 1)
                                                            ->exists())
                                                <a href="javascript:void(0)"
                                                   class="btn btn-primary Rectangle_4 me-1 service-pos-modal-event"
                                                   data-station_id="{{ $station->id }}"
                                                   data-service_id="{{ $service->id }}"
                                                >
                                                    <img src="{{ asset('lib/assets/img/pos-icon.png') }}" alt="Icon"/>
                                                    <b class=""
                                                       id="pos_0001_pos_text">{{$convenienceStoreSelection}}</b>
                                                </a>
                                                <a href="javascript:void(0)"
                                                   class="btn btn-primary Rectangle_4 me-1 service-supplier-modal-event"
                                                   data-station_id="{{ $station->id }}"
                                                   data-service_id="{{ $service->id }}"
                                                >
                                                    <img src="{{ asset('lib/assets/img/select-setting-icon.png') }}"
                                                         alt="Icon"/>
                                                    <b class=""
                                                       id="pos_0001_supplier_text">{{$convenienceStoreSupplierSelection}}</b>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="station-ser-view-switch-buttons-bottom">
                                        @if(\App\Models\StationService::where('station_id', $station->id)
                                                           ->where('service_id', $service->id)
                                                           ->where('is_active', 1)
                                                           ->exists())
                                            @if($service->scode=='0004')
                                                <a href="javascript:void(0)"
                                                   class="btn btn-primary Rectangle_4 me-1 restaurant-pos-machine-modal-event"
                                                   data-station_id="{{ $station->id }}"
                                                   data-service_id="{{ $service->id }}"
                                                >
                                                    <img src="{{ asset('lib/assets/img/add-icon.png') }}" alt="Icon"/>
                                                    <b class="ms-1">{{$restaurantSelection}}</b>
                                                </a>
                                            @endif
                                            @if($service->scode=='0005')
                                                <a href="javascript:void(0)"
                                                   class="btn btn-primary Rectangle_4 me-1 lotto-machine-modal-event"
                                                   data-station_id="{{ $station->id }}"
                                                   data-service_id="{{ $service->id }}"
                                                >
                                                    <img src="{{ asset('lib/assets/img/add-icon.png') }}" alt="Icon"/>
                                                    <b>{{$lotterySelection}}</b>
                                                </a>
                                            @endif

                                            @if($service->scode=='0006')
                                                <a href="javascript:void(0)"
                                                   class="btn btn-primary Rectangle_4 me-1 car-wash-modal-event"
                                                   data-station_id="{{ $station->id }}"
                                                   data-service_id="{{ $service->id }}"
                                                >
                                                    <img src="{{ asset('lib/assets/img/add-icon.png') }}" alt="Icon"/>
                                                    <b>{{$carWashSelection}}</b>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- /Social Accounts -->
        </div>
    </div>

    <!-- Supplier Modal -->
    <div class="modal fade" id="fuelSupplierModal" aria-labelledby="fuelSupplierModalLabel" tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body custom-radio-buttons">
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Add Supplier</h3>
                    </div>
                    <form id="fuelSupplierModalForm">
                        @csrf
                        <input type="hidden" name="station_id" id="modal_supplier_station_id">
                        <input type="hidden" name="service_id" id="modal_supplier_service_id">
                        <div class="col-12" style="max-width: 300px;margin: 0 auto;">
                            <div class="row">
                                <div class="col-md mb-md-0 mb-3">
                                    <div class="form-check custom-option custom-option-icon">
                                        <label class="form-check-label custom-option-content" for="customRadioDealer">
                                          <span class="custom-option-body">
                                          <img src="{{ asset('lib/assets/img/deal-icon.png') }}" alt="Icon"/>

                                            <span class="custom-option-title">Is Dealer</span>
                                          </span>
                                            <input
                                                    name="fuel_service_type"
                                                    class="form-check-input"
                                                    type="radio"
                                                    value="1"
                                                    id="customRadioDealer"
                                            />
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md mb-md-0 mb-3">
                                    <div class="form-check custom-option custom-option-icon">
                                        <label class="form-check-label custom-option-content"
                                               for="customRadioCommission">
                                          <span class="custom-option-body">
                                          <img src="{{ asset('lib/assets/img/commission-icon.png') }}" alt="Icon"/>

                                            <span class="custom-option-title"> Is Commission </span>
                                          </span>
                                            <input
                                                    name="fuel_service_type"
                                                    class="form-check-input"
                                                    type="radio"
                                                    value="2"
                                                    id="customRadioCommission"
                                            />
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3">
                                <div class="mb-3 row">
                                    <label class="col-form-label" for="supplier_id">Supplier</label>
                                    <select id="supplier_id" class="form-select" name="supplier_id">
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="commissionFields" class="d-none col-12">
                            <hr>
                            <h5 class="text-center mb-3">Fuel Range (Commission Details)</h5>

                            <div class="row g-3">
                                <div class="col-12 col-md-3">
                                    <div class="form-check mt-3">
                                        <input name="qty_unit" class="form-check-input" type="radio" value="liter"
                                               id="qty_liter_unit"/>
                                        <label class="form-check-label" for="qty_liter_unit">Liter</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-check mt-3">
                                        <input name="qty_unit" class="form-check-input" type="radio" value="gallon"
                                               id="qty_gallon_unit"/>
                                        <label class="form-check-label" for="qty_gallon_unit">Gallon</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="effective_date_field_update" class="col-form-label">Effective
                                        From</label>
                                    <input class="form-control" type="date" name="effective_date"
                                           id="effective_date_field_update"/>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="qty_from_field_update_1" class="col-form-label">Qty From</label>
                                    <input class="form-control" type="number" id="qty_from_field_update_1"
                                           name="qty_from[]" value="1"/>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="qty_to_field_update_1" class="col-form-label">Qty To</label>
                                    <input class="form-control" type="number" name="qty_to[]" value="1000"
                                           id="qty_to_field_update_1"/>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="col-form-label" for="fuel_commission_rate_field_update_1">Commission
                                        Rate</label>
                                    <input id="fuel_commission_rate_field_update_1" name="commission_rate[]" type="text"
                                           class="form-control" placeholder="0.00"/>
                                </div>

                                <!-- Second Range -->
                                <div class="col-12 col-md-4">
                                    <label for="qty_from_field_update_2" class="col-form-label">Qty From</label>
                                    <input readonly class="form-control" type="number" id="qty_from_field_update_2"
                                           name="qty_from[]" value="1001"/>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="qty_to_field_update_2" class="col-form-label">Qty To</label>
                                    <input class="form-control" type="number" name="qty_to[]" value="2000"
                                           id="qty_to_field_update_2"/>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="col-form-label" for="fuel_commission_rate_field_update_2">Commission
                                        Rate</label>
                                    <input id="fuel_commission_rate_field_update_2" name="commission_rate[]" type="text"
                                           class="form-control" placeholder="0.00"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-5">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            <button
                                    type="reset"
                                    class="btn btn-label-secondary btn-reset"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pos Machine Modal -->
    <div class="modal fade" id="posMachineModal" aria-labelledby="posMachineModalLabel" tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body custom-radio-buttons">
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    <div class="text-center mb-4">
                        <h3 class="mb-2">POS Machine</h3>
                    </div>
                    <form id="posMachineModalForm">
                        @csrf
                        <input type="hidden" name="station_id" id="modal_pos_station_id">
                        <input type="hidden" name="service_id" id="modal_pos_service_id">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md mb-md-0 mb-3">
                                    <div class="form-check custom-option custom-option-icon">
                                        <label class="form-check-label pos_gilbarco_class custom-option-content"
                                               for="pos_gilbarco_type">
                                  <span class="custom-option-body">
                                    
                                  <img src="{{ asset('lib/assets/img/gilbarco-icon.png') }}" alt="Icon"/>
                                    <span class="custom-option-title">Gilbarco</span>
                                  </span>
                                            <input
                                                    name="pos_machine_type"
                                                    class="form-check-input"
                                                    type="radio"
                                                    value="0009"
                                                    id="pos_gilbarco_type"
                                            />
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md mb-md-0 mb-3">
                                    <div class="form-check custom-option custom-option-icon">
                                        <label class="form-check-label pos_rubi_class custom-option-content"
                                               for="pos_rubi_type">
                                  <span class="custom-option-body">
                                  <img src="{{ asset('lib/assets/img/rubi-icon.png') }}" alt="Icon"/>

                                    <span class="custom-option-title"> Rubi </span>
                                  </span>
                                            <input
                                                    name="pos_machine_type"
                                                    class="form-check-input"
                                                    type="radio"
                                                    value="0010"
                                                    id="pos_rubi_type"
                                            />
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            <button
                                    type="reset"
                                    class="btn btn-label-secondary btn-reset"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pos Restaurant Machine Modal -->
    <div class="modal fade" id="restaurantPosMachineModal" aria-labelledby="restaurantPosMachineModalLabel"
         tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body custom-radio-buttons">
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Restaurant POS Machine Configuration</h3>
                    </div>
                    <form id="restaurantPosMachineModalForm">
                        @csrf
                        <input type="hidden" name="station_id" id="modal_restaurant_pos_station_id">
                        <input type="hidden" name="service_id" id="modal_restaurant_pos_service_id">
                        <div class="col-12">
                            <div class="row">

                                <div class="col-md mb-md-0 mb-3">
                                    <div class="form-check custom-option custom-option-icon">
                                        <label class="form-check-label ncr_aloha_class custom-option-content"
                                               for="ncr_aloha">
                                          <span class="custom-option-body">

                                               <img src="{{ asset('lib/assets/img/NCR.png') }}" alt="Icon"/>
                                                <span class="custom-option-title">NCR Aloha</span>
                                          </span>
                                            <input
                                                    name="restaurant_pos_machine_type"
                                                    class="form-check-input"
                                                    type="radio"
                                                    value="0017"
                                                    id="ncr_aloha"
                                            />
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md mb-md-0 mb-3">
                                    <div class="form-check custom-option custom-option-icon">
                                        <label class="form-check-label dc_super_mart_class custom-option-content"
                                               for="dc_super_mart">
                                              <span class="custom-option-body">

                                                <img src="{{ asset('lib/assets/img/dc.png') }}" alt="Icon"/>
                                                <span class="custom-option-title">DC Super Mart</span>
                                              </span>
                                            <input
                                                    name="restaurant_pos_machine_type"
                                                    class="form-check-input"
                                                    type="radio"
                                                    value="0018"
                                                    id="dc_super_mart"
                                            />
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            <button
                                    type="reset"
                                    class="btn btn-label-secondary btn-reset"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lottery Machine Modal -->
    <div class="modal fade" id="lotteryMachineModal" aria-labelledby="lotteryMachineModalLabel" tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content p-1 p-md-4">
                <div class="modal-body lottery-modal-check-box-main">
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Add Lottery Machine</h3>
                    </div>
                    <form id="lotteryMachineModalForm">
                        @csrf
                        <input type="hidden" name="station_id" id="modal_lottery_station_id">
                        <input type="hidden" name="service_id" id="modal_lottery_service_id">

                        <div class="col-12 my-3">
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input lottery-selector" type="radio" name="lottery_type"
                                           id="lottery_dc" value="dc" checked>
                                    <label class="form-check-label" for="lottery_dc">DC Lottery</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input lottery-selector" type="radio" name="lottery_type"
                                           id="lottery_va" value="va">
                                    <label class="form-check-label" for="lottery_va">Virginia Lottery</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input lottery-selector" type="radio" name="lottery_type"
                                           id="lottery_md" value="md">
                                    <label class="form-check-label" for="lottery_md">Maryland Lottery</label>
                                </div>
                            </div>
                        </div>
                        {{--dc lottery--}}
                        <div class="lottery-section dc">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md mb-md-0 mb-2">
                                        <div class="form-check custom-option custom-option-icon">
                                            <label class="form-check-label custom-option-content"
                                                   for="dc_lotto_online_machine">
                                                  <span class="custom-option-body">
                                                    <i class="ti ti-server"></i>
                                                    <span class="custom-option-title"> DC Lotto Online </span>
                                                  </span>
                                                <input
                                                        name="dc_lotto_online"
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        value="0011"
                                                        id="dc_lotto_online_machine"
                                                />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-xl-12 my-4">
                                    <div class="card" style="box-shadow: none;">
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-md mb-md-0 mb-2">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                               for="pat_machine_1">
                                                              <span class="custom-option-body">
                                                                <i class="ti ti-server"></i>
                                                                <span class="custom-option-title"> PAT 1 </span>
                                                              </span>
                                                            <input
                                                                    name="pat_machine_1"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="0014"
                                                                    id="pat_machine_1"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md mb-md-0 mb-2">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                               for="pat_machine_2">
                                                              <span class="custom-option-body">
                                                                <i class="ti ti-server"></i>
                                                                <span class="custom-option-title"> PAT 2 </span>
                                                              </span>
                                                            <input
                                                                    name="pat_machine_2"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="0015"
                                                                    id="pat_machine_2"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                               for="pat_machine_3">
                                                              <span class="custom-option-body">
                                                                <i class="ti ti-server"></i>
                                                                <span class="custom-option-title"> PAT 3 </span>
                                                              </span>
                                                            <input
                                                                    name="pat_machine_3"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="0016"
                                                                    id="pat_machine_3"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-xl-12 my-4">
                                    <div class="card" style="box-shadow: none;">
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-md mb-md-0 mb-2">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                               for="gaming_bet_machine_1">
                                                              <span class="custom-option-body">
                                                                <i class="ti ti-server"></i>
                                                                <span class="custom-option-title"> Gaming Bet 1 </span>
                                                              </span>
                                                            <input
                                                                    name="gaming_bet_machine_1"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="0030"
                                                                    id="gaming_bet_machine_1"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md mb-md-0 mb-2">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                               for="gaming_bet_machine_2">
                                                              <span class="custom-option-body">
                                                                <i class="ti ti-server"></i>
                                                                <span class="custom-option-title"> Gaming Bet 2 </span>
                                                              </span>
                                                            <input
                                                                    name="gaming_bet_machine_2"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="0031"
                                                                    id="gaming_bet_machine_2"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md mb-md-0 mb-2">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                               for="gaming_bet_machine_3">
                                                              <span class="custom-option-body">
                                                                <i class="ti ti-server"></i>
                                                                <span class="custom-option-title"> Gaming Bet 3 </span>
                                                              </span>
                                                            <input
                                                                    name="gaming_bet_machine_3"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="0032"
                                                                    id="gaming_bet_machine_3"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--virginia lottery--}}
                        <div class="lottery-section va d-none">
                            <div class="col-12">
                                <div class="col-xl-12 my-4">
                                    <div class="card" style="box-shadow: none;">
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-md mb-md-0 mb-2">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                               for="virginia_lottery_scratch_card">
                                                              <span class="custom-option-body">
                                                                <i class="ti ti-server"></i>
                                                                <span class="custom-option-title"> Virginia (Scratch Card) </span>
                                                              </span>
                                                            <input
                                                                    name="virginia_scratch_card"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="0035"
                                                                    id="virginia_lottery_scratch_card"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md mb-md-0 mb-2">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                               for="virginia_lottery_online">
                                                              <span class="custom-option-body">
                                                                <i class="ti ti-server"></i>
                                                                <span class="custom-option-title"> Virginia Lottery (Online) </span>
                                                              </span>
                                                            <input
                                                                    name="virginia_online"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="0036"
                                                                    id="virginia_lottery_online"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--maryland lottery--}}
                        <div class="lottery-section md d-none">
                            <div class="col-12">
                                <div class="col-xl-12 my-4">
                                    <div class="card" style="box-shadow: none;">
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-md mb-md-0 mb-2">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                               for="maryland_lottery_scratch_card">
                                                              <span class="custom-option-body">
                                                                <i class="ti ti-server"></i>
                                                                <span class="custom-option-title"> Maryland (Scratch Card) </span>
                                                              </span>
                                                            <input
                                                                    name="maryland_scratch_card"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="0037"
                                                                    id="maryland_lottery_scratch_card"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md mb-md-0 mb-2">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content"
                                                               for="maryland_lottery_online">
                                                              <span class="custom-option-body">
                                                                <i class="ti ti-server"></i>
                                                                <span class="custom-option-title"> Maryland Lottery (Online) </span>
                                                              </span>
                                                            <input
                                                                    name="maryland_online"
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    value="0038"
                                                                    id="maryland_lottery_online"
                                                            />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 text-center mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                                <button
                                        type="reset"
                                        class="btn btn-label-secondary btn-reset"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pos Machine Modal -->
    <div class="modal fade" id="carWashModal" aria-labelledby="carWashModalLabel" tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body custom-radio-buttons">
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Car Wash Operators</h3>
                    </div>
                    <form id="carWashModalForm">
                        @csrf
                        <input type="hidden" name="station_id" id="modal_carwash_station_id">
                        <input type="hidden" name="service_id" id="modal_carwash_service_id">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md mb-md-0 mb-3">
                                    <div class="form-check custom-option custom-option-icon">
                                        <label class="form-check-label self_operated_class custom-option-content"
                                               for="self_operated_type">
                                  <span class="custom-option-body">

                                  <img src="{{ asset('lib/assets/img/self.png') }}" alt="Icon"/>
                                    <span class="custom-option-title">Self Operated</span>
                                  </span>
                                            <input
                                                    name="car_wash_operated_type"
                                                    class="form-check-input"
                                                    type="radio"
                                                    value="0039"
                                                    id="self_operated_type"
                                            />
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md mb-md-0 mb-3">
                                    <div class="form-check custom-option custom-option-icon">
                                        <label class="form-check-label company_operated_class custom-option-content"
                                               for="company_operated_type">
                                  <span class="custom-option-body">
                                  <img src="{{ asset('lib/assets/img/company.png') }}" alt="Icon"/>

                                    <span class="custom-option-title"> Company Operated </span>
                                  </span>
                                            <input
                                                    name="car_wash_operated_type"
                                                    class="form-check-input"
                                                    type="radio"
                                                    value="0040"
                                                    id="company_operated_type"
                                            />
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            <button
                                    type="reset"
                                    class="btn btn-label-secondary btn-reset"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
            $(document).ready(function () {

                function add(a, b) {
                    return a + b;
                }

                $('#qty_to_field_update_1').on('change', function () {
                    let qtyTo = parseInt($(this).val()); // Convert to integer
                    if (!isNaN(qtyTo)) { // Check if the conversion was successful
                        $('#qty_from_field_update_2').val(add(qtyTo, 1));
                    } else {
                        // Handle cases where the input is not a valid number (optional)
                        $('#qty_from_field_update_2').val(''); // Or some other appropriate action
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const radios = document.querySelectorAll('.lottery-selector');

                radios.forEach(function (radio) {
                    radio.addEventListener('change', function () {
                        const selected = this.value;

                        // Hide all sections and uncheck all checkboxes
                        document.querySelectorAll('.lottery-section').forEach(function (section) {
                            section.classList.add('d-none');
                            // Uncheck all checkboxes inside this section
                            section.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                                checkbox.checked = false;
                            });
                        });

                        // Show the selected section
                        document.querySelectorAll('.lottery-section.' + selected).forEach(function (section) {
                            section.classList.remove('d-none');
                        });
                    });
                });
            });
        </script>

        <script>
            $(document).on('change', 'input[type="radio"]', function () {
                const groupName = $(this).attr('name');
                // Remove active from all radios in the same group
                $(`input[name="${groupName}"]`).each(function () {
                    $(this).closest('.custom-option-content').removeClass('active');
                });
                // Add active to the selected one
                $(this).closest('.custom-option-content').addClass('active');
            });

            $(document).on('click', 'input[type="radio"][name="restaurant_pos_machine_type"]', function () {
                const groupName = $(this).attr('name');
                $(`input[name="${groupName}"]`).each(function () {
                    $(this).closest('.custom-option-content').removeClass('active');
                });
                $(this).closest('.custom-option-content').addClass('active');
            });

            $(document).ready(function () {
                function updateActiveClass() {
                    $('.lottery-modal-check-box-main input[type="checkbox"]').each(function () {
                        $(this).closest("label").toggleClass("active", this.checked);
                    });
                }

                // Run on page load
                updateActiveClass();
                // Add event listener for changes
                $('.lottery-modal-check-box-main input[type="checkbox"]').on("change", updateActiveClass);
            });

        </script>

        {{--add and remove service--}}
        <script>
            $(document).on('click', '.add-station-service', function () {
                const stationId = $(this).data('station_id');
                const serviceId = $(this).data('service_id');
                const isChecked = $(this).is(':checked'); // Check if the checkbox is checked

                // Handle other services
                if (isChecked) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You are assigning this service to the station!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Update it!',
                        cancelButtonText: 'Cancel'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route("station.service.save") }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    station_id: stationId,
                                    service_id: serviceId
                                },
                                success: function (response) {
                                    Swal.fire({
                                        title: 'success!',
                                        text: "Request Successfully processed!",
                                        icon: 'success'
                                    }).then(function () {
                                        location.reload();
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
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    // Checkbox OFF logic for other services
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You are removing this service from the station!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Remove it!',
                        cancelButtonText: 'Cancel'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route("station.service.remove") }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    station_id: stationId,
                                    service_id: serviceId
                                },
                                success: function (response) {
                                    Swal.fire({
                                        title: 'success!',
                                        text: "Request Successfully processed!",
                                        icon: 'success'
                                    }).then(function () {
                                        location.reload();
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
                        } else {
                            location.reload();
                        }
                    });
                }
            });
        </script>

        {{--add/update station configuration--}}
        <script>

            // add-update supplier
            document.getElementById("fuelSupplierModalForm").addEventListener("submit", function (event) {
                var modal = new bootstrap.Modal(document.getElementById("fuelSupplierModal"));
                event.preventDefault();
                var formData = new FormData(this);
                fetch("/supplier/add-update", {
                    method: "POST",
                    body: formData
                })
                    .then(function (response) {
                        if (response.ok) {
                            modal.hide();
                            Swal.fire(
                                'Confirmed!',
                                'Supplier Saved successfully.',
                                'success'
                            ).then(function () {
                                location.reload(); // Reload the page or handle UI updates
                            });
                        } else {
                            alert("Error saving data.");
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.message || 'Something went wrong.',
                            'error'
                        );
                    });
            });

            // update fuel configuration and new service commission
            document.getElementById("posMachineModalForm").addEventListener("submit", function (event) {
                var modal = new bootstrap.Modal(document.getElementById("posMachineModal"));
                event.preventDefault();
                var formData = new FormData(this);
                fetch("/station-pos/add-update", {
                    method: "POST",
                    body: formData
                })
                    .then(function (response) {
                        if (response.ok) {
                            modal.hide();
                            Swal.fire(
                                'Confirmed!',
                                'POS Type Saved successfully.',
                                'success'
                            ).then(function () {
                                location.reload(); // Reload the page or handle UI updates
                            });
                        } else {
                            alert("Error saving data.");
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.message || 'Something went wrong.',
                            'error'
                        );
                    });
            });

            // update fuel service commission
            document.getElementById("lotteryMachineModalForm").addEventListener("submit", function (event) {
                var modal = new bootstrap.Modal(document.getElementById("lotteryMachineModal"));
                event.preventDefault();
                var formData = new FormData(this);
                fetch("/station-lotto/add-update", {
                    method: "POST",
                    body: formData
                })
                    .then(function (response) {
                        if (response.ok) {
                            modal.hide();
                            Swal.fire(
                                'Confirmed!',
                                'Lottery Machine recorded successfully.',
                                'success'
                            ).then(function () {
                                location.reload(); // Reload the page or handle UI updates
                            });
                        } else {
                            alert("Error saving data.");
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.message || 'Something went wrong.',
                            'error'
                        );
                    });
            });

            // add/update restaurent operator
            document.getElementById("restaurantPosMachineModalForm").addEventListener("submit", function (event) {
                var modal = new bootstrap.Modal(document.getElementById("restaurantPosMachineModal"));
                event.preventDefault();
                var formData = new FormData(this);
                fetch("/station-restaurant-pos/add-update", {
                    method: "POST",
                    body: formData
                })
                    .then(function (response) {
                        if (response.ok) {
                            modal.hide();
                            Swal.fire(
                                'Confirmed!',
                                'Restaurant POS Machine recorded successfully.',
                                'success'
                            ).then(function () {
                                location.reload(); // Reload the page or handle UI updates
                            });
                        } else {
                            alert("Error saving data.");
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.message || 'Something went wrong.',
                            'error'
                        );
                    });
            });

            // add/update carWash pos machine
            document.getElementById("carWashModalForm").addEventListener("submit", function (event) {
                var modal = new bootstrap.Modal(document.getElementById("carWashModal"));
                event.preventDefault();
                var formData = new FormData(this);
                fetch("/station-car-wash/add-update", {
                    method: "POST",
                    body: formData
                })
                    .then(function (response) {
                        if (response.ok) {
                            modal.hide();
                            Swal.fire(
                                'Confirmed!',
                                'Car Wash Operator Type recorded successfully.',
                                'success'
                            ).then(function () {
                                location.reload(); // Reload the page or handle UI updates
                            });
                        } else {
                            alert("Error saving data.");
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON.message || 'Something went wrong.',
                            'error'
                        );
                    });
            });

        </script>

        {{--set station and service input--}}
        <script>

            $(document).on('click', '.service-pos-modal-event', function () {
                const stationId = $(this).data('station_id');
                const serviceId = $(this).data('service_id');

                // Set the hidden input values
                $('#modal_pos_station_id').val(stationId);
                $('#modal_pos_service_id').val(serviceId);

                $.ajax({
                    url: '{{ route("station.service.getPos.machine") }}',
                    type: 'GET',
                    data: {
                        station_id: stationId,
                        service_id: serviceId
                    },
                    success: function (response) {
                        console.log('AJAX Response:', response);
                        $('#posMachineModal').modal('show');

                        // Wait until the modal is fully opened before setting the radio buttons
                        console.log(response.result);
                        if (response.result.pos_id === '0009') {
                            $('#pos_gilbarco_type').prop('checked', true).change();
                            // Add active class to Dealer
                            $('#pos_gilbarco_class').closest('.form-check-label').addClass('active');
                            $('#pos_rubi_class').closest('.form-check-label').removeClass('active');
                            console.log('Gilbarco type checked');
                        }
                        if (response.result.pos_id === '0010') {
                            $('#pos_rubi_type').prop('checked', true).change();
                            $('#pos_rubi_class').closest('.form-check-label').addClass('active');
                            $('#pos_gilbarco_class').closest('.form-check-label').removeClass('active');
                            console.log('Rubi type checked');
                        }
                    },
                    error: function () {
                        $('#posMachineModal').modal('show');
                        // Swal.fire('Error!', 'Failed to fetch service data.', 'error');
                    }
                });
            });

            $(document).on('click', '.lotto-machine-modal-event', function () {
                const stationId = $(this).data('station_id');
                const serviceId = $(this).data('service_id');

                // Set the values of the hidden input fields
                $('#modal_lottery_station_id').val(stationId);
                $('#modal_lottery_service_id').val(serviceId);

                // --- Helper function to update the lottery section display ---
                function updateLotteryDisplay(selectedValue) {
                    console.log('updateLotteryDisplay called for:', selectedValue); // Debugging

                    // Hide all sections and uncheck all checkboxes
                    $('.lottery-section').addClass('d-none').each(function () {
                    });

                    // Show the selected section (if a value is provided)
                    if (selectedValue) {
                        $('.lottery-section.' + selectedValue).removeClass('d-none');
                        console.log('Displaying section:', '.lottery-section.' + selectedValue); // Debugging
                    }
                }

                $(document).off('change', '.lottery-selector').on('change', '.lottery-selector', function () {
                    const selectedValue = $(this).val();
                    updateLotteryDisplay(selectedValue);
                });
                // --- End of user interaction listener setup ---


                $.ajax({
                    url: '{{ route("service.get.lotto.machine") }}',
                    type: 'GET',
                    data: {
                        station_id: stationId,
                        service_id: serviceId
                    },
                    success: function (response) {
                        console.log("AJAX Success. Full response:", response); // Debugging: Full response
                        console.log("AJAX Success. response.result:", response.result); // Debugging: Just the result

                        $('#lotteryMachineModal').modal('show');

                        // --- Reset ALL relevant checkboxes and active classes first ---
                        //$('input[type="checkbox"]').prop('checked', false);
                        //$('.custom-option-content').removeClass('active'); // Remove active from ALL custom contents
                        $('#lottery_dc, #lottery_va, #lottery_md').prop('checked', false); // Uncheck main radios


                        if (response.result) {
                            let initialSelectedLotteryClass = '';

                            // 1. Check Maryland conditions
                            if (response.result.maryland_online !== '' || response.result.maryland_scratch_card !== '') {
                                if (response.result.maryland_online !== '') {
                                    const $mdOnline = $('#maryland_lottery_online');
                                    $mdOnline.prop('checked', true);
                                    const $mdOnlineParent = $mdOnline.closest('.custom-option-content');
                                    $mdOnlineParent.addClass('active');
                                }
                                if (response.result.maryland_scratch_card !== '') {
                                    const $mdScratch = $('#maryland_lottery_scratch_card');
                                    $mdScratch.prop('checked', true);
                                    const $mdScratchParent = $mdScratch.closest('.custom-option-content');
                                    $mdScratchParent.addClass('active');
                                }
                                $('#lottery_md').prop('checked', true);
                                initialSelectedLotteryClass = 'md';
                            }
                            // 2. Check Virginia conditions
                            else if (response.result.virginia_online !== '' || response.result.virginia_scratch_card !== '') {
                                if (response.result.virginia_online !== '') {
                                    const $vaOnline = $('#virginia_lottery_online');
                                    $vaOnline.prop('checked', true);
                                    $vaOnline.closest('.custom-option-content').addClass('active');
                                }
                                if (response.result.virginia_scratch_card !== '') {
                                    const $vaScratch = $('#virginia_lottery_scratch_card');
                                    $vaScratch.prop('checked', true);
                                    $vaScratch.closest('.custom-option-content').addClass('active');
                                }
                                $('#lottery_va').prop('checked', true);
                                initialSelectedLotteryClass = 'va';
                            }
                            // 3. Check DC conditions
                            else if (
                                response.result.dc_lotto_online !== '' || response.result.pat_machine_1 !== '' ||
                                response.result.pat_machine_2 !== '' || response.result.pat_machine_3 !== '' ||
                                response.result.gaming_bet_machine_1 !== '' || response.result.gaming_bet_machine_2 !== '' ||
                                response.result.gaming_bet_machine_3 !== ''
                            ) {
                                if (response.result.dc_lotto_online !== '') {
                                    const $dcOnline = $('#dc_lotto_online_machine');
                                    $dcOnline.prop('checked', true);
                                    $dcOnline.closest('.custom-option-content').addClass('active');
                                }
                                // ... (add similar console logs for other DC machines)
                                if (response.result.pat_machine_1 !== '') {
                                    $('#pat_machine_1').prop('checked', true).closest('.custom-option-content').addClass('active');
                                }
                                if (response.result.pat_machine_2 !== '') {
                                    $('#pat_machine_2').prop('checked', true).closest('.custom-option-content').addClass('active');
                                }
                                if (response.result.pat_machine_3 !== '') {
                                    $('#pat_machine_3').prop('checked', true).closest('.custom-option-content').addClass('active');
                                }
                                if (response.result.gaming_bet_machine_1 !== '') {
                                    $('#gaming_bet_machine_1').prop('checked', true).closest('.custom-option-content').addClass('active');
                                }
                                if (response.result.gaming_bet_machine_2 !== '') {
                                    $('#gaming_bet_machine_2').prop('checked', true).closest('.custom-option-content').addClass('active');
                                }
                                if (response.result.gaming_bet_machine_3 !== '') {
                                    $('#gaming_bet_machine_3').prop('checked', true).closest('.custom-option-content').addClass('active');
                                }
                                $('#lottery_dc').prop('checked', true);
                                initialSelectedLotteryClass = 'dc';
                            }

                            // --- Call the display update function after setting checkboxes ---
                            updateLotteryDisplay(initialSelectedLotteryClass);

                        } else {
                            console.log("response.result is empty or null. Hiding all sections.");
                            updateLotteryDisplay('');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", status, error, xhr);
                        $('#lotteryMachineModal').modal('show');
                        updateLotteryDisplay('');
                    }
                });
            });

            $(document).on('click', '.restaurant-pos-machine-modal-event', function () {
                const stationId = $(this).data('station_id');
                const serviceId = $(this).data('service_id');

                // Set the values of the hidden input fields
                $('#modal_restaurant_pos_station_id').val(stationId);
                $('#modal_restaurant_pos_service_id').val(serviceId);

                $.ajax({
                    url: '{{ route("service.get.restaurant.pos.machine") }}', // Adjust route as needed
                    type: 'GET',
                    data: {
                        station_id: stationId,
                        service_id: serviceId
                    },
                    success: function (response) {
                        console.log('AJAX Response:', response);
                        $('#restaurantPosMachineModal').modal('show');
                        const posId = response.result.restaurant_pos_id;

                        $('#restaurantPosMachineModal .custom-option-content').removeClass('active');

                        if (response.result && posId) {

                            if (posId === '0017') {
                                $('#ncr_aloha').prop('checked', true);
                                $('#ncr_aloha').parent('label').addClass('active');
                            } else if (posId === '0018') {
                                $('#dc_super_mart').prop('checked', true);
                                $('#dc_super_mart').parent('label').addClass('active');
                            }

                        } else {
                            console.log('restaurant_pos_id not found in response.result');
                        }
                        // $('#fuelServiceModal').modal('show');
                    },
                    error: function () {
                        $('#restaurantPosMachineModal').modal('show');
                    }
                });

            });

            $(document).on('click', '.service-supplier-modal-event', function () {
                const stationId = $(this).data('station_id');
                const serviceId = $(this).data('service_id');

                // Set the values of the hidden input fields
                $('#modal_supplier_station_id').val(stationId);
                $('#modal_supplier_service_id').val(serviceId);

                // Perform a single AJAX request to fetch both supplier and commission data
                $.ajax({
                    url: '{{ route("service.getSupplierAndFuelCommission") }}', // Combined route
                    type: 'GET',
                    data: {
                        station_id: stationId,
                        service_id: serviceId
                    },
                    success: function (response) {
                        $('#fuelSupplierModal').modal('show');

                        // Handle the Supplier Data
                        if (response.result) {
                            $('#supplier_id').val(response.result.supplier_id).trigger('change');

                            // Check if it's a Dealer or Commission and select the appropriate radio button
                            if (response.result.is_dealer === 1) {
                                $('#customRadioDealer').prop('checked', true); // Select the dealer radio button
                                $('#commissionFields').addClass('d-none'); // Hide commission fields

                                // Add active class to Dealer
                                $('#customRadioDealer').closest('.form-check-label').addClass('active');
                                $('#customRadioCommission').closest('.form-check-label').removeClass('active');
                            } else if (response.result.is_commission === 1) {
                                $('#customRadioCommission').prop('checked', true); // Select the commission radio button
                                $('#commissionFields').removeClass('d-none'); // Show commission fields

                                // Add active class to Commission
                                $('#customRadioCommission').closest('.form-check-label').addClass('active');
                                $('#customRadioDealer').closest('.form-check-label').removeClass('active');
                            }
                        }

                        // Handle the Fuel Commission Data
                        if (response.commissions && response.commissions.length > 0) {
                            response.commissions.forEach(function (commission, index) {
                                if (index === 0) {
                                    if (commission.qty_unit === 'liter') {
                                        $('#qty_liter_unit').prop('checked', true);
                                    } else if (commission.qty_unit === 'gallon') {
                                        $('#qty_gallon_unit').prop('checked', true);
                                    }
                                    $('#effective_date_field_update').val(commission.effective_date);
                                    $('#qty_from_field_update_1').val(commission.qty_from);
                                    $('#qty_to_field_update_1').val(commission.qty_to);
                                    $('#fuel_commission_rate_field_update_1').val(commission.commission_rate);
                                } else if (index === 1) {
                                    if (commission.qty_unit === 'liter') {
                                        $('#qty_liter_unit').prop('checked', true);
                                    } else if (commission.qty_unit === 'gallon') {
                                        $('#qty_gallon_unit').prop('checked', true);
                                    }
                                    $('#qty_from_field_update_2').val(commission.qty_from);
                                    $('#qty_to_field_update_2').val(commission.qty_to);
                                    $('#fuel_commission_rate_field_update_2').val(commission.commission_rate);
                                }
                            });
                        } else {
                            Swal.fire('Info!', 'No commission data found.', 'info');
                        }
                    },
                    error: function () {
                        $('#fuelSupplierModal').modal('show');
                        // Swal.fire('Error!', 'Failed to fetch service data.', 'error');
                    }
                });
            });

            $(document).on('click', '.car-wash-modal-event', function () {
                const stationId = $(this).data('station_id');
                const serviceId = $(this).data('service_id');

                // Set the hidden input values
                $('#modal_carwash_station_id').val(stationId);
                $('#modal_carwash_service_id').val(serviceId);

                $.ajax({
                    url: '{{ route("station.service.get.carWash.Operator") }}',
                    type: 'GET',
                    data: {
                        station_id: stationId,
                        service_id: serviceId
                    },
                    success: function (response) {
                        console.log('AJAX Response:', response);
                        $('#carWashModal').modal('show');

                        // Wait until the modal is fully opened before setting the radio buttons
                        console.log(response.result);
                        if (response.result.car_wash_operator_id === '0039') {
                            $('#self_operated_type').prop('checked', true).change();
                            // Add active class to Dealer
                            $('#self_operated_class').closest('.form-check-label').addClass('active');
                            $('#company_operated_class').closest('.form-check-label').removeClass('active');
                            console.log('self operated type checked');
                        }
                        if (response.result.car_wash_operator_id === '0040') {
                            $('#company_operated_type').prop('checked', true).change();
                            $('#company_operated_class').closest('.form-check-label').addClass('active');
                            $('#self_operated_class').closest('.form-check-label').removeClass('active');
                            console.log('company operated type checked');
                        }
                    },
                    error: function () {
                        $('#carWashModal').modal('show');
                        // Swal.fire('Error!', 'Failed to fetch service data.', 'error');
                    }
                });
            });

        </script>

        <script>
            $(document).ready(function () {
                $('input[name="fuel_service_type"]').on('change', function () {
                    if ($(this).val() === '2') {
                        // If 'Is Commission' selected
                        $('#commissionFields').removeClass('d-none');
                    } else {
                        // If 'Is Dealer' selected
                        $('#commissionFields').addClass('d-none');
                    }
                });
            });

            // When the radio button changes, toggle the active class on the label
            document.querySelectorAll('input[name="restaurant_pos_machine_type"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    // Remove the active class from all labels
                    document.querySelectorAll('.custom-option-content').forEach(function (label) {
                        label.classList.remove('active');
                    });

                    // Add the active class to the label of the selected radio button
                    if (this.checked) {
                        this.closest('label').classList.add('active');
                    }
                });
            });

        </script>

    </x-slot>

</x-app-layout>
