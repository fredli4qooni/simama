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

        User::updateOrCreate(
            ['email' => 'adminfakultas@radenintan.ac.id'],
            [
                'name' => 'Admin Fakultas Saintek',
                'password' => Hash::make('password'),
                'role' => 'admin_fakultas',
                'prodi_id' => null,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'adminprodi@radenintan.ac.id'],
            [
                'name' => 'Admin Prodi SI',
                'password' => Hash::make('password'),
                'role' => 'admin', 
                'prodi_id' => $prodiId,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'dpl@radenintan.ac.id'],
            [
                'name' => 'Bapak/Ibu DPL',
                'password' => Hash::make('password'),
                'role' => 'dpl',
                'prodi_id' => $prodiId,
                'is_active' => true,
            ]
        );

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