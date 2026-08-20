<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = [
            ['nama_prodi' => 'Sistem Informasi', 'kode_prodi' => 'SI'],
            ['nama_prodi' => 'Biologi', 'kode_prodi' => 'BIO'],
            ['nama_prodi' => 'Kimia', 'kode_prodi' => 'KIM'],
            ['nama_prodi' => 'Fisika', 'kode_prodi' => 'FIS'],
        ];

        foreach ($prodis as $prodi) {
            \App\Models\Prodi::create($prodi);
        }
    }
}
