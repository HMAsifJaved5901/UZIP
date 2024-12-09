<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\EmployeeService;
use App\Models\Service;
use App\Models\Station;
use App\Models\User;
use function Carbon\create;
use Faker\Calculator\Luhn;
use Faker\Extension\PhoneNumberExtension;
use Faker\Factory;
use Faker\Provider\en_NG\Address;
use Faker\Provider\PhoneNumber as TechPhone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use function Symfony\Component\String\lower;
use function Termwind\ValueObjects\lowercase;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function index(Request $request)
    {
        $companies = Company::select(['id', 'company_name'])->where('is_active', 1)->get();
        $stations = Station::select(['id', 'name'])->where('is_active', 1)->where('is_deleted', 0)->get();
        $services = Service::select(['id', 'name'])->where('is_active', 1)->where('is_deleted', 0)->get();
        $countries = Country::select(['id', 'name', 'code', 'placeholder'])->where('status', 1)->get();
        $role = Role::select(['id', 'name'])->where('status', 1)->get();
        return view('profile.index', [
            'user' => $request->user(),
            'companies' => $companies,
            'stations' => $stations,
            'services' => $services,
            'countries' => $countries,
            'roles' => $role
        ]);
    }

    public function getUserList(Request $request)
    {
        $users = User::join('roles', 'roles.id', '=', 'users.default_role_id')
            ->leftjoin('stations', 'stations.id', '=', 'users.station_id')
            ->select('users.*', 'roles.name as role_name', 'stations.name as station_name')->get();

        $formattedUsers = $users->map(function ($user) {
            $role = Role::select('name')->where('id', $user->default_role_id)->first()->name;
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'ssn' => $user->ssn,
                'role' => $user->role_name,
                'station' => $user->station_name,
                'status' => $user->status,
                'is_deleted' => $user->is_deleted,
                'avatar' => $user->avatar
            ];
        });

        // Return the data in the requested structure
        return response()->json(['data' => $formattedUsers]);
    }

    public function getUserById($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not found',
        ], 404);
    }

    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($request->user_id), // Ignore the current user when updating
            ],
            'company_id' => 'required|integer',
            'station_id' => 'integer',
            'country_id' => 'required|integer',
            'contact_no' => 'required|string|max:15',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'dob' => 'required|date',
            'ssn' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'default_role_id' => 'required|integer',
            'station_service_id' => 'array', // Ensure it's an array
            'station_service_id.*' => 'integer', // Each value in the array must be an integer
        ]);
        // Add password rule only if it's a new user or provided
        if (!$request->user_id || $request->filled('password')) {
            $validatedData['password'] = 'required|string|min:8';
        }

        $user = User::where('email', $validatedData['email'])->first();

        if ($user) {
            // Update existing record
            $updateData = [
                'name' => $validatedData['name'],
                'company_id' => !empty($validatedData['company_id']) ? $validatedData['company_id'] : null,
                'station_id' => !empty($validatedData['station_id']) ? $validatedData['station_id'] : null,
                'country_id' => !empty($validatedData['country_id']) ? $validatedData['country_id'] : null,
                'first_name' => !empty($validatedData['first_name']) ? $validatedData['first_name'] : null,
                'last_name' => !empty($validatedData['last_name']) ? $validatedData['last_name'] : null,
                'dob' => !empty($validatedData['dob']) ? $validatedData['dob'] : null,
                'ssn' => !empty($validatedData['ssn']) ? $validatedData['ssn'] : null,
                'address' => !empty($validatedData['address']) ? $validatedData['address'] : null,
                'contact_no' => $validatedData['contact_no'],
                'default_role_id' => $validatedData['default_role_id'],
                'status' => 1,
            ];

            // Only update the password if provided
            if (!empty($validatedData['password'])) {
                $updateData['password'] = bcrypt($validatedData['password']);
            }

            $user->update($updateData);

        } else {
            // Create new record
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'company_id' => !empty($validatedData['company_id']) ? $validatedData['company_id'] : null,
                'station_id' => !empty($validatedData['station_id']) ? $validatedData['station_id'] : null,
                'country_id' => !empty($validatedData['country_id']) ? $validatedData['country_id'] : null,
                'first_name' => !empty($validatedData['first_name']) ? $validatedData['first_name'] : null,
                'last_name' => !empty($validatedData['last_name']) ? $validatedData['last_name'] : null,
                'dob' => !empty($validatedData['dob']) ? $validatedData['dob'] : null,
                'ssn' => !empty($validatedData['ssn']) ? $validatedData['ssn'] : null,
                'address' => !empty($validatedData['address']) ? $validatedData['address'] : null,
                'contact_no' => $validatedData['contact_no'],
                'default_role_id' => $validatedData['default_role_id'],
                'status' => 1,
            ]);
        }

        return redirect()->route('profile.index')->with('success', 'User operation successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'], // Ensure the user exists
        ]);

        // Find the user to be deleted
        $user = User::findOrFail($request->user_id);

        // Check if the admin is trying to delete their own account
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 403);
        }

        // Perform soft delete by marking the user as deleted
        $message = $user->is_deleted == 1 ? 'restored' : 'deleted';
        $deleted = $user->is_deleted == 1 ? 0 : 1;

        $user->is_deleted = $deleted;
        $user->save();

        return response()->json(['message' => 'User '.$message.' successfully.', 200]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'], // Ensure the user exists
        ]);

        // Find the user to be operated
        $user = User::findOrFail($request->user_id);

        // Check if the admin is trying to update
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot update your own account.'], 403);
        }

        // Perform status call by marking the user as active/inactive
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return response()->json(['message' => 'User status updated successfully.', 200]);
    }

    //=====================================================================================================

    public function viewUser(Request $request)
    {
        $user = User::join('companies', 'companies.id', '=', 'users.company_id')
            ->join('stations', 'stations.id', '=', 'users.station_id')
            ->join('countries', 'countries.id', '=', 'users.country_id')
            ->join('roles', 'roles.id', '=', 'users.default_role_id')
            ->select('users.*', 'companies.company_name', 'stations.name as station_name',
                'countries.name as country_name', 'roles.name as role_name')
            ->where('users.id', $request->id)->first();

        $services = Service::leftJoin('employee_services', 'employee_services.service_id', '=', 'services.id')
            ->leftJoin('users', 'users.id', '=', 'employee_services.employee_id')
            ->where(function ($query) use ($request) {
                $query->where('users.id', $request->id)// Filter by specific user ID
                ->orWhereNull('employee_services.service_id'); // Include services that are not linked to the station
            })
            ->where('services.is_active', 1)// Only active services
            ->where('services.is_deleted', 0)// Exclude deleted services
            ->select('services.id', 'services.name', 'services.description','employee_services.wage_id',
                DB::raw('IF(employee_services.service_id IS NOT NULL, "1", "0") as checked'),
                DB::raw('IF(employee_services.wage_id > 0, "1", "0") as wage_checked')
            )
            ->get();

        $wages_rates = Configuration::where('is_deleted', 0)->get();
        return view('cards.profile', [
            'user' => $user,
            'services' => $services,
            'wages_rates' => $wages_rates
        ]);

    }

    public function profileServiceStore(Request $request)
    {
        // Handle station services
        $employeeService = EmployeeService::where('id', $request->user_id)
            ->where('service_id', $request->service_id)
            ->first();
        if (!empty($employeeService) && $employeeService != null) {
            $deleted = $employeeService->is_deleted == 1?0:1;
            $employeeService->is_deleted = $deleted;
            $employeeService->save();
            return response()->json(['message' => 'User Service updated successfully.', 200]);
        }else{
            $employeeServices[] = [
                'employee_id' => $request->user_id,
                'service_id' => $request->service_id,
            ];

            DB::table('employee_services')->insert($employeeServices);

            return response()->json(['message' => 'User Service Inserted successfully.', 200]);
        }

    }

    public function profileWageStore(Request $request)
    {
        // Handle station services
        $employeeService = EmployeeService::where('employee_id', $request->user_id)
            ->where('service_id', $request->service_id)
            ->first();
        if (!empty($employeeService) && $employeeService != null) {
            $employeeService->wage_id = $request->wage_id;
            $employeeService->save();
            return response()->json(['message' => 'User Wage assigned successfully.', 200]);
        }

    }
}
