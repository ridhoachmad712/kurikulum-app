<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Rps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RpsController extends Controller
{
    /* ----------------------------------------------------------------
     * INDEX — daftar RPS
     * ---------------------------------------------------------------- */
    public function index(Request $request)
    {
        $user   = auth()->user();
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Rps::with(['mataKuliah', 'dosen'])
            ->when($user->isDosen(), fn($q) => $q->where('dosen_id', $user->id))
            ->when($search, fn($q) => $q->whereHas('mataKuliah', fn($mq) =>
                $mq->where('nama', 'like', "%$search%")->orWhere('kode_rps', 'like', "%$search%")
            ))
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('updated_at');

        $items = $query->paginate(20)->withQueryString();

        return view('kurikulum.rps.index', compact('items', 'status'));
    }

    /* ----------------------------------------------------------------
     * CREATE — form buat RPS baru
     * ---------------------------------------------------------------- */
    public function create()
    {
        $user = auth()->user();
        $psId = (int) $user->program_studi_id;

        if ($user->isDosen()) {
            $mataKuliahs = $user->mataKuliahs()->where('program_studi_id', $psId)->where('aktif', true)->orderBy('urutan')->get();
        } else {
            $mataKuliahs = MataKuliah::where('program_studi_id', $psId)->where('aktif', true)->orderBy('urutan')->get();
        }

        return view('kurikulum.rps.form', ['rps' => new Rps, 'mataKuliahs' => $mataKuliahs]);
    }

    /* ----------------------------------------------------------------
     * STORE — simpan RPS baru
     * ---------------------------------------------------------------- */
    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'tahun_ajaran'   => 'required|string|max:20',
            'semester'       => 'required|in:Ganjil,Genap',
        ]);

        // Cek dosen hanya bisa buat RPS untuk MK yang ditugaskan
        if ($user->isDosen()) {
            abort_unless($user->mataKuliahs()->where('mata_kuliahs.id', $data['mata_kuliah_id'])->exists(), 403);
        }

        Rps::create([
            ...$data,
            'dosen_id' => $user->id,
            'status'   => 'Draft',
        ]);

        return redirect()->route('kurikulum.rps.index')->with('success', 'RPS berhasil dibuat.');
    }

    /* ----------------------------------------------------------------
     * SHOW — detail RPS
     * ---------------------------------------------------------------- */
    public function show(Rps $rps)
    {
        $this->authorizeRps($rps);
        $rps->load(['mataKuliah.cpmks.subCpmks', 'dosen']);
        return view('kurikulum.rps.show', compact('rps'));
    }

    /* ----------------------------------------------------------------
     * EDIT — form edit (hanya status Draft)
     * ---------------------------------------------------------------- */
    public function edit(Rps $rps)
    {
        $this->authorizeRps($rps);
        abort_if($rps->status !== 'Draft', 403, 'Hanya RPS berstatus Draft yang bisa diedit.');

        $user        = auth()->user();
        $psId        = (int) $user->program_studi_id;
        $mataKuliahs = $user->isDosen()
            ? $user->mataKuliahs()->where('program_studi_id', $psId)->where('aktif', true)->orderBy('urutan')->get()
            : MataKuliah::where('program_studi_id', $psId)->where('aktif', true)->orderBy('urutan')->get();

        return view('kurikulum.rps.form', compact('rps', 'mataKuliahs'));
    }

    /* ----------------------------------------------------------------
     * UPDATE — simpan perubahan (hanya Draft)
     * ---------------------------------------------------------------- */
    public function update(Request $request, Rps $rps)
    {
        $this->authorizeRps($rps);
        abort_if($rps->status !== 'Draft', 403);

        $data = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'tahun_ajaran'   => 'required|string|max:20',
            'semester'       => 'required|in:Ganjil,Genap',
        ]);

        $rps->update($data);
        return redirect()->route('kurikulum.rps.show', $rps)->with('success', 'RPS diperbarui.');
    }

    /* ----------------------------------------------------------------
     * SUBMIT — Dosen ajukan ke Review
     * ---------------------------------------------------------------- */
    public function submit(Rps $rps)
    {
        $this->authorizeRps($rps);
        abort_if($rps->status !== 'Draft', 403, 'Hanya RPS berstatus Draft yang bisa diajukan.');

        $rps->update(['status' => 'Review', 'submitted_at' => now()]);
        return back()->with('success', 'RPS diajukan untuk review.');
    }

    /* ----------------------------------------------------------------
     * APPROVE — Kaprodi/Admin finalisasi ke Final
     * ---------------------------------------------------------------- */
    public function approve(Rps $rps)
    {
        abort_if(auth()->user()->isDosen(), 403);
        abort_if($rps->status !== 'Review', 403, 'Hanya RPS berstatus Review yang bisa disetujui.');

        $rps->update(['status' => 'Final', 'approved_at' => now()]);
        return back()->with('success', 'RPS disetujui dan berubah ke status Final.');
    }

    /* ----------------------------------------------------------------
     * REJECT — Kaprodi/Admin kembalikan ke Draft
     * ---------------------------------------------------------------- */
    public function reject(Rps $rps)
    {
        abort_if(auth()->user()->isDosen(), 403);
        abort_if($rps->status !== 'Review', 403);

        $rps->update(['status' => 'Draft', 'submitted_at' => null]);
        return back()->with('success', 'RPS dikembalikan ke Draft.');
    }

    /* ----------------------------------------------------------------
     * DESTROY — hapus RPS (hanya Draft, hanya pemilik/admin)
     * ---------------------------------------------------------------- */
    public function destroy(Rps $rps)
    {
        $this->authorizeRps($rps);
        abort_if($rps->status !== 'Draft', 403, 'Hanya RPS berstatus Draft yang bisa dihapus.');

        $rps->delete();
        return redirect()->route('kurikulum.rps.index')->with('success', 'RPS dihapus.');
    }

    /* ----------------------------------------------------------------
     * Helper: pastikan user boleh akses RPS ini
     * ---------------------------------------------------------------- */
    private function authorizeRps(Rps $rps): void
    {
        $user = auth()->user();
        if ($user->isDosen()) {
            abort_unless($rps->dosen_id === $user->id, 403);
        }
    }
}
