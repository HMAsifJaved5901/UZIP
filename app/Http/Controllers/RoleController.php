<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use function Termwind\ValueObjects\pr;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('is_deleted',0)->get();
        $modelPermission = DB::table('permissions')
            ->select('id', 'module', 'slug', 'guard_name')
            ->whereNotIn('module', ['Permission', 'Role'])
            ->orderBy('module')
            ->get();
        $permissions = [];
        foreach ($modelPermission as $row) {
            $permissions[$row->module][] = [
                'id' => $row->id,
                'slug' => $row->slug,
                'guard_name' => $row->guard_name,
            ];
        }

        return view('role-permission.role.index', ['data' => $roles, 'permissions' => $permissions]);
    }

    public function save(Request $request)
    {
        $isUpdate = $request->has('id'); // Check if this is an update operation
        $roleId = $request->input('id'); // Role ID for update

        // Validation rules
        $validator = Validator::make($request->all(), [
            'RoleName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId), // Ignore unique validation for the current role during update
            ],
        ],
        [
            'RoleName.unique' => 'The role name already exists. Please choose a different name.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Determine guard name
            $guard_name = $request->has('admin') ? 'admin' : 'web';

            // Create or update the role
            if ($isUpdate) {
                $role = Role::findOrFail($roleId);
                $role->update([
                    'name' => $request['RoleName'],
                    'guard_name' => $guard_name,
                ]);
            } else {
                $role = Role::create([
                    'name' => $request['RoleName'],
                    'guard_name' => $guard_name,
                ]);
            }

            // Handle permissions for non-admin roles
            if ($guard_name !== 'admin') {
                $permissionsArray = $request->input('permissions', []); // Default to an empty array if no permissions
                $permissionObjects = Permission::whereIn('id', $permissionsArray)->get();

                // Sync permissions (remove unselected, add new ones)
                $role->syncPermissions($permissionObjects);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while saving the role: ' . $e->getMessage());
        }

        return redirect()->route('roles.index')->with('success', $isUpdate ? 'Role updated successfully.' : 'Role created and permissions assigned successfully.');
    }


    public function edit()
    {
        return view('role-permission.role.update');
    }

    public function update()
    {

    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $deleted = $role->is_deleted == 1 ? 0 : 1;
        $status = $deleted == 1 ? 0 : 1;
        $role->is_deleted = $deleted;
        $role->status = $status;
        $role->save();

        return response()->json(['message' => 'Role soft deleted successfully.', 200, 'data'=>$role]);
    }

    public function getRoleList(Request $request)
    {
        $roles = Role::all();
        $formattedRoles = $roles->map(function ($role) {
            $createdDate = Carbon::parse($role->created_at)->format('d M Y, g:i A');
            return [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'status' => $role->status,
                'deleted' => $role->is_deleted,
                'created_at' => $createdDate
            ];
        });
        // Return the data in the requested structure
        return response()->json(['data' => $formattedRoles]);
    }

    public function fetchRoleById(Request $request)
    {
        $role = Role::where('id',$request->id)->select('id','name')->first();
        return response()->json(['data' => $role]);
    }
}
