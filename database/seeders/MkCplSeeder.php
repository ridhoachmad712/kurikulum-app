<?php
namespace Database\Seeders;

use App\Models\Cpl;
use App\Models\MataKuliah;
use App\Models\MkCpl;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MkCplSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Update SKS dari data acuan Excel
        $sks = [
            'RPS001'=>2,'RPS002'=>2,'RPS003'=>2,'RPS004'=>2,'RPS005'=>2,
            'RPS006'=>2,'RPS007'=>2,'RPS008'=>2,'RPS009'=>2,'RPS010'=>2,
            'RPS011'=>3,'RPS012'=>3,'RPS013'=>2,'RPS014'=>2,'RPS015'=>3,
            'RPS016'=>2,'RPS017'=>2,'RPS018'=>3,'RPS019'=>3,'RPS020'=>3,
            'RPS021'=>3,'RPS022'=>3,'RPS023'=>3,'RPS024'=>3,'RPS025'=>3,
            'RPS026'=>3,'RPS027'=>3,'RPS028'=>3,'RPS029'=>3,'RPS030'=>3,
            'RPS031'=>3,'RPS032'=>3,'RPS033'=>3,'RPS034'=>3,'RPS035'=>3,
            'RPS036'=>3,'RPS037'=>3,'RPS038'=>3,'RPS039'=>3,'RPS040'=>3,
            'RPS041'=>3,'RPS042'=>3,'RPS043'=>3,'RPS044'=>3,'RPS045'=>3,
            'RPS046'=>3,'RPS047'=>3,'RPS048'=>3,'RPS049'=>3,'RPS050'=>3,
            'RPS051'=>3,'RPS052'=>3,'RPS053'=>3,'RPS054'=>3,'RPS055'=>3,
            'RPS056'=>3,'RPS057'=>3,'RPS058'=>3,'RPS059'=>3,'RPS060'=>3,
            'RPS061'=>3,'RPS062'=>3,'RPS063'=>3,'RPS064'=>3,'RPS065'=>3,
            'RPS066'=>3,'RPS067'=>3,'RPS068'=>3,'RPS069'=>3,'RPS070'=>3,
            'RPS071'=>3,'RPS072'=>3,'RPS073'=>3,'RPS074'=>3,'RPS075'=>3,
            'RPS076'=>3,'RPS077'=>3,'RPS078'=>3,'RPS079'=>3,'RPS080'=>3,
            'RPS081'=>3,'RPS082'=>3,'RPS083'=>3,'RPS084'=>3,'RPS085'=>3,
            'RPS086'=>3,'RPS087'=>4,'RPS088'=>6,
        ];

        foreach ($sks as $kode => $nilaiSks) {
            MataKuliah::where('kode_rps', $kode)->update(['sks' => $nilaiSks]);
        }

        // 2. Import level keterkaitan MK–CPL
        $mkCpls = [
            ['RPS001','CPL1','Tinggi'],['RPS001','CPL5','Rendah'],['RPS001','CPL7','Rendah'],
            ['RPS002','CPL3','Sedang'],['RPS002','CPL5','Rendah'],['RPS002','CPL7','Tinggi'],
            ['RPS003','CPL3','Sedang'],['RPS003','CPL5','Sedang'],['RPS003','CPL7','Tinggi'],
            ['RPS004','CPL3','Tinggi'],['RPS004','CPL5','Sedang'],['RPS004','CPL7','Tinggi'],
            ['RPS005','CPL3','Tinggi'],['RPS005','CPL5','Sedang'],['RPS005','CPL7','Tinggi'],
            ['RPS006','CPL3','Tinggi'],['RPS006','CPL5','Sedang'],['RPS006','CPL7','Tinggi'],
            ['RPS008','CPL2','Tinggi'],['RPS008','CPL3','Tinggi'],['RPS008','CPL5','Rendah'],['RPS008','CPL7','Sedang'],
            ['RPS009','CPL2','Tinggi'],['RPS009','CPL3','Tinggi'],['RPS009','CPL5','Rendah'],['RPS009','CPL7','Sedang'],
            ['RPS010','CPL3','Rendah'],['RPS010','CPL5','Tinggi'],['RPS010','CPL7','Tinggi'],
            ['RPS011','CPL1','Tinggi'],['RPS011','CPL2','Sedang'],
            ['RPS012','CPL2','Tinggi'],['RPS012','CPL3','Sedang'],['RPS012','CPL9','Tinggi'],
            ['RPS013','CPL3','Sedang'],['RPS013','CPL5','Tinggi'],['RPS013','CPL7','Tinggi'],
            ['RPS014','CPL1','Rendah'],['RPS014','CPL2','Sedang'],['RPS014','CPL4','Tinggi'],['RPS014','CPL8','Rendah'],['RPS014','CPL9','Tinggi'],
            ['RPS015','CPL2','Rendah'],['RPS015','CPL4','Tinggi'],['RPS015','CPL6','Sedang'],['RPS015','CPL8','Sedang'],
            ['RPS016','CPL4','Tinggi'],['RPS016','CPL6','Sedang'],['RPS016','CPL8','Tinggi'],
            ['RPS017','CPL2','Tinggi'],['RPS017','CPL5','Sedang'],['RPS017','CPL8','Sedang'],['RPS017','CPL9','Tinggi'],
            ['RPS018','CPL1','Tinggi'],['RPS018','CPL5','Sedang'],['RPS018','CPL7','Tinggi'],
            ['RPS019','CPL1','Sedang'],['RPS019','CPL4','Tinggi'],['RPS019','CPL6','Sedang'],['RPS019','CPL8','Tinggi'],['RPS019','CPL9','Rendah'],
            ['RPS020','CPL1','Sedang'],['RPS020','CPL4','Tinggi'],['RPS020','CPL6','Sedang'],['RPS020','CPL8','Tinggi'],
            ['RPS021','CPL2','Rendah'],['RPS021','CPL4','Sedang'],['RPS021','CPL5','Sedang'],['RPS021','CPL6','Tinggi'],['RPS021','CPL8','Tinggi'],['RPS021','CPL9','Sedang'],
            ['RPS022','CPL3','Sedang'],['RPS022','CPL4','Tinggi'],['RPS022','CPL5','Sedang'],['RPS022','CPL8','Tinggi'],
            ['RPS023','CPL1','Sedang'],['RPS023','CPL4','Tinggi'],['RPS023','CPL5','Rendah'],['RPS023','CPL6','Sedang'],['RPS023','CPL8','Tinggi'],
            ['RPS024','CPL1','Sedang'],['RPS024','CPL4','Tinggi'],['RPS024','CPL6','Sedang'],['RPS024','CPL8','Tinggi'],['RPS024','CPL10','Rendah'],
            ['RPS025','CPL1','Sedang'],['RPS025','CPL4','Tinggi'],['RPS025','CPL8','Rendah'],['RPS025','CPL10','Rendah'],
            ['RPS026','CPL1','Tinggi'],['RPS026','CPL3','Sedang'],['RPS026','CPL4','Sedang'],['RPS026','CPL10','Tinggi'],
            ['RPS027','CPL4','Rendah'],['RPS027','CPL6','Sedang'],['RPS027','CPL8','Tinggi'],['RPS027','CPL10','Tinggi'],
            ['RPS028','CPL3','Tinggi'],['RPS028','CPL4','Sedang'],['RPS028','CPL5','Tinggi'],['RPS028','CPL8','Rendah'],
            ['RPS029','CPL2','Tinggi'],['RPS029','CPL3','Tinggi'],['RPS029','CPL6','Rendah'],['RPS029','CPL9','Sedang'],
            ['RPS030','CPL4','Tinggi'],['RPS030','CPL6','Sedang'],['RPS030','CPL8','Tinggi'],['RPS030','CPL9','Rendah'],
            ['RPS031','CPL1','Sedang'],['RPS031','CPL6','Sedang'],['RPS031','CPL8','Tinggi'],['RPS031','CPL10','Tinggi'],
            ['RPS032','CPL2','Rendah'],['RPS032','CPL4','Tinggi'],['RPS032','CPL6','Sedang'],['RPS032','CPL8','Tinggi'],
            ['RPS033','CPL2','Rendah'],['RPS033','CPL4','Sedang'],['RPS033','CPL8','Sedang'],['RPS033','CPL9','Tinggi'],
            ['RPS034','CPL4','Tinggi'],['RPS034','CPL6','Sedang'],['RPS034','CPL8','Tinggi'],['RPS034','CPL10','Rendah'],
            ['RPS035','CPL3','Tinggi'],['RPS035','CPL4','Tinggi'],['RPS035','CPL6','Sedang'],['RPS035','CPL8','Tinggi'],
            ['RPS036','CPL1','Sedang'],['RPS036','CPL4','Sedang'],['RPS036','CPL6','Tinggi'],['RPS036','CPL8','Rendah'],
            ['RPS037','CPL4','Tinggi'],['RPS037','CPL7','Sedang'],['RPS037','CPL8','Tinggi'],
            ['RPS038','CPL4','Tinggi'],['RPS038','CPL5','Rendah'],['RPS038','CPL6','Sedang'],['RPS038','CPL8','Tinggi'],
            ['RPS039','CPL3','Rendah'],['RPS039','CPL4','Tinggi'],['RPS039','CPL9','Sedang'],
            ['RPS040','CPL4','Tinggi'],['RPS040','CPL6','Sedang'],['RPS040','CPL8','Tinggi'],['RPS040','CPL9','Sedang'],
            ['RPS041','CPL1','Sedang'],['RPS041','CPL4','Sedang'],['RPS041','CPL5','Rendah'],['RPS041','CPL6','Sedang'],['RPS041','CPL8','Tinggi'],['RPS041','CPL10','Tinggi'],
            ['RPS042','CPL4','Sedang'],['RPS042','CPL6','Sedang'],['RPS042','CPL8','Sedang'],['RPS042','CPL9','Tinggi'],['RPS042','CPL10','Tinggi'],
            ['RPS043','CPL4','Sedang'],['RPS043','CPL8','Tinggi'],['RPS043','CPL9','Tinggi'],
            ['RPS044','CPL1','Rendah'],['RPS044','CPL2','Tinggi'],['RPS044','CPL4','Tinggi'],['RPS044','CPL6','Sedang'],
            ['RPS045','CPL2','Rendah'],['RPS045','CPL4','Tinggi'],['RPS045','CPL6','Sedang'],['RPS045','CPL8','Tinggi'],
            ['RPS046','CPL4','Tinggi'],['RPS046','CPL6','Sedang'],['RPS046','CPL8','Sedang'],['RPS046','CPL9','Tinggi'],
            ['RPS047','CPL4','Tinggi'],['RPS047','CPL6','Sedang'],['RPS047','CPL7','Rendah'],['RPS047','CPL8','Tinggi'],
            ['RPS048','CPL2','Rendah'],['RPS048','CPL4','Tinggi'],['RPS048','CPL6','Sedang'],['RPS048','CPL8','Tinggi'],
            ['RPS049','CPL3','Sedang'],['RPS049','CPL4','Tinggi'],['RPS049','CPL7','Tinggi'],['RPS049','CPL10','Rendah'],
            ['RPS050','CPL2','Tinggi'],['RPS050','CPL4','Tinggi'],['RPS050','CPL6','Rendah'],['RPS050','CPL8','Sedang'],
            ['RPS051','CPL2','Tinggi'],['RPS051','CPL4','Sedang'],['RPS051','CPL6','Sedang'],['RPS051','CPL9','Tinggi'],
            ['RPS052','CPL4','Tinggi'],['RPS052','CPL7','Sedang'],['RPS052','CPL8','Tinggi'],['RPS052','CPL9','Sedang'],
            ['RPS053','CPL4','Sedang'],['RPS053','CPL6','Tinggi'],['RPS053','CPL7','Rendah'],['RPS053','CPL8','Tinggi'],
            ['RPS054','CPL1','Sedang'],['RPS054','CPL2','Sedang'],['RPS054','CPL6','Tinggi'],['RPS054','CPL8','Rendah'],
            ['RPS055','CPL4','Tinggi'],['RPS055','CPL6','Rendah'],['RPS055','CPL7','Sedang'],['RPS055','CPL8','Tinggi'],
            ['RPS056','CPL1','Tinggi'],['RPS056','CPL4','Sedang'],['RPS056','CPL6','Tinggi'],['RPS056','CPL8','Sedang'],['RPS056','CPL9','Sedang'],
            ['RPS057','CPL1','Tinggi'],['RPS057','CPL2','Tinggi'],['RPS057','CPL6','Sedang'],['RPS057','CPL8','Sedang'],
            ['RPS058','CPL4','Sedang'],['RPS058','CPL5','Sedang'],['RPS058','CPL8','Sedang'],['RPS058','CPL10','Tinggi'],
            ['RPS059','CPL1','Rendah'],['RPS059','CPL6','Tinggi'],['RPS059','CPL9','Sedang'],
            ['RPS060','CPL2','Sedang'],['RPS060','CPL4','Sedang'],['RPS060','CPL6','Tinggi'],['RPS060','CPL8','Tinggi'],
            ['RPS061','CPL2','Tinggi'],['RPS061','CPL4','Tinggi'],['RPS061','CPL8','Sedang'],['RPS061','CPL9','Sedang'],
            ['RPS062','CPL1','Rendah'],['RPS062','CPL4','Tinggi'],['RPS062','CPL6','Sedang'],['RPS062','CPL8','Tinggi'],
            ['RPS063','CPL2','Tinggi'],['RPS063','CPL4','Tinggi'],['RPS063','CPL6','Sedang'],['RPS063','CPL9','Tinggi'],
            ['RPS064','CPL4','Sedang'],['RPS064','CPL5','Tinggi'],['RPS064','CPL6','Sedang'],['RPS064','CPL8','Rendah'],['RPS064','CPL9','Sedang'],
            ['RPS065','CPL4','Tinggi'],['RPS065','CPL5','Sedang'],['RPS065','CPL6','Sedang'],['RPS065','CPL8','Rendah'],
            ['RPS066','CPL1','Rendah'],['RPS066','CPL5','Tinggi'],['RPS066','CPL6','Sedang'],['RPS066','CPL8','Sedang'],
            ['RPS067','CPL3','Rendah'],['RPS067','CPL4','Tinggi'],['RPS067','CPL5','Sedang'],['RPS067','CPL7','Sedang'],['RPS067','CPL8','Sedang'],
            ['RPS068','CPL4','Tinggi'],['RPS068','CPL5','Sedang'],['RPS068','CPL6','Tinggi'],['RPS068','CPL9','Sedang'],
            ['RPS069','CPL2','Tinggi'],['RPS069','CPL4','Tinggi'],['RPS069','CPL5','Sedang'],['RPS069','CPL6','Sedang'],['RPS069','CPL9','Tinggi'],
            ['RPS070','CPL2','Sedang'],['RPS070','CPL4','Tinggi'],['RPS070','CPL6','Rendah'],['RPS070','CPL8','Tinggi'],
            ['RPS071','CPL2','Tinggi'],['RPS071','CPL4','Sedang'],['RPS071','CPL6','Tinggi'],['RPS071','CPL8','Rendah'],
            ['RPS072','CPL4','Tinggi'],['RPS072','CPL5','Rendah'],['RPS072','CPL8','Sedang'],['RPS072','CPL10','Tinggi'],
            ['RPS073','CPL4','Tinggi'],['RPS073','CPL5','Sedang'],['RPS073','CPL6','Rendah'],['RPS073','CPL8','Tinggi'],
            ['RPS074','CPL3','Rendah'],['RPS074','CPL4','Rendah'],['RPS074','CPL7','Sedang'],['RPS074','CPL8','Tinggi'],
            ['RPS075','CPL4','Tinggi'],['RPS075','CPL5','Sedang'],['RPS075','CPL6','Tinggi'],['RPS075','CPL8','Sedang'],
            ['RPS076','CPL3','Rendah'],['RPS076','CPL5','Sedang'],['RPS076','CPL7','Sedang'],['RPS076','CPL8','Tinggi'],
            ['RPS077','CPL5','Rendah'],['RPS077','CPL6','Sedang'],['RPS077','CPL7','Tinggi'],['RPS077','CPL8','Tinggi'],
            ['RPS078','CPL2','Rendah'],['RPS078','CPL3','Tinggi'],['RPS078','CPL4','Tinggi'],['RPS078','CPL5','Sedang'],['RPS078','CPL8','Sedang'],
            ['RPS079','CPL2','Tinggi'],['RPS079','CPL4','Rendah'],['RPS079','CPL7','Sedang'],['RPS079','CPL8','Tinggi'],
            ['RPS080','CPL4','Tinggi'],['RPS080','CPL6','Tinggi'],['RPS080','CPL7','Rendah'],['RPS080','CPL8','Rendah'],
            ['RPS081','CPL3','Tinggi'],['RPS081','CPL4','Tinggi'],['RPS081','CPL5','Rendah'],['RPS081','CPL6','Rendah'],['RPS081','CPL8','Tinggi'],
            ['RPS082','CPL4','Tinggi'],['RPS082','CPL6','Sedang'],['RPS082','CPL8','Tinggi'],['RPS082','CPL10','Rendah'],
            ['RPS083','CPL3','Tinggi'],['RPS083','CPL4','Tinggi'],['RPS083','CPL6','Sedang'],['RPS083','CPL7','Rendah'],['RPS083','CPL8','Sedang'],
            ['RPS084','CPL3','Sedang'],['RPS084','CPL4','Sedang'],['RPS084','CPL5','Rendah'],['RPS084','CPL7','Tinggi'],['RPS084','CPL8','Tinggi'],
            ['RPS085','CPL2','Tinggi'],['RPS085','CPL4','Rendah'],['RPS085','CPL7','Sedang'],['RPS085','CPL8','Tinggi'],
            ['RPS086','CPL4','Tinggi'],['RPS086','CPL5','Rendah'],['RPS086','CPL8','Tinggi'],['RPS086','CPL10','Sedang'],
            ['RPS087','CPL1','Rendah'],['RPS087','CPL3','Sedang'],['RPS087','CPL4','Tinggi'],['RPS087','CPL5','Sedang'],['RPS087','CPL8','Tinggi'],
            ['RPS088','CPL4','Sedang'],['RPS088','CPL5','Sedang'],['RPS088','CPL6','Rendah'],['RPS088','CPL8','Tinggi'],['RPS088','CPL10','Tinggi'],
        ];

        // Ambil semua MK dan CPL dalam satu query
        $mks  = MataKuliah::pluck('id', 'kode_rps');
        $cpls = Cpl::pluck('id', 'kode');

        $now  = now();
        $rows = [];

        foreach ($mkCpls as [$kodeRps, $kodeCpl, $level]) {
            $mkId  = $mks[$kodeRps]  ?? null;
            $cplId = $cpls[$kodeCpl] ?? null;
            if (!$mkId || !$cplId) continue;

            $rows[] = [
                'mata_kuliah_id' => $mkId,
                'cpl_id'         => $cplId,
                'level'          => $level,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        // Upsert agar aman dijalankan ulang
        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('mk_cpls')->upsert(
                $chunk,
                ['mata_kuliah_id', 'cpl_id'],
                ['level', 'updated_at']
            );
        }

        $this->command->info('MkCplSeeder: ' . count($rows) . ' relasi MK-CPL berhasil di-import.');
    }
}
