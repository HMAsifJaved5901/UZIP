<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Business;
use App\Http\Requests\Companies\Index;
use App\Http\Requests\Companies\Show;
use App\Http\Requests\Companies\Create;
use App\Http\Requests\Companies\Store;
use App\Http\Requests\Companies\Edit;
use App\Http\Requests\Companies\Update;
use App\Http\Requests\Companies\Destroy;
use Illuminate\Support\Facades\DB;


/**
 * Description of CompanyController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        return view('pages.companies.index', [
            'companies' => Company::where('id',1)->get()
        ]);
    }

    public function dataTableList(Request $request)
    {
        $company_model = DB::table('companies as c1')
            ->select(
                'c1.id',
                DB::raw('(SELECT cr.name FROM businesses cr WHERE cr.id = c1.business_id) as business'),
                'c1.company_name',
                'c1.company_code',
                'c1.company_address',
                'c1.is_active',
                'c1.is_deleted'
            )
            ->get();
        $formattedCompany = $company_model->map(function ($company) {
            return [
                'id' => $company->id,
                'business' => $company->business,
                'company_name' => $company->company_name,
                'company_code' => $company->company_code,   // inner codes for multiple companies under single business
                'company_address' => $company->company_address,
                'is_active' => $company->is_active,
                'is_deleted' => $company->is_deleted
            ];
        });
        // Return the data in the requested structure
        return response()->json(['data' => $formattedCompany]);
    }

    public function getById($id)
    {
        $company = Company::where('id', $id)->first();
        return response()->json(['data' => $company]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if there is a file uploaded
        if ($request->hasFile('company_logo')) {
            // Retrieve the file
            $file = $request->file('company_logo');

            // Generate a unique file name
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

            // Store the file in the 'uploads' directory (public storage)
            $filePath = $file->storeAs('company_logo', $fileName, 'public');
        } else {
            // If no new file is uploaded, keep the existing file path (if any)
            $filePath = $request->input('existing_company_logo'); // Assuming you have an 'existing_company_logo' field in your form for updates
        }

        $model = Company::find($request->id) ?? new Company; // Find the company by ID for updates, or create a new one for inserts
        $model->fill($request->all());

        if ($filePath) {
            $model->company_logo = $filePath;
        }

        if ($model->save()) {
            session()->flash('app_message', 'Company saved successfully');
            return redirect()->back()->with('success', 'Company ' . ($request->id ? 'updated' : 'saved') . ' successfully');
        } else {
            session()->flash('app_message', 'Something went wrong while saving the Company');
            return redirect()->back()->with('error', 'Failed to save Company!');
        }

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
        $company = Company::findOrFail($request->company_id);
        $deleted = $company->is_deleted == 1 ? 0 : 1;
        $company->is_deleted = $deleted;

        if ($company->save()) {
            if ($deleted == 1) {
                return response()->json(['message' => 'Company deleted successfully.', 200]);
            } else {
                return response()->json(['message' => 'Company Restored deleted successfully.', 200]);
            }
        } else {
            return redirect()->back()->with('error', 'Error occurred while deleting Data!');
        }
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'company_id' => ['required', 'exists:companies,id'], // Ensure the user exists
        ]);

        $id = $request->company_id;

        // Find the user to be operated
        $company = Company::findOrFail($id);

        // Perform status call by marking the user as active/inactive
        $company->is_active = $company->is_active == 1 ? 0 : 1;
        $company->save();

        return response()->json(['message' => 'Company status updated successfully.', 200]);
    }
}
