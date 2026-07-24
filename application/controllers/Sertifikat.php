<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Sertifikat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Sertifikat_model', 'Signature_model']);
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form']);

        // Terapkan login check kecuali untuk metode verifikasi (akses publik lewat QR Code)
        $method = $this->router->fetch_method();
        if ($method !== 'verifikasi' && !$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
    
    public function index()
    {
        $role = $this->session->userdata('role');
        if (in_array($role, ['kemahasiswaan', 'admin'])) {
            redirect('sertifikat/admin');
        }

        $user_id = $this->session->userdata('user_id');
        $data['title']         = 'Pengajuan Sertifikat';
        $data['user_data']     = $this->_get_session_user();
        $data['riwayat']       = $this->Sertifikat_model->get_by_mahasiswa($user_id);
        $data['flash_success'] = $this->session->flashdata('success');
        $data['flash_error']   = $this->session->flashdata('error');

        $this->load->view('sertifikat/index', $data);
    }

    public function kirim()
{
    if ($this->input->method() !== 'post') redirect('sertifikat');

    $this->form_validation->set_rules('nama_pic',         'Nama PIC',         'required|trim');
    $this->form_validation->set_rules('judul_kegiatan',   'Judul Kegiatan',   'required|trim');
    $this->form_validation->set_rules('tanggal_kegiatan', 'Tanggal Kegiatan', 'required');
    $this->form_validation->set_rules('lokasi_kegiatan',  'Lokasi Kegiatan',  'required|trim');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('sertifikat');
    }

    $user_id = $this->session->userdata('user_id');
    $id = $this->input->post('id');

    $data = [
        'nama_pic'           => $this->input->post('nama_pic', TRUE),
        'judul_kegiatan'     => $this->input->post('judul_kegiatan', TRUE),
        'deskripsi_kegiatan' => $this->input->post('deskripsi_kegiatan', TRUE),
        'tanggal_kegiatan'   => $this->input->post('tanggal_kegiatan'),
        'lokasi_kegiatan'    => $this->input->post('lokasi_kegiatan', TRUE),
        'catatan_tambahan'   => $this->input->post('catatan_tambahan', TRUE),
    ];

    if ($id) {
        // Mode Edit Pengajuan yang Ditolak
        $existing = $this->Sertifikat_model->get_by_id($id);
        if ($existing && $existing['mahasiswa_id'] == $user_id) {
            $update_data = array_merge($data, [
                'status'         => 'submitted',
                'catatan_admin'  => NULL,
                'updated_at'     => date('Y-m-d H:i:s')
            ]);
            $this->db->where('id', $id);
            $this->db->update('pengajuan_sertifikat', $update_data);

            // Log update status
            $this->db->insert('sertifikat_status_log', [
                'pengajuan_id'    => $id,
                'status_lama'     => 'rejected',
                'status_baru'     => 'submitted',
                'catatan'         => 'Pengajuan diperbaiki oleh mahasiswa',
                'changed_by'      => $user_id,
                'changed_by_role' => 'mahasiswa',
                'changed_at'      => date('Y-m-d H:i:s'),
            ]);

            $this->session->set_flashdata('success', 'Pengajuan sertifikat berhasil diperbarui! Menunggu persetujuan admin.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui pengajuan. Data tidak ditemukan.');
        }
    } else {
        // Mode Pengajuan Baru
        $insert_data = array_merge($data, [
            'mahasiswa_id'   => $user_id,
            'nim'            => $this->session->userdata('nim') ?? '-',
            'nama_mahasiswa' => $this->session->userdata('nama'),
            'prodi'          => $this->session->userdata('prodi'),
        ]);
        $kode = $this->Sertifikat_model->insert($insert_data, $user_id);

        if ($kode) {
            $this->session->set_flashdata('success', 'Pengajuan pengajuan sertifikat berhasil dikirim! Menunggu persetujuan admin. Kode Pengajuan: <strong>' . $kode . '</strong>');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan pengajuan. Silakan coba lagi.');
        }
    }
    redirect('sertifikat');
}

// HAPUS method _upload_file() karena tidak digunakan lagi
// private function _upload_file($field, $subfolder) { ... }
    public function admin()
{
    $this->_check_admin();

    $status_filter = $this->input->get('status') ?? '';
    $search        = $this->input->get('q') ?? '';

    // Ambil data dari model
    $pengajuan_list = $this->Sertifikat_model->get_all($status_filter, $search);
    $stats          = $this->Sertifikat_model->get_stats();
    
    // Hitung pending count untuk badge di sidebar
    $pending_count = $stats['submitted'] ?? 0;

    // Fetch list of students for the new certificate modal
    $this->db->select('id, nim, nama');
    $this->db->where('role', 'mahasiswa');
    $this->db->order_by('nama', 'ASC');
    $mahasiswa_list = $this->db->get('users')->result_array();

    // Scan folder custom template (tanpa butuh tabel DB)
    $custom_dir   = FCPATH . 'templates/sertifikat/custom/';
    $custom_files = [];
    if (is_dir($custom_dir)) {
        $idx = 1;
        foreach (glob($custom_dir . '*.{png,jpg,jpeg}', GLOB_BRACE) as $filepath) {
            $filename = basename($filepath);
            $name = ucwords(str_replace(['_', '-'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
            $custom_files[] = [
                'id'   => $idx++,
                'name' => $name,
                'file' => $filename,
            ];
        }
    }

    // Fetch all signatures
    $signatures = $this->Signature_model->get_all();

    $data = [
        'title' => 'Manajemen Pengajuan Sertifikat',
        'pengajuan_list' => $pengajuan_list,
        'stats' => $stats,
        'status_filter' => $status_filter,
        'search' => $search,
        'mahasiswa_list' => $mahasiswa_list,
        'custom_templates' => $custom_files,
        'signatures' => $signatures,
        'flash_success' => $this->session->flashdata('success'),
        'flash_error' => $this->session->flashdata('error')
    ];

    $this->load->view('sertifikat/admin', $data);
}

public function admin_tambah_pengajuan()
{
    $this->_check_admin();

    $this->form_validation->set_rules('mahasiswa_id',     'Mahasiswa',        'required');
    $this->form_validation->set_rules('nama_pic',         'Nama PIC',         'required|trim');
    $this->form_validation->set_rules('judul_kegiatan',   'Judul Kegiatan',   'required|trim');
    $this->form_validation->set_rules('tanggal_kegiatan', 'Tanggal Kegiatan', 'required');
    $this->form_validation->set_rules('lokasi_kegiatan',  'Lokasi Kegiatan',  'required|trim');

    if ($this->form_validation->run() == FALSE) {
        $this->session->set_flashdata('error', validation_errors());
        redirect('sertifikat/admin');
        return;
    }

    $mahasiswa_id = $this->input->post('mahasiswa_id');
    $m = $this->db->get_where('users', ['id' => $mahasiswa_id])->row();

    if ($m) {
        $insert_data = [
            'mahasiswa_id'       => $mahasiswa_id,
            'nim'                => $m->nim ?? '-',
            'nama_mahasiswa'     => $m->nama,
            'prodi'              => $m->prodi ?? '-',
            'nama_pic'           => $this->input->post('nama_pic', TRUE),
            'judul_kegiatan'     => $this->input->post('judul_kegiatan', TRUE),
            'deskripsi_kegiatan' => $this->input->post('deskripsi_kegiatan', TRUE),
            'tanggal_kegiatan'   => $this->input->post('tanggal_kegiatan'),
            'lokasi_kegiatan'    => $this->input->post('lokasi_kegiatan', TRUE),
            'catatan_tambahan'   => $this->input->post('catatan_tambahan', TRUE),
            'status'             => 'submitted',
        ];
        $kode = $this->Sertifikat_model->insert($insert_data, $mahasiswa_id);
        if ($kode) {
            $this->session->set_flashdata('success', 'Pengajuan sertifikat atas nama ' . $m->nama . ' berhasil dibuat!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan pengajuan.');
        }
    } else {
        $this->session->set_flashdata('error', 'Mahasiswa tidak ditemukan.');
    }
    redirect('sertifikat/admin');
}

    // AJAX: approve atau reject
public function update_status()
{
    header('Content-Type: application/json');
    $this->_check_admin_ajax();

    // Log untuk debugging
    log_message('debug', 'update_status called with POST: ' . print_r($_POST, true));

    $id      = (int) $this->input->post('id');
    $action  = $this->input->post('action'); // 'approve' atau 'reject'
    $catatan = $this->input->post('catatan', TRUE);

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
        return;
    }

    if (!in_array($action, ['approve', 'reject'])) {
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid.']);
        return;
    }

    // Jika reject, catatan wajib diisi
    if ($action === 'reject' && empty(trim($catatan))) {
        echo json_encode(['status' => 'error', 'message' => 'Alasan penolakan wajib diisi.']);
        return;
    }

    $extra = [];
    if ($action === 'approve') {
        $nomor = $this->input->post('nomor_sertifikat', TRUE);
        if (empty(trim($nomor))) {
            echo json_encode(['status' => 'error', 'message' => 'Nomor sertifikat wajib diisi.']);
            return;
        }
        $extra['nomor_sertifikat'] = $nomor;
        


        // Handle Signature Upload, Saved Signature, or Drawn Signature
        $signature_id   = $this->input->post('signature_id', TRUE);
        $signature_data = $this->input->post('signature_data', FALSE); // Don't filter HTML characters since it is base64

        if (!empty($signature_id)) {
            $sig = $this->Signature_model->get_by_id($signature_id);
            if (!$sig) {
                echo json_encode(['status' => 'error', 'message' => 'Tanda tangan tersimpan tidak ditemukan.']);
                return;
            }
            $extra['signature_image'] = $sig['image_path'];
        } elseif (!empty($signature_data)) {
            // Decoded base64 image data
            $signature_data = str_replace('data:image/png;base64,', '', $signature_data);
            $signature_data = str_replace(' ', '+', $signature_data);
            $data_decoded   = base64_decode($signature_data);

            $sig_dir = FCPATH . 'uploads/sertifikat/signatures/';
            if (!is_dir($sig_dir)) {
                mkdir($sig_dir, 0755, true);
            }

            $sig_safe_name = 'sig_draw_' . $id . '_' . date('YmdHis') . '_' . uniqid() . '.png';
            $sig_dest      = $sig_dir . $sig_safe_name;

            if (file_put_contents($sig_dest, $data_decoded)) {
                $extra['signature_image'] = 'uploads/sertifikat/signatures/' . $sig_safe_name;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan hasil gambar tanda tangan.']);
                return;
            }
        } else {
            if (empty($_FILES['signature_file']['name'])) {
                echo json_encode(['status' => 'error', 'message' => 'Gambar tanda tangan wajib diunggah atau digambar.']);
                return;
            }

            $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
            $sig_file_type = $_FILES['signature_file']['type'];
            if (!in_array($sig_file_type, $allowed_types)) {
                echo json_encode(['status' => 'error', 'message' => 'Tipe file tanda tangan tidak didukung. Gunakan PNG/JPG.']);
                return;
            }

            $sig_dir = FCPATH . 'uploads/sertifikat/signatures/';
            if (!is_dir($sig_dir)) {
                mkdir($sig_dir, 0755, true);
            }

            $sig_ext       = strtolower(pathinfo($_FILES['signature_file']['name'], PATHINFO_EXTENSION));
            $sig_safe_name = 'sig_' . $id . '_' . date('YmdHis') . '_' . uniqid() . '.' . $sig_ext;
            $sig_dest      = $sig_dir . $sig_safe_name;

            if (!move_uploaded_file($_FILES['signature_file']['tmp_name'], $sig_dest)) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan file tanda tangan ke server.']);
                return;
            }
            $extra['signature_image'] = 'uploads/sertifikat/signatures/' . $sig_safe_name;
        }

        // Handle QR Code
        $qr_method = $this->input->post('qr_method', TRUE);
        if ($qr_method === 'browse') {
            if (empty($_FILES['qr_file']['name'])) {
                echo json_encode(['status' => 'error', 'message' => 'File QR Code wajib diunggah.']);
                return;
            }

            $qr_file_type = $_FILES['qr_file']['type'];
            if (!in_array($qr_file_type, $allowed_types)) {
                echo json_encode(['status' => 'error', 'message' => 'Tipe file QR Code tidak didukung. Gunakan PNG/JPG.']);
                return;
            }

            $upload_dir = FCPATH . 'uploads/sertifikat/qr_codes/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $ext       = strtolower(pathinfo($_FILES['qr_file']['name'], PATHINFO_EXTENSION));
            $safe_name = 'qr_' . $id . '_' . date('YmdHis') . '_' . uniqid() . '.' . $ext;
            $dest      = $upload_dir . $safe_name;

            if (!move_uploaded_file($_FILES['qr_file']['tmp_name'], $dest)) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan file QR Code ke server.']);
                return;
            }

            $extra['qr_code'] = 'uploads/sertifikat/qr_codes/' . $safe_name;
        } else {
            $extra['qr_code'] = $this->input->post('qr_code', TRUE);
        }
    }

    $admin_id = $this->session->userdata('user_id');
    $result   = $this->Sertifikat_model->update_status($id, $action, $catatan, $admin_id, $extra);

    if ($result) {
        $label = ($action === 'approve') ? 'Disetujui' : 'Ditolak';
        echo json_encode([
            'status' => 'success', 
            'message' => 'Pengajuan berhasil ' . $label . '.', 
            'new_status' => ($action === 'approve' ? 'approved' : 'rejected')
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Gagal mengubah status. Pastikan status pengajuan masih "Menunggu".'
        ]);
    }
}
    // AJAX: detail pengajuan untuk modal
    public function get_detail_json($id)
    {
        header('Content-Type: application/json');
        $this->_check_admin_ajax();

        $pengajuan = $this->Sertifikat_model->get_by_id($id);
        if (!$pengajuan) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            return;
        }
        $log = $this->Sertifikat_model->get_log($id);
        echo json_encode(['status' => 'success', 'pengajuan' => $pengajuan, 'log' => $log]);
    }


    private function _get_session_user()
    {
        $role = $this->session->userdata('role');
        $map  = ['mahasiswa' => 'Mahasiswa', 'kemahasiswaan' => 'Staff Kemahasiswaan', 'admin' => 'Admin', 'dosen' => 'Dosen'];
        return [
            'user_id'      => $this->session->userdata('user_id'),
            'nama'         => $this->session->userdata('nama'),
            'nim'          => $this->session->userdata('nim'),
            'role'         => $role,
            'prodi'        => $this->session->userdata('prodi'),
            'role_display' => $map[$role] ?? ucfirst($role),
            'foto'         => $this->session->userdata('foto'),
            'logged_in'    => true,
        ];
    }

    public function admin_hapus($id)
    {
        $this->_check_admin();
        $id = (int)$id;

        $existing = $this->Sertifikat_model->get_by_id($id);
        if ($existing) {
            // Hapus file fisik sertifikat jika ada
            if (!empty($existing['file_sertifikat']) && file_exists(FCPATH . $existing['file_sertifikat'])) {
                @unlink(FCPATH . $existing['file_sertifikat']);
            }
            
            // Hapus log status terkait
            $this->db->where('pengajuan_id', $id);
            $this->db->delete('sertifikat_status_log');

            // Hapus dari database
            $this->db->where('id', $id);
            $this->db->delete('pengajuan_sertifikat');

            $this->session->set_flashdata('success', 'Pengajuan sertifikat berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Pengajuan sertifikat tidak ditemukan.');
        }
        redirect('sertifikat/admin');
    }

    private function _check_admin()
    {
        if (!in_array($this->session->userdata('role'), ['kemahasiswaan', 'admin'])) {
            show_error('Akses ditolak.', 403);
        }
    }

    private function _check_admin_ajax()
    {
        if (!in_array($this->session->userdata('role'), ['kemahasiswaan', 'admin'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }
    }
    /**
 * Download file export Excel
 */
public function download_export($id)
{
    // Cek login
    if (!$this->session->userdata('logged_in')) {
        redirect('login');
    }
    
    // Ambil data pengajuan
    $pengajuan = $this->Sertifikat_model->get_by_id($id);
    
    if (!$pengajuan) {
        show_404();
    }
    
    // Cek kepemilikan (hanya pemilik atau admin yang bisa download)
    $user_id = $this->session->userdata('user_id');
    $role = $this->session->userdata('role');
    
    if ($pengajuan['mahasiswa_id'] != $user_id && !in_array($role, ['admin', 'kemahasiswaan'])) {
        show_error('Akses ditolak.', 403);
    }
    
    // Cek file export
    if (empty($pengajuan['file_export']) || !file_exists(FCPATH . $pengajuan['file_export'])) {
        // Jika file tidak ada, generate ulang
        $data_pengajuan = [
            'kode_pengajuan' => $pengajuan['kode_pengajuan'],
            'judul_kegiatan' => $pengajuan['judul_kegiatan'],
            'tanggal_kegiatan' => $pengajuan['tanggal_kegiatan'],
            'lokasi_kegiatan' => $pengajuan['lokasi_kegiatan'],
            'nama_pic' => $pengajuan['nama_pic'],
            'nim' => $pengajuan['nim'],
            'nama_mahasiswa' => $pengajuan['nama_mahasiswa'],
            'prodi' => $pengajuan['prodi'],
            'deskripsi_kegiatan' => $pengajuan['deskripsi_kegiatan'],
            'catatan_tambahan' => $pengajuan['catatan_tambahan'],
        ];
        
        $this->Sertifikat_model->regenerate_export($id, $data_pengajuan);
        
        // Ambil ulang data pengajuan
        $pengajuan = $this->Sertifikat_model->get_by_id($id);
    }
    
    $filepath = FCPATH . $pengajuan['file_export'];
    $filename = 'pengajuan_' . $pengajuan['kode_pengajuan'] . '.xls';
    
    // Download file
    $this->load->helper('download');
    force_download($filename, file_get_contents($filepath));
}

    /**
     * Export Excel untuk Canva Bulk Design
     * Format kolom: nama_mahasiswa, nim, prodi, judul_kegiatan, tanggal_kegiatan,
     *               lokasi_kegiatan, nomor_sertifikat, nama_pic, tanggal_pengajuan
     */
    public function export_excel_canva()
    {
        $this->_check_admin();

        // Ambil semua sertifikat yang sudah approved
        $this->db->where('status', 'approved');
        $this->db->order_by('approved_at', 'DESC');
        $data = $this->db->get('pengajuan_sertifikat')->result_array();

        // Set header untuk download Excel
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="Sertifikat_Canva_' . date('Ymd_His') . '.xls"');
        header('Cache-Control: max-age=0');

        // BOM untuk UTF-8 agar Excel baca dengan benar
        echo "\xEF\xBB\xBF";

        // Buat konten HTML table (format .xls via HTML)
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8">
        <style>
            th { background-color: #f97316; color: white; font-weight: bold; padding: 8px; border: 1px solid #ddd; text-align: center; }
            td { padding: 6px 10px; border: 1px solid #ddd; vertical-align: middle; }
            tr:nth-child(even) td { background-color: #fff7ed; }
            .header-row td { background: #1e293b; color: white; font-size: 14pt; font-weight: bold; text-align: center; }
            .info-row td { background: #f1f5f9; font-size: 9pt; color: #64748b; }
        </style>
        </head><body>';

        echo '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; font-family:Arial, sans-serif; font-size:10pt;">';

        // Header utama
        echo '<tr class="header-row"><td colspan="10" style="padding:14px;font-size:14pt;font-weight:bold;background:#1e293b;color:white;text-align:center;">DATA SERTIFIKAT KEGIATAN — FORMAT CANVA BULK DESIGN</td></tr>';
        echo '<tr class="info-row"><td colspan="10" style="padding:6px;font-size:9pt;color:#64748b;background:#f1f5f9;">Export tanggal: ' . date('d-m-Y H:i:s') . ' | Total: ' . count($data) . ' sertifikat disetujui</td></tr>';
        echo '<tr><td colspan="10" style="height:4px;background:#f97316;"></td></tr>';

        // Kolom header — sesuai format Canva Bulk Design
        echo '<tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>NIM</th>
            <th>Program Studi</th>
            <th>Judul Kegiatan</th>
            <th>Tanggal Kegiatan</th>
            <th>Lokasi Kegiatan</th>
            <th>Nomor Sertifikat</th>
            <th>Nama PIC / Penanggung Jawab</th>
            <th>Tanggal Disetujui</th>
        </tr>';

        // Data rows
        if (empty($data)) {
            echo '<tr><td colspan="10" style="text-align:center;color:#999;padding:20px;">Belum ada sertifikat yang disetujui</td></tr>';
        } else {
            foreach ($data as $i => $row) {
                $tanggal_kegiatan = !empty($row['tanggal_kegiatan'])
                    ? date('d/m/Y', strtotime($row['tanggal_kegiatan']))
                    : '-';
                $tanggal_approved = !empty($row['approved_at'])
                    ? date('d/m/Y', strtotime($row['approved_at']))
                    : '-';

                echo '<tr>
                    <td style="text-align:center;">' . ($i + 1) . '</td>
                    <td><strong>' . htmlspecialchars($row['nama_mahasiswa'] ?? '-') . '</strong></td>
                    <td style="text-align:center;mso-number-format:\'\@\';">' . htmlspecialchars($row['nim'] ?? '-') . '</td>
                    <td>' . htmlspecialchars($row['prodi'] ?? '-') . '</td>
                    <td>' . htmlspecialchars($row['judul_kegiatan'] ?? '-') . '</td>
                    <td style="text-align:center;">' . $tanggal_kegiatan . '</td>
                    <td>' . htmlspecialchars($row['lokasi_kegiatan'] ?? '-') . '</td>
                    <td style="text-align:center;font-weight:bold;color:#f97316;">' . htmlspecialchars($row['nomor_sertifikat'] ?? '-') . '</td>
                    <td>' . htmlspecialchars($row['nama_pic'] ?? '-') . '</td>
                    <td style="text-align:center;">' . $tanggal_approved . '</td>
                </tr>';
            }
        }

        echo '</table></body></html>';
        exit;
    }

    /**
     * Halaman Generate Sertifikat (pilih data + template)
     */
    public function generate()
    {
        // Allow both admin/kemahasiswaan and mahasiswa roles
        $role = $this->session->userdata('role');
        $user_id = $this->session->userdata('user_id');

        $this->db->where('status', 'approved');
        if ($role === 'mahasiswa') {
            $nim = $this->session->userdata('nim');
            $this->db->group_start();
            $this->db->where('mahasiswa_id', $user_id);
            if ($nim) {
                $this->db->or_where('nim', $nim);
            }
            $this->db->group_end();
        }
        $this->db->order_by('approved_at', 'DESC');
        $data['sertifikat_list'] = $this->db->get('pengajuan_sertifikat')->result_array();
        $data['title']           = 'Cetak Sertifikat';

        // Scan folder custom template (tanpa butuh tabel DB)
        $custom_dir   = FCPATH . 'templates/sertifikat/custom/';
        $custom_files = [];
        if (is_dir($custom_dir)) {
            $idx = 1;
            foreach (glob($custom_dir . '*.{png,jpg,jpeg}', GLOB_BRACE) as $filepath) {
                $filename = basename($filepath);
                // Nama template: nama file tanpa ekstensi, underscores → spasi
                $name = ucwords(str_replace(['_', '-'], ' ', pathinfo($filename, PATHINFO_FILENAME)));
                $custom_files[] = [
                    'id'   => $idx++,
                    'name' => $name,
                    'file' => $filename,
                ];
            }
        }
        $data['custom_templates'] = $custom_files;

        $this->load->view('sertifikat/generate', $data);
    }

    /**
     * AJAX: Upload template custom ke folder templates/sertifikat/custom/
     * Tidak butuh tabel database. Response JSON: { success, id, name, url }
     */
    public function upload_template()
    {
        header('Content-Type: application/json');
        $this->_check_admin_ajax();

        $custom_dir = FCPATH . 'templates/sertifikat/custom/';

        // Pastikan folder ada
        if (!is_dir($custom_dir)) {
            mkdir($custom_dir, 0755, true);
        }

        if (empty($_FILES['template_file']['name'])) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada file yang diunggah.']);
            return;
        }

        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
        $file_type     = $_FILES['template_file']['type'];
        if (!in_array($file_type, $allowed_types)) {
            echo json_encode(['success' => false, 'message' => 'Tipe file tidak didukung. Gunakan PNG/JPG.']);
            return;
        }

        // Ukuran maks 5 MB
        if ($_FILES['template_file']['size'] > 5 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'Ukuran file melebihi batas 5 MB.']);
            return;
        }

        // Nama template yang diinput (opsional), fallback ke nama file
        $template_name = $this->input->post('template_name', TRUE);
        if (empty($template_name)) {
            $template_name = pathinfo($_FILES['template_file']['name'], PATHINFO_FILENAME);
        }
        $template_name = preg_replace('/[^a-zA-Z0-9\s_-]/', '', $template_name);

        // Buat nama file unik agar tidak tumpang-tindih
        $ext       = strtolower(pathinfo($_FILES['template_file']['name'], PATHINFO_EXTENSION));
        $safe_name = 'custom_' . date('YmdHis') . '_' . uniqid() . '.' . $ext;
        $dest      = $custom_dir . $safe_name;

        if (!move_uploaded_file($_FILES['template_file']['tmp_name'], $dest)) {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file ke server.']);
            return;
        }

        // Hitung id urut berdasarkan jumlah file yang sudah ada
        $existing = glob($custom_dir . '*.{png,jpg,jpeg}', GLOB_BRACE);
        $new_id   = count($existing);

        $url = base_url('templates/sertifikat/custom/' . $safe_name);
        $display_name = ucwords(str_replace(['_', '-'], ' ', $template_name));

        echo json_encode([
            'success' => true,
            'id'      => $new_id,
            'name'    => $display_name,
            'url'     => $url,
        ]);
    }

    /**
     * Endpoint JSON: ambil semua data approved untuk AJAX
     */
    public function get_approved_json()
    {
        header('Content-Type: application/json');
        $this->_check_admin_ajax();

        $this->db->where('status', 'approved');
        $this->db->order_by('approved_at', 'DESC');
        $rows = $this->db->get('pengajuan_sertifikat')->result_array();

        echo json_encode(['status' => 'success', 'data' => $rows]);
    }

    /**
     * Halaman verifikasi sertifikat publik (via QR Code)
     */
    public function verifikasi()
    {
        // Ambil semua segment URI mulai dari segment ke-4 ke bawah
        $segments = $this->uri->segment_array();
        // Segment 1: sertifikat, Segment 2: verifikasi, Segment 3 dst: nomor sertifikat
        $nomor_segments = array_slice($segments, 2);
        
        if (empty($nomor_segments)) {
            show_404();
        }

        $nomor_sertifikat = implode('/', $nomor_segments);

        $this->db->where('nomor_sertifikat', $nomor_sertifikat);
        $this->db->where('status', 'approved');
        $sertifikat = $this->db->get('pengajuan_sertifikat')->row_array();

        $data['title']       = 'Verifikasi Sertifikat - ' . $nomor_sertifikat;
        $data['sertifikat']  = $sertifikat;
        $data['nomor']       = $nomor_sertifikat;

        $this->load->view('sertifikat/verifikasi', $data);
    }

    /**
     * AJAX: Import penerima sertifikat dari Excel
     */
    public function import_excel()
    {
        header('Content-Type: application/json');
        
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }

        $this->form_validation->set_rules('nama_pic', 'Nama PIC', 'required|trim');
        $this->form_validation->set_rules('judul_kegiatan', 'Judul Kegiatan', 'required|trim');
        $this->form_validation->set_rules('tanggal_kegiatan', 'Tanggal Kegiatan', 'required');
        $this->form_validation->set_rules('lokasi_kegiatan', 'Lokasi Kegiatan', 'required|trim');
        $this->form_validation->set_rules('data_penerima', 'Data Penerima', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $role = $this->session->userdata('role');
        $is_admin = in_array($role, ['admin', 'kemahasiswaan']);

        $nama_pic = $this->input->post('nama_pic', TRUE);
        $judul_kegiatan = $this->input->post('judul_kegiatan', TRUE);
        $deskripsi_kegiatan = $this->input->post('deskripsi_kegiatan', TRUE);
        $tanggal_kegiatan = $this->input->post('tanggal_kegiatan');
        $lokasi_kegiatan = $this->input->post('lokasi_kegiatan', TRUE);
        
        $import_status = $is_admin ? ($this->input->post('import_status', TRUE) ?: 'approved') : 'submitted';
        $nomor_sertifikat_start = $is_admin ? $this->input->post('nomor_sertifikat_start', TRUE) : null;
        $data_penerima = json_decode($this->input->post('data_penerima'), true);

        if (empty($data_penerima)) {
            echo json_encode(['status' => 'error', 'message' => 'Data penerima tidak valid atau kosong.']);
            return;
        }

        $success_count = 0;
        $num_val = 0;
        $num_len = 0;
        $num_str = '';
        $has_numbering = false;

        if ($import_status === 'approved' && $nomor_sertifikat_start && preg_match('/(\d+)/', $nomor_sertifikat_start, $matches)) {
            $num_str = $matches[1];
            $num_val = (int)$num_str;
            $num_len = strlen($num_str);
            $has_numbering = true;
        }

        $this->db->trans_start();

        foreach ($data_penerima as $index => $row) {
            $nama = trim($row['nama']);
            $nim = trim($row['nim']);
            $jurusan = isset($row['jurusan']) ? trim($row['jurusan']) : '';
            $jabatan = isset($row['jabatan']) ? trim($row['jabatan']) : '';

            // Cari user berdasarkan NIM
            $m = $this->db->get_where('users', ['nim' => $nim])->row();
            if (!$m) {
                // Buat user otomatis
                $username_prefix = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama)) . '_' . $nim;
                $user_data = [
                    'username'   => $username_prefix,
                    'nama'       => $nama,
                    'nim'        => $nim,
                    'prodi'      => $jurusan ?: '-',
                    'email'      => $nim . '@student.telkomuniversity.ac.id',
                    'password'   => password_hash($nim, PASSWORD_DEFAULT),
                    'role'       => 'mahasiswa',
                    'status'     => 'aktif',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->db->insert('users', $user_data);
                $mahasiswa_id = $this->db->insert_id();
            } else {
                $mahasiswa_id = $m->id;
            }

            // Generate nomor sertifikat jika approved
            $nomor_sertifikat = null;
            $qr_code = null;
            if ($import_status === 'approved') {
                if ($has_numbering) {
                    $current_num_str = str_pad($num_val + $index, $num_len, '0', STR_PAD_LEFT);
                    // Ganti kemunculan pertama angka di template
                    $nomor_sertifikat = substr_replace(
                        $nomor_sertifikat_start, 
                        $current_num_str, 
                        strpos($nomor_sertifikat_start, $num_str), 
                        $num_len
                    );
                } else {
                    $nomor_sertifikat = 'SERT/' . $nim . '/' . date('Y');
                }
                $qr_code = base_url('sertifikat/verifikasi/') . $nomor_sertifikat;
            }

            $current_user_id = $this->session->userdata('user_id');
            $insert_data = [
                'mahasiswa_id'       => ($import_status === 'submitted' && $current_user_id) ? $current_user_id : $mahasiswa_id,
                'nim'                => $nim,
                'nama_mahasiswa'     => $nama,
                'prodi'              => $jabatan ?: ($jurusan ?: '-'), // Prodi di DB menyimpan Jabatan/Prodi mahasiswa
                'nama_pic'           => $nama_pic,
                'judul_kegiatan'     => $judul_kegiatan,
                'deskripsi_kegiatan' => $deskripsi_kegiatan,
                'tanggal_kegiatan'   => $tanggal_kegiatan,
                'lokasi_kegiatan'    => $lokasi_kegiatan,
                'status'             => $import_status,
                'nomor_sertifikat'   => $nomor_sertifikat,
                'qr_code'            => $qr_code,
            ];

            $kode = $this->Sertifikat_model->insert($insert_data, $this->session->userdata('user_id'));
            if ($kode) {
                if ($import_status === 'approved') {
                    // Update ke approved dan set tanggal approved
                    $this->db->where('kode_pengajuan', $kode);
                    $this->db->update('pengajuan_sertifikat', [
                        'status' => 'approved',
                        'approved_at' => date('Y-m-d H:i:s'),
                        'reviewed_by' => $this->session->userdata('user_id')
                    ]);
                    
                    // Buka data untuk log
                    $new_sert = $this->db->get_where('pengajuan_sertifikat', ['kode_pengajuan' => $kode])->row();
                    
                    // Log
                    $this->db->insert('sertifikat_status_log', [
                        'pengajuan_id'    => $new_sert->id,
                        'status_lama'     => 'submitted',
                        'status_baru'     => 'approved',
                        'catatan'         => 'Disetujui otomatis melalui import Excel admin',
                        'changed_by'      => $this->session->userdata('user_id'),
                        'changed_by_role' => 'kemahasiswaan',
                        'changed_at'      => date('Y-m-d H:i:s'),
                    ]);
                }
                $success_count++;
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengimport data sertifikat.']);
        } else {
            echo json_encode(['status' => 'success', 'message' => "Berhasil mengimport {$success_count} data penerima sertifikat!"]);
        }
    }

    // Halaman Dashboard Manajemen Tanda Tangan
    public function tanda_tangan()
    {
        $this->_check_admin();

        $data = [
            'title'      => 'Manajemen Tanda Tangan',
            'signatures' => $this->Signature_model->get_all()
        ];

        $this->load->view('sertifikat/tanda_tangan', $data);
    }

    // Aksi Simpan Tanda Tangan
    public function tanda_tangan_simpan()
    {
        $this->_check_admin();

        $nama           = $this->input->post('nama', TRUE);
        $jabatan        = $this->input->post('jabatan', TRUE);
        $signature_data = $this->input->post('signature_data', FALSE);

        if (empty($nama) || empty($jabatan)) {
            $this->session->set_flashdata('error', 'Nama dan Jabatan wajib diisi.');
            redirect('sertifikat/tanda_tangan');
        }

        $image_path = '';

        if (!empty($signature_data)) {
            // Decode base64
            $signature_data = str_replace('data:image/png;base64,', '', $signature_data);
            $signature_data = str_replace(' ', '+', $signature_data);
            $data_decoded   = base64_decode($signature_data);

            $sig_dir = FCPATH . 'uploads/sertifikat/signatures/';
            if (!is_dir($sig_dir)) {
                mkdir($sig_dir, 0755, true);
            }

            $sig_safe_name = 'sig_master_' . date('YmdHis') . '_' . uniqid() . '.png';
            $sig_dest      = $sig_dir . $sig_safe_name;

            if (file_put_contents($sig_dest, $data_decoded)) {
                $image_path = 'uploads/sertifikat/signatures/' . $sig_safe_name;
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan hasil gambar tanda tangan.');
                redirect('sertifikat/tanda_tangan');
            }
        } else {
            if (empty($_FILES['signature_file']['name'])) {
                $this->session->set_flashdata('error', 'File tanda tangan wajib diunggah atau digambar.');
                redirect('sertifikat/tanda_tangan');
            }

            $sig_dir = FCPATH . 'uploads/sertifikat/signatures/';
            if (!is_dir($sig_dir)) {
                mkdir($sig_dir, 0755, true);
            }

            $sig_ext       = strtolower(pathinfo($_FILES['signature_file']['name'], PATHINFO_EXTENSION));
            $sig_safe_name = 'sig_master_' . date('YmdHis') . '_' . uniqid() . '.' . $sig_ext;
            $sig_dest      = $sig_dir . $sig_safe_name;

            if (move_uploaded_file($_FILES['signature_file']['tmp_name'], $sig_dest)) {
                $image_path = 'uploads/sertifikat/signatures/' . $sig_safe_name;
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan file tanda tangan.');
                redirect('sertifikat/tanda_tangan');
            }
        }

        if (!empty($image_path)) {
            $insert_data = [
                'nama'       => $nama,
                'jabatan'    => $jabatan,
                'image_path' => $image_path
            ];
            $this->Signature_model->insert($insert_data);
            $this->session->set_flashdata('success', 'Tanda tangan berhasil ditambahkan!');
        }

        redirect('sertifikat/tanda_tangan');
    }

    // Aksi Update Tanda Tangan
    public function tanda_tangan_update()
    {
        $this->_check_admin();

        $id      = (int) $this->input->post('id');
        $nama    = $this->input->post('nama', TRUE);
        $jabatan = $this->input->post('jabatan', TRUE);

        $sig = $this->Signature_model->get_by_id($id);
        if (!$sig) {
            $this->session->set_flashdata('error', 'Tanda tangan tidak ditemukan.');
            redirect('sertifikat/tanda_tangan');
        }

        if (empty($nama) || empty($jabatan)) {
            $this->session->set_flashdata('error', 'Nama dan Jabatan wajib diisi.');
            redirect('sertifikat/tanda_tangan');
        }

        $update_data = [
            'nama'    => $nama,
            'jabatan' => $jabatan
        ];

        // Jika ada file tanda tangan baru
        if (!empty($_FILES['signature_file']['name'])) {
            $sig_dir = FCPATH . 'uploads/sertifikat/signatures/';
            if (!is_dir($sig_dir)) {
                mkdir($sig_dir, 0755, true);
            }

            $sig_ext       = strtolower(pathinfo($_FILES['signature_file']['name'], PATHINFO_EXTENSION));
            $sig_safe_name = 'sig_master_' . date('YmdHis') . '_' . uniqid() . '.' . $sig_ext;
            $sig_dest      = $sig_dir . $sig_safe_name;

            if (move_uploaded_file($_FILES['signature_file']['tmp_name'], $sig_dest)) {
                // Hapus file lama jika ada
                if (!empty($sig['image_path']) && file_exists(FCPATH . $sig['image_path'])) {
                    @unlink(FCPATH . $sig['image_path']);
                }
                $update_data['image_path'] = 'uploads/sertifikat/signatures/' . $sig_safe_name;
            }
        } else {
            $signature_data = $this->input->post('signature_data', FALSE);
            if (!empty($signature_data)) {
                // Decode base64
                $signature_data = str_replace('data:image/png;base64,', '', $signature_data);
                $signature_data = str_replace(' ', '+', $signature_data);
                $data_decoded   = base64_decode($signature_data);

                $sig_dir = FCPATH . 'uploads/sertifikat/signatures/';
                if (!is_dir($sig_dir)) {
                    mkdir($sig_dir, 0755, true);
                }

                $sig_safe_name = 'sig_master_' . date('YmdHis') . '_' . uniqid() . '.png';
                $sig_dest      = $sig_dir . $sig_safe_name;

                if (file_put_contents($sig_dest, $data_decoded)) {
                    // Hapus file lama jika ada
                    if (!empty($sig['image_path']) && file_exists(FCPATH . $sig['image_path'])) {
                        @unlink(FCPATH . $sig['image_path']);
                    }
                    $update_data['image_path'] = 'uploads/sertifikat/signatures/' . $sig_safe_name;
                }
            }
        }

        $this->Signature_model->update($id, $update_data);
        $this->session->set_flashdata('success', 'Tanda tangan berhasil diperbarui!');
        redirect('sertifikat/tanda_tangan');
    }

    // Aksi Hapus Tanda Tangan
    public function tanda_tangan_hapus($id)
    {
        $this->_check_admin();

        $id  = (int) $id;
        $sig = $this->Signature_model->get_by_id($id);
        if (!$sig) {
            $this->session->set_flashdata('error', 'Tanda tangan tidak ditemukan.');
            redirect('sertifikat/tanda_tangan');
        }

        // Hapus file dari server
        if (!empty($sig['image_path']) && file_exists(FCPATH . $sig['image_path'])) {
            @unlink(FCPATH . $sig['image_path']);
        }

        $this->Signature_model->delete($id);
        $this->session->set_flashdata('success', 'Tanda tangan berhasil dihapus!');
        redirect('sertifikat/tanda_tangan');
    }
}
