<?php

namespace Database\Seeders;

use App\Models\Cpmk;
use App\Models\CpmkCpl;
use App\Models\SubCpmk;
use App\Models\SubCpmkClo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CpmkImportSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // Hapus 6 CPMK salah untuk RPS011 (cascade ke cpmk_cpls, sub_cpmks, sub_cpmk_clos)
            Cpmk::where('mata_kuliah_id', 11)->delete();

            // CLO id map (kode => id)
            $clo = [
                'A1' => 7,  'A2' => 8,  'A3' => 9,  'A4' => 10, 'A5' => 11, 'A6' => 12,
                'C1' => 1,  'C2' => 2,  'C3' => 3,  'C4' => 4,  'C5' => 5,  'C6' => 6,
                'P1' => 13, 'P2' => 14, 'P3' => 15, 'P4' => 16, 'P5' => 17, 'P6' => 18,
            ];

            // CPL urutan = id (urutan 1 → id 1, ..., urutan 10 → id 10)

            $mkData = [
                // ── RPS011 (mk_id=11) ──────────────────────────────────────────────────────
                11 => [
                    'cpmks' => [
                        [
                            'kode'     => 'CPMK 1',
                            'urutan'   => 1,
                            'deskripsi'=> 'Menerapkan konsep literasi sains, teknologi, dan seni dalam konteks inovasi di bidang manajemen',
                            'cpls'     => [[1, 40.00]],
                        ],
                        [
                            'kode'     => 'CPMK 2',
                            'urutan'   => 2,
                            'deskripsi'=> 'Mengembangkan ide kreatif dan inovatif berbasis sains, teknologi, dan seni sebagai wujud mindset kewirausahaan.',
                            'cpls'     => [[2, 37.50]],
                        ],
                        [
                            'kode'     => 'CPMK 3',
                            'urutan'   => 3,
                            'deskripsi'=> 'Menunjukkan sikap humanis, etis, dan bertanggung jawab dalam pemanfaatan sains, teknologi, dan seni.',
                            'cpls'     => [[1, 22.50]],
                        ],
                    ],
                    // [cpmk_urutan, rel_urutan_dlm_cpmk, global_urutan, deskripsi, kode_clo_tertinggi, [kode_clo...]]
                    'subs' => [
                        [1, 1, 1, 'Menganalisis penerapan literasi sains, teknologi, dan seni dalam pengambilan keputusan dan inovasi manajemen.',          'A3', ['A3', 'C2']],
                        [1, 2, 2, 'Mengintegrasikan literasi sains, teknologi, dan seni dalam penyelesaian masalah manajemen secara kreatif.',               'C4', ['A4', 'C4']],
                        [2, 1, 3, 'Mengidentifikasi peluang kewirausahaan berbasis sains, teknologi, dan seni.',                                            'C4', ['A4', 'C4', 'P3']],
                        [2, 2, 4, 'Mengembangkan rancangan produk, layanan, atau strategi bisnis inovatif berbasis literasi sains, teknologi, dan seni.',    'C6', ['C6', 'P4']],
                        [2, 3, 5, 'Mengimplementasikan ide kreatif dan inovatif dalam konteks kewirausahaan.',                                              'C5', ['A5', 'C5', 'P5']],
                        [3, 1, 6, 'Menunjukkan sikap humanis, etis, kolaboratif, dan bertanggung jawab dalam pemanfaatan sains, teknologi, dan seni.',       'A5', ['A5', 'P3']],
                    ],
                ],

                // ── RPS036 (mk_id=36) ──────────────────────────────────────────────────────
                36 => [
                    'cpmks' => [
                        [
                            'kode'     => 'CPMK 1',
                            'urutan'   => 1,
                            'deskripsi'=> 'Mampu menjelaskan konsep, komponen, dan peranan sistem informasi manajemen dalam mendukung fungsi dan strategi organisasi.',
                            'cpls'     => [[4, 15.00], [6, 10.00]],
                        ],
                        [
                            'kode'     => 'CPMK 2',
                            'urutan'   => 2,
                            'deskripsi'=> 'Mampu menganalisis kebutuhan informasi serta merancang sistem informasi untuk mendukung efektivitas pengambilan keputusan manajerial.',
                            'cpls'     => [[4, 10.00], [6, 17.67], [8, 8.33]],
                        ],
                        [
                            'kode'     => 'CPMK 3',
                            'urutan'   => 3,
                            'deskripsi'=> 'Mampu mengevaluasi pemanfaatan teknologi informasi dalam proses bisnis organisasi untuk menciptakan inovasi dan peningkatan kinerja manajerial.',
                            'cpls'     => [[1, 25.00], [6, 14.00]],
                        ],
                    ],
                    // CPMK1→[Sub1,Sub2,Sub3]  CPMK2→[Sub4,Sub5,Sub8]  CPMK3→[Sub6,Sub7]
                    'subs' => [
                        [1, 1, 1, 'Menjelaskan hubungan antara data, informasi, dan sistem informasi dalam organisasi',                                     'C2', ['A2', 'C2']],
                        [1, 2, 2, 'Menguraikan komponen utama sistem informasi manajemen',                                                                  'C2', ['A2', 'C2']],
                        [1, 3, 3, 'Menjelaskan fungsi dan peranan sistem informasi manajemen dalam mendukung proses dan strategi organisasi',                'C3', ['A3', 'C3']],
                        [2, 1, 4, 'Menganalisis kebutuhan informasi pada fungsi-fungsi manajerial',                                                        'C4', ['A3', 'C4', 'P3']],
                        [2, 2, 5, 'Memetakan proses bisnis organisasi untuk identifikasi kebutuhan sistem informasi',                                       'C4', ['A3', 'C4', 'P3']],
                        [3, 1, 6, 'Mengevaluasi efektivitas pemanfaatan teknologi informasi dalam proses bisnis organisasi',                                'C5', ['A4', 'C5']],
                        [3, 2, 7, 'Memberikan rekomendasi inovasi berbasis teknologi informasi untuk meningkatkan kinerja organisasi',                      'C5', ['A4', 'C5', 'P3']],
                        [2, 3, 8, 'Merancang model sistem informasi yang relevan dengan kebutuhan dan permasalahan manajerial organisasi',                  'C6', ['A4', 'C6', 'P4']],
                    ],
                ],

                // ── RPS066 (mk_id=66) ──────────────────────────────────────────────────────
                66 => [
                    'cpmks' => [
                        [
                            'kode'     => 'CPMK 1',
                            'urutan'   => 1,
                            'deskripsi'=> 'Memahami konsep dasar manajemen konten serta peranannya dalam strategi komunikasi dan pemasaran digital.',
                            'cpls'     => [[5, 15.00]],
                        ],
                        [
                            'kode'     => 'CPMK 2',
                            'urutan'   => 2,
                            'deskripsi'=> 'Merancang strategi konten kreatif yang relevan dengan perilaku audiens dan perkembangan teknologi digital.',
                            'cpls'     => [[1, 8.33], [6, 17.00]],
                        ],
                        [
                            'kode'     => 'CPMK 3',
                            'urutan'   => 3,
                            'deskripsi'=> 'Menilai efektivitas kinerja konten melalui analisis data, metrik digital, dan indikator keberhasilan komunikasi.',
                            'cpls'     => [[6, 8.00]],
                        ],
                        [
                            'kode'     => 'CPMK 4',
                            'urutan'   => 4,
                            'deskripsi'=> 'Menginternalisasi nilai etika, tanggung jawab, dan profesionalisme dalam pengelolaan dan komunikasi konten digital.',
                            'cpls'     => [[8, 25.00]],
                        ],
                    ],
                    // CPMK1→[Sub1,Sub2]  CPMK2→[Sub3,Sub4]  CPMK3→[Sub5,Sub6]  CPMK4→[Sub7,Sub8]
                    'subs' => [
                        [1, 1, 1, 'Menjelaskan prinsip dasar manajemen konten digital dan fungsinya dalam komunikasi pemasaran.',                           'C2', ['A2', 'C2']],
                        [1, 2, 2, 'Mengidentifikasi elemen konten yang membentuk daya tarik serta efektivitas pesan konten digital.',                       'C3', ['A2', 'C3']],
                        [2, 1, 3, 'Menganalisis kebutuhan audiens dan tren digital dalam perencanaan konten.',                                              'C4', ['A3', 'C4']],
                        [2, 2, 4, 'Menghasilkan rancangan konten kreatif yang menarik dan relevan dengan identitas merek.',                                 'C6', ['A3', 'C6', 'P4']],
                        [3, 1, 5, 'Menganalisis data performa konten dari berbagai platform digital',                                                       'C4', ['A3', 'C4', 'P3']],
                        [3, 2, 6, 'Mengevaluasi kesesuaian desain visual dan pesan komunikasi dengan tujuan strategis.',                                    'C5', ['A3', 'C5', 'P3']],
                        [4, 1, 7, 'Menunjukkan sikap etis dan tanggung jawab dalam produksi serta publikasi konten.',                                       'A5', ['A5', 'P2']],
                        [4, 2, 8, 'Menunjukkan sikap kolaboratif dan profesional dalam proyek pembuatan konten digital.',                                   'A5', ['A5', 'P3']],
                    ],
                ],
            ];

            foreach ($mkData as $mkId => $mk) {
                $cpmkIdMap = []; // cpmk_urutan => cpmk.id

                // Sisipkan CPMK + CpmkCpl
                foreach ($mk['cpmks'] as $c) {
                    $cpmk = Cpmk::create([
                        'mata_kuliah_id' => $mkId,
                        'kode'           => $c['kode'],
                        'urutan'         => $c['urutan'],
                        'deskripsi'      => $c['deskripsi'],
                    ]);
                    $cpmkIdMap[$c['urutan']] = $cpmk->id;

                    foreach ($c['cpls'] as [$cplUrutan, $bobot]) {
                        CpmkCpl::create([
                            'cpmk_id'     => $cpmk->id,
                            'cpl_id'      => $cplUrutan, // urutan == id pada prodi ini
                            'has_relation' => true,
                            'bobot_persen' => $bobot,
                        ]);
                    }
                }

                // Sisipkan SubCpmk + SubCpmkClo
                foreach ($mk['subs'] as [$cpmkUrutan, $relUrutan, $globalUrutan, $deskripsi, $tertinggi, $cloKodes]) {
                    $sub = SubCpmk::create([
                        'cpmk_id'          => $cpmkIdMap[$cpmkUrutan],
                        'mata_kuliah_id'   => $mkId,
                        'kode'             => "Sub-CPMK {$cpmkUrutan}.{$relUrutan}",
                        'deskripsi'        => $deskripsi,
                        'kode_clo_tertinggi' => $tertinggi,
                        'urutan'           => $globalUrutan,
                    ]);

                    foreach ($cloKodes as $kodeC) {
                        SubCpmkClo::create([
                            'sub_cpmk_id' => $sub->id,
                            'clo_id'      => $clo[$kodeC],
                            'is_tertinggi' => ($kodeC === $tertinggi),
                        ]);
                    }
                }
            }
        });

        $this->command->info('Import selesai: 10 CPMK + 22 Sub-CPMK untuk RPS011, RPS036, RPS066.');
    }
}
