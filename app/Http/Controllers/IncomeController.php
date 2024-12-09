<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LookupValue;
use App\Models\Service;
use App\Models\Station;
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

    public function incomeIndex(Request $request)
    {
        $incomeModel = Income::join('stations','stations.id','=','income.station_id')
            ->join('services','services.id','=','income.service_id')
            ->join('lookup_values','lookup_values.id','=','income.category_id')
            ->select('income.*','stations.name as station_name','services.name as service_name','lookup_values.value as category_name')
            ->where('lookup_values.type','income_category')
            ->get();
        $formattedModel = $incomeModel->map(function ($model) {
            return [
                'id' => $model->id,
                'income_date' => $model->income_date,
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

    public function getIncomeById($id)
    {
        $model = Income::where('id', $id)->first();
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
        $model = Income::join('stations','stations.id','=','income.station_id')
            ->join('services','services.id','=','income.service_id')
            ->join('lookup_values','lookup_values.id','=','income.category_id')
            ->select('income.*','stations.name as station_name','services.name as service_name','lookup_values.value as category_name')
            ->where('lookup_values.type','income_category')
            ->where('income.id',$request->id)
            ->first();

        return view('cards.income', [
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
            'income_date' => 'required|date',
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
            $filePath = $file->storeAs('income_files', $fileName, 'public');
        } else {
            // If no new file is uploaded, keep the existing file path (if any)
            $filePath = $request->input('existing_image_file'); // Assuming you have an 'existing_company_logo' field in your form for updates
        }

        // Get the data and filter out null values
        $data = array_filter($request->all(), function ($value) {
            return $value !== null;
        });
        // If there is an ID, we are updating, else we are creating a new record
        $model = $request->id ? Income::find($request->id) : new Income;
        if ($model) {
            $model->fill($data);
            if ($filePath) {
                $model->image_file = $filePath;
            }
            // Save the model and return the appropriate response
            if ($model->save()) {
                session()->flash('app_message', 'Income ' . ($request->id ? 'updated' : 'saved') . ' successfully');
                return redirect()->route('income.index');
            } else {
                session()->flash('app_message', 'Something went wrong while saving Income');
            }
        } else {
            session()->flash('app_message', 'Income not found');
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
            'income_id' => ['required', 'exists:income,id'], // Ensure the user exists
        ]);

        // Find the user to be deleted
        $model = Income::findOrFail($request->income_id);

        // Perform soft delete by marking the user as deleted
        $message = $model->is_deleted == 1 ? 'restored' : 'deleted';
        $deleted = $model->is_deleted == 1 ? 0 : 1;

        $model->is_deleted = $deleted;
        $model->save();

        return response()->json(['message' => 'User '.$message.' successfully.', 200]);
    }
}
