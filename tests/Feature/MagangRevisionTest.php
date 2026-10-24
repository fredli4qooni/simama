<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Prodi;
use App\Models\MagangApplication;
use App\Models\DplAssignment;
use App\Models\Evaluation;
use App\Models\ApplicationDocument;
use App\Models\Logbook;
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

        $this->actingAs($user)->get('/konsultasi')->assertNotFound();
        $this->actingAs($user)->post('/konsultasi/1', ['message' => 'Halo DPL'])->assertNotFound();
        $this->assertFalse(class_exists(\App\Models\Consultation::class));
        $this->assertFalse(method_exists(\App\Models\MagangApplication::class, 'consultations'));
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
            'leader_spp' => UploadedFile::fake()->create('spp.pdf', 500, 'application/pdf'),
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
            'type' => 'SLIP_SPP',
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
            'leader_spp' => UploadedFile::fake()->create('spp.pdf', 500, 'application/pdf'),
            'member_user_ids' => [''],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('mahasiswa.pengajuan.index'));

        $this->assertDatabaseHas('application_documents', [
            'user_id' => $mahasiswa->id,
            'type' => 'SLIP_SPP',
        ]);
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
            'leader_spp' => UploadedFile::fake()->create('spp.jpg', 800, 'image/jpeg'),
            'member_user_ids' => [''],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('mahasiswa.pengajuan.index'));

        $this->assertDatabaseHas('magang_applications', [
            'user_id' => $mahasiswa->id,
            'is_group' => false,
            'jenis_magang' => 'prodi',
        ]);

        $this->assertDatabaseHas('application_documents', [
            'user_id' => $mahasiswa->id,
            'type' => 'SLIP_SPP',
        ]);
    }

    public function test_form_submission_kelompok_with_member_spp(): void
    {
        Storage::fake('public');

        $leader = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050091',
            'total_sks' => 120,
            'ipk' => 3.80,
        ]);

        $member = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050092',
            'total_sks' => 110,
            'ipk' => 3.50,
        ]);

        $response = $this->actingAs($leader)->post(route('mahasiswa.pengajuan.store'), [
            'is_group' => '1',
            'jenis_magang' => 'prodi',
            'instansi' => 'PT. Inovasi Bersama',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'leader_ktm' => UploadedFile::fake()->create('ktm_leader.pdf', 500, 'application/pdf'),
            'leader_transkrip' => UploadedFile::fake()->create('transkrip_leader.pdf', 500, 'application/pdf'),
            'leader_spp' => UploadedFile::fake()->create('spp_leader.pdf', 500, 'application/pdf'),
            'member_user_ids' => [$member->id],
            'member_ktm' => [UploadedFile::fake()->create('ktm_member.pdf', 500, 'application/pdf')],
            'member_transkrip' => [UploadedFile::fake()->create('transkrip_member.pdf', 500, 'application/pdf')],
            'member_spp' => [UploadedFile::fake()->create('spp_member.pdf', 500, 'application/pdf')],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('mahasiswa.pengajuan.index'));

        // Assert leader documents
        $this->assertDatabaseHas('application_documents', [
            'user_id' => $leader->id,
            'type' => 'SLIP_SPP',
        ]);

        // Assert member documents
        $this->assertDatabaseHas('application_documents', [
            'user_id' => $member->id,
            'type' => 'SLIP_SPP',
        ]);
        $this->assertDatabaseHas('application_documents', [
            'user_id' => $member->id,
            'type' => 'KTM',
        ]);
        $this->assertDatabaseHas('application_documents', [
            'user_id' => $member->id,
            'type' => 'TRANSKRIP',
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
            'status' => 'approved',
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

    public function test_admin_can_create_dpl_user_with_nip(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'prodi_id' => $this->prodi->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Dr. Ahmad Fauzi, M.Kom',
            'email' => 'ahmad.fauzi@radenintan.ac.id',
            'password' => 'password123',
            'role' => 'dpl',
            'nip' => '198501012010121001',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Dr. Ahmad Fauzi, M.Kom',
            'email' => 'ahmad.fauzi@radenintan.ac.id',
            'role' => 'dpl',
            'nip' => '198501012010121001',
            'prodi_id' => $this->prodi->id,
        ]);
    }

    public function test_admin_can_update_dpl_user_with_nip(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'prodi_id' => $this->prodi->id,
        ]);

        $dpl = User::factory()->create([
            'name' => 'Dosen Lama',
            'email' => 'dosen.lama@radenintan.ac.id',
            'role' => 'dpl',
            'nip' => '198001012005011001',
            'prodi_id' => $this->prodi->id,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.users.update', $dpl->id), [
            'name' => 'Dosen Baru, M.T.I.',
            'email' => 'dosen.lama@radenintan.ac.id',
            'role' => 'dpl',
            'nip' => '198001012005011002',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $dpl->id,
            'name' => 'Dosen Baru, M.T.I.',
            'nip' => '198001012005011002',
        ]);
    }

    public function test_mahasiswa_cannot_apply_magang_if_ipk_below_2_75(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050055',
            'total_sks' => 110,
            'ipk' => 2.70, // di bawah 2.75
        ]);

        $response = $this->actingAs($mahasiswa)->post(route('mahasiswa.pengajuan.store'), [
            'is_group' => '0',
            'jenis_magang' => 'mandiri',
            'instansi' => 'PT. Test',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('2.75', session('error'));
    }

    public function test_mahasiswa_cannot_apply_magang_if_sks_below_110(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2111050056',
            'total_sks' => 105, // di bawah 110
            'ipk' => 3.50,
        ]);

        $response = $this->actingAs($mahasiswa)->post(route('mahasiswa.pengajuan.store'), [
            'is_group' => '0',
            'jenis_magang' => 'mandiri',
            'instansi' => 'PT. Test',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('110', session('error'));
    }

    public function test_mahasiswa_can_download_logbook_pdf_when_magang_selesai(): void
    {
        $dpl = User::factory()->create([
            'role' => 'dpl',
            'name' => 'Dr. H. Ahmad Fauzi, M.Kom.',
            'nip' => '198001012005011001',
            'prodi_id' => $this->prodi->id,
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'name' => 'Budi Pratama',
            'nim' => '2111050099',
            'total_sks' => 120,
            'ipk' => 3.75,
            'prodi_id' => $this->prodi->id,
        ]);

        $application = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'instansi' => 'Dinas Komunikasi dan Informatika Provinsi Lampung',
            'alamat' => 'Jl. Wolter Monginsidi No. 69, Bandar Lampung',
            'alamat_instansi' => 'Jl. Wolter Monginsidi No. 69, Bandar Lampung',
            'start_date' => '2026-07-01',
            'end_date' => '2026-08-31',
            'status' => 'selesai',
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'prodi_id' => $this->prodi->id,
        ]);

        DplAssignment::create([
            'application_id' => $application->id,
            'dpl_user_id' => $dpl->id,
            'assigned_at' => now(),
        ]);

        Logbook::create([
            'user_id' => $mahasiswa->id,
            'application_id' => $application->id,
            'date' => '2026-07-02',
            'kegiatan' => 'Orientasi lingkungan kerja dan setup environment database PostgreSQL',
            'output' => 'Database server lokal terkonfigurasi dan schema awal terpasang',
            'feedback_dpl' => 'Bagus, pastikan backup berkala dilakukan.',
        ]);

        Logbook::create([
            'user_id' => $mahasiswa->id,
            'application_id' => $application->id,
            'date' => '2026-07-03',
            'kegiatan' => 'Implementasi modul autentikasi RESTful API',
            'output' => 'Endpoint API login dan registrasi berhasil dites dengan Postman',
            'feedback_dpl' => 'Perhatikan sanitasi input dan validasi token JWT.',
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.logbook.cetak'));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('Logbook_Magang_2111050099.pdf', (string) $response->headers->get('Content-Disposition'));
    }

    public function test_mahasiswa_can_download_logbook_pdf_when_magang_active_or_not_yet_selesai(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'name' => 'Budi Pratama',
            'nim' => '2111050099',
            'total_sks' => 120,
            'ipk' => 3.75,
            'prodi_id' => $this->prodi->id,
        ]);

        $application = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'instansi' => 'PT Telkom Indonesia',
            'alamat' => 'Bandar Lampung',
            'alamat_instansi' => 'Bandar Lampung',
            'start_date' => '2026-07-01',
            'end_date' => '2026-08-31',
            'status' => 'active', // sedang berjalan, belum selesai
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'prodi_id' => $this->prodi->id,
        ]);

        Logbook::create([
            'user_id' => $mahasiswa->id,
            'application_id' => $application->id,
            'date' => '2026-07-02',
            'kegiatan' => 'Orientasi dan pengenalan tim',
            'output' => 'Briefing selesai',
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.logbook.cetak'));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('Logbook_Magang_2111050099.pdf', (string) $response->headers->get('Content-Disposition'));
    }

    public function test_mahasiswa_cannot_download_logbook_pdf_when_application_still_pending(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'name' => 'Budi Pratama',
            'nim' => '2111050099',
            'total_sks' => 120,
            'ipk' => 3.75,
            'prodi_id' => $this->prodi->id,
        ]);

        MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'instansi' => 'PT Telkom Indonesia',
            'alamat' => 'Bandar Lampung',
            'alamat_instansi' => 'Bandar Lampung',
            'start_date' => '2026-07-01',
            'end_date' => '2026-08-31',
            'status' => 'pending',
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'prodi_id' => $this->prodi->id,
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.logbook.cetak'));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_mahasiswa_can_view_logbook_page_when_magang_selesai_and_sees_print_button(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'name' => 'Budi Pratama',
            'nim' => '2111050099',
            'total_sks' => 120,
            'ipk' => 3.75,
            'prodi_id' => $this->prodi->id,
        ]);

        $application = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'instansi' => 'Dinas Kominfo',
            'alamat' => 'Bandar Lampung',
            'alamat_instansi' => 'Bandar Lampung',
            'start_date' => '2026-07-01',
            'end_date' => '2026-08-31',
            'status' => 'selesai',
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'prodi_id' => $this->prodi->id,
        ]);

        Logbook::create([
            'user_id' => $mahasiswa->id,
            'application_id' => $application->id,
            'date' => '2026-07-02',
            'kegiatan' => 'Penyusunan modul analitik',
            'output' => 'Dokumen SRS modul analitik',
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.logbook.index'));

        $response->assertOk();
        $response->assertSee('Kegiatan Magang Anda Telah Selesai');
        $response->assertSee(route('mahasiswa.logbook.cetak'));
        // Form input baru tidak ditampilkan karena sudah selesai
        $response->assertDontSee('Tulis Jurnal Kegiatan Harian Baru');
    }

    public function test_mahasiswa_can_view_logbook_page_when_magang_active_and_sees_print_button(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'name' => 'Budi Pratama',
            'nim' => '2111050099',
            'total_sks' => 120,
            'ipk' => 3.75,
            'prodi_id' => $this->prodi->id,
        ]);

        MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'instansi' => 'Dinas Kominfo',
            'alamat' => 'Bandar Lampung',
            'alamat_instansi' => 'Bandar Lampung',
            'start_date' => '2026-07-01',
            'end_date' => '2026-08-31',
            'status' => 'active',
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'prodi_id' => $this->prodi->id,
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.logbook.index'));

        $response->assertOk();
        $response->assertSee(route('mahasiswa.logbook.cetak'));
        $response->assertSee('Cetak Logbook Resmi (PDF)');
        $response->assertDontSee('Cetak Logbook (Tersedia Saat Selesai)');
    }

    public function test_mahasiswa_cannot_store_logbook_when_magang_selesai(): void
    {
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'name' => 'Budi Pratama',
            'nim' => '2111050099',
            'total_sks' => 120,
            'ipk' => 3.75,
            'prodi_id' => $this->prodi->id,
        ]);

        $application = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'instansi' => 'Dinas Kominfo',
            'alamat' => 'Bandar Lampung',
            'alamat_instansi' => 'Bandar Lampung',
            'start_date' => '2026-07-01',
            'end_date' => '2026-08-31',
            'status' => 'selesai',
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'prodi_id' => $this->prodi->id,
        ]);

        $response = $this->actingAs($mahasiswa)->post(route('mahasiswa.logbook.store'), [
            'date' => '2026-07-05',
            'kegiatan' => 'Kegiatan tambahan setelah selesai',
            'output' => 'Output tambahan',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('selesai', session('error'));
    }

    public function test_dpl_can_store_evaluation_with_uploaded_files_and_calculate_average(): void
    {
        Storage::fake('public');

        $dpl = User::factory()->create([
            'role' => 'dpl',
            'name' => 'Dosen Pembimbing Lapangan M.Kom',
            'nip' => '198501012010011002',
            'prodi_id' => $this->prodi->id,
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'name' => 'Siswa Magang',
            'nim' => '2111050088',
            'total_sks' => 125,
            'ipk' => 3.80,
            'prodi_id' => $this->prodi->id,
        ]);

        $application = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'instansi' => 'PT Telkom Akses',
            'alamat' => 'Bandar Lampung',
            'alamat_instansi' => 'Bandar Lampung',
            'start_date' => '2026-07-01',
            'end_date' => '2026-08-31',
            'status' => 'active',
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'prodi_id' => $this->prodi->id,
        ]);

        DplAssignment::create([
            'application_id' => $application->id,
            'dpl_user_id' => $dpl->id,
            'assigned_at' => now(),
        ]);

        $fileInstansi = UploadedFile::fake()->create('form_nilai_instansi_ttd.pdf', 800, 'application/pdf');
        $fileDpl = UploadedFile::fake()->create('form_nilai_dpl_ttd.pdf', 600, 'application/pdf');

        $response = $this->actingAs($dpl)->post(route('dpl.evaluasi.store', $application->id), [
            'nilai_dpl' => '86.00',
            'nilai_instansi' => '94.00',
            'komentar' => 'Kinerja mahasiswa sangat baik dan program pengabdian berdampak luas.',
            'berkas_nilai_instansi' => $fileInstansi,
            'berkas_nilai_dpl' => $fileDpl,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('evaluations', [
            'application_id' => $application->id,
            'dpl_user_id' => $dpl->id,
            'nilai_dpl' => 86.00,
            'nilai_instansi' => 94.00,
            'nilai_akhir' => 90.00,
        ]);

        $this->assertDatabaseHas('magang_applications', [
            'id' => $application->id,
            'status' => 'selesai',
        ]);

        $eval = Evaluation::where('application_id', $application->id)->first();
        $this->assertNotNull($eval->berkas_nilai_instansi);
        $this->assertNotNull($eval->berkas_nilai_dpl);

        Storage::disk('public')->assertExists($eval->berkas_nilai_instansi);
        Storage::disk('public')->assertExists($eval->berkas_nilai_dpl);
    }

    public function test_mahasiswa_can_view_uploaded_instansi_and_dpl_evaluation_files(): void
    {
        Storage::fake('public');

        $dpl = User::factory()->create([
            'role' => 'dpl',
            'prodi_id' => $this->prodi->id,
        ]);

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'nim' => '2111050077',
            'total_sks' => 125,
            'ipk' => 3.80,
            'prodi_id' => $this->prodi->id,
        ]);

        $application = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'instansi' => 'Bank Mandiri',
            'alamat' => 'Bandar Lampung',
            'alamat_instansi' => 'Bandar Lampung',
            'start_date' => '2026-07-01',
            'end_date' => '2026-08-31',
            'status' => 'selesai',
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'prodi_id' => $this->prodi->id,
        ]);

        DplAssignment::create([
            'application_id' => $application->id,
            'dpl_user_id' => $dpl->id,
            'assigned_at' => now(),
        ]);

        $pathInstansi = UploadedFile::fake()->create('nilai_instansi.pdf', 300, 'application/pdf')->store('evaluasi/nilai_instansi', 'public');
        $pathDpl = UploadedFile::fake()->create('nilai_dpl.pdf', 300, 'application/pdf')->store('evaluasi/nilai_dpl', 'public');

        Evaluation::create([
            'application_id' => $application->id,
            'dpl_user_id' => $dpl->id,
            'nilai_dpl' => 88.00,
            'nilai_instansi' => 92.00,
            'nilai_akhir' => 90.00,
            'berkas_nilai_instansi' => $pathInstansi,
            'berkas_nilai_dpl' => $pathDpl,
            'published_at' => now(),
        ]);

        // Mahasiswa melihat berkas nilai instansi
        $responseInstansi = $this->actingAs($mahasiswa)->get(route('evaluasi.berkas_instansi', $application->id));
        $responseInstansi->assertOk();

        // Mahasiswa melihat berkas nilai DPL
        $responseDpl = $this->actingAs($mahasiswa)->get(route('evaluasi.berkas_dpl', $application->id));
        $responseDpl->assertOk();

        // Mahasiswa melihat link kedua berkas pada dashboard
        $responseDashboard = $this->actingAs($mahasiswa)->get(route('mahasiswa.dashboard'));
        $responseDashboard->assertOk();
        $responseDashboard->assertSee(route('evaluasi.berkas_instansi', $application->id));
        $responseDashboard->assertSee(route('evaluasi.berkas_dpl', $application->id));
    }

    public function test_unauthorized_user_cannot_view_evaluation_files(): void
    {
        Storage::fake('public');

        $mahasiswaA = User::factory()->create(['role' => 'mahasiswa', 'prodi_id' => $this->prodi->id]);
        $mahasiswaLain = User::factory()->create(['role' => 'mahasiswa', 'prodi_id' => $this->prodi->id]);

        $application = MagangApplication::create([
            'user_id' => $mahasiswaA->id,
            'instansi' => 'PT GoTo',
            'alamat' => 'Jakarta',
            'start_date' => '2026-07-01',
            'end_date' => '2026-08-31',
            'status' => 'selesai',
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'prodi_id' => $this->prodi->id,
        ]);

        $pathInstansi = UploadedFile::fake()->create('nilai_instansi.pdf', 300, 'application/pdf')->store('evaluasi/nilai_instansi', 'public');

        Evaluation::create([
            'application_id' => $application->id,
            'dpl_user_id' => $mahasiswaA->id,
            'nilai_dpl' => 88.00,
            'nilai_instansi' => 92.00,
            'nilai_akhir' => 90.00,
            'berkas_nilai_instansi' => $pathInstansi,
            'published_at' => now(),
        ]);

        // Mahasiswa lain mengakses berkas mahasiswa A -> Ditolak (403)
        $response = $this->actingAs($mahasiswaLain)->get(route('evaluasi.berkas_instansi', $application->id));
        $response->assertForbidden();
    }

    public function test_user_can_download_blank_evaluation_templates_for_dpl_and_instansi(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa', 'prodi_id' => $this->prodi->id]);

        // Unduh template DPL (PDF)
        $responseDpl = $this->actingAs($user)->get(route('mahasiswa.penilaian_dpl.template'));
        $responseDpl->assertOk();
        $this->assertEquals('application/pdf', $responseDpl->headers->get('Content-Type'));

        // Unduh template Instansi (PDF)
        $responseInstansiPdf = $this->actingAs($user)->get(route('mahasiswa.penilaian_instansi.template', ['format' => 'pdf']));
        $responseInstansiPdf->assertOk();
        $this->assertEquals('application/pdf', $responseInstansiPdf->headers->get('Content-Type'));

        // Unduh template Instansi (DOCX)
        $responseInstansiDocx = $this->actingAs($user)->get(route('mahasiswa.penilaian_instansi.template'));
        $responseInstansiDocx->assertOk();
    }

    public function test_surat_tugas_dpl_route_has_been_removed(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'prodi_id' => $this->prodi->id]);
        $response = $this->actingAs($admin)->get('/admin/penetapan-dpl/1/surat');
        $response->assertNotFound();
    }

    public function test_user_can_download_surat_permohonan_izin_magang_and_logbook_docx_templates(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa', 'prodi_id' => $this->prodi->id]);

        // 1. Download Surat Permohonan Izin Magang DOCX
        $responseIzin = $this->actingAs($user)->get(route('mahasiswa.izin_magang.template'));
        $responseIzin->assertOk();
        $this->assertStringContainsString('Surat_Permohonan_Izin_Magang.docx', $responseIzin->headers->get('Content-Disposition') ?? '');

        // 2. Download Log Book Kegiatan Magang DOCX
        $responseLogbook = $this->actingAs($user)->get(route('mahasiswa.logbook.template_docx'));
        $responseLogbook->assertOk();
        $this->assertStringContainsString('Log_Book_Kegiatan_Magang.docx', $responseLogbook->headers->get('Content-Disposition') ?? '');

        // 3. Guest cannot download without auth
        auth()->logout();
        $guestResponse = $this->get(route('mahasiswa.izin_magang.template'));
        $guestResponse->assertRedirect(route('login'));
    }

    public function test_download_surat_permohonan_izin_magang_docx_auto_populates_data(): void
    {
        $mahasiswa = User::factory()->create([
            'name' => 'Fauzi Ramadhan',
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2271020088',
            'total_sks' => 125,
            'ipk' => 3.80,
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $this->prodi->id,
            'is_group' => false,
            'jenis_magang' => 'prodi',
            'instansi' => 'PT Teknologi Digital Nusantara',
            'alamat' => 'Jl. ZA Pagar Alam No. 100 Bandar Lampung',
            'start_date' => '2026-11-01',
            'end_date' => '2027-01-31',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.izin_magang.template', ['pengajuan_id' => $pengajuan->id]));
        $response->assertOk();
        $disposition = $response->headers->get('Content-Disposition') ?? '';
        $this->assertStringContainsString('Surat_Permohonan_Izin_Magang_2271020088.docx', $disposition);

        // Verify DOCX contents
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $tempFile = tempnam(sys_get_temp_dir(), 'test_docx_') . '.docx';
        file_put_contents($tempFile, $content);

        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($tempFile) === true);
        $documentXml = $zip->getFromName('word/document.xml');
        $zip->close();
        @unlink($tempFile);

        $this->assertStringContainsString('PT Teknologi Digital Nusantara', $documentXml);
        $this->assertStringContainsString('Fauzi Ramadhan', $documentXml);
        $this->assertStringContainsString('2271020088', $documentXml);
        $this->assertStringContainsString('Bandar Lampung,', $documentXml);
        $this->assertStringContainsString('Jurusan/Semester', $documentXml);
    }

    public function test_laporan_akhir_upload_is_locked_during_phase_2_and_unlocked_on_active_phase(): void
    {
        Storage::fake('public');

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2271020002',
            'total_sks' => 120,
            'ipk' => 3.75,
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $this->prodi->id,
            'instansi' => 'PT Test Teknologi',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->addDays(2),
            'end_date' => now()->addMonths(2),
            'status' => 'pending', // Phase 2
            'jenis_magang' => 'prodi',
            'is_group' => false,
        ]);

        // 1. In Phase 2 (pending), dashboard view shows locked status, not an active upload form
        $viewResponse = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengajuan.index'));
        $viewResponse->assertOk();
        $viewResponse->assertSee('Fase 5 • Terkunci');
        $viewResponse->assertSee('Upload Terkunci (Fase 5)');
        $viewResponse->assertDontSee('Upload' . "\n" . '                                            </button>');

        // 2. Direct POST attempt while pending should be rejected
        $file = UploadedFile::fake()->create('laporan.pdf', 500, 'application/pdf');
        $postResponse = $this->actingAs($mahasiswa)->post(route('mahasiswa.pengajuan.laporan_akhir', $pengajuan->id), [
            'laporan_akhir' => $file,
        ]);
        $postResponse->assertSessionHas('error');
        $this->assertDatabaseMissing('application_documents', [
            'application_id' => $pengajuan->id,
            'type' => 'LAPORAN_AKHIR',
        ]);

        // 3. When status is 'active' (Phase 4 / 5), dashboard view shows active form and upload succeeds
        $pengajuan->update(['status' => 'active']);
        
        $activeViewResponse = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengajuan.index'));
        $activeViewResponse->assertOk();
        $activeViewResponse->assertSee('Fase 5 • Perlu Diunggah');

        $activePostResponse = $this->actingAs($mahasiswa)->post(route('mahasiswa.pengajuan.laporan_akhir', $pengajuan->id), [
            'laporan_akhir' => $file,
        ]);
        $activePostResponse->assertSessionHas('success');
        $this->assertDatabaseHas('application_documents', [
            'application_id' => $pengajuan->id,
            'type' => 'LAPORAN_AKHIR',
        ]);
    }

    public function test_bottom_section_cards_are_disabled_while_pending_admin_verification(): void
    {
        Storage::fake('public');

        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $this->prodi->id,
            'nim' => '2271020003',
            'total_sks' => 125,
            'ipk' => 3.80,
        ]);

        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $this->prodi->id,
            'instansi' => 'PT Solusi Bangsa',
            'alamat' => 'Bandar Lampung',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addMonths(3),
            'status' => 'pending', // Pending verification
            'jenis_magang' => 'prodi',
            'is_group' => false,
        ]);

        // 1. Check UI shows disabled cards and pending banner
        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengajuan.index'));
        $response->assertOk();
        $response->assertSee('Aksi Dokumen Dinonaktifkan Sementara (Menunggu Verifikasi Admin)');
        $response->assertSee('Menunggu Verifikasi');
        $response->assertSee('Surat Pengantar Belum Terbit');
        $response->assertSee('Upload Belum Dibuka');

        // 2. Direct attempt to download Surat Pengantar should be rejected while pending
        $pengantarResponse = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengajuan.surat_pengantar', $pengajuan->id));
        $pengantarResponse->assertSessionHas('error');

        // 3. Direct attempt to upload Surat Balasan should be rejected while pending
        $file = UploadedFile::fake()->create('balasan.pdf', 500, 'application/pdf');
        $balasanResponse = $this->actingAs($mahasiswa)->post(route('mahasiswa.pengajuan.surat_balasan', $pengajuan->id), [
            'surat_balasan' => $file,
        ]);
        $balasanResponse->assertSessionHas('error');
        $this->assertDatabaseMissing('application_documents', [
            'application_id' => $pengajuan->id,
            'type' => 'SURAT_PENERIMAAN',
        ]);

        // 4. Once approved by Admin, the section unlocks
        $pengajuan->update(['status' => 'approved']);

        $approvedViewResponse = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengajuan.index'));
        $approvedViewResponse->assertOk();
        $approvedViewResponse->assertSee('Format Siap Cetak');
        $approvedViewResponse->assertSee('Unduh Surat Pengantar (DOCX)');
        $approvedViewResponse->assertDontSee('Surat Pengantar Belum Terbit');

        $approvedDownloadResponse = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengajuan.surat_pengantar', $pengajuan->id));
        $approvedDownloadResponse->assertOk();

        $approvedUploadResponse = $this->actingAs($mahasiswa)->post(route('mahasiswa.pengajuan.surat_balasan', $pengajuan->id), [
            'surat_balasan' => $file,
        ]);
        $approvedUploadResponse->assertSessionHas('success');
        $this->assertDatabaseHas('application_documents', [
            'application_id' => $pengajuan->id,
            'type' => 'SURAT_PENERIMAAN',
        ]);
    }

    public function test_mahasiswa_can_view_assigned_dpl_on_pengajuan_and_dashboard(): void
    {
        $prodi = Prodi::firstOrCreate(['id' => 1], ['nama_prodi' => 'Sistem Informasi']);
        $mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'prodi_id' => $prodi->id,
            'total_sks' => 120,
            'ipk' => 3.50,
        ]);
        $dpl = User::factory()->create([
            'name' => 'Dr. Budi Santoso, M.Kom',
            'email' => 'budi.dpl@uin.ac.id',
            'role' => 'dpl',
            'prodi_id' => $prodi->id,
            'nip' => '198501012010121001',
        ]);
        $pengajuan = MagangApplication::create([
            'user_id' => $mahasiswa->id,
            'prodi_id' => $prodi->id,
            'instansi' => 'PT Solusi Teknologi Indonesia',
            'alamat' => 'Jl. Raden Intan No. 45',
            'alamat_instansi' => 'Jl. Raden Intan No. 45',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'status' => 'active',
            'jenis_magang' => 'prodi',
            'is_group' => false,
        ]);

        DplAssignment::create([
            'application_id' => $pengajuan->id,
            'dpl_user_id' => $dpl->id,
        ]);

        // Verify DPL visible on Pengajuan Index (Fase 4 Card)
        $pengajuanResponse = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengajuan.index'));
        $pengajuanResponse->assertOk();
        $pengajuanResponse->assertSee('Fase 4: Pembimbing (DPL)');
        $pengajuanResponse->assertSee('Fase 4 • DPL Ditetapkan');
        $pengajuanResponse->assertSee('Dr. Budi Santoso, M.Kom');
        $pengajuanResponse->assertSee('198501012010121001');

        // Verify DPL visible on Mahasiswa Dashboard
        $dashboardResponse = $this->actingAs($mahasiswa)->get(route('mahasiswa.dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('PT Solusi Teknologi Indonesia');
        $dashboardResponse->assertSee('Dosen Pembimbing (DPL)');
        $dashboardResponse->assertSee('Dr. Budi Santoso, M.Kom');
        $dashboardResponse->assertSee('198501012010121001');
        $dashboardResponse->assertSee('budi.dpl@uin.ac.id');
    }

    public function test_surat_pengantar_nomor_surat_is_dynamic_with_wd1_and_date(): void
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
            'status' => 'approved',
        ]);

        $expectedNomor = 'B-..../Un.16/WD1/PP.00.' . date('d/m/Y');

        // Check DOCX download contains dynamic nomor surat
        $docxResponse = $this->actingAs($mahasiswa)->get(route('mahasiswa.izin_magang.template', ['pengajuan_id' => $pengajuan->id]));
        $docxResponse->assertOk();
        $tempPath = tempnam(sys_get_temp_dir(), 'docx_test');
        file_put_contents($tempPath, $docxResponse->streamedContent());
        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($tempPath));
        $xml = $zip->getFromName('word/document.xml');
        $this->assertStringContainsString($expectedNomor, $xml);
        $zip->close();
        @unlink($tempPath);
    }
}




