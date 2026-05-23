<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\ProfilLulusan;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProfilLulusanController extends Controller
{
    public function index()
    {
        $prodi = ProgramStudi::first();
        $items = $prodi ? $prodi->profilLulusans : collect();
        return view('master.profil-lulusan.index', compact('items', 'prodi'));
    }

    public function create()
    {
        $prodi = ProgramStudi::first();
        return view('master.profil-lulusan.form', ['item' => new ProfilLulusan, 'prodi' => $prodi]);
    }

    public function store(Request $request)
    {
        $prodi = ProgramStudi::first();
        $data = $request->validate([
            'kode'     => 'required|string|max:10|unique:profil_lulusans,kode,NULL,id,program_studi_id,' . $prodi->id,
            'nama'     => 'required|string|max:255',
            'deskripsi'=> 'nullable|string',
            'urutan'   => 'required|integer|min:1',
        ]);
        $data['program_studi_id'] = $prodi->id;
        ProfilLulusan::create($data);
        return redirect()->route('master.profil-lulusan.index')->with('success', 'Profil Lulusan berhasil ditambahkan.');
    }

    public function edit(ProfilLulusan $profilLulusan)
    {
        $prodi = ProgramStudi::first();
        return view('master.profil-lulusan.form', ['item' => $profilLulusan, 'prodi' => $prodi]);
    }

    public function update(Request $request, ProfilLulusan $profilLulusan)
    {
        $data = $request->validate([
            'kode'     => 'required|string|max:10|unique:profil_lulusans,kode,' . $profilLulusan->id . ',id,program_studi_id,' . $profilLulusan->program_studi_id,
            'nama'     => 'required|string|max:255',
            'deskripsi'=> 'nullable|string',
            'urutan'   => 'required|integer|min:1',
        ]);
        $profilLulusan->update($data);
        return redirect()->route('master.profil-lulusan.index')->with('success', 'Profil Lulusan berhasil diperbarui.');
    }

    public function destroy(ProfilLulusan $profilLulusan)
    {
        $profilLulusan->delete();
        return back()->with('success', 'Profil Lulusan dihapus.');
    }
}
