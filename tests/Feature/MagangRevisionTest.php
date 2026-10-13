<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Prodi;
use App\Models\MagangApplication;
use App\Models\DplAssignment;
use App\Models\Evaluation;
use App\Models\ApplicationDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MagangRevisionTest extends TestCase
{
    use RefreshDatabase;

    private Prodi $prodi;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->prodi = Prodi::create([
            'nama_prodi' => 'Sistem Informasi',
            'kode_prodi' => 'SI',
        ]);
    }

    public function test_konsultasi_route_has_been_removed(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa', 'prodi_id' => $this->prodi->id]);

        $response = $this->actingAs($user)->post('/konsultasi/1', [
            'message' => 'Halo DPL'
        ]);

        $response->assertNotFound();
    }

    public function test_mahasiswa_can_register_magang_mandiri(): void
    {
        Storage::fake('public');

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050011',
            'total_sks' => 115,
            'ipk' => 3.65,
        ]);

        $response = $this->actingAs($mahasiswa)->post(route('mahasiswa.pengajuan.store'), [
            'is_group' => '0',
            'jenis_magang' => 'mandiri',
            'instansi' => 'PT. GoTo Gojek Tokopedia Tbk',
            'alamat' => 'Jakarta Selatan',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'leader_ktm' => UploadedFile::fake()->create('ktm.pdf', 500, 'application/pdf'),
            'leader_transkrip' => UploadedFile::fake()->create('transkrip.pdf', 500, 'application/pdf'),
            'bukti_magang_mandiri' => UploadedFile::fake()->create('surat_penerimaan_msib.pdf', 800, 'application/pdf'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('magang_applications', [
            'user_id' => $mahasiswa->id,
            'jenis_magang' => 'mandiri',
            'is_group' => false,
            'instansi' => 'PT. GoTo Gojek Tokopedia Tbk',
        ]);

        $this->assertDatabaseHas('application_documents', [
            'user_id' => $mahasiswa->id,
            'type' => 'BUKTI_MAGANG_MANDIRI',
        ]);
    }

    public function test_form_submission_with_empty_member_select(): void
    {
        Storage::fake('public');

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050099',
            'total_sks' => 115,
            'ipk' => 3.65,
        ]);

        $response = $this->actingAs($mahasiswa)->post(route('mahasiswa.pengajuan.store'), [
            'is_group' => '1',
            'jenis_magang' => 'prodi',
            'instansi' => 'PT. Telkom',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'leader_ktm' => UploadedFile::fake()->create('ktm.pdf', 500, 'application/pdf'),
            'leader_transkrip' => UploadedFile::fake()->create('transkrip.pdf', 500, 'application/pdf'),
            'member_user_ids' => [''],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('mahasiswa.pengajuan.index'));
    }

    public function test_form_submission_individu_with_empty_member_select_and_jpeg(): void
    {
        Storage::fake('public');

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050098',
            'total_sks' => 115,
            'ipk' => 3.65,
        ]);

        $response = $this->actingAs($mahasiswa)->post(route('mahasiswa.pengajuan.store'), [
            'is_group' => '0',
            'jenis_magang' => 'prodi',
            'instansi' => 'Dinas Pendidikan',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'leader_ktm' => UploadedFile::fake()->create('ktm.jpeg', 800, 'image/jpeg'),
            'leader_transkrip' => UploadedFile::fake()->create('transkrip.jpg', 800, 'image/jpeg'),
            'member_user_ids' => [''],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('mahasiswa.pengajuan.index'));

        $this->assertDatabaseHas('magang_applications', [
            'user_id' => $mahasiswa->id,
            'is_group' => false,
            'jenis_magang' => 'prodi',
        ]);
    }

    public function test_mahasiswa_can_upload_additional_bukti_mandiri(): void
    {
        Storage::fake('public');

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050012',
            'total_sks' => 120,
            'ipk' => 3.75,
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $this->prodi->id,
            'is_group' => false,
            'jenis_magang' => 'mandiri',
            'instansi' => 'PT. Bank Mandiri Tbk',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonths(2)->format('Y-m-d'),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($mahasiswa)->post(route('mahasiswa.pengajuan.bukti_mandiri', $pengajuan->id), [
            'bukti_magang' => UploadedFile::fake()->create('sertifikat_nilai_mitra.pdf', 1024, 'application/pdf'),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('application_documents', [
            'application_id' => $pengajuan->id,
            'user_id' => $mahasiswa->id,
            'type' => 'BUKTI_MAGANG_MANDIRI',
        ]);
    }

    public function test_dpl_evaluates_and_system_calculates_average_score(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050013',
            'total_sks' => 110,
            'ipk' => 3.50,
        ]);

        $dpl = User::factory()->create([
            'role' => 'dpl',
            'prodi_id' => $this->prodi->id,
            'name' => 'Dosen Pembimbing M.Kom',
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $this->prodi->id,
            'is_group' => false,
            'jenis_magang' => 'mandiri',
            'instansi' => 'PT. Telkom Indonesia',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->subMonths(2)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'status' => 'active',
        ]);

        DplAssignment::create([
            'application_id' => $pengajuan->id,
            'dpl_user_id' => $dpl->id,
        ]);

        // DPL menginput nilai: Nilai Instansi = 90, Nilai DPL = 80
        $response = $this->actingAs($dpl)->post(route('dpl.evaluasi.store', $pengajuan->id), [
            'nilai_instansi' => 90.00,
            'nilai_dpl' => 80.00,
            'komentar' => 'Kinerja mahasiswa sangat memuaskan dan berdedikasi tinggi.',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        // Verifikasi perhitungan nilai rata-rata otomatis: (90 + 80) / 2 = 85.00
        $this->assertDatabaseHas('evaluations', [
            'application_id' => $pengajuan->id,
            'nilai_instansi' => 90.00,
            'nilai_dpl' => 80.00,
            'nilai_akhir' => 85.00,
            'komentar' => 'Kinerja mahasiswa sangat memuaskan dan berdedikasi tinggi.',
        ]);

        $pengajuan->refresh();
        $this->assertSame('selesai', $pengajuan->status);
    }

    public function test_mahasiswa_can_download_lembar_penilaian_pdf(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050014',
            'total_sks' => 110,
            'ipk' => 3.50,
        ]);

        $dpl = User::factory()->create([
            'role' => 'dpl',
            'prodi_id' => $this->prodi->id,
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $this->prodi->id,
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'instansi' => 'Dinas Kominfo Provinsi Lampung',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->subMonths(2)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'status' => 'selesai',
        ]);

        DplAssignment::create([
            'application_id' => $pengajuan->id,
            'dpl_user_id' => $dpl->id,
        ]);

        Evaluation::create([
            'application_id' => $pengajuan->id,
            'dpl_user_id' => $dpl->id,
            'nilai_instansi' => 88.00,
            'nilai_dpl' => 86.00,
            'nilai_akhir' => 87.00,
            'komentar' => 'Sangat baik.',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengajuan.lembar_nilai', $pengajuan->id));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_mahasiswa_can_download_panduan_dan_template_laporan_docx(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050015',
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.laporan.template'));

        $response->assertOk();
        $this->assertStringContainsString('Panduan_dan_Template_Laporan_Magang_SI.docx', $response->headers->get('content-disposition'));
    }

    public function test_mahasiswa_can_download_form_penilaian_instansi_docx(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050016',
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.penilaian_instansi.template'));

        $response->assertOk();
        $this->assertStringContainsString('Form_Penilaian_Pembimbing_Instansi_SI.docx', $response->headers->get('content-disposition'));
    }

    public function test_dpl_can_also_download_form_penilaian_instansi_docx(): void
    {
        $dpl = User::factory()->create([
            'role' => 'dpl',
            'prodi_id' => $this->prodi->id,
            'name' => 'Dosen Pembimbing DPL',
        ]);

        $response = $this->actingAs($dpl)->get(route('mahasiswa.penilaian_instansi.template'));

        $response->assertOk();
        $this->assertStringContainsString('Form_Penilaian_Pembimbing_Instansi_SI.docx', $response->headers->get('content-disposition'));
    }

    public function test_surat_pengantar_renders_with_official_kop_and_kaprodi(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050017',
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $this->prodi->id,
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'instansi' => 'PT. Inovasi Lampung',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonths(2)->format('Y-m-d'),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengajuan.surat_pengantar', $pengajuan->id));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_view_rekap_nilai_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'prodi_id' => $this->prodi->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

        $response->assertOk();
        $response->assertSee('Daftar Rekapitulasi Nilai Mahasiswa');
        $response->assertSee('Cetak PDF Laporan');
    }

    public function test_admin_can_download_rekap_nilai_pdf(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'prodi_id' => $this->prodi->id,
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $this->prodi->id,
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'instansi' => 'PT. Rekap Test',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->subMonths(2)->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
            'status' => 'selesai',
        ]);

        Evaluation::create([
            'application_id' => $pengajuan->id,
            'dpl_user_id' => $admin->id,
            'nilai_instansi' => 90.00,
            'nilai_dpl' => 85.00,
            'nilai_akhir' => 87.50,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rekap.cetak'));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_view_approved_applications_in_penetapan_dpl_with_null_or_matching_prodi_id(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'prodi_id' => $this->prodi->id,
        ]);

        $dpl = User::factory()->create([
            'role' => 'dpl',
            'prodi_id' => $this->prodi->id,
            'is_active' => true,
        ]);

        // Mahasiswa with null prodi_id
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => null,
            'name' => 'Udin Null Prodi',
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => null,
            'is_group' => false,
            'jenis_magang' => 'mandiri',
            'instansi' => 'PT. Test Penerimaan',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addMonths(2)->format('Y-m-d'),
            'status' => 'approved',
        ]);

        ApplicationDocument::create([
            'application_id' => $pengajuan->id,
            'user_id' => $mahasiswa->id,
            'type' => 'BUKTI_MAGANG_MANDIRI',
            'file_path' => 'bukti_mandiri/dummy.pdf',
            'uploaded_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.penetapan_dpl.index'));

        $response->assertOk();
        $response->assertSee('PT. Test Penerimaan');
        $response->assertSee('Udin Null Prodi');
        $response->assertSee('Lihat Bukti Penerimaan Mandiri');
    }

    public function test_admin_can_assign_dpl_for_approved_application_with_bukti_mandiri(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'prodi_id' => $this->prodi->id,
        ]);

        $dpl = User::factory()->create([
            'role' => 'dpl',
            'prodi_id' => $this->prodi->id,
            'is_active' => true,
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => null,
            'name' => 'Udin Mandiri',
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => null,
            'is_group' => false,
            'jenis_magang' => 'mandiri',
            'instansi' => 'PT. Mandiri Solusi',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addMonths(2)->format('Y-m-d'),
            'status' => 'approved',
        ]);

        ApplicationDocument::create([
            'application_id' => $pengajuan->id,
            'user_id' => $mahasiswa->id,
            'type' => 'BUKTI_MAGANG_MANDIRI',
            'file_path' => 'bukti_mandiri/dummy.pdf',
            'uploaded_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.penetapan_dpl.store'), [
            'application_id' => $pengajuan->id,
            'dpl_user_id' => $dpl->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dpl_assignments', [
            'application_id' => $pengajuan->id,
            'dpl_user_id' => $dpl->id,
        ]);

        $pengajuan->refresh();
        $this->assertEquals('active', $pengajuan->status);
        $this->assertEquals($this->prodi->id, $pengajuan->prodi_id);
    }

    public function test_mahasiswa_can_view_presensi_page_with_map(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $this->prodi->id,
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'instansi' => 'PT. Inovasi Digital',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->subDay()->format('Y-m-d'),
            'end_date' => now()->addMonths(2)->format('Y-m-d'),
            'status' => 'active',
        ]);

        \App\Models\MagangMember::create([
            'application_id' => $pengajuan->id,
            'user_id' => $mahasiswa->id,
            'is_leader' => true,
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.presensi.index'));

        $response->assertOk();
        $response->assertSee('id="map-presensi"', false);
        $response->assertSee('leaflet.css', false);
        $response->assertSee('leaflet.js', false);
        $response->assertSee('Peta Lokasi Presensi');
    }

    public function test_mahasiswa_can_submit_presensi_with_gps_coordinates(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $this->prodi->id,
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'instansi' => 'PT. Inovasi Digital',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->subDay()->format('Y-m-d'),
            'end_date' => now()->addMonths(2)->format('Y-m-d'),
            'status' => 'active',
        ]);

        \App\Models\MagangMember::create([
            'application_id' => $pengajuan->id,
            'user_id' => $mahasiswa->id,
            'is_leader' => true,
        ]);

        $response = $this->actingAs($mahasiswa)->post(route('mahasiswa.presensi.store'), [
            'type' => 'masuk',
            'status' => 'hadir',
            'latitude' => '-5.38556',
            'longitude' => '105.29664',
            'keterangan' => 'Hadir tepat waktu di kantor',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendances', [
            'application_id' => $pengajuan->id,
            'user_id' => $mahasiswa->id,
            'type' => 'masuk',
            'status' => 'hadir',
            'latitude' => '-5.38556',
            'longitude' => '105.29664',
        ]);
    }
}


