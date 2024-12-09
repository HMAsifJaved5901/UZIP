<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $model = DB::table('permissions')
            ->select('id','module', 'name', 'slug', 'guard_name')
            ->whereNotIn('module',['Permission','Role'])
            ->orderBy('module')
            ->get();
        $permissions = [];
        foreach ($model as $row) {
            $permissions[$row->module][] = [
                'id' => $row->id,
                'name' => $row->name,
                'slug' => $row->slug,
                'guard_name' => $row->guard_name,
            ];
        }

        return view('role-permission.permission.index',['data'=>$permissions]);
    }
    public function create()
    {
        return view('role-permission.permission.create');
    }
    public function saveRolePermission(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'role' => 'required|integer',
            'permission' => 'required|array',
            'permission.*' => 'integer', // Each permission ID must be an integer
        ]);

        $roleId = $validated['role'];
        $permissions = $validated['permission'];

        // Find the role
        $role = Role::findOrFail($roleId);

        // Sync permissions in role_has_permissions (Eloquent handles this table automatically)
        $role->permissions()->sync($permissions);

        return redirect()->route('permissions.index')->with('success', 'Permissions assigned successfully.');

    }

    public function getPermissionsByRole(Request $request)
    {
        $roleId = $request->get('role_id');

        if (!$roleId) {
            return response()->json(['error' => 'Role ID is required'], 400);
        }

        // Fetch permissions associated with the role
        $permissions = DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->pluck('permission_id'); // Get only permission IDs

        return response()->json(['permissions' => $permissions]);
    }

    public function show()
    {

    }

    public function getPermissionList(Request $request)
    {
        $permissions = Permission::all();
        $formattedPermissions = $permissions->map(function ($permission) {
            $createdDate = Carbon::parse($permission->created_at)->format('d M Y, g:i A');
            $assignedTo = is_array($permission->guard_name) ? $permission->guard_name : [$permission->guard_name];
            return [
                'id' => $permission->id,
                'name' => $permission->slug,
                'assigned_to' => $assignedTo,
                'module' => $permission->module,
                'created_at' => $createdDate
            ];
        });
        // Return the data in the requested structure
        return response()->json(['data' => $formattedPermissions]);
    }
}
