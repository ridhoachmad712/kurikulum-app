<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $psId  = (int) auth()->user()->program_studi_id;
        $search = request('search');

        $users = User::where('program_studi_id', $psId)
            ->with('roles')
            ->where('id', '!=', auth()->id())
            ->when($search, fn($q) => $q->where(fn($s) =>
                $s->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
            ))
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:dosen,kaprodi',
        ]);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => Hash::make($data['password']),
            'email_verified_at' => now(),
            'program_studi_id'  => auth()->user()->program_studi_id,
        ]);

        $user->assignRole($data['role']);

        ActivityLog::record('created', "membuat akun \"{$user->name}\" (role: {$data['role']})", 'Akun', $user->id, $user->name);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:dosen,kaprodi',
        ]);

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            ...($data['password'] ? ['password' => Hash::make($data['password'])] : []),
        ]);

        $user->syncRoles([$data['role']]);

        ActivityLog::record('updated', "memperbarui akun \"{$user->name}\" (role: {$data['role']})", 'Akun', $user->id, $user->name);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $nama = $user->name; $uid = $user->id;
        $user->delete();
        ActivityLog::record('deleted', "menghapus akun \"{$nama}\"", 'Akun', $uid, $nama);
        return redirect()->route('admin.users.index')->with('success', 'Akun dihapus.');
    }
}
