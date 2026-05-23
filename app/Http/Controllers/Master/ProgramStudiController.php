<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $items = ProgramStudi::withCount(['cpls','mataKuliahs'])->paginate(15);
        return view('master.program-studi.index', compact('items'));
    }

    public function create()
    {
        return view('master.program-studi.form', ['item' => new ProgramStudi]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'        => 'required|string|max:20|unique:program_studis',
            'nama'        => 'required|string|max:255',
            'jenjang'     => 'required|in:D3,D4,S1,S2,S3',
            'fakultas'    => 'nullable|string|max:255',
            'universitas' => 'nullable|string|max:255',
            'aktif'       => 'boolean',
        ]);
        ProgramStudi::create($data);
        return redirect()->route('master.program-studi.index')->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function edit(ProgramStudi $programStudi)
    {
        return view('master.program-studi.form', ['item' => $programStudi]);
    }

    public function update(Request $request, ProgramStudi $programStudi)
    {
        $data = $request->validate([
            'kode'        => 'required|string|max:20|unique:program_studis,kode,'.$programStudi->id,
            'nama'        => 'required|string|max:255',
            'jenjang'     => 'required|in:D3,D4,S1,S2,S3',
            'fakultas'    => 'nullable|string|max:255',
            'universitas' => 'nullable|string|max:255',
            'aktif'       => 'boolean',
        ]);
        $programStudi->update($data);
        return redirect()->route('master.program-studi.index')->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy(ProgramStudi $programStudi)
    {
        $programStudi->delete();
        return back()->with('success', 'Program Studi dihapus.');
    }
}
