<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LookupValue;
use App\Http\Requests\LookupValues\Index;
use App\Http\Requests\LookupValues\Show;
use App\Http\Requests\LookupValues\Create;
use App\Http\Requests\LookupValues\Store;
use App\Http\Requests\LookupValues\Edit;
use App\Http\Requests\LookupValues\Update;
use App\Http\Requests\LookupValues\Destroy;


/**
 * Description of LookupValueController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class LookupValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        return view('pages.lookup_values.index',
            [
                'records' => LookupValue::paginate(10)
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function transactionIndex(Index $request)
    {
        $desired_type = 'transaction_category';
        return view('pages.lookup_values.transaction_index',
            [
                'records' => LookupValue::where('type', $desired_type)->get()
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function incomeIndex(Index $request)
    {
        $desired_type = 'income_category';
        return view('pages.lookup_values.income_index',
            [
                LookupValue::where('type', $desired_type)->get()
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function expenseIndex(Index $request)
    {
        $desired_type = 'expense_category';
        return view('pages.lookup_values.expense_index',
            [
                LookupValue::where('type', $desired_type)->get()
            ]
        );
    }

    public function getLookupList(Request $request)
    {
        $lookup_model = LookupValue::where('type',$request->type)->get();
        $formattedRoles = $lookup_model->map(function ($lookup) {
            return [
                'id' => $lookup->id,
                'type' => $lookup->type,
                'value' => $lookup->value,
                'description' => $lookup->description,
                'is_deleted' => $lookup->is_deleted
            ];
        });
        // Return the data in the requested structure
        return response()->json(['data' => $formattedRoles]);
    }

    public function getLookupById($id)
    {
        $lookup = LookupValue::where('id',$id)->first();
        return response()->json(['data' => $lookup]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Store $request
     * @return \Illuminate\Http\Response
     */
    public function save(Store $request)
    {
        // Check if the request contains an ID for updating
        $model = $request->id ? LookupValue::find($request->id) : new LookupValue;

        if (!$model) {
            return redirect()->back()->with('error', 'LookupValue not found!');
        }

        // Fill model with request data
        $model->fill($request->only(['type', 'value', 'description']));

        // Save model and provide appropriate feedback
        if ($model->save()) {
            $message = $request->id
                ? ucwords(str_replace('_', ' ', $request->type)) . ' updated successfully!'
                : ucwords(str_replace('_', ' ', $request->type)) . ' saved successfully!';

            session()->flash('app_message', $message);
            return redirect()->back()->with('success', $message);
            // Optionally, redirect to the listing page instead
            // return redirect()->route('lookup_values.index')->with('success', $message);
        } else {
            session()->flash('app_message', 'Something went wrong while saving LookupValue');
            return redirect()->back()->with('error', 'Failed to save LookupValue!');
        }
    }


    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy $request
     * @param  LookupValue $lookupvalue
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $lookupvalue = LookupValue::findOrFail($id);
        $deleted = $lookupvalue->is_deleted == 1 ? 0 : 1;
        $lookupvalue->is_deleted = $deleted;

        if ($lookupvalue->save()) {
            if($deleted == 1){
                return response()->json(['message' => 'Data deleted successfully.', 200]);
            }else{
                return response()->json(['message' => 'Data Restored deleted successfully.', 200]);
            }
        } else {
            return redirect()->back()->with('error', 'Error occurred while deleting Data!');
        }
    }
}
