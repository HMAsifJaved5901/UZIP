<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\IncomeImage;
use App\Models\LookupValue;
use App\Models\RejectedTransactionStats;
use App\Models\Service;
use App\Models\Station;
use App\Models\TransactionAdjustment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Http\Requests\Income\Index;
use App\Http\Requests\Income\Show;
use App\Http\Requests\Income\Create;
use App\Http\Requests\Income\Store;
use App\Http\Requests\Income\Edit;
use App\Http\Requests\Income\Update;
use App\Http\Requests\Income\Destroy;
use function Illuminate\Support\data;
use Illuminate\Support\Facades\DB;


/**
 * Description of IncomeController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        $categories = LookupValue::select(['id', 'value'])->where('type', 'income_category')->get();
        $stations = Station::select(['id', 'name'])->where('is_deleted', 0)->where('is_active', 1)->get();
        $services = Service::select(['id', 'name'])->where('is_deleted', 0)->where('is_active', 1)->get();
        return view('pages.income.index',
            [
                'categories' => $categories,
                'stations' => $stations,
                'services' => $services,
            ]);
    }

    public function dataTableList(Request $request)
    {
        $incomeModel = Income::leftJoin('stations', 'stations.id', '=', 'income.station_id')
            ->leftJoin('services', 'services.id', '=', 'income.service_id')
            ->leftJoin('lookup_values', 'lookup_values.id', '=', 'income.category_id')
            ->select(
                'income.*',
                DB::raw("COALESCE(stations.name, 'NA') as station_name"),
                DB::raw("COALESCE(services.name, 'NA') as service_name"),
                DB::raw("COALESCE(lookup_values.value, 'NA') as category_name")
            )
            ->where(function ($query) {
                $query->where('lookup_values.type', 'income_category')
                    ->orWhereNull('lookup_values.type');
            })
            ->get();
        $formattedModel = $incomeModel->map(function ($model) {
            return [
                'id' => $model->id,
                'income_date' => $model->income_date ? Carbon::parse($model->income_date)->format('F jS, Y') : 'NA',
                'station_name' => $model->station_name,
                'total_fuel_sale' => '$'.$model->total_fuel_sale,
                'total_other_sale' => '$'.$model->total_other_sale,
                'net_tax' => '$'.$model->net_tax,
                'net_sale' => '$'.$model->net_sale,
                'is_deleted' => $model->is_deleted,
                'status' => $model->status
            ];
        });
        // Return the data in the requested structure
        return response()->json(['data' => $formattedModel]);
    }

    public function getServicesList($stationId)
    {
        $services = Service::select('id', 'name')
            ->whereHas('stations', function ($query) use ($stationId) {
                $query->where('station_id', $stationId);
            })
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->get();

        return response()->json($services);
    }

    public function getCategoryList($category, $serviceId)
    {
        $categories = LookupValue::select('id', 'value')
            ->where('type', $category)
            ->where('reference_type', 'service')
            ->where('reference_value', $serviceId)
            ->get();

        return response()->json($categories);
    }

    public function getById($id)
    {
        $model = Income::where('id', $id)->first();
        return response()->json(['data' => $model]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Income::join('stations', 'stations.id', '=', 'income.station_id')
            ->join('services', 'services.id', '=', 'income.service_id')
            ->leftJoin('lookup_values', 'lookup_values.id', '=', 'income.category_id')
            ->select(
                'income.*',
                DB::raw("COALESCE(stations.name, 'NA') as station_name"),
                DB::raw("COALESCE(services.name, 'NA') as service_name"),
                DB::raw("COALESCE(lookup_values.value, 'NA') as category_name")
            )
            ->where(function ($query) {
                $query->where('lookup_values.type', 'income_category')
                    ->orWhereNull('lookup_values.type');
            })
            ->where('income.id', $id)
            ->first();

        if (!$model) {
            return back()->with('error', 'Record not found.');
        }

        $adjustmentAmount = 0;
        if ($model->transaction_adjustment_id) {
            $adjustment = TransactionAdjustment::find($model->transaction_adjustment_id);
            $adjustmentAmount = $adjustment ? $adjustment->delta_amount : 0;
        }

        $imagesModel = IncomeImage::where('income_id', $id)->where('deleted', 0)->get();

        return view('cards.income', [
            'record' => $model,
            'images' => $imagesModel,
            'adjustmentAmount' => $adjustmentAmount
        ]);

    }

//    public function store(Request $request)
//    {
//        // Validate the unique code
//        $request->validate([
//            'income_date' => 'required|date',
//            'station_id' => 'required|integer',
//            'service_id' => 'required|integer',
//        ]);
//
//        // Check if there is a file uploaded
//        if ($request->hasFile('image_file')) {
//            // Retrieve the file
//            $file = $request->file('image_file');
//
//            // Generate a unique file name
//            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
//
//            // Store the file in the 'uploads' directory (public storage)
//            $filePath = $file->storeAs('income_files', $fileName, 'public');
//        } else {
//            // If no new file is uploaded, keep the existing file path (if any)
//            $filePath = $request->input('existing_image_file'); // Assuming you have an 'existing_company_logo' field in your form for updates
//        }
//
//        // Get the data and filter out null values
//        $data = array_filter($request->all(), function ($value) {
//            return $value !== null;
//        });
//        // If there is an ID, we are updating, else we are creating a new record
//        $model = $request->id ? Income::find($request->id) : new Income;
//        if ($model) {
//            $model->fill($data);
//            if ($filePath) {
//                $model->image_file = $filePath;
//            }
//            // Save the model and return the appropriate response
//            if ($model->save()) {
//                session()->flash('app_message', 'Income ' . ($request->id ? 'updated' : 'saved') . ' successfully');
//                return redirect()->route('income.index');
//            } else {
//                session()->flash('app_message', 'Something went wrong while saving Income');
//            }
//        } else {
//            session()->flash('app_message', 'Income not found');
//        }
//
//        return redirect()->back();
//    }

    /**
     * Delete a  resource from  storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'income_id' => ['required', 'exists:income,id'], // Ensure the user exists
        ]);

        // Find the user to be deleted
        $model = Income::findOrFail($request->income_id);

        // Perform soft delete by marking the user as deleted
        $message = $model->is_deleted == 1 ? 'restored' : 'deleted';
        $deleted = $model->is_deleted == 1 ? 0 : 1;

        $model->is_deleted = $deleted;
        $model->save();

        return response()->json(['message' => 'Income ' . $message . ' successfully.', 200]);
    }

    public function approve($id)
    {
        $sale = Income::findOrFail($id);
        $sale->status = 1;
        $sale->save();

        if(isset($sale->cash_register_id) && $sale->cash_register_id!=null){
            return redirect()->route('web.station.cih.register.view', ['id' => $sale->cash_register_id])
                ->with('success', 'Transaction approved successfully.');
        }else{
            return back()->with('success', 'Transaction approved successfully.');
        }
    }

    public function pending($id)
    {
        $sale = Income::findOrFail($id);
        $sale->status = 0;
        $sale->save();

        if(isset($sale->cash_register_id) && $sale->cash_register_id!=null){
            return redirect()->route('web.station.cih.register.view', ['id' => $sale->cash_register_id])
                ->with('success', 'Transaction reversed successfully.');
        }else{
            return back()->with('success', 'Transaction reversed successfully.');
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $sale = Income::findOrFail($id);

            DB::transaction(function () use ($id, $request, $sale) {
                $sale->status = 2;
                $sale->rejected_reason = $request->rejected_reason;
                $sale->save();

                RejectedTransactionStats::recordRejection(
                    $sale->station_id,
                    'CStore',
                    now()->format('Y-m'),
                    '0001',
                    $sale->category_id
                );
            });

            if(isset($sale->cash_register_id) && $sale->cash_register_id!=null){
                return redirect()->route('web.station.cih.register.view', ['id' => $sale->cash_register_id])
                    ->with('success', 'Transaction rejected successfully.');
            }else{
                return back()->with('success', 'Transaction rejected successfully.');
            }

        } catch (\Exception $e) {
            // If anything fails inside the closure, the transaction rolls back automatically
            return back()->with('error', 'Failed to reject transaction: ' . $e->getMessage());
        }
    }
}
