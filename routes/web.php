<?php

use App\Http\Controllers\Admin\PenugasanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Kurikulum\CpmkController;
use App\Http\Controllers\Kurikulum\MatriksApiController;
use App\Http\Controllers\Kurikulum\MatriksController;
use App\Http\Controllers\Kurikulum\AsesmenController;
use App\Http\Controllers\Kurikulum\RpsController;
use App\Http\Controllers\Kurikulum\SubCpmkController;
use App\Http\Controllers\Master\CloTaxonomiController;
use App\Http\Controllers\Master\CplController;
use App\Http\Controllers\Master\MataKuliahController;
use App\Http\Controllers\Master\ProfilLulusanController;
use App\Http\Controllers\Master\ProgramStudiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// ── PUBLIC (tanpa login) ─────────────────────────────────────────
Route::get('/',                       [PublicController::class, 'home'])            ->name('public.home');
Route::get('/profil',                 [PublicController::class, 'profil'])           ->name('public.profil');
Route::get('/kurikulum',              [PublicController::class, 'kurikulum'])        ->name('public.kurikulum');
Route::get('/mata-kuliah',            [PublicController::class, 'mataKuliah'])      ->name('public.mata-kuliah');
Route::get('/mata-kuliah/{mataKuliah}',[PublicController::class, 'mataKuliahShow'])->name('public.mata-kuliah.show');
Route::get('/cpl',                    [PublicController::class, 'cpl'])             ->name('public.cpl');
Route::get('/matriks-mk-cpl',         [PublicController::class, 'matriks'])         ->name('public.matriks');

// /admin → redirect ke dashboard (panel)
Route::get('/admin', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kelengkapan', [DashboardController::class, 'kelengkapan'])->name('kelengkapan');

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('program-studi', ProgramStudiController::class)->except(['show'])->middleware('role:admin|kaprodi');
        Route::resource('profil-lulusan', ProfilLulusanController::class)->except(['show'])->middleware('role:admin|kaprodi');
        Route::resource('cpl', CplController::class)->except(['show']);
        Route::resource('mata-kuliah', MataKuliahController::class)->except(['show']);
        Route::get('mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'show'])
            ->name('mata-kuliah.show')
            ->middleware('role:admin|kaprodi');
        Route::resource('clo-taxonomi', CloTaxonomiController::class)->only(['index','edit','update']);
    });

    // Kurikulum
    Route::prefix('kurikulum')->name('kurikulum.')->group(function () {

        // Matriks MK–CPL
        Route::get('matriks',        [MatriksController::class, 'input'])   ->name('matriks.input');
        Route::get('matriks/tabel2', [MatriksController::class, 'tabel2'])  ->name('matriks.tabel2');
        Route::get('matriks/tabel3', [MatriksController::class, 'tabel3'])  ->name('matriks.tabel3');
        Route::get('matriks/tabel4', [MatriksController::class, 'tabel4'])  ->name('matriks.tabel4');
        Route::get('matriks/tabel5', [MatriksController::class, 'tabel5'])  ->name('matriks.tabel5');
        Route::post('api/matriks/save-cell', [MatriksApiController::class, 'saveCell'])->name('api.matriks.save-cell');
        Route::post('api/matriks/bulk-save', [MatriksApiController::class, 'bulkSave'])->name('api.matriks.bulk-save');

        // CPMK
        Route::get ('cpmk',               [CpmkController::class, 'index'])  ->name('cpmk.index');
        Route::get ('cpmk/tabel6',        [CpmkController::class, 'tabel6']) ->name('cpmk.tabel6');
        Route::get ('cpmk/tabel7',        [CpmkController::class, 'tabel7']) ->name('cpmk.tabel7');
        Route::get ('cpmk/tabel11',       [CpmkController::class, 'tabel11'])->name('cpmk.tabel11');
        Route::get ('cpmk/tabel12',       [CpmkController::class, 'tabel12'])->name('cpmk.tabel12');
        Route::get ('cpmk/{mataKuliah}',  [CpmkController::class, 'show'])   ->name('cpmk.show');
        Route::post('cpmk/{mataKuliah}',  [CpmkController::class, 'store'])  ->name('cpmk.store');
        Route::patch('api/cpmk/{cpmk}',              [CpmkController::class, 'update'])         ->name('api.cpmk.update');
        Route::delete('api/cpmk/{cpmk}',             [CpmkController::class, 'destroy'])        ->name('api.cpmk.destroy');
        Route::post('api/cpmk/{cpmk}/cpl-relation',  [CpmkController::class, 'saveCplRelation'])->name('api.cpmk.cpl-relation');
        Route::post('api/cpmk/{cpmk}/bobot',         [CpmkController::class, 'saveBobot'])      ->name('api.cpmk.bobot');

        // Sub-CPMK
        Route::get ('sub-cpmk',               [SubCpmkController::class, 'index'])  ->name('sub-cpmk.index');
        Route::get ('sub-cpmk/tabel8',        [SubCpmkController::class, 'tabel8']) ->name('sub-cpmk.tabel8');
        Route::get ('sub-cpmk/tabel9',        [SubCpmkController::class, 'tabel9']) ->name('sub-cpmk.tabel9');
        Route::get ('sub-cpmk/tabel10',       [SubCpmkController::class, 'tabel10'])->name('sub-cpmk.tabel10');
        Route::get ('sub-cpmk/{cpmk}',        [SubCpmkController::class, 'show'])   ->name('sub-cpmk.show');
        Route::post('sub-cpmk/{cpmk}',        [SubCpmkController::class, 'store'])  ->name('sub-cpmk.store');
        Route::patch ('api/sub-cpmk/{subCpmk}',      [SubCpmkController::class, 'update'])  ->name('api.sub-cpmk.update');
        Route::delete('api/sub-cpmk/{subCpmk}',      [SubCpmkController::class, 'destroy']) ->name('api.sub-cpmk.destroy');
        Route::post  ('api/sub-cpmk/{subCpmk}/clo',  [SubCpmkController::class, 'saveClo']) ->name('api.sub-cpmk.clo');

        // Asesmen
        Route::get ('asesmen',               [AsesmenController::class, 'index']) ->name('asesmen.index');
        Route::get ('asesmen/{mataKuliah}',  [AsesmenController::class, 'show'])  ->name('asesmen.show');
        Route::post('api/asesmen/{subCpmk}', [AsesmenController::class, 'save'])  ->name('api.asesmen.save');

        // RPS
        Route::resource('rps', RpsController::class);
        Route::post('rps/{rps}/submit',  [RpsController::class, 'submit']) ->name('rps.submit');
        Route::post('rps/{rps}/approve', [RpsController::class, 'approve'])->name('rps.approve');
        Route::post('rps/{rps}/reject',  [RpsController::class, 'reject']) ->name('rps.reject');
    });

    // Admin: Penugasan Dosen
    Route::middleware(['role:admin|kaprodi'])->prefix('admin')->name('admin.')->group(function () {
        Route::get ('penugasan',                      [PenugasanController::class, 'index'])  ->name('penugasan.index');
        Route::post('penugasan/{mataKuliah}/toggle',  [PenugasanController::class, 'toggle']) ->name('penugasan.toggle');
        Route::resource('users', UserController::class)->except(['show']);
        Route::get ('hak-akses',                      fn() => view('admin.hak-akses'))        ->name('hak-akses');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
