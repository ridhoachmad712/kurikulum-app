<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Peo;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PeoController extends Controller
{
    private function prodi(): ProgramStudi
    {
        return ProgramStudi::findOrFail(auth()->user()->program_studi_id);
    }

    private function authorizedPeo(Peo $peo): Peo
    {
        abort_unless($peo->program_studi_id === $this->prodi()->id, 404);
        return $peo;
    }

    private function validated(Request $request, ProgramStudi $prodi, ?Peo $peo = null): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:20', Rule::unique('peos')->where('program_studi_id', $prodi->id)->ignore($peo?->id)],
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'urutan' => ['required', 'integer', 'min:1', 'max:65535'],
        ]);
    }

    public function index()
    {
        $prodi = $this->prodi();
        $items = $prodi->peos()->orderBy('id')->get();

        return view('master.peo.index', compact('prodi', 'items'));
    }

    public function create()
    {
        $this->prodi();
        return view('master.peo.form', ['item' => new Peo]);
    }

    public function store(Request $request)
    {
        $prodi = $this->prodi();
        $prodi->peos()->create($this->validated($request, $prodi));

        return redirect()->route('master.peo.index')->with('success', 'PEO berhasil ditambahkan.');
    }

    public function edit(Peo $peo)
    {
        return view('master.peo.form', ['item' => $this->authorizedPeo($peo)]);
    }

    public function update(Request $request, Peo $peo)
    {
        $peo = $this->authorizedPeo($peo);
        $peo->update($this->validated($request, $this->prodi(), $peo));

        return redirect()->route('master.peo.index')->with('success', 'PEO berhasil diperbarui.');
    }

    public function destroy(Peo $peo)
    {
        $this->authorizedPeo($peo)->delete();

        return redirect()->route('master.peo.index')->with('success', 'PEO berhasil dihapus.');
    }
}
