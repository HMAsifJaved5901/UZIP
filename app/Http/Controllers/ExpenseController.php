<?php

namespace App\Http\Controllers;

use App\Models\ExpenseImage;
use App\Models\LookupValue;
use App\Models\RejectedTransactionStats;
use App\Models\Service;
use App\Models\Station;
use App\Models\TransactionAdjustment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;


/**
 * Description of ExpenseController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = LookupValue::select(['id', 'value'])->where('type', 'expense_category')->get();
        $stations = Station::select(['id', 'name'])->where('is_deleted', 0)->where('is_active', 1)->get();
        $services = Service::select(['id', 'name'])->where('is_deleted', 0)->where('is_active', 1)->get();
        return view('pages.expense.index',
            [
                'categories' => $categories,
                'stations' => $stations,
                'services' => $services,
            ]);
    }

    public function dataTableList(Request $request)
    {
        $query = Expense::leftJoin('stations', 'stations.id', '=', 'expense.station_id')
            ->leftJoin('services', 'services.id', '=', 'expense.service_id')
            ->leftJoin('lookup_values', 'lookup_values.id', '=', 'expense.category_id')
            ->select(
                'expense.*',
                DB::raw("IFNULL(stations.name, 'N/A') as station_name"),
                DB::raw("IFNULL(services.name, 'N/A') as service_name"),
                DB::raw("IFNULL(lookup_values.value, 'N/A') as category_name")
            )
            ->where(function ($query) {
                $query->where('lookup_values.type', 'expense_category')
                    ->orWhereNull('lookup_values.type');
            })
            ->get();
        $formattedModel = $query->map(function ($model) {
            return [
                'id' => $model->id,
                'expense_date' => $model->expense_date ? Carbon::parse($model->expense_date)->format('F jS, Y') : 'NA',
                'station_name' => $model->station_name,
                'service_name' => $model->service_name,
                'category_name' => $model->category_name,
                'amount' => $model->amount,
                'description' => $model->description,
                'status' => $model->status,
                'is_deleted' => $model->is_deleted
            ];
        });
        // Return the data in the requested structure
        return response()->json(['data' => $formattedModel]);
    }

    public function getById($id)
    {
        $model = Expense::where('id', $id)->first();
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
        $model = Expense::leftJoin('stations', 'stations.id', '=', 'expense.station_id')
            ->leftJoin('services', 'services.id', '=', 'expense.service_id')
            ->leftJoin('lookup_values', 'lookup_values.id', '=', 'expense.category_id')
            ->select(
                'expense.*',
                DB::raw("IFNULL(stations.name, 'N/A') as station_name"),
                DB::raw("IFNULL(services.name, 'N/A') as service_name"),
                DB::raw("IFNULL(lookup_values.value, 'N/A') as category_name")
            )
            ->where(function ($model) {
                $model->where('lookup_values.type', 'expense_category')
                    ->orWhereNull('lookup_values.type');
            })
            ->where('expense.id', $id)
            ->first();

        if (!$model) {
            return back()->with('error', 'Record not found.');
        }

        $adjustmentAmount = 0;
        if ($model->transaction_adjustment_id) {
            $adjustment = TransactionAdjustment::find($model->transaction_adjustment_id);
            $adjustmentAmount = $adjustment ? $adjustment->delta_amount : 0;
        }

        $imagesModel = ExpenseImage::where('expense_id',$id)->where('deleted',0)->get();

        return view('cards.expense', [
            'record' => $model,
            'images' => $imagesModel,
            'adjustmentAmount' => $adjustmentAmount
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
        // Validate the unique code
        $request->validate([
            'expense_date' => 'required|date',
            'station_id' => 'required|integer',
            'service_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'amount' => 'required|numeric|min:0',
            'exp_si_unit' => 'nullable|string',
            'exp_quantity' => 'nullable|string',
            'cheque_no' => 'nullable|string',
        ]);

        // Check if there is a file uploaded
        if ($request->hasFile('image_file')) {
            // Retrieve the file
            $file = $request->file('image_file');

            // Generate a unique file name
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            // Store the file in the 'uploads' directory (public storage)
            $filePath = $file->storeAs('expense_files', $fileName, 'public');
        } else {
            // If no new file is uploaded, keep the existing file path (if any)
            $filePath = $request->input('existing_image_file'); // Assuming you have an 'existing_company_logo' field in your form for updates
        }


        // Get the data and filter out null values
        $data = array_filter($request->all(), function ($value) {
            return $value !== null;
        });

        // If there is an ID, we are updating, else we are creating a new record
        $model = $request->id ? Expense::find($request->id) : new Expense;

        if ($model) {
            $model->fill($data);

            if ($filePath) {
                $model->image_file = $filePath;
            }
            // Save the model and return the appropriate response
            if ($model->save()) {
                session()->flash('app_message', 'Expense ' . ($request->id ? 'updated' : 'saved') . ' successfully');
            } else {
                session()->flash('app_message', 'Something went wrong while saving Expense');
            }
        } else {
            session()->flash('app_message', 'Expense not found');
        }

        return redirect()->back();
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
        $request->validate([
            'expense_id' => ['required', 'exists:expense,id'], // Ensure the user exists
        ]);

        // Find the user to be deleted
        $model = Expense::findOrFail($request->expense_id);

        // Perform soft delete by marking the user as deleted
        $message = $model->is_deleted == 1 ? 'restored' : 'deleted';
        $deleted = $model->is_deleted == 1 ? 0 : 1;

        $model->is_deleted = $deleted;
        $model->save();

        return response()->json(['message' => 'Expense '.$message.' successfully.', 200]);
    }

    public function approve($id)
    {
        $sale = Expense::findOrFail($id);
        $sale->status = 1;
        $sale->save();

        if($sale->cih_source == 'cash_register'){
            $route = 'web.station.cih.register.view';
        }else{
            $route = 'web.station.cih.view';
        }

        if(isset($sale->cash_register_id) && $sale->cash_register_id!=null){
            return redirect()->route($route, ['id' => $sale->cash_register_id])
                ->with('success', 'Transaction approved successfully.');
        }else{
            return back()->with('success', 'Transaction approved successfully.');
        }
    }

    public function pending($id)
    {
        $sale = Expense::findOrFail($id);
        $sale->status = 0;
        $sale->save();

        if($sale->cih_source == 'cash_register'){
            $route = 'web.station.cih.register.view';
        }else{
            $route = 'web.station.cih.view';
        }

        if(isset($sale->cash_register_id) && $sale->cash_register_id!=null){
            return redirect()->route($route, ['id' => $sale->cash_register_id])
                ->with('success', 'Transaction reversed successfully.');
        }else{
            return back()->with('success', 'Transaction reversed successfully.');
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $sale = Expense::findOrFail($id);

            DB::transaction(function () use ($id, $request, $sale) {
                $sale->status = 2;
                $sale->rejected_reason = $request->rejected_reason;
                $sale->save();

                RejectedTransactionStats::recordRejection(
                    $sale->station_id,
                    'Expense',
                    now()->format('Y-m'),
                    '8888'
                );
            });

            if($sale->cih_source == 'cash_register'){
                $route = 'web.station.cih.register.view';
            }else{
                $route = 'web.station.cih.view';
            }

            if(isset($sale->cash_register_id) && $sale->cash_register_id!=null){
                return redirect()->route($route, ['id' => $sale->cash_register_id])
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
