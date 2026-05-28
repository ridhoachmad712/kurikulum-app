<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Concerns\DosenMkScope;
use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    use DosenMkScope;

    public function index(Request $request)
    {
        $programStudiId = $request->input('program_studi_id', auth()->user()->program_studi_id);
        $programStudis  = ProgramStudi::where('aktif', true)->get();
        $semester       = $request->input('semester');

        $search = $request->input('search');

        $query = MataKuliah::where('program_studi_id', $programStudiId)
            ->withCount(['cpmks','subCpmks'])
            ->when($search, fn($q) => $q->where(fn($s) =>
                $s->where('nama', 'like', "%$search%")
                  ->orWhere('kode_rps', 'like', "%$search%")
            ))
            ->orderBy('urutan');

        if ($semester) $query->where('semester', $semester);
        $this->applyDosenScope($query);

        $items = $query->paginate(20)->withQueryString();
        return view('master.mata-kuliah.index', compact('items','programStudis','programStudiId','semester'));
    }

    public function create()
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $programStudis = ProgramStudi::where('aktif', true)->get();
        return view('master.mata-kuliah.form', ['item' => new MataKuliah, 'programStudis' => $programStudis]);
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $data = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'kode_rps'         => 'required|string|max:20|unique:mata_kuliahs',
            'nama'             => 'required|string|max:255',
            'sks'              => 'required|integer|min:1|max:6',
            'semester'         => 'nullable|integer|min:1|max:8',
            'konsentrasi'      => 'nullable|string|max:100',
            'is_pilihan'       => 'boolean',
            'urutan'           => 'required|integer|min:0',
            'aktif'            => 'boolean',
        ]);
        $data['is_pilihan'] = $request->boolean('is_pilihan');
        $data['aktif']      = $request->boolean('aktif', true);
        MataKuliah::create($data);
        return redirect()->route('master.mata-kuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $mataKuliah)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $programStudis = ProgramStudi::where('aktif', true)->get();
        return view('master.mata-kuliah.form', ['item' => $mataKuliah, 'programStudis' => $programStudis]);
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $data = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'kode_rps'         => 'required|string|max:20|unique:mata_kuliahs,kode_rps,'.$mataKuliah->id,
            'nama'             => 'required|string|max:255',
            'sks'              => 'required|integer|min:1|max:6',
            'semester'         => 'nullable|integer|min:1|max:8',
            'konsentrasi'      => 'nullable|string|max:100',
            'is_pilihan'       => 'boolean',
            'urutan'           => 'required|integer|min:0',
            'aktif'            => 'boolean',
        ]);
        $data['is_pilihan'] = $request->boolean('is_pilihan');
        $data['aktif']      = $request->boolean('aktif', true);
        $mataKuliah->update($data);
        return redirect()->route('master.mata-kuliah.index')->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    public function show(MataKuliah $mataKuliah)
    {
        $mataKuliah->load([
            'dosens',
            'mkCpls.cpl',
            'cpmks.cpmkCpls.cpl',
            'cpmks.subCpmks.subCpmkClos.cloTaxonomi',
        ]);

        return view('master.mata-kuliah.show', compact('mataKuliah'));
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $mataKuliah->delete();
        return back()->with('success', 'Mata Kuliah dihapus.');
    }
}
