<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Plo;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PloController extends Controller
{
    private function prodi(): ProgramStudi
    {
        return ProgramStudi::findOrFail(auth()->user()->program_studi_id);
    }

    private function authorizedPlo(Plo $plo): Plo
    {
        abort_unless($plo->program_studi_id === $this->prodi()->id, 404);
        return $plo;
    }

    private function validated(Request $request, ProgramStudi $prodi, ?Plo $plo = null): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:20', Rule::unique('plos')->where('program_studi_id', $prodi->id)->ignore($plo?->id)],
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'urutan' => ['required', 'integer', 'min:1', 'max:65535'],
        ]);
    }

    public function index()
    {
        $prodi = $this->prodi();
        $items = $prodi->plos()->orderBy('id')->get();

        return view('master.plo.index', compact('prodi', 'items'));
    }

    public function create()
    {
        $this->prodi();
        return view('master.plo.form', ['item' => new Plo]);
    }

    public function store(Request $request)
    {
        $prodi = $this->prodi();
        $prodi->plos()->create($this->validated($request, $prodi));

        return redirect()->route('master.plo.index')->with('success', 'PLO berhasil ditambahkan.');
    }

    public function edit(Plo $plo)
    {
        return view('master.plo.form', ['item' => $this->authorizedPlo($plo)]);
    }

    public function update(Request $request, Plo $plo)
    {
        $plo = $this->authorizedPlo($plo);
        $plo->update($this->validated($request, $this->prodi(), $plo));

        return redirect()->route('master.plo.index')->with('success', 'PLO berhasil diperbarui.');
    }

    public function destroy(Plo $plo)
    {
        $this->authorizedPlo($plo)->delete();

        return redirect()->route('master.plo.index')->with('success', 'PLO berhasil dihapus.');
    }
}
