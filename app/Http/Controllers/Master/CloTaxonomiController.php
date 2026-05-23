<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\CloTaxonomi;
use Illuminate\Http\Request;

class CloTaxonomiController extends Controller
{
    public function index()
    {
        $items = CloTaxonomi::orderBy('domain')->orderBy('level')->get()->groupBy('domain');
        return view('master.clo-taxonomi.index', compact('items'));
    }

    public function edit(CloTaxonomi $cloTaxonomi)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        return view('master.clo-taxonomi.form', ['item' => $cloTaxonomi]);
    }

    public function update(Request $request, CloTaxonomi $cloTaxonomi)
    {
        abort_if(auth()->user()->hasRole('dosen'), 403);
        $data = $request->validate([
            'deskripsi' => 'required|string|max:255',
        ]);
        $cloTaxonomi->update($data);
        return redirect()->route('master.clo-taxonomi.index')->with('success', 'CLO Taxonomy diperbarui.');
    }
}
