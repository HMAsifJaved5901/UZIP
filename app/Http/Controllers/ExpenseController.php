<?php

namespace App\Http\Controllers;

use App\Models\LookupValue;
use App\Models\Service;
use App\Models\Station;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Http\Requests\Expense\Index;
use App\Http\Requests\Expense\Show;
use App\Http\Requests\Expense\Create;
use App\Http\Requests\Expense\Store;
use App\Http\Requests\Expense\Edit;
use App\Http\Requests\Expense\Update;
use App\Http\Requests\Expense\Destroy;


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
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
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

    public function expenseIndex(Request $request)
    {
        $expenseModel = Expense::join('stations','stations.id','=','expense.station_id')
            ->join('services','services.id','=','expense.service_id')
            ->join('lookup_values','lookup_values.id','=','expense.category_id')
            ->select('expense.*','stations.name as station_name','services.name as service_name','lookup_values.value as category_name')
            ->where('lookup_values.type','expense_category')
            ->get();
        $formattedModel = $expenseModel->map(function ($model) {
            return [
                'id' => $model->id,
                'expense_date' => $model->expense_date,
                'station_name' => $model->station_name,
                'service_name' => $model->service_name,
                'category_name' => $model->category_name,
                'amount' => $model->amount,
                'description' => $model->description,
                'is_deleted' => $model->is_deleted
            ];
        });
        // Return the data in the requested structure
        return response()->json(['data' => $formattedModel]);
    }

    public function getExpenseById($id)
    {
        $model = Expense::where('id', $id)->first();
        return response()->json(['data' => $model]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $model = Expense::join('stations','stations.id','=','expense.station_id')
            ->join('services','services.id','=','expense.service_id')
            ->join('lookup_values','lookup_values.id','=','expense.category_id')
            ->select('expense.*','stations.name as station_name','services.name as service_name','lookup_values.value as category_name')
            ->where('lookup_values.type','expense_category')
            ->where('expense.id',$request->id)
            ->first();

        return view('cards.expense', [
            'record' => $model,
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
            'service_id' => 'integer',
            'category_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
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
                return redirect()->route('expense.index');
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

        return response()->json(['message' => 'User '.$message.' successfully.', 200]);
    }
}
