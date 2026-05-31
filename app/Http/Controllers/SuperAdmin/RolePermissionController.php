<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    /** Role inti yang tidak boleh dihapus/diubah namanya. */
    public const CORE_ROLES = ['super_admin', 'admin', 'kaprodi', 'dosen'];

    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('super-admin.roles.index', [
            'roles'       => $roles,
            'permissions' => $permissions,
            'coreRoles'   => self::CORE_ROLES,
        ]);
    }

    public function storeRole(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9_]+$/', Rule::unique('roles', 'name')],
        ], [
            'name.regex' => 'Nama role hanya boleh huruf kecil, angka, dan garis bawah (_).',
        ]);

        Role::create(['name' => $data['name'], 'guard_name' => 'web']);

        return back()->with('success', "Role '{$data['name']}' berhasil dibuat.");
    }

    public function destroyRole(Role $role)
    {
        if (in_array($role->name, self::CORE_ROLES)) {
            return back()->with('error', "Role inti '{$role->name}' tidak dapat dihapus.");
        }
        if ($role->users()->count() > 0) {
            return back()->with('error', "Role '{$role->name}' masih dipakai {$role->users()->count()} pengguna.");
        }
        $name = $role->name;
        $role->delete();
        return back()->with('success', "Role '{$name}' dihapus.");
    }

    public function editRole(Role $role)
    {
        return view('super-admin.roles.edit', [
            'role'        => $role,
            'permissions' => Permission::orderBy('name')->get(),
            'assigned'    => $role->permissions->pluck('name')->all(),
            'isCore'      => in_array($role->name, self::CORE_ROLES),
        ]);
    }

    public function updateRole(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions'   => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('super-admin.roles.index')
            ->with('success', "Permission untuk role '{$role->name}' diperbarui.");
    }

    public function storePermission(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', 'regex:/^[a-z0-9_.\-]+$/', Rule::unique('permissions', 'name')],
        ], [
            'name.regex' => 'Nama permission hanya boleh huruf kecil, angka, titik, strip, dan garis bawah.',
        ]);

        Permission::create(['name' => $data['name'], 'guard_name' => 'web']);

        return back()->with('success', "Permission '{$data['name']}' berhasil dibuat.");
    }

    public function destroyPermission(Permission $permission)
    {
        $name = $permission->name;
        $permission->delete();
        return back()->with('success', "Permission '{$name}' dihapus.");
    }
}
