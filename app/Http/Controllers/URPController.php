<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use SebastianBergmann\CodeCoverage\Test\TestStatus\Unknown;

class URPController extends Controller
{
    public function AllRoles()
    {
        $roles = Role::where('id', '<>', 1)->withCount('users')->get();
        // Log activity
        // activity()->event('access')->log('Accessed all roles page');
        return view('backend.urp.roles.all_roles', compact('roles'));
    }

    // Show the Add Role page
    public function AddRoles()
    {
        // activity()->event('access')->log('Accessed add roles page');
        return view('backend.urp.roles.add_roles');
    }

    // Store a new role
    public function StoreRoles(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        try {
            $role = Role::create([
                'name' => $validatedData['name'],
                'defined_by' => 'user'
            ]);

            // Log the activity
            // activity()->performedOn($role)->withProperties($role)->event('created')->log('Role created');

            // return redirect()->route('all.roles')->with('success', 'Role added successfully.');
            return response()->json(['status' => 'success', 'message' => 'Role added successfully.', 'redirect_url' => route('all.roles')]);
        } catch (\Exception $e) {
            // logActivity('Add Role Exception', $validatedData, $e->getMessage(), 'error');

            // return redirect()->back()->with('error', 'An error occurred while adding the role. Please try again.');
            return response()->json(['status' => 'error', 'error' => 'An error occurred while adding the role. Please try again.']);
        }
    }

    public function RetrieveRoles(Request $request)
    {

        $page = $request->input('start') / $request->input('length') + 1;
        $perPage = $request->input('length');

        // Your additional search or filter criteria, if any
        $searchValue = $request->input('search.value');
        $query = Role::query()->where('id', '<>', 1)->withCount('users');

        if ($searchValue) {
            $query->where('name', 'like', "%$searchValue%");

            // Add more conditions for other columns as needed
        }

        $totalRecords = $query->count();
        $query->orderBy('id', 'asc');
        // Get the paginated records
        $records = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
        foreach ($records as $key => $record) {
            $data[] = [
                'DT_RowIndex' => $key + 1,
                'name' => $record->name,
                'defined_by' => $record->defined_by,
                'user_count' => $record->users_count, // ✅ user count added
                'edit_permission_url' => route('edit.roles.permission', ['id' => $record->id]),
                'action' => [$record->id, (($page - 1) * $perPage + $key + 1)], // Replace with your actual URL for viewing a single notice
            ];
        }

        if (empty($data)) {
            $data = []; // Return an empty array when no data
        }

        $output = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ];

        // activity()->withProperties($data)->event('retrieve')->log('Retrieved roles data');


        return response()->json($output);
    }

    // Show the Edit Role page
    public function EditRoles($id)
    {
        // $roles = Role::findOrFail($id);
        // return view('backend.roles.edit_roles', compact('roles'));

        $roles = Role::find($id);
        if ($id == 1 || $roles->defined_by === "system") {
            return redirect()->route('all.roles')->with('error', 'Access Denied!');
        } else if ($roles) {

            // activity()->withProperties($roles)->event('access')->log('Accessed edit roles page');
            return view("backend.urp.roles.edit_roles", compact("roles"));
        } else {
            // activity()->event('error')->log(' Roles not Found');
            return redirect()->route('all.roles')->with('error', 'Roles Not Found!');
        }
    }

    // Update an existing role
    public function UpdateRoles(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255|unique:roles,name,' . $request->id,
        ]);

        try {
            $role = Role::findOrFail($validatedData['id']);
            $role->update([
                'name' => $validatedData['name'],
            ]);

            // TriggerNotificationHookJob::dispatch('update_role', [
            //     'role_name' => $role->name,
            //     'action' => 'Role updated',
            //     'link' => '/edit/roles/' . $role->id,
            // ]);

            // Log the activity
            // activity()->performedOn($role)->withProperties($role)->event('updated')->log('Role updated');

            // return redirect()->route('all.roles')->with('success', 'Role updated successfully.');
            return response()->json(['status' => 'success', 'message' => 'Role updated successfully.', 'redirect_url' => route('all.roles')]);
        } catch (\Exception $e) {
            // logActivity('Update Role Exception', [], $e->getMessage(), 'error');
            // return redirect()->back()->with('error', 'An error occurred while updating the role. Please try again.');
            return response()->json(['status' => 'error', 'error' => 'An error occurred while updating the role. Please try again.']);
        }
    }

    public function DeleteRoles(Request $request)
    {
        $id = $request->input("id");

        try {
            $role = Role::findOrFail($id);

            // Prevent deletion of Super Admin role
            if ($id == 1) {
                return response()->json(['error' => "Access Denied"], 403);
            }

            $role->delete();

            // Log the activity
            // activity()
            //     ->performedOn($role)
            //     ->withProperties(['name' => $role->name])
            //     ->event('deleted')
            //     ->log('Role deleted');

            return response()->json(['message' => "Role deleted successfully"]);
        } catch (\Exception $e) {
            // Log the error
            // activity()
            //     ->withProperties(['error' => $e->getMessage()])
            //     ->event('error')
            //     ->log('Role deletion failed');

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function EditRolesPermission($id)
    {
        if ($id == 1) {
            return redirect()->route('all.roles')->with('error', 'Access Denied!');
        }
        $role = Role::findOrFail($id);
        $permissions = Permission::where('group_name', '<>', 'permissions')->get();
        $permission_groups = User::getpermissionGroups();

        // activity()->event('access')->withProperties([$role,$permissions,$permission_groups])->log('Accessed roles in permission page');
        return view('backend.urp.roles.edit_roles_permission', compact('role', 'permissions', 'permission_groups'));
    }

    public function UpdateRolesPermission(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Retrieve permissions from the request
        $permissions = $request->permission;

        if (!empty($permissions)) {
            // Convert permission IDs to names
            $permissionNames = Permission::whereIn('id', $permissions)->pluck('name')->toArray();

            // Sync permissions using names
            $role->syncPermissions($permissionNames);

            // Log the activity
            // activity()->event('updated')->withProperties(['role_id' => $role->id])->log('Role in permissions updated');
        }

        return redirect()->back()->with('success', 'Role and permissions updated successfully.');
    }

    //All User admin Manage method
    public function AllUser()
    {

        // $Users = User::with('roles')->get();
        $Users = User::where('id', '<>', 1)->with('roles')->get();

        foreach ($Users as $key => $admin) {
            $allUsers[] = [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'role' => $admin->roles->pluck('name')->first(),
                'category_name' => 'Unknown',
            ];
        }
        // Log::info($Users);

        // activity()->withProperties($alladmin)->event('access')->log('accesed  all users page');

        return view('backend.urp.admin.all_admin', compact('allUsers'));
    }

    public function AddUser()
    {
        $roles = Role::where('id', '<>', 1)->get();


        // activity()->event('access')->log('Accessed add page for user');
        return view('backend.urp.admin.add_admin', compact('roles'));
    }
    public function StoreUser(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|unique:users,phone',
            'password' => 'required|string|min:6', //
            'roles' => 'required|exists:roles,id',
        ]);

        // Create the new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->role = 'admin'; // This should align with your application's role setup
        $user->save();

        // Assign role to the user
        $role = Role::find($request->roles);
        $user->assignRole($role->name);

        $role = $user->roles()->select('name')->first();


        // TriggerNotificationHookJob::dispatch('create_user', [
        //     'user_name' => $request->name,
        //     'user_email' => $request->email,
        //     'user_mobile' => $request->phone,
        //     'user_role' => $role->name,
        //     'user_department' => $dept->name,
        //     'action' => 'New user created',
        //     'link' => '/edit/users/' . $user->id,
        // ]);


        // Log the activity
        // activity()->causedBy(Auth::user())->performedOn($user)
        //     ->withProperties(['name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'role' => $role->name, 'dept_id' => $request->dept_id])
        //     ->event('created')->log('user created');

        // Redirect to the admin listing page with a success message
        // return redirect()->route('all.users')->with('success', 'Admin user created successfully.');

        return response()->json(['status' => 'success', 'message' => 'User created successfully.', 'redirect_url' => route('all.users')]);
    }

    public function EditUser($id)
    {
        $user = User::findOrFail($id);
        if ($id == 1) {
            return redirect()->route('all.users')->with('error', 'Access Denied!');
        }
        $roles = Role::where('id', '<>', 1)->get();

        // Log activity
        // activity()->event('access')->withProperties(['user_id' => $id, 'name' => $user->name, 'email' => $user->email])->log('Accessed edit page for user');
        return view('backend.urp.admin.edit_admin', compact('user', 'roles'));
    }

    public function UpdateUser(Request $request, $id)
    {
        // Validate request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15|unique:users,phone,' . $id,
            'roles' => 'nullable|exists:roles,id',
        ]);

        try {
            // Find the user
            $user = User::findOrFail($id);

            // Update user data
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'role' => 'admin',
            ]);

            // Update roles
            $user->roles()->detach();
            if ($request->roles) {
                $user->assignRole((int) $validatedData['roles']);
            }


            $role = $user->roles()->select('name')->first();


            // TriggerNotificationHookJob::dispatch('update_user', [
            //     'user_name' => $request->name,
            //     'user_email' => $request->email,
            //     'user_mobile' => $request->phone,
            //     'user_role' => $role->name,
            //     'user_department' => $dept->name,
            //     'action' => 'User updated',
            //     'link' => '/edit/users/' . $user->id,
            // ]);

            // Log the activity
            // activity()->performedOn($user)->withProperties($validatedData)->event('updated')->log('User updated');

            // Redirect with success message
            // return redirect()->route('all.users')->with('success', 'Admin updated successfully.');
            return response()->json(['status' => 'success', 'message' => 'User updated successfully.', 'redirect_url' => route('all.users')]);
        } catch (\Exception $e) {
            // Redirect with error message
            // return redirect()->back()->with('error', 'An error occurred while updating the admin. Please try again.');

            return response()->json(['status' => 'error', 'error' => 'An error occurred while updating the user. Please try again.']);
        }
    }
}
