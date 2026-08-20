<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodiSI = Prodi::where('kode_prodi', 'SI')->first();
        $prodiId = $prodiSI ? $prodiSI->id : null;

        // 1. Admin / Koordinator Magang Prodi SI
        User::updateOrCreate(
            ['email' => 'adminprodi@radenintan.ac.id'],
            [
                'name' => 'Koordinator Magang SI',
                'nip' => '198501012010121001',
                'password' => Hash::make('password'),
                'role' => 'admin', 
                'prodi_id' => $prodiId,
                'is_active' => true,
            ]
        );

        // 2. Dosen Pembimbing Lapangan (DPL)
        User::updateOrCreate(
            ['email' => 'dpl@radenintan.ac.id'],
            [
                'name' => 'Dosen Pembimbing Lapangan, M.Kom.',
                'nip' => '198802022015041002',
                'password' => Hash::make('password'),
                'role' => 'dpl',
                'prodi_id' => $prodiId,
                'is_active' => true,
            ]
        );

        // 3. Mahasiswa Sistem Informasi
        User::updateOrCreate(
            ['email' => 'mahasiswa@radenintan.ac.id'],
            [
                'name' => 'FREDLI FOURQONI',
                'nim' => '2271020103',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'prodi_id' => $prodiId,
                'total_sks' => 142,
                'ipk' => 3.75,
                'is_active' => true,
            ]
        );
    }
}