<?php

namespace App\Imports;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Import akun massal dari Excel.
 * Kolom yang dikenali (baris pertama = header): nama, email, role, program_studi, password, nip
 * - email   : wajib & unik (kunci updateOrCreate)
 * - role    : super_admin | admin | kaprodi | dosen (default: dosen)
 * - program_studi : kode atau nama prodi (default: prodi pertama)
 * - password: opsional (default: 'password') — hanya diset untuk akun BARU
 * - nip     : opsional
 */
class UserImport implements ToCollection, WithHeadingRow
{
    public array $result = ['created' => 0, 'updated' => 0, 'skipped' => []];

    private array $prodiByKode = [];
    private array $prodiByNama = [];
    private ?int  $defaultProdiId = null;

    private const VALID_ROLES = ['super_admin', 'admin', 'kaprodi', 'dosen'];

    public function __construct()
    {
        foreach (ProgramStudi::all() as $p) {
            $this->prodiByKode[mb_strtolower(trim($p->kode))] = $p->id;
            $this->prodiByNama[mb_strtolower(trim($p->nama))] = $p->id;
        }
        $this->defaultProdiId = ProgramStudi::min('id');
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $i => $row) {
            $line  = $i + 2; // +2: header + index 0
            $email = trim((string) ($row['email'] ?? ''));
            $nama  = trim((string) ($row['nama'] ?? ''));

            if ($email === '' && $nama === '') {
                continue; // baris kosong
            }
            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->result['skipped'][] = "Baris {$line}: email kosong/tidak valid";
                continue;
            }
            if ($nama === '') {
                $this->result['skipped'][] = "Baris {$line}: nama kosong ({$email})";
                continue;
            }

            // Role
            $role = mb_strtolower(trim((string) ($row['role'] ?? 'dosen'))) ?: 'dosen';
            if (! in_array($role, self::VALID_ROLES)) {
                $this->result['skipped'][] = "Baris {$line}: role '{$role}' tidak valid ({$email})";
                continue;
            }

            // Program studi
            $prodiRaw = mb_strtolower(trim((string) ($row['program_studi'] ?? '')));
            $prodiId  = $this->prodiByKode[$prodiRaw] ?? $this->prodiByNama[$prodiRaw] ?? $this->defaultProdiId;
            if (! $prodiId) {
                $this->result['skipped'][] = "Baris {$line}: program studi tidak ditemukan ({$email})";
                continue;
            }

            $existing = User::where('email', $email)->first();

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'              => $nama,
                    'nip'               => trim((string) ($row['nip'] ?? '')) ?: null,
                    'program_studi_id'  => $prodiId,
                    'email_verified_at' => now(),
                ] + ($existing
                    ? []  // jangan timpa password akun lama
                    : ['password' => Hash::make(trim((string) ($row['password'] ?? '')) ?: 'password')]),
            );

            $user->syncRoles($role === 'super_admin' ? ['super_admin', 'admin'] : [$role]);

            $this->result[$existing ? 'updated' : 'created']++;
        }
    }
}
