<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\Service;
use App\Models\ServiceCommission;
use App\Models\StationLotteryMachine;
use App\Models\StationService;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Http\Requests\Stations\Index;
use App\Http\Requests\Stations\Show;
use App\Http\Requests\Stations\Create;
use App\Http\Requests\Stations\Store;
use App\Http\Requests\Stations\Edit;
use App\Http\Requests\Stations\Update;
use App\Http\Requests\Stations\Destroy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Description of StationController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        $companies = Company::select(['id', 'company_name'])->where('is_active', 1)->get();
        $businessHours = config('general.business_hours');
        $posCategories = Category::select(['id', 'name'])->where('category_key', 'pos')->get();

        return view('pages.stations.index',
            [
                'companies' => $companies,
                'businessHours' => $businessHours,
                'posCategories' => $posCategories,
            ]);
    }

    public function dataTableList(Request $request)
    {
        $model = Station::join('companies', 'stations.company_id', '=', 'companies.id')
            ->select('stations.*', 'companies.company_name as company_name')
            ->get();
        $formattedModel = $model->map(function ($m) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'code' => $m->code,
                'company_name' => $m->company_name,
                'location' => $m->location,
                'phone' => $m->phone,
                'opening_hours' => $m->opening_hours,
                'is_active' => $m->is_active,
                'is_deleted' => $m->is_deleted,
                'created_at' => $m->created_at
            ];
        });
        // Return the data in the requested structure
        return response()->json(['data' => $formattedModel]);
    }

    public function getById($id)
    {
        $station = Station::where('id', $id)->first();
        return response()->json(['data' => $station]);
    }

    public function getManagerList(Request $request)
    {
        $companyId = $request->company_id;

        // Fetch users based on the company
        $managers = User::where('company_id', $companyId)->get();

        return response()->json($managers);
    }

    /**
     * Display the specified resource.
     *
     * @param  Show $request
     * @param  Station $station
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, Station $station)
    {
        return view('pages.stations.show', [
            'record' => $station,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code'       => 'required|unique:stations,code,' . $request->id,
            'phone'      => 'nullable|string'
        ]);

        $station = Station::updateOrCreate(
            ['id' => $request->id],
            $validatedData
        );

        if ($station) {
            $status = $request->id ? 'updated' : 'saved';
            return redirect()->route('station.service.index', ['station_id' => $station->id])
                ->with('app_message', "Station {$status} successfully");
        }

        return redirect()->back()->with('app_message', 'Something went wrong');
    }



    /**
     * Delete a  resource from  storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        $model = Station::findOrFail($request->station_id);
        $deleted = $model->is_deleted == 1 ? 0 : 1;
        $model->is_deleted = $deleted;

        if ($model->save()) {
            if ($deleted == 1) {
                return response()->json(['message' => 'Station deleted successfully.', 200]);
            } else {
                return response()->json(['message' => 'Station Restored deleted successfully.', 200]);
            }
        } else {
            return redirect()->back()->with('error', 'Error occurred while deleting Data!');
        }
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'station_id' => ['required', 'exists:stations,id'], // Ensure the user exists
        ]);

        $id = $request->station_id;

        // Find the user to be operated
        $model = Station::findOrFail($id);

        // Perform status call by marking the user as active/inactive
        $model->is_active = $model->is_active == 1 ? 0 : 1;
        $model->save();

        return response()->json(['message' => 'Station status updated successfully.', 200]);
    }

//    ==============================Station Service Functionality================================================

    public function stationServiceIndex($id)
    {
        $model = Station::join('companies', 'stations.company_id', '=', 'companies.id')
            ->join('categories', 'stations.category_id', '=', 'categories.id')
            ->select('stations.*', 'companies.company_name', 'categories.name as category_name')
            ->where('stations.id', $id)
            ->first();

        $services = Service::where('services.is_active', 1)// Only active services
        ->where('services.is_deleted', 0)// Exclude deleted services
        ->get();

        $suppliers = Supplier::where('is_active', 1)// Only active
        ->where('is_deleted', 0)// Exclude deleted
        ->get();

        $selections = [
            'convenienceStore' => 'Configure POS Machine',
            'convenienceStoreSupplier' => 'Configure Supplier',
            'restaurant' => 'Configure Restaurant POS Machine',
            'lottery' => 'Configure Lotto Machine',
            'carWash' => 'Configure Service Operator',
        ];

        // Map service codes to possible values
        $posMapping = [
            '0009' => 'Gilbarco POS Machine',
            '0010' => 'Rubi POS Machine',
        ];

        $restaurantMapping = [
            '0017' => 'NCR Aloha POS Machine',
            '0018' => 'DC Super Mart POS Machine',
        ];

        $carWashMapping = [
            '0039' => 'Self Operated',
            '0040' => 'Company Operated',
        ];


        $stationServices = StationService::with('service')
            ->where('station_id', $id)
            ->where('is_active', 1)
            ->get();

        foreach ($stationServices as $service) {
            switch ($service->service->scode) {
                case '0001': // Convenience Store
                    $selections['convenienceStore'] = $posMapping[$service->pos_id] ?? $selections['convenienceStore'];
                    $selections['convenienceStoreSupplier'] = $service->is_dealer ? 'Dealer Site' :
                        ($service->is_commission ? 'Commission Site' : $selections['convenienceStoreSupplier']);
                    break;

                case '0004': // Restaurant
                    $selections['restaurant'] = $restaurantMapping[$service->restaurant_pos_id] ?? $selections['restaurant'];
                    break;

                case '0005': // Lottery (handled separately below)
                    break;

                case '0006': // Car Wash
                    $selections['carWash'] = $carWashMapping[$service->car_wash_operator_id] ?? $selections['carWash'];
                    break;
            }
        }

        $stationLottery = StationLotteryMachine::where('station_id', $id)
            ->where('is_active', 1)
            ->first();

        if ($stationLottery) {
            if ($stationLottery->dc_lotto_online
                || $stationLottery->pat_machine_1
                || $stationLottery->pat_machine_2
                || $stationLottery->pat_machine_3
                || $stationLottery->gaming_bet_machine_1
                || $stationLottery->gaming_bet_machine_2
                || $stationLottery->gaming_bet_machine_3) {
                $selections['lottery'] = 'DC Lottery';
            }

            if ($stationLottery->virginia_scratch_card || $stationLottery->virginia_online) {
                $selections['lottery'] = 'Virginia Lottery';
            }

            if ($stationLottery->maryland_scratch_card || $stationLottery->maryland_online) {
                $selections['lottery'] = 'Maryland Lottery';
            }
        }

        return view('cards.station_service', [
            'station' => $model,
            'services' => $services,
            'suppliers' => $suppliers,
            'convenienceStoreSelection' => $selections['convenienceStore'],
            'convenienceStoreSupplierSelection' => $selections['convenienceStoreSupplier'],
            'restaurantSelection' => $selections['restaurant'],
            'lotterySelection' => $selections['lottery'],
            'carWashSelection' => $selections['carWash'],
        ]);
    }


    public function stationServiceStore(Request $request)
    {
        $model = StationService::where('station_id', $request->station_id)
            ->where('service_id', $request->service_id)
            ->first();
        if ($model) {
            $model->is_active = 1;
            $model->save();
        } else {
            $model = new StationService();
            $model->station_id = $request->station_id;
            $model->service_id = $request->service_id;
            $model->save();
        }

        session()->flash('app_message', 'Station Service saved successfully!');

        return redirect()->back();
    }

    public function stationServiceRemove(Request $request)
    {
        $model = StationService::where('station_id', $request->station_id)
            ->where('service_id', $request->service_id)
            ->first();
        if ($model) {
            $model->is_active = 0;
            $model->save();
            session()->flash('app_message', 'Station Service disabled successfully!');
        }

        return redirect()->back();
    }

    public function SupplierAddUpdate(Request $request)
    {
        // Combine validation rules for both updates
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
            'fuel_service_type' => 'required|in:1,2',
            'supplier_id' => 'nullable|string'
        ]);

        // Use a single database transaction for atomicity
        DB::beginTransaction();

        try {
            StationService::updateOrCreate(
                [
                    'station_id' => $validated['station_id'],
                    'service_id' => $validated['service_id'],
                ],
                [
                    'is_dealer' => $validated['fuel_service_type'] == 1,
                    'is_commission' => $validated['fuel_service_type'] == 2,
                    'supplier_id' => $validated['supplier_id']
                ]
            );

            DB::commit(); // Commit the transaction
            return response()->json(['message' => 'Supplier saved successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            // Log the exception for debugging
            Log::error($e);
            return response()->json(['message' => 'Error saving Supplier.'], 500); // Or a more specific error message
        }
    }

    public function FuelCommissionAddUpdate(Request $request)
    {
        // Combine validation rules for both updates
        $validated = $request->validate([
            'service_commissions_id' => 'nullable|numeric',
            'station_id' => 'required|numeric',
            'service_id' => 'required|numeric',
            'qty_unit' => 'required|string',
            'effective_date' => 'required|date',
            'commission_rate' => 'required|array',
            'commission_rate.*' => 'numeric|between:0,99.99',
            'qty_from' => 'required|array',
            'qty_from.*' => 'numeric',
            'qty_to' => 'required|array',
            'qty_to.*' => 'numeric',
        ]);
        // Use a single database transaction for atomicity
        DB::beginTransaction();

        try {

            // Delete existing ranges for the given effective date, station, and service
            ServiceCommission::where('station_id', $validated['station_id'])
                ->where('service_id', $validated['service_id'])
                ->where('effective_date', $validated['effective_date'])
                ->delete();

            // Insert new commission ranges as separate rows
            foreach ($validated['qty_from'] as $index => $qtyFrom) {
                ServiceCommission::create([
                    'station_id' => $validated['station_id'],
                    'service_id' => $validated['service_id'],
                    'qty_unit' => $validated['qty_unit'],
                    'effective_date' => $validated['effective_date'],
                    'qty_from' => $qtyFrom,
                    'qty_to' => $validated['qty_to'][$index],
                    'commission_rate' => $validated['commission_rate'][$index],
                ]);
            }

            DB::commit(); // Commit the transaction
            return response()->json(['message' => 'Fuel Commission saved successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            // Log the exception for debugging
            Log::error($e);

            return response()->json(['message' => 'Error saving Fuel Commission.', 'error' => $e], 500); // Or a more specific error message
        }
    }

    public function SupplierAndCommissionSave(Request $request)
    {
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
            'fuel_service_type' => 'required|in:1,2', // 1 = Dealer, 2 = Commission
            'supplier_id' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Always update StationService
            StationService::updateOrCreate(
                [
                    'station_id' => $validated['station_id'],
                    'service_id' => $validated['service_id'],
                ],
                [
                    'is_dealer' => $validated['fuel_service_type'] == 1,
                    'is_commission' => $validated['fuel_service_type'] == 2,
                    'supplier_id' => $validated['supplier_id'],
                ]
            );

            // If Commission is selected, validate additional commission fields
            if ($validated['fuel_service_type'] == 2) {
                $commissionValidated = $request->validate([
                    'qty_unit' => 'required|string|in:liter,gallon',
                    'effective_date' => 'required|date',
                    'commission_rate' => 'required|array',
                    'commission_rate.*' => 'numeric|between:0,99.99',
                    'qty_from' => 'required|array',
                    'qty_from.*' => 'numeric',
                    'qty_to' => 'required|array',
                    'qty_to.*' => 'numeric',
                ]);

                // Remove old commissions for same station, service, effective date
                ServiceCommission::where('station_id', $validated['station_id'])
                    ->where('service_id', $validated['service_id'])
                    ->where('effective_date', $commissionValidated['effective_date'])
                    ->delete();

                // Insert new commission rates
                foreach ($commissionValidated['qty_from'] as $index => $qtyFrom) {
                    ServiceCommission::create([
                        'station_id' => $validated['station_id'],
                        'service_id' => $validated['service_id'],
                        'qty_unit' => $commissionValidated['qty_unit'],
                        'effective_date' => $commissionValidated['effective_date'],
                        'qty_from' => $qtyFrom,
                        'qty_to' => $commissionValidated['qty_to'][$index],
                        'commission_rate' => $commissionValidated['commission_rate'][$index],
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Supplier and Commission saved successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => 'Error saving Supplier or Commission.', 'error' => $e->getMessage()], 500);
        }
    }

    public function StationPosMachineAddUpdate(Request $request)
    {
        // Combine validation rules for both updates
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
            'pos_machine_type' => 'required|string',
        ]);
        // Use a single database transaction for atomicity
        DB::beginTransaction();

        try {
            StationService::updateOrCreate(
                [
                    'station_id' => $validated['station_id'],
                    'service_id' => $validated['service_id'],
                ],
                [
                    'pos_id' => $validated['pos_machine_type']
                ]
            );

            DB::commit(); // Commit the transaction
            return response()->json(['message' => 'Station Pos Type saved successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            // Log the exception for debugging
            Log::error($e);
            return response()->json(['message' => 'Error saving Station Pos Type.'], 500); // Or a more specific error message
        }
    }

    public function carWashAddUpdate(Request $request)
    {
        // Combine validation rules for both updates
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
            'car_wash_operated_type' => 'required|string',
        ]);
        // Use a single database transaction for atomicity
        DB::beginTransaction();

        try {
            StationService::updateOrCreate(
                [
                    'station_id' => $validated['station_id'],
                    'service_id' => $validated['service_id'],
                ],
                [
                    'car_wash_operator_id' => $validated['car_wash_operated_type']
                ]
            );

            DB::commit(); // Commit the transaction
            return response()->json(['message' => 'Car-Wash Operated Type saved successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            // Log the exception for debugging
            Log::error($e);
            return response()->json(['message' => 'Error saving Car-Wash Operated Type.'], 500); // Or a more specific error message
        }
    }

    public function StationLottoMachineAddUpdate(Request $request)
    {
        // 1. Validate the incoming request data
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
            'lottery_type' => 'required|in:dc,va,md', // New validation for the radio button

            // DC Lottery fields (nullable because they depend on lottery_type)
            'dc_lotto_online' => 'nullable|numeric',
            'pat_machine_1' => 'nullable|numeric',
            'pat_machine_2' => 'nullable|numeric',
            'pat_machine_3' => 'nullable|numeric',
            'gaming_bet_machine_1' => 'nullable|numeric',
            'gaming_bet_machine_2' => 'nullable|numeric',
            'gaming_bet_machine_3' => 'nullable|numeric',

            // Virginia Lottery fields (nullable)
            'virginia_scratch_card' => 'nullable|numeric',
            'virginia_online' => 'nullable|numeric',

            // Maryland Lottery fields (nullable)
            'maryland_scratch_card' => 'nullable|numeric',
            'maryland_online' => 'nullable|numeric',
        ]);

        // 2. Prepare the data for update/create, initializing all potential fields to empty
        // This ensures fields not included in the active lottery_type are explicitly cleared.
        $updateData = [
            'dc_lotto_online' => '',
            'pat_machine_1' => '',
            'pat_machine_2' => '',
            'pat_machine_3' => '',
            'gaming_bet_machine_1' => '',
            'gaming_bet_machine_2' => '',
            'gaming_bet_machine_3' => '',
            'virginia_scratch_card' => '',
            'virginia_online' => '',
            'maryland_scratch_card' => '',
            'maryland_online' => '',
        ];

        // 3. Populate $updateData based on the selected lottery_type
        $lotteryType = $validated['lottery_type'];

        switch ($lotteryType) {
            case 'dc':
                $updateData['dc_lotto_online'] = $validated['dc_lotto_online'] ?? '';
                $updateData['pat_machine_1'] = $validated['pat_machine_1'] ?? '';
                $updateData['pat_machine_2'] = $validated['pat_machine_2'] ?? '';
                $updateData['pat_machine_3'] = $validated['pat_machine_3'] ?? '';
                $updateData['gaming_bet_machine_1'] = $validated['gaming_bet_machine_1'] ?? '';
                $updateData['gaming_bet_machine_2'] = $validated['gaming_bet_machine_2'] ?? '';
                $updateData['gaming_bet_machine_3'] = $validated['gaming_bet_machine_3'] ?? '';
                break;

            case 'va':
                $updateData['virginia_scratch_card'] = $validated['virginia_scratch_card'] ?? '';
                $updateData['virginia_online'] = $validated['virginia_online'] ?? '';
                break;

            case 'md':
                $updateData['maryland_scratch_card'] = $validated['maryland_scratch_card'] ?? '';
                $updateData['maryland_online'] = $validated['maryland_online'] ?? '';
                break;
        }

        // 4. Use a database transaction for atomicity
        DB::beginTransaction();

        try {
            StationLotteryMachine::updateOrCreate(
                [
                    'station_id' => $validated['station_id'],
                    'service_id' => $validated['service_id'],
                ],
                $updateData // Pass the dynamically populated data
            );

            DB::commit(); // Commit the transaction if successful
            return response()->json(['message' => 'Station Lottery Machine configuration saved successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error to maintain data integrity
            Log::error("Error saving Station Lottery Machine configuration: " . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Error saving Station Lottery Machine configuration.', 'error' => $e->getMessage()], 500);
        }
    }

    public function StationRestaurantMachineAddUpdate(Request $request)
    {
        // Combine validation rules for both updates
        $validated = $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
            'restaurant_pos_machine_type' => 'nullable|string',
        ]);

        // Use a single database transaction for atomicity
        DB::beginTransaction();

        try {
            StationService::updateOrCreate(
                [
                    'station_id' => $validated['station_id'],
                    'service_id' => $validated['service_id'],
                ],
                [
                    'restaurant_pos_id' => $validated['restaurant_pos_machine_type']
                ]
            );

            DB::commit(); // Commit the transaction
            return response()->json(['message' => 'Station Restaurant Pos Type saved successfully!'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            // Log the exception for debugging
            Log::error($e);
            return response()->json(['message' => 'Error saving Station Restaurant Pos Type.', 'error' => $e], 500); // Or a more specific error message
        }
    }

    public function getSupplierList(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $stationId = $request->station_id;
        $serviceId = $request->service_id;

        $stationService = StationService::select('station_services.*')
            ->where('station_id', $stationId)
            ->where('service_id', $serviceId)
            ->first();

        if (!$stationService) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        return response()->json(['message' => 'Data Found Successfully.', 'result' => $stationService], 200);
    }

    public function getPosMachineList(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $stationId = $request->station_id;
        $serviceId = $request->service_id;

        $stationService = StationService::select('id', 'station_id', 'service_id', 'pos_id')
            ->where('station_id', $stationId)
            ->where('service_id', $serviceId)
            ->first();

        if (!$stationService) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        return response()->json(['message' => 'Data Found Successfully.', 'result' => $stationService], 200);
    }

    public function getRestaurantPosMachineList(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $stationId = $request->station_id;
        $serviceId = $request->service_id;

        $stationService = StationService::select('id', 'station_id', 'service_id', 'restaurant_pos_id')
            ->where('station_id', $stationId)
            ->where('service_id', $serviceId)
            ->first();

        if (!$stationService) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        return response()->json(['message' => 'Data Found Successfully.', 'result' => $stationService], 200);
    }

    public function getLottoMachineList(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $stationId = $request->station_id;
        $serviceId = $request->service_id;

        $stationLottoModel = StationLotteryMachine::select('station_lottery_machines.*')
            ->where('station_id', $stationId)
            ->where('service_id', $serviceId)
            ->where('is_active', 1)
            ->first();

        if (!$stationLottoModel) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        return response()->json(['message' => 'Data Found Successfully.', 'result' => $stationLottoModel], 200);
    }

    public function getCarWashOperatorList(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $stationId = $request->station_id;
        $serviceId = $request->service_id;

        $stationLottoModel = StationService::select('station_services.car_wash_operator_id')
            ->where('station_id', $stationId)
            ->where('service_id', $serviceId)
            ->where('is_active', 1)
            ->first();

        if (!$stationLottoModel) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        return response()->json(['message' => 'Data Found Successfully.', 'result' => $stationLottoModel], 200);
    }

    public function getFuelCommission(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $stationId = $request->station_id;
        $serviceId = $request->service_id;

        $stationServices = ServiceCommission::select('service_commissions.*')
            ->where('station_id', $stationId)
            ->where('service_id', $serviceId)
            ->get();

        if ($stationServices->isEmpty()) {
            return response()->json(['message' => 'Commission not found.'], 404);
        }

        $commissions = $stationServices->map(function ($service) {
            return [
                'commission_id' => $service->id,
                'qty_from' => $service->qty_from,
                'qty_to' => $service->qty_to,
                'qty_unit' => $service->qty_unit,
                'commission_rate' => $service->commission_rate,
                'effective_date' => $service->effective_date,
            ];
        });

        return response()->json($commissions);
    }

    public function getSupplierWithFuelCommission(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $stationId = $request->station_id;
        $serviceId = $request->service_id;

        // Get the station service details (supplier data)
        $stationService = StationService::select('station_services.*')
            ->where('station_id', $stationId)
            ->where('service_id', $serviceId)
            ->first();

        if (!$stationService) {
            return response()->json(['message' => 'Service not found.'], 404);
        }

        // Get the service commission details
        $stationServices = ServiceCommission::select('service_commissions.*')
            ->where('station_id', $stationId)
            ->where('service_id', $serviceId)
            ->get();

        if ($stationServices->isEmpty()) {
            return response()->json(['message' => 'Commission not found.'], 404);
        }

        // Map commissions data for response
        $commissions = $stationServices->map(function ($service) {
            return [
                'commission_id' => $service->id,
                'qty_from' => $service->qty_from,
                'qty_to' => $service->qty_to,
                'qty_unit' => $service->qty_unit,
                'commission_rate' => $service->commission_rate,
                'effective_date' => $service->effective_date,
            ];
        });

        // Return both supplier and commission data
        return response()->json([
            'message' => 'Data Found Successfully.',
            'result' => $stationService, // Supplier data
            'commissions' => $commissions // Commission data
        ], 200);
    }

    public function siteCashFlow($station_id)
    {
        $station = Station::join('companies', 'stations.company_id', '=', 'companies.id')
            ->join('categories', 'stations.category_id', '=', 'categories.id')
            ->select('stations.*', 'companies.company_name', 'categories.name as category_name')
            ->where('stations.id', $station_id)
            ->first();

        $today = Carbon::today();
        $TodayCihs = DB::table('cih')->whereDate('cih_date', $today)->first();

        if ($TodayCihs) {
            $openingCih = [
                'main' => $TodayCihs->opening_cih,
                'coins' => $TodayCihs->opening_coins,
                'lotto_commission' => $TodayCihs->opening_lotto_commission,
                'total' => $TodayCihs->opening_cih + $TodayCihs->opening_coins + $TodayCihs->opening_lotto_commission,
            ];
        } else {
            // Handle the case when no record is found
            $openingCih = [
                'main' => 0,
                'coins' => 0,
                'lotto_commission' => 0,
                'total' => 0,
            ];
        }

        $coins = DB::table('station_cih')
            ->where('station_id', $station->id)
            ->whereDate('cih_date', $today)
            ->sum('coins');

        $lottoBetMachineSale = DB::table('gaming_lotto_betmachine_sale')->where('station_id', $station->id)->whereDate('sale_date', $today)->sum('cih_amount');
        $lottoOnlineSale = DB::table('main_lotto_online_sale')->where('station_id', $station->id)->whereDate('sale_date', $today)->sum('cih_amount');
        $lottoMachineSale = DB::table('pat_lotto_machine_sale')->where('station_id', $station->id)->whereDate('sale_date', $today)->sum('cih_amount');
        $RestaurantSale = DB::table('restaurant_sale')->where('station_id', $station->id)->whereDate('sale_date', $today)->sum('sale_amount');
        $cStoreSale = DB::table('income')->where('station_id', $station->id)->whereDate('income_date', $today)->sum('cash_sale');

        $lottoCommission = DB::table('main_lotto_online_sale')
                ->where('station_id', $station->id)
                ->whereDate('sale_date', $today)
                ->selectRaw('SUM(online_sale_commission + online_sale_volume + instant_sale_commission + instant_sale_volume) as total')
                ->value('total') ?? 0;

        $atm = 0;

        $cashInflow = [
            'c_store' => $cStoreSale,
            'atm' => $atm,
            'lotto' => $lottoBetMachineSale + $lottoOnlineSale + $lottoMachineSale,
            'lotto_commission' => $lottoCommission,
            'restaurant' => $RestaurantSale,
            'coins' => $coins,
            'total' => $cStoreSale + ($lottoBetMachineSale + $lottoOnlineSale + $lottoMachineSale) + $lottoCommission + $RestaurantSale + $coins + $atm,
        ];

        $categories = DB::table('lookup_values')
            ->where('type', 'expense_category')
            ->pluck('value', 'id');
        $expenses = DB::table('expense')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->whereDate('expense_date', $today)
            ->groupBy('category_id')
            ->pluck('total', 'category_id');
        $cashOutflow = [];
        $total = 0;

        foreach ($categories as $id => $category) {
            $amount = $expenses[$id] ?? 0;
            $cashOutflow[$category] = $amount;
            $total += $amount;
        }

        $cashOutflow['total'] = $total;

        $amountDeposited = DB::table('deposits')
            ->where('station_id', $station->id)
            ->whereDate('deposit_date', $today)
            ->sum('amount');

        if ($TodayCihs) {
            $closingCih = [
                'main' => $TodayCihs->opening_cih + $TodayCihs->main_cih,
                'coins' => $TodayCihs->opening_coins + $TodayCihs->coins,
                'lotto_commission' => $TodayCihs->opening_lotto_commission + $TodayCihs->lotto_commission,
                'total' => ($TodayCihs->main_cih + $TodayCihs->coins + $TodayCihs->lotto_commission) + ($TodayCihs->opening_cih + $TodayCihs->opening_coins + $TodayCihs->opening_lotto_commission),
            ];
        } else {
            // Handle the case when no record is found
            $closingCih = [
                'main' => 0,
                'coins' => 0,
                'lotto_commission' => 0,
                'total' => 0,
            ];
        }

        return view('pages.stations.cash_flow', compact('openingCih', 'cashInflow', 'cashOutflow', 'amountDeposited', 'closingCih', 'station'));
    }

}
