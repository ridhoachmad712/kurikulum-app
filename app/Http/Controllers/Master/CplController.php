<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Cpl;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class CplController extends Controller
{
    public function index(Request $request)
    {
        $programStudiId = $request->input('program_studi_id', auth()->user()->program_studi_id);
        $programStudis  = ProgramStudi::where('aktif', true)->get();
        $search = $request->input('search');
        $items = Cpl::where('program_studi_id', $programStudiId)
            ->when($search, fn($q) => $q->where(fn($s) =>
                $s->where('kode', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%")
            ))
            ->orderBy('urutan')->paginate(20)->withQueryString();
        return view('master.cpl.index', compact('items','programStudis','programStudiId'));
    }

    public function create()
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $programStudis = ProgramStudi::where('aktif', true)->get();
        return view('master.cpl.form', ['item' => new Cpl, 'programStudis' => $programStudis]);
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $data = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'kode'             => 'required|string|max:20',
            'deskripsi'        => 'required|string',
            'keterangan'       => 'nullable|string|max:255',
            'urutan'           => 'required|integer|min:0',
        ]);
        Cpl::create($data);
        return redirect()->route('master.cpl.index')->with('success', 'CPL berhasil ditambahkan.');
    }

    public function edit(Cpl $cpl)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $programStudis = ProgramStudi::where('aktif', true)->get();
        return view('master.cpl.form', ['item' => $cpl, 'programStudis' => $programStudis]);
    }

    public function update(Request $request, Cpl $cpl)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $data = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'kode'             => 'required|string|max:20',
            'deskripsi'        => 'required|string',
            'keterangan'       => 'nullable|string|max:255',
            'urutan'           => 'required|integer|min:0',
        ]);
        $cpl->update($data);
        return redirect()->route('master.cpl.index')->with('success', 'CPL berhasil diperbarui.');
    }

    public function destroy(Cpl $cpl)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $cpl->delete();
        return back()->with('success', 'CPL dihapus.');
    }
}
