<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\EmployeePayrollMethod;
use App\Models\EmployeeService;
use App\Models\EmployeeShift;
use App\Models\Service;
use App\Models\Station;
use App\Models\User;
use App\Models\UserPayrollMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public static function generateReadableUniqueCode($length = 8)
    {
        // Define the pool of characters to use for the code
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        $maxAttempts = 100; // Maximum attempts to find a unique code to prevent infinite loops

        // Loop to generate and check for uniqueness
        for ($i = 0; $i < $maxAttempts; $i++) {
            $code = ''; // Reset code for each attempt
            // Generate a random string of the specified length
            for ($j = 0; $j < $length; $j++) {
                // Pick a random character from the pool
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Check if the generated code already exists in the 'ssn' column of the 'users' table
            // If it does NOT exist, then it's unique, and we can return it.
            if (!User::where('ssn', $code)->exists()) {
                return $code; // Found a unique code, return it
            }
        }
    }

    public function index(Request $request)
    {
        $companies = Company::select(['id', 'company_name'])->where('is_active', 1)->get();
        $stations = Station::select(['id', 'name'])->where('is_active', 1)->where('is_deleted', 0)->get();
        $services = Service::select(['id', 'name'])->where('is_active', 1)->where('is_deleted', 0)->get();
        $countries = Country::select(['id', 'name', 'code', 'placeholder'])->where('status', 1)->get();
        $role = Role::select(['id', 'name', 'r_code'])->where('status', 1)->get();
        $wages = Configuration::where('config_key', 'wages')->where('is_deleted', 0)
            ->select('id', 'label', 'value', 'value_unit')->get();
        $user = $request->user()->load(['payrollMethods.method']);

        $payrollTypes = Category::where('category_key', 'payroll_type')->get();
        $payrollMethods = Category::where('category_key', 'payroll_method')->get();

        return view('profile.index', [
            'user' => $user,
            'companies' => $companies,
            'stations' => $stations,
            'services' => $services,
            'countries' => $countries,
            'roles' => $role,
            'wages' => $wages,
            'payrollTypes' => $payrollTypes,
            'payrollMethods' => $payrollMethods,
            'existingPayrollMethods' => $user->payrollMethods
        ]);
    }

    public function PayRollTypeByRoll($code)
    {
        if ($code == '0003') {
            $payrollTypes = Category::where('category_key', 'payroll_type')->where('ccode', '0043')->select('id', 'name', 'ccode')->get();
        } else {
            $payrollTypes = Category::where('category_key', 'payroll_type')->whereIn('ccode', ['0041', '0042'])->select('id', 'name', 'ccode')->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully!',
            'data' => $payrollTypes
        ], 200);
    }

    public function dataTableList(Request $request)
    {
        $users = User::leftjoin('companies', 'companies.id', '=', 'users.company_id')
            ->leftjoin('roles', 'roles.id', '=', 'users.default_role_id')
            ->leftjoin('stations', 'stations.id', '=', 'users.station_id')
            ->select('users.*', 'roles.name as role_name', 'roles.r_code as role_code', 'stations.name as station_name', 'companies.company_name')
            ->where('users.status', 1)
            ->where('users.is_deleted', 0)
            ->get();

        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'ssn' => $user->ssn,
                'company' => $user->company_name,
                'role' => $user->role_name,
                'station' => $user->employeeServices->pluck('station.name')->toArray(),
                'status' => $user->status,
                'is_deleted' => $user->is_deleted,
                'avatar' => $user->avatar,
                'wage' => ($user->role_code == '003') ? $user->commission_rate . ' %' : $user->wage_rate . ' $'
            ];
        });

        // Return the data in the requested structure
        return response()->json(['data' => $formattedUsers]);
    }

    public function getById($id)
    {
        $user = DB::table('users')
            ->join('roles', 'users.default_role_id', '=', 'roles.id')
            ->where('users.id', $id)
            ->select('users.*', 'roles.r_code as role_code', 'roles.name as role_name')
            ->first();

        if ($user) {
            $payrollMethods = [];
            $methodsWithPayrollType = [];
            // Load employee services and stations using Eloquent
            $userModel = User::with(['employeeServices.station', 'payrollMethods'])// add relation if exists
            ->find($id);
            $stations = $userModel->employeeServices->pluck('station.name')->toArray();

            $payrollTypeId = optional($userModel->payrollMethods->first())->payroll_type_id;
            if (isset($payrollTypeId) && $payrollTypeId != null) {
                $payrollMethods = $userModel->payrollMethods->map(function ($method) {
                    return [
                        'method_id' => $method->method_id,
                        'rate' => $method->rate,
                    ];
                });
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'stations' => $stations,
                    'payrollTypeId' => $payrollTypeId ? $payrollTypeId : 0,
                    'selectedPayrollMethods' => $payrollMethods
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'User not found',
        ], 404);
    }

    public function saveOld(Request $request)
    {
        $rules = [
            'login_web' => 'sometimes|in:1',
            'login_mobile' => 'sometimes|in:1',

            'email' => [
                Rule::requiredIf($request->login_web == 1 || $request->login_mobile == 1),
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($request->user_id),
            ],

            'password' => [
                Rule::requiredIf(function () use ($request) {
                    return ($request->login_web == 1 || $request->login_mobile == 1) && empty($request->user_id);
                }),
                'nullable',
                'string',
                'min:6'
            ],

            'country_id' => 'nullable|integer',
            'wage_rate' => 'nullable',
            'commission_rate' => 'nullable',
            'default_role_id' => 'required|integer',
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'dob' => 'nullable|date',
            'ssn' => 'nullable|string|max:100',
            'contact_no' => [
                'nullable',
                'string',
                Rule::requiredIf(function () use ($request) {
                    return $request->login_web == 1 || $request->login_mobile == 1;
                }),
            ],
            'station_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                        $fail('The station_id field must be a valid JSON array.');
                    }
                },
            ],
        ];

        $validatedData = $request->validate($rules);

        // Decode and extract station names
        $stationIds = json_decode($request->input('station_id'), true);
        $stationNames = collect(is_array($stationIds) ? $stationIds : [])->pluck('value')->filter()->toArray();

        // Prepare user data
        $userData = [
            'name' => trim($validatedData['first_name'] . ' ' . ($validatedData['last_name'] ?? '')),
            'country_id' => $validatedData['country_id'] ?? null,
            'email' => $validatedData['email'] ?? null,
//            'wage_rate' => $validatedData['wage_rate'] ?? null,
//            'commission_rate' => $validatedData['commission_rate'] ?? null,
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'] ?? null,
            'dob' => $validatedData['dob'] ?? null,
            'ssn' => $validatedData['ssn'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'contact_no' => $validatedData['contact_no'] ?? null,
            'default_role_id' => $validatedData['default_role_id'],
            'login_web' => ($validatedData['login_web'] ?? null) === '1' ? 1 : 0,
            'login_mobile' => ($validatedData['login_mobile'] ?? null) === '1' ? 1 : 0,
            'status' => 1,
        ];

        // Include password if provided
        if (!empty($validatedData['password'])) {
            $userData['password'] = Hash::make($validatedData['password']);
        }

        if ($request->user_id) {
            $user = User::where('id', $request->user_id)->select('ssn')->first();
            if (!empty($user) && (empty($user->ssn) || $user->ssn == null)) {
                $userData['ssn'] = $this::generateReadableUniqueCode(8);
            } else {
                $userData['ssn'] = $user->ssn;
            }
        } else {
            $userData['ssn'] = $this->generateReadableUniqueCode(8);
        }


        $model = User::updateOrCreate(
            ['id' => $request->user_id], // Look for this ID
            $userData                    // Data to fill/update
        );

        // Process station associations
        if (!empty($stationNames)) {
            // Fetch matching stations from the database
            $stations = Station::whereIn('name', $stationNames)
                ->select('id', 'company_id')// Select both id and company_id
                ->get();

            if ($stations->isNotEmpty()) {
                // Remove existing EmployeeService records
                EmployeeService::where('employee_id', $model->id)->delete();

                // Prepare data for bulk insert
                $employeeStations = $stations->map(function ($station) use ($model, $validatedData) {
                    return [
                        'employee_id' => $model->id,
                        'station_id' => $station->id,
//                        'wage_rate' => $validatedData['wage_rate'] ?? null,
//                        'commission_rate' => $validatedData['commission_rate'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                });

                // Insert all records in one query
                EmployeeService::insert($employeeStations->toArray());

                // Update or create the user record
                $userCompanyUpdate['company_id'] = $companyId = $stations->first()->company_id ?? null;

                $model = User::updateOrCreate(
                    ['id' => $model->id],
                    $userCompanyUpdate
                );
            }
        }


        $methods = $request->input('methods', []);

        $validMethods = collect($methods)->filter(function ($method) {
            return !empty($method['method_id']) && isset($method['rate']);
        });

        if ($validMethods->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['methods' => 'At least one valid payroll method must be provided.']);
        }

        EmployeePayrollMethod::where('user_id', $model->id)->delete();

        $payrollTypeId = $request->input('payroll_type_id');
        $methods = $request->input('methods', []);

        $request->validate([
            'methods.*.method_id' => [
                'required',
                'distinct',
                Rule::unique('employee_payroll_method', 'method_id')
                    ->where('user_id', $model->id)
                    ->where('payroll_type_id', $payrollTypeId)
            ],
            'methods.*.rate' => 'required|numeric',
        ]);

        foreach ($request->input('methods', []) as $methodEntry) {
            EmployeePayrollMethod::create([
                'user_id' => $model->id,
                'payroll_type_id' => $payrollTypeId,
                'method_id' => $methodEntry['method_id'],
                'rate' => $methodEntry['rate'],
            ]);
        }


        return redirect()
            ->route('user.view', ['id' => $model->id])
            ->with('success', 'Employee saved successfully.');
    }

    public function save(Request $request)
    {
        $payrollTypeId = $request->input('payroll_type_id');
        $userId = $request->user_id;

        $rules = [
            'login_web' => 'sometimes|in:1',
            'login_mobile' => 'sometimes|in:1',

            'email' => [
                Rule::requiredIf($request->login_web == 1 || $request->login_mobile == 1),
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($request->user_id),
            ],

            'password' => [
                Rule::requiredIf(function () use ($request) {
                    return ($request->login_web == 1 || $request->login_mobile == 1) && empty($request->user_id);
                }),
                'nullable',
                'string',
                'min:6'
            ],

            'country_id' => 'nullable|integer',
            'wage_rate' => 'nullable',
            'commission_rate' => 'nullable',
            'default_role_id' => 'required|integer',
            'first_name' => 'required|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'dob' => 'nullable|date',
            'ssn' => 'nullable|string|max:100',
            'contact_no' => [
                'nullable',
                'string',
                Rule::requiredIf(function () use ($request) {
                    return $request->login_web == 1 || $request->login_mobile == 1;
                }),
            ],
            'station_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                        $fail('The station_id field must be a valid JSON array.');
                    }
                },
            ],
        ];

        $validatedData = $request->validate($rules);

        return DB::transaction(function () use ($request, $validatedData, $userId, $payrollTypeId) {

            $userData = [
                'name' => trim($validatedData['first_name'] . ' ' . ($request->last_name ?? '')),
                'country_id' => $request->country_id,
                'email' => $validatedData['email'],
                'first_name' => $validatedData['first_name'],
                'last_name' => $request->last_name,
                'dob' => $request->dob,
                'address' => $request->address,
                'contact_no' => $validatedData['contact_no'],
                'default_role_id' => $validatedData['default_role_id'],
                'login_web' => $request->login_web ? 1 : 0,
                'login_mobile' => $request->login_mobile ? 1 : 0,
                'status' => 1,
            ];

            if (!empty($validatedData['password'])) {
                $userData['password'] = Hash::make($validatedData['password']);
            }

            $model = User::updateOrCreate(['id' => $userId], $userData);

            if (empty($model->ssn)) {
                $model->update(['ssn' => $this->generateReadableUniqueCode(8)]);
            }

            $stationData = json_decode($request->input('station_id'), true);
            $stationNames = collect($stationData)->pluck('value')->filter()->toArray();

            if (!empty($stationNames)) {
                $stations = Station::whereIn('name', $stationNames)->get();
                if ($stations->isNotEmpty()) {
                    EmployeeService::where('employee_id', $model->id)->delete();

                    $employeeStations = $stations->map(function($s) use ($model) {
                        return [
                            'employee_id' => $model->id,
                            'station_id' => $s->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    })->toArray();

                EmployeeService::insert($employeeStations);
                $model->update(['company_id' => $stations->first()->company_id]);
            }
            }


//            EmployeePayrollMethod::where('user_id', $model->id)->delete();
//
//            $payrollTypeId = $request->input('payroll_type_id');
//            $userId = $model->id;
//
//            $request->validate([
//                'methods.*.method_id' => [
//                    'required',
//                    'distinct',
//                    function ($attribute, $value, $fail) use ($request, $model, $payrollTypeId) {
//                        $exists = EmployeePayrollMethod::where('user_id', $model->id)
//                            ->where('payroll_type_id', $payrollTypeId)
//                            ->where('method_id', $value)
//                            ->exists();
//
//                        if ($exists) {
//                            $methodName = Method::where('id', $value)->value('name') ?? 'Selected method';
//                            $fail("The method \"{$methodName}\" is already assigned to this employee.");
//                        }
//                    },
//                ],
//                'methods.*.rate' => 'required|numeric',
//            ], [
//                'methods.*.method_id.distinct' => 'You have selected the same method more than once in this form.',
//            ]);

//            DB::transaction(function () use ($model, $payrollTypeId, $request) {
//                // Delete only after validation passes
//                EmployeePayrollMethod::where('user_id', $model->id)->delete();
//
//                foreach ($request->input('methods') as $methodEntry) {
//                    EmployeePayrollMethod::create([
//                        'user_id'         => $model->id,
//                        'payroll_type_id' => $payrollTypeId,
//                        'method_id'       => $methodEntry['method_id'],
//                        'rate'            => $methodEntry['rate'],
//                    ]);
//                }
//            });

            return redirect()
                ->route('user.view', ['id' => $model->id])
                ->with('success', 'Employee saved successfully.');
        });
    }

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

        return response()->json(['message' => 'User ' . $message . ' successfully.', 200]);
    }

    public function changeStatus(Request $request)
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

    public function getServiceList(Request $request)
    {
        $services = Service::join('station_services', 'station_services.service_id', '=', 'services.id')
            ->where('station_services.station_id', $request->station_id)
            ->where('services.is_active', 1)// Only active services
            ->where('services.is_deleted', 0)// Exclude deleted services
            ->select('services.id', 'services.name')
            ->get();
        return response()->json($services);
    }

    public function show($id)
    {
        $user = User::with(['company', 'station', 'country', 'role', 'payrollMethods'])->where('id', $id)->first();
        $UserStationList = $user->employeeStation;

        $stations = Station::where('is_active', 1)->where('is_deleted', 0)->select('id', 'name', 'code')->get();

        $wages = Configuration::where('config_key', 'wages')->where('is_deleted', 0)->select('id', 'label', 'value', 'value_unit')->get();
        $employeeServices = EmployeeService::where('employee_id', $id)->get();

        $ShiftConfiguration = Configuration::where('config_key', 'Shift')->get();
        $employeeShifts = EmployeeShift::where('employee_id', $id)->get();


        $existingPayrollMethods = $user->payrollMethods->map(function ($method) {
            return [
                'station_id' => $method->station_id, // THIS IS MISSING
                'method_id' => $method->method_id,
                'payroll_type_id' => $method->payroll_type_id,
                'rate' => $method->rate,
                'payroll_name' => optional($method->payrollType)->name,
                'method_name' => optional($method->method)->name,
            ];
        });

        return view('cards.profile', [
            'user' => $user,
            'stations' => $stations,
            'UserStationList' => $UserStationList,
            'wages' => $wages,
            'employeeServices' => $employeeServices,
            'ShiftConfiguration' => $ShiftConfiguration,
            'employeeShifts' => $employeeShifts,
            'existingPayrollMethods' => $existingPayrollMethods
        ]);

    }

    public function savePayroll(Request $request)
    {
        $userId = $request->user_id;
        try {
            DB::beginTransaction();
            EmployeePayrollMethod::where('user_id', $userId)->delete();

            if ($request->has('method_id') && is_array($request->method_id)) {
                foreach ($request->method_id as $key => $methodId) {
                    $rate = $request->amount[$key] ?? null;
                    if ($rate !== null && $rate !== '') {
                        EmployeePayrollMethod::create([
                            'user_id'         => $userId,
                            'station_id'      => $request->station_id[$key],
                            'payroll_type_id' => $request->payroll_type_id[$key],
                            'method_id'       => $methodId,
                            'rate'            => $rate,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Payroll saved successfully!',
                'redirect' => route('user.view', $userId)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getPayrollMethods($id) {
        $methods = Category::where('category_key', 'payroll_method')->where('parent_id', $id)->get(['id', 'name']);
        return response()->json($methods);
    }


    public function employeeServiceStore(Request $request)
    {
        $request->validate([
            'employee_services' => 'required|array',
            'employee_services.*.station_id' => 'required|exists:stations,id',
//            'employee_services.*.service_id' => 'required|exists:services,id',
        ]);

        $employeeId = $request->employee_id;

        // Remove existing data for the employee
        EmployeeService::where('employee_id', $employeeId)->delete();

        // Insert new data
        foreach ($request->employee_services as $service) {
            EmployeeService::create([
                'employee_id' => $employeeId,
                'station_id' => $service['station_id'],
//                'service_id' => $service['service_id'],
                'wage_id' => $service['wage_id'],
            ]);
        }

        return redirect()->back()->with('success', 'Employee services saved successfully.');
    }

    public function employeeShiftStore(Request $request)
    {
        $employeeId = $request->employee_id;
        EmployeeShift::where('employee_id', $employeeId)->delete();

        foreach ($request->employee_services as $service) {
            EmployeeShift::create([
                'employee_id' => $employeeId,
                'station_id' => $service['station_id'],
                'shift_id' => isset($service['shift_id']) ? $service['shift_id'] : NULL,
                'shift_start_time' => isset($service['shift_start_time']) ? $service['shift_start_time'] : NULL,
                'shift_end_time' => isset($service['shift_end_time']) ? $service['shift_end_time'] : NULL,
            ]);
        }

        return redirect()->back()->with('success', 'Employee Shift saved successfully.');
    }
}
