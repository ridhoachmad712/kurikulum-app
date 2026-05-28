<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMenuGroups(): array
    {
        $user = auth()->user();
        if (! $user) {
            return [];
        }

        if ($user->hasAnyRole(['admin', 'kaprodi'])) {
            return static::adminMenu();
        }

        if ($user->hasRole('dosen')) {
            return static::dosenMenu();
        }

        return [];
    }

    // ─── Admin / Kaprodi ──────────────────────────────────────────────────────

    private static function adminMenu(): array
    {
        $tabelActive = request()->routeIs('kurikulum.matriks.tabel*')
            || request()->routeIs('kurikulum.cpmk.tabel*')
            || request()->routeIs('kurikulum.sub-cpmk.tabel*');

        return [
            [
                'title' => 'Main',
                'items' => [
                    ['name' => 'Dashboard',          'path' => route('dashboard'),                    'icon' => 'home',             'active' => request()->routeIs('dashboard')],
                ],
            ],
            [
                'title' => 'Administrasi',
                'items' => [
                    ['name' => 'Status Kelengkapan', 'path' => route('kelengkapan'),                  'icon' => 'clipboard-check',  'active' => request()->routeIs('kelengkapan')],
                    ['name' => 'Kelola Akun',         'path' => route('admin.users.index'),            'icon' => 'users',            'active' => request()->routeIs('admin.users.*')],
                    ['name' => 'Penugasan Dosen',     'path' => route('admin.penugasan.index'),        'icon' => 'clipboard',        'active' => request()->routeIs('admin.penugasan.*')],
                    ['name' => 'Hak Akses',           'path' => route('admin.hak-akses'),              'icon' => 'shield-check',     'active' => request()->routeIs('admin.hak-akses')],
                ],
            ],
            [
                'title' => 'Master Data',
                'items' => [
                    ['name' => 'Program Studi',      'path' => route('master.program-studi.index'),   'icon' => 'building-library', 'active' => request()->routeIs('master.program-studi.*')],
                    ['name' => 'Profil Lulusan',     'path' => route('master.profil-lulusan.index'),  'icon' => 'user-group',       'active' => request()->routeIs('master.profil-lulusan.*')],
                    ['name' => 'CPL',                 'path' => route('master.cpl.index'),             'icon' => 'badge-check',      'active' => request()->routeIs('master.cpl.*')],
                    ['name' => 'Mata Kuliah',         'path' => route('master.mata-kuliah.index'),     'icon' => 'book-open',        'active' => request()->routeIs('master.mata-kuliah.*')],
                    ['name' => 'Bahan Kajian',        'path' => route('master.bahan-kajian.index'),    'icon' => 'document-text',    'active' => request()->routeIs('master.bahan-kajian.*')],
                    ['name' => 'CLO Taxonomy',        'path' => route('master.clo-taxonomi.index'),    'icon' => 'tag',              'active' => request()->routeIs('master.clo-taxonomi.*')],
                ],
            ],
            [
                'title' => 'Laporan',
                'items' => [
                    ['name' => 'Tabel Kurikulum',    'path' => route('kurikulum.matriks.tabel1'),     'icon' => 'table-cells',      'active' => $tabelActive],
                    ['name' => 'RPS',                 'path' => route('kurikulum.rps.index'),          'icon' => 'document',         'active' => request()->routeIs('kurikulum.rps.*')],
                    ['name' => 'Halaman Publik',      'path' => route('public.home'),                  'icon' => 'arrow-top-right',  'active' => false, 'external' => true],
                ],
            ],
        ];
    }

    // ─── Dosen ────────────────────────────────────────────────────────────────

    private static function dosenMenu(): array
    {
        $tabelActive = request()->routeIs('kurikulum.matriks.tabel*')
            || request()->routeIs('kurikulum.cpmk.tabel*')
            || request()->routeIs('kurikulum.sub-cpmk.tabel*');

        $cpmkActive = request()->routeIs('kurikulum.cpmk.index')
            || request()->routeIs('kurikulum.cpmk.show');

        $subActive = request()->routeIs('kurikulum.sub-cpmk.index')
            || request()->routeIs('kurikulum.sub-cpmk.show');

        return [
            [
                'title' => 'Main',
                'items' => [
                    ['name' => 'Dashboard',          'path' => route('dashboard'),                    'icon' => 'home',             'active' => request()->routeIs('dashboard')],
                ],
            ],
            [
                'title' => 'Input',
                'items' => [
                    ['name' => 'Level CPL MK',       'path' => route('kurikulum.matriks.input'),      'icon' => 'table-cells',      'active' => request()->routeIs('kurikulum.matriks.input')],
                    ['name' => 'CPMK',                'path' => route('kurikulum.cpmk.index'),         'icon' => 'clipboard-list',   'active' => $cpmkActive],
                    ['name' => 'Sub CPMK',            'path' => route('kurikulum.sub-cpmk.index'),     'icon' => 'list-bullet',      'active' => $subActive],
                    ['name' => 'Bobot Asesmen',       'path' => route('kurikulum.asesmen.index'),      'icon' => 'calculator',       'active' => request()->routeIs('kurikulum.asesmen.*')],
                    ['name' => 'Pengukuran',          'path' => route('kurikulum.pengukuran.cpmk'),    'icon' => 'chart-bar',        'active' => request()->routeIs('kurikulum.pengukuran.cpmk*')],
                ],
            ],
            [
                'title' => 'Rekap',
                'items' => [
                    ['name' => 'Tabel Kurikulum',    'path' => route('kurikulum.matriks.tabel1'),     'icon' => 'table-cells',      'active' => $tabelActive],
                    ['name' => 'Hasil Pengukuran',   'path' => route('kurikulum.pengukuran.cpl'),     'icon' => 'chart-pie',        'active' => request()->routeIs('kurikulum.pengukuran.cpl')],
                    ['name' => 'RPS',                 'path' => route('kurikulum.rps.index'),          'icon' => 'document',         'active' => request()->routeIs('kurikulum.rps.*')],
                ],
            ],
        ];
    }

    // ─── Icon SVG ─────────────────────────────────────────────────────────────

    public static function getIconSvg(string $name): string
    {
        $paths = [
            'home' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
            'clipboard-check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
            'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>',
            'clipboard' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
            'shield-check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>',
            'building-library' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/>',
            'user-group' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>',
            'badge-check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-.723 3.065 3.745 3.745 0 01-3.065.723 3.745 3.745 0 01-3.068 1.593 3.745 3.745 0 01-3.068-1.593 3.745 3.745 0 01-3.065-.723 3.745 3.745 0 01-.723-3.065A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 01.723-3.065 3.745 3.745 0 013.065-.723A3.745 3.745 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.065.723 3.746 3.746 0 01.723 3.065A3.745 3.745 0 0121 12z"/>',
            'book-open' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
            'document-text' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>',
            'tag' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>',
            'table-cells' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25"/>',
            'document' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
            'arrow-top-right' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>',
            'clipboard-list' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
            'list-bullet' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>',
            'calculator' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0012 2.25z"/>',
            'chart-bar' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>',
            'chart-pie' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/>',
        ];

        $path = $paths[$name] ?? $paths['document'];

        return '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">'
            . $path
            . '</svg>';
    }
}
