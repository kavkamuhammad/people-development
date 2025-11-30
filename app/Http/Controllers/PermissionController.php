<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name|max:100',
            'display_name' => 'required|max:100',
            'description' => 'nullable|max:255',
        ]);

        Permission::create($request->all());

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil ditambahkan');
    }

    public function show(Permission $permission)
    {
        return view('permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|max:100|unique:permissions,name,' . $permission->id,
            'display_name' => 'required|max:100',
            'description' => 'nullable|max:255',
        ]);

        $permission->update($request->all());

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil diupdate');
    }

    public function destroy(Permission $permission)
    {
        // Check if permission is being used by any role
        if ($permission->roles()->count() > 0) {
            return redirect()->route('permissions.index')
                ->with('error', 'Permission tidak dapat dihapus karena masih digunakan oleh role');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil dihapus');
    }
}
