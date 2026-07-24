<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | Admin Sertifikat</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- QR Code Generator -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 270px;
            padding: 2rem;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
        }

        .admin-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2C3E50;
            margin: 0;
        }

        .admin-header .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-header .user-info span {
            color: #666;
        }

        .admin-header .user-info .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .admin-header .user-info .logout-btn:hover {
            background: #c0392b;
        }

        /* Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .filter-bar .form-control,
        .filter-bar .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }

        .filter-bar .form-control:focus,
        .filter-bar .form-select:focus {
            border-color: #E67E22;
            box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
        }

        .filter-bar .btn-filter {
            background: #E67E22;
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-bar .btn-filter:hover {
            background: #d35400;
            transform: translateY(-2px);
        }

        .filter-bar .btn-reset {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-bar .btn-reset:hover {
            background: #7f8c8d;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-bottom-color: #E67E22;
            box-shadow: 0 10px 30px rgba(230, 126, 34, 0.15);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(230, 126, 34, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-card .stat-icon i {
            font-size: 1.8rem;
            color: #E67E22;
        }

        .stat-card .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2C3E50;
        }

        .stat-card .stat-detail {
            font-size: 0.8rem;
            color: #999;
            margin-top: 0.5rem;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .table-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 1.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            color: #666;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        /* Status Badges */
        .badge-status {
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-submitted {
            background: #fff3cd;
            color: #856404;
        }

        .badge-approved {
            background: #d4edda;
            color: #155724;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-view {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .btn-view:hover {
            background: #3498db;
            color: white;
        }

        .btn-approve {
            background: rgba(22, 163, 74, 0.1);
            color: #16a34a;
        }

        .btn-approve:hover {
            background: #16a34a;
            color: white;
        }

        .btn-reject {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }

        .btn-reject:hover {
            background: #dc2626;
            color: white;
        }

        .btn-pdf {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }

        .btn-pdf:hover {
            background: #dc2626;
            color: white;
        }

        .btn-view-pdf {
            background: rgba(230, 126, 34, 0.1);
            color: #E67E22;
        }

        .btn-view-pdf:hover {
            background: #E67E22;
            color: white;
        }

        /* Empty State */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #999;
        }

        .empty-state i {
            font-size: 4rem;
            color: #E67E22;
            opacity: 0.3;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 0.9rem;
        }

        /* Modal */
        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, #2C3E50, #1a2632);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .modal-header-approve {
            background: linear-gradient(135deg, #16a34a, #15803d);
        }

        .modal-header-reject {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #eee;
        }

        .alert-info {
            background: rgba(230, 126, 34, 0.1);
            border-left: 4px solid #E67E22;
            border-radius: 10px;
            padding: 1rem;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2C3E50;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #E67E22;
            box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .text-danger {
            font-size: 0.8rem;
        }

        /* Validation Highlights */
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15) !important;
        }
        .form-control.is-invalid:focus, .form-select.is-invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.25) !important;
        }

        /* === MOBILE RESPONSIVE === */
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; max-width: 100%; }
        @media (max-width: 768px) {
            .admin-main { margin-left: 0 !important; padding: 1rem !important; padding-top: 4.5rem !important; max-width: 100vw; overflow-x: hidden; }
            .admin-header { flex-direction: column !important; align-items: stretch !important; gap: 0.75rem; margin-bottom: 1.5rem; }
            .admin-header h1 { font-size: 1.3rem !important; word-break: break-word; }
            .admin-header .user-info > span, .admin-header .user-info .logout-btn { display: none; }
            .admin-header .user-info { width: 100%; display: flex; flex-direction: column; gap: 0.5rem; justify-content: stretch; }
            .admin-header .user-info .btn { width: 100%; margin-right: 0 !important; margin-left: 0 !important; text-align: center; }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .filter-bar .row {
                gap: 1rem;
            }
            .filter-bar .col-md-2.text-end {
                text-align: left !important;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php $this->load->view('partials/admin_sidebar', ['active_menu' => 'sertifikat']); ?>

        <!-- Main Content -->
        <div class="admin-main">
            <div class="admin-header">
                <h1>Manajemen Pengajuan Sertifikat</h1>
                <div class="user-info">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPengajuan" style="background:#f97316;color:white;padding:0.5rem 1.2rem;border-radius:25px;font-weight:600;font-size:0.85rem;transition:all 0.3s;border:none;margin-right:10px;" title="Buat pengajuan sertifikat baru atas nama mahasiswa">
                        <i class="fas fa-plus me-2"></i>Bikin Sertifikat
                    </button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportExcel" style="background:#10b981;color:white;padding:0.5rem 1.2rem;border-radius:25px;border:none;font-weight:600;font-size:0.85rem;transition:all 0.3s;margin-right:10px;" title="Import data penerima sertifikat dari Excel">
                        <i class="fas fa-file-upload me-2"></i>Import Excel
                    </button>
                    <a href="<?= base_url('sertifikat/export_excel_canva') ?>" class="btn" style="background:#27ae60;color:white;padding:0.5rem 1.2rem;border-radius:25px;text-decoration:none;font-weight:600;font-size:0.85rem;transition:all 0.3s;margin-right:10px;" title="Export semua data approved ke Excel format Canva">
                        <i class="fas fa-file-excel me-2"></i>Export Excel Canva
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if(isset($flash_success) && $flash_success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $flash_success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if(isset($flash_error) && $flash_error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= $flash_error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="stat-label">Total Pengajuan</div>
                    <div class="stat-value"><?= number_format($stats['total'] ?? 0) ?></div>
                    <div class="stat-detail">Semua pengajuan</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-label">Menunggu Review</div>
                    <div class="stat-value"><?= number_format($stats['submitted'] ?? 0) ?></div>
                    <div class="stat-detail">
                        <span class="text-warning">Perlu ditindaklanjuti</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-label">Disetujui</div>
                    <div class="stat-value"><?= number_format($stats['approved'] ?? 0) ?></div>
                    <div class="stat-detail">
                        <span class="text-success">Sertifikat diterbitkan</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-label">Ditolak</div>
                    <div class="stat-value"><?= number_format($stats['rejected'] ?? 0) ?></div>
                    <div class="stat-detail">
                        <span class="text-danger">Perlu perbaikan</span>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <form method="get" action="">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="q" placeholder="Cari NIM, nama, judul kegiatan..." value="<?= htmlspecialchars($search ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="submitted" <?= ($status_filter ?? '') == 'submitted' ? 'selected' : '' ?>>Menunggu Review</option>
                                <option value="approved" <?= ($status_filter ?? '') == 'approved' ? 'selected' : '' ?>>Disetujui</option>
                                <option value="rejected" <?= ($status_filter ?? '') == 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn-filter">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <a href="<?= base_url('sertifikat/admin') ?>" class="btn-reset ms-2">
                                <i class="fas fa-times me-2"></i>Reset
                            </a>
                        </div>
                        <div class="col-md-2 text-end">
                            <span class="text-muted"><?= count($pengajuan_list) ?> data ditemukan</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-card">
                <h3><i class="fas fa-list me-2" style="color: #E67E22;"></i>Daftar Pengajuan Sertifikat</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Mahasiswa</th>
                                <th>Judul Kegiatan</th>
                                <th>Tanggal Kegiatan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th>Nomor Sertifikat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pengajuan_list)): ?>
                                <?php foreach ($pengajuan_list as $p): ?>
                                <tr>
                                    <td>
                                        <code style="font-size:0.8rem;"><?= htmlspecialchars($p['kode_pengajuan'] ?? '-') ?></code>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($p['nama_mahasiswa'] ?? '-') ?></strong>
                                        <br>
                                        <small class="text-muted"><?= htmlspecialchars($p['nim'] ?? '-') ?></small>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars(mb_strimwidth($p['judul_kegiatan'] ?? '-', 0, 40, '...')) ?></strong>
                                        <br>
                                        <small class="text-muted">PIC: <?= htmlspecialchars($p['nama_pic'] ?? '-') ?></small>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($p['tanggal_kegiatan'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($p['submitted_at'])) ?></td>
                                    <td>
                                        <?php if ($p['status'] == 'submitted'): ?>
                                            <span class="badge-status badge-submitted">Menunggu</span>
                                        <?php elseif ($p['status'] == 'approved'): ?>
                                            <span class="badge-status badge-approved">Disetujui</span>
                                        <?php else: ?>
                                            <span class="badge-status badge-rejected">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($p['nomor_sertifikat'])): ?>
                                            <small class="text-muted"><?= htmlspecialchars($p['nomor_sertifikat']) ?></small>
                                        <?php else: ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <!-- Detail -->
                                            <button class="btn-action btn-view" title="Detail" onclick="showDetail(<?= $p['id'] ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <?php if ($p['status'] == 'submitted'): ?>
                                                <!-- Setujui -->
                                                <button class="btn-action btn-approve" title="Setujui" onclick="openApprove(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['judul_kegiatan'])) ?>')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <!-- Tolak -->
                                                <button class="btn-action btn-reject" title="Tolak" onclick="openReject(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['judul_kegiatan'])) ?>')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($p['status'] == 'approved' && !empty($p['file_sertifikat'])): ?>
                                                <!-- Lihat PDF -->
                                                <a href="<?= base_url($p['file_sertifikat']) ?>" target="_blank" class="btn-action btn-view-pdf" title="Lihat Sertifikat">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($p['status'] == 'approved' && !empty($p['qr_code'])): ?>
                                                <!-- Lihat QR -->
                                                <button class="btn-action btn-show-qr" title="Lihat QR Code" style="background:#6366f1;color:white;"
													data-qr="<?= htmlspecialchars($p['qr_code'], ENT_QUOTES) ?>"
													data-nomor="<?= htmlspecialchars($p['nomor_sertifikat'], ENT_QUOTES) ?>">
													<i class="fas fa-qrcode"></i>
												</button>
                                            <?php endif; ?>

                                            <!-- Hapus -->
                                            <a href="<?= base_url('sertifikat/admin/hapus/' . $p['id']) ?>" class="btn-action btn-reject" title="Hapus" onclick="confirmDelete(event, '<?= base_url('sertifikat/admin/hapus/' . $p['id']) ?>')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-certificate"></i>
                                            <p>Belum ada pengajuan sertifikat</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-certificate me-2"></i>Detail Pengajuan Sertifikat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approve -->
    <div class="modal fade" id="modalApprove" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-approve">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Setujui Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Anda akan menyetujui pengajuan sertifikat untuk kegiatan <strong id="approveJudul"></strong>.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Sertifikat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomorSertifikat" placeholder="Contoh: SERT/FIK/2026/001">
                        <div class="text-danger" id="nomorError" style="display: none;">Nomor sertifikat wajib diisi</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block fw-bold">Pilihan QR Code</label>
                        <div class="btn-group w-100" role="group" aria-label="QR Code Method">
                            <input type="radio" class="btn-check" name="qr_method" id="qrMethodGenerate" value="generate" checked onchange="toggleQrMethod()">
                            <label class="btn btn-outline-success" for="qrMethodGenerate">
                                <i class="fas fa-qrcode me-2"></i>Generate QR
                            </label>
                            
                            <input type="radio" class="btn-check" name="qr_method" id="qrMethodBrowse" value="browse" onchange="toggleQrMethod()">
                            <label class="btn btn-outline-success" for="qrMethodBrowse">
                                <i class="fas fa-folder-open me-2"></i>Browse QR
                            </label>
                        </div>
                    </div>

                    <div id="qrGenerateContainer" class="mb-3">
                        <label class="form-label">QR Code / Link Verifikasi (Opsional)</label>
                        <input type="text" class="form-control" id="qrCode" placeholder="Kosongkan untuk generate otomatis" oninput="updateQrPreview()">
                        <small class="text-muted">Jika dikosongkan, QR akan di-generate otomatis dari nomor sertifikat</small>
                    </div>

                    <div id="qrBrowseContainer" class="mb-3" style="display: none;">
                        <label class="form-label">Upload QR Code Gambar (JPG/PNG) <span class="text-danger">*</span></label>
                        <div id="qrDropZone" class="border border-2 border-dashed rounded-3 p-4 text-center cursor-pointer mb-2" style="border-style: dashed !important; border-color: #27ae60 !important; background: #f0f9ff; transition: all 0.2s; border-radius: 12px;">
                            <i class="fas fa-cloud-upload-alt fa-2x text-success mb-2"></i>
                            <p class="mb-1 fw-bold">Tarik & Lepas gambar QR di sini</p>
                            <p class="text-muted small mb-2">atau</p>
                            <button type="button" class="btn btn-sm btn-success px-3" onclick="document.getElementById('qrFile').click()" style="border-radius: 8px;">Pilih File</button>
                            <input type="file" id="qrFile" accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="previewUploadedQr(this)">
                        </div>
                        <small class="text-muted">Format file gambar yang didukung: JPG atau PNG.</small>
                        <div class="text-danger" id="qrFileError" style="display: none;">File QR Code wajib diunggah</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preview QR Code</label>
                        <div style="background:#f8f9fa;border-radius:12px;padding:16px;text-align:center;">
                            <canvas id="qrCanvas" style="border-radius:8px; max-width: 100%; height: auto;"></canvas>
                            <p class="text-muted mt-2" style="font-size:0.8rem;" id="qrInfo">Isi nomor sertifikat untuk generate QR</p>
                        </div>
                    </div>



                    <!-- Pilihan Tanda Tangan Tersimpan -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Tanda Tangan Tersimpan (Opsional)</label>
                        <select class="form-select" id="signatureSelect" onchange="previewSavedSignature()" style="border-radius: 8px;">
                            <option value="">-- Unggah file baru saja (seret/pilih di bawah) --</option>
                            <?php if (!empty($signatures)): ?>
                                <?php foreach ($signatures as $sig): ?>
                                    <option value="<?= $sig['id'] ?>" data-image="<?= base_url($sig['image_path']) ?>"><?= htmlspecialchars($sig['nama']) ?> (<?= htmlspecialchars($sig['jabatan'] ?? '-') ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Upload Tanda Tangan (Drag & Drop) -->
                    <div class="mb-3" id="sigUploadContainer">
                        <label class="form-label font-weight-bold">Gambar Tanda Tangan (PNG/JPG) <span class="text-danger">*</span></label>
                        <div id="sigDropZone" class="border border-2 border-dashed rounded-3 p-4 text-center cursor-pointer mb-2" style="border-style: dashed !important; border-color: #f97316 !important; background: #fffaf0; transition: all 0.2s; border-radius: 12px;">
                            <i class="fas fa-signature fa-2x text-warning mb-2" style="color: #f97316 !important;"></i>
                            <p class="mb-1 fw-bold">Tarik & Lepas file Tanda Tangan di sini</p>
                            <p class="text-muted small mb-2">atau</p>
                            <button type="button" class="btn btn-sm btn-warning text-white px-3" onclick="document.getElementById('approveSignature').click()" style="border-radius: 8px; background-color: #f97316; border: none;">Pilih File</button>
                            <input type="file" id="approveSignature" accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="previewUploadedSignature(this)">
                        </div>
                        <small class="text-muted">Format file gambar: PNG/JPG (transparan direkomendasikan).</small>
                    </div>

                    <!-- Preview Area for Saved / Uploaded -->
                    <div id="sigPreviewContainer" style="display: none; background:#f8f9fa; border-radius:12px; padding:16px; text-align:center;" class="mb-2">
                        <img id="sigPreviewImg" style="max-height: 80px; max-width: 100%; border-radius: 8px;" alt="Preview Tanda Tangan">
                        <p class="text-muted mt-2 small mb-0">Preview Tanda Tangan</p>
                    </div>
                    <div class="text-danger" id="signatureError" style="display: none;">Gambar tanda tangan wajib diunggah</div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="catatanApprove" rows="3" placeholder="Tambahkan catatan untuk mahasiswa..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="submitApprove()">
                        <i class="fas fa-check me-2"></i>Setujui
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reject -->
    <div class="modal fade" id="modalReject" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-reject">
                    <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i>Tolak Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert-info" style="background:#fee2e2; border-left-color:#dc2626;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Anda akan menolak pengajuan sertifikat untuk kegiatan <strong id="rejectJudul"></strong>.
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="catatanReject" rows="3" placeholder="Tuliskan alasan penolakan..."></textarea>
                        <div class="text-danger" id="rejectError" style="display: none;">Alasan penolakan wajib diisi</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block fw-bold mb-2">Tandai Inputan yang Salah:</label>
                        <div class="d-flex flex-column gap-2 p-3 bg-light rounded" style="border: 1px solid #e0e0e0;">
                            <div class="form-check">
                                <input class="form-check-input reject-field-check" type="checkbox" value="nama_pic" id="checkPic">
                                <label class="form-check-label" for="checkPic">Nama PIC</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input reject-field-check" type="checkbox" value="judul_kegiatan" id="checkJudul">
                                <label class="form-check-label" for="checkJudul">Judul Kegiatan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input reject-field-check" type="checkbox" value="tanggal_kegiatan" id="checkTanggal">
                                <label class="form-check-label" for="checkTanggal">Tanggal Kegiatan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input reject-field-check" type="checkbox" value="lokasi_kegiatan" id="checkLokasi">
                                <label class="form-check-label" for="checkLokasi">Lokasi Kegiatan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input reject-field-check" type="checkbox" value="deskripsi_kegiatan" id="checkDeskripsi">
                                <label class="form-check-label" for="checkDeskripsi">Deskripsi Kegiatan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input reject-field-check" type="checkbox" value="catatan_tambahan" id="checkCatatan">
                                <label class="form-check-label" for="checkCatatan">Catatan Tambahan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input reject-field-check" type="checkbox" value="file_sertifikat" id="checkFileSertifikat">
                                <label class="form-check-label" for="checkFileSertifikat">File Desain Sertifikat</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input reject-field-check" type="checkbox" value="file_penerima" id="checkFilePenerima">
                                <label class="form-check-label" for="checkFilePenerima">File Data Penerima</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="submitReject()">
                        <i class="fas fa-times me-2"></i>Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal QR Code Viewer -->
    <div class="modal fade" id="modalQR" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                <div class="modal-header" style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:white;border:none;">
                    <h5 class="modal-title"><i class="fas fa-qrcode me-2"></i>QR Code Sertifikat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="text-muted mb-2" style="font-size:0.8rem;">Nomor Sertifikat</p>
                    <p id="qrModalNomor" class="fw-bold text-dark mb-3" style="font-size:0.9rem;"></p>
                    <div style="background:#f8f9fa;border-radius:16px;padding:20px;display:inline-block;max-width:100%;">
                        <canvas id="qrModalCanvas" style="max-width: 100%; height: auto;"></canvas>
                    </div>
                    <p class="text-muted mt-3 mb-0" style="font-size:0.75rem; word-break: break-all;" id="qrModalLink"></p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button class="btn btn-sm" style="background:#6366f1;color:white;border-radius:20px;padding:8px 24px;" onclick="downloadQR()">
                        <i class="fas fa-download me-2"></i>Download QR
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="border-radius:20px;padding:8px 24px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let currentId = null;

        // Show Detail Modal
        function showDetail(id) {
            const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
            document.getElementById('detailBody').innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            modal.show();

            fetch('<?= base_url("sertifikat/get_detail_json/") ?>' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const p = data.pengajuan;
                        const log = data.log || [];
                        
                        let logHtml = '';
                        log.forEach(l => {
                            const statusClass = l.status_baru === 'approved' ? 'text-success' : (l.status_baru === 'rejected' ? 'text-danger' : 'text-warning');
                            const statusText = l.status_baru === 'approved' ? 'Disetujui' : (l.status_baru === 'rejected' ? 'Ditolak' : 'Menunggu');
                            logHtml += `
                                <div class="d-flex gap-3 mb-2">
                                    <div style="width:80px; font-size:0.8rem;">${l.changed_at}</div>
                                    <div><span class="${statusClass} fw-bold">${statusText}</span></div>
                                    <div class="text-muted">${l.catatan || '-'}</div>
                                </div>
                            `;
                        });

                        document.getElementById('detailBody').innerHTML = `
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Kode</th>
                                            <td><code>${p.kode_pengajuan || '-'}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Mahasiswa</th>
                                            <td>${p.nama_mahasiswa || '-'}<br><small class="text-muted">${p.nim || '-'}</small></td>
                                        </tr>
                                        <tr>
                                            <th>Prodi</th>
                                            <td>${p.prodi || '-'}</td>
                                        </tr>
                                        <tr>
                                            <th>PIC</th>
                                            <td>${p.nama_pic || '-'}</td>
                                        </tr>
                                        <tr>
                                            <th>Judul Kegiatan</th>
                                            <td>${p.judul_kegiatan || '-'}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Kegiatan</th>
                                            <td>${p.tanggal_kegiatan ? new Date(p.tanggal_kegiatan).toLocaleDateString('id-ID') : '-'}</td>
                                        </tr>
                                        <tr>
                                            <th>Lokasi</th>
                                            <td>${p.lokasi_kegiatan || '-'}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Status</th>
                                            <td>
                                                ${p.status == 'submitted' ? '<span class="badge-status badge-submitted">Menunggu</span>' : 
                                                  p.status == 'approved' ? '<span class="badge-status badge-approved">Disetujui</span>' : 
                                                  '<span class="badge-status badge-rejected">Ditolak</span>'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Nomor Sertifikat</th>
                                            <td><strong class="text-warning">${p.nomor_sertifikat || '-'}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>QR Code</th>
                                            <td style="word-break: break-all;">${p.qr_code || '-'}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pengajuan</th>
                                            <td>${p.submitted_at ? new Date(p.submitted_at).toLocaleString('id-ID') : '-'}</td>
                                        </tr>
                                        ${p.approved_at ? `<tr><th>Tanggal Disetujui</th><td>${new Date(p.approved_at).toLocaleString('id-ID')}</td></tr>` : ''}
                                        ${p.rejected_at ? `<tr><th>Tanggal Ditolak</th><td>${new Date(p.rejected_at).toLocaleString('id-ID')}</td></tr>` : ''}
                                        <tr>
                                            <th>File Sertifikat</th>
                                            <td>
                                                ${p.file_sertifikat ? 
                                                    `<a href="<?= base_url() ?>${p.file_sertifikat}" target="_blank" class="btn-action btn-view-pdf" style="width:auto; padding:0.3rem 1rem;">
                                                        <i class="fas fa-file-pdf me-1"></i>Lihat
                                                    </a>` : '-'}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>File Penerima</th>
                                            <td>
                                                ${p.file_penerima ? 
                                                    `<a href="<?= base_url() ?>${p.file_penerima}" target="_blank" class="btn-action btn-view" style="width:auto; padding:0.3rem 1rem;">
                                                        <i class="fas fa-file-excel me-1"></i>Lihat
                                                    </a>` : '-'}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            ${p.catatan_tambahan ? `
                            <div class="mt-3 p-3" style="background:#f8f9fa; border-radius:10px;">
                                <strong>Catatan Mahasiswa:</strong><br>
                                ${p.catatan_tambahan}
                            </div>` : ''}
                            ${p.catatan_admin ? `
                            <div class="mt-3 p-3" style="background:#fff3e0; border-radius:10px;">
                                <strong>Catatan Admin:</strong><br>
                                ${p.catatan_admin}
                            </div>` : ''}
                            <div class="mt-3">
                                <h6 class="fw-bold">Riwayat Status</h6>
                                <div style="max-height:200px; overflow-y:auto; background:#f8f9fa; padding:1rem; border-radius:10px;">
                                    ${logHtml || '<p class="text-muted">Belum ada riwayat</p>'}
                                </div>
                            </div>
                        `;
                    } else {
                        document.getElementById('detailBody').innerHTML = '<p class="text-danger">Gagal memuat data</p>';
                    }
                })
                .catch(error => {
                    document.getElementById('detailBody').innerHTML = '<p class="text-danger">Terjadi kesalahan</p>';
                });
        }

        // Open Approve Modal
        function openApprove(id, judul) {
            currentId = id;
            document.getElementById('approveJudul').textContent = judul;
            document.getElementById('nomorSertifikat').value = '';
            document.getElementById('qrCode').value = '';
            document.getElementById('qrFile').value = ''; // Reset file input
            document.getElementById('catatanApprove').value = '';
            document.getElementById('nomorError').style.display = 'none';
            document.getElementById('qrFileError').style.display = 'none';
            
            // Reset Signature
            document.getElementById('approveSignature').value = '';
            document.getElementById('sigPreviewContainer').style.display = 'none';
            document.getElementById('sigPreviewImg').src = '';
            document.getElementById('signatureError').style.display = 'none';
            const sigSelect = document.getElementById('signatureSelect');
            if (sigSelect) {
                sigSelect.value = '';
            }

            // Reset method to generate
            document.getElementById('qrMethodGenerate').checked = true;
            toggleQrMethod();

            // Reset QR canvas
            const canvas = document.getElementById('qrCanvas');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            document.getElementById('qrInfo').textContent = 'Isi nomor sertifikat untuk generate QR';

            new bootstrap.Modal(document.getElementById('modalApprove')).show();
        }

        // Toggle QR Input Fields depending on selected method
        function toggleQrMethod() {
            const isBrowse = document.getElementById('qrMethodBrowse').checked;
            if (isBrowse) {
                document.getElementById('qrGenerateContainer').style.display = 'none';
                document.getElementById('qrBrowseContainer').style.display = 'block';
                previewUploadedQr(document.getElementById('qrFile'));
            } else {
                document.getElementById('qrGenerateContainer').style.display = 'block';
                document.getElementById('qrBrowseContainer').style.display = 'none';
                updateQrPreview();
            }
        }

        // Preview Signature Image
        function previewUploadedSignature(input) {
            const file = input.files[0];
            const errorDiv = document.getElementById('signatureError');
            const previewDiv = document.getElementById('sigPreviewContainer');
            const previewImg = document.getElementById('sigPreviewImg');
            
            if (!file) {
                previewDiv.style.display = 'none';
                return;
            }
            
            errorDiv.style.display = 'none';
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewDiv.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }



        function previewSavedSignature() {
            const select = document.getElementById('signatureSelect');
            const selectedOption = select.options[select.selectedIndex];
            const errorDiv = document.getElementById('signatureError');
            const previewDiv = document.getElementById('sigPreviewContainer');
            const previewImg = document.getElementById('sigPreviewImg');

            if (!selectedOption || !selectedOption.value) {
                previewDiv.style.display = 'none';
                return;
            }

            errorDiv.style.display = 'none';
            const imageUrl = selectedOption.getAttribute('data-image');
            previewImg.src = imageUrl;
            previewDiv.style.display = 'block';
        }

        // Preview local QR Image on Canvas
        function previewUploadedQr(input) {
            const file = input.files[0];
            const canvas = document.getElementById('qrCanvas');
            const ctx = canvas.getContext('2d');
            const qrInfo = document.getElementById('qrInfo');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        canvas.width = 180;
                        canvas.height = 180;
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, 180, 180);
                        qrInfo.textContent = 'Preview QR Code dari File: ' + file.name;
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
                document.getElementById('qrFileError').style.display = 'none';
            } else {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                qrInfo.textContent = 'Belum ada file yang dipilih';
            }
        }

        // Update QR when nomor sertifikat changes and setup Drag & Drop
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nomorSertifikat').addEventListener('input', updateQrPreview);

            const dropZone = document.getElementById('qrDropZone');
            const fileInput = document.getElementById('qrFile');
            
            if (dropZone && fileInput) {
                // Prevent default drag behaviors
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }, false);
                });
                
                // Highlight drop zone when item is dragged over it
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, function() {
                        dropZone.style.background = '#e0f2fe';
                        dropZone.style.borderColor = '#15803d';
                    }, false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, function() {
                        dropZone.style.background = '#f0f9ff';
                        dropZone.style.borderColor = '#27ae60';
                    }, false);
                });
                
                // Handle dropped files
                dropZone.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    if (files.length) {
                        fileInput.files = files;
                        previewUploadedQr(fileInput);
                    }
                }, false);
            }
            // Excel Drag & Drop setup
            const excelDropZone = document.getElementById('excelDropZone');
            const excelFileInput = document.getElementById('excelFileImport');
            
            if (excelDropZone && excelFileInput) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    excelDropZone.addEventListener(eventName, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }, false);
                });
                
                ['dragenter', 'dragover'].forEach(eventName => {
                    excelDropZone.addEventListener(eventName, function() {
                        excelDropZone.style.background = '#e0f2fe';
                        excelDropZone.style.borderColor = '#15803d';
                    }, false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    excelDropZone.addEventListener(eventName, function() {
                        excelDropZone.style.background = '#f0f9ff';
                        excelDropZone.style.borderColor = '#27ae60';
                    }, false);
                });
                
                excelDropZone.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    if (files.length) {
                        excelFileInput.files = files;
                        handleExcelImportDirect(excelFileInput);
                    }
                }, false);
            }
            
            // Signature Drag & Drop setup
            const sigDropZone = document.getElementById('sigDropZone');
            const sigFileInput = document.getElementById('approveSignature');
            
            if (sigDropZone && sigFileInput) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    sigDropZone.addEventListener(eventName, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }, false);
                });
                
                ['dragenter', 'dragover'].forEach(eventName => {
                    sigDropZone.addEventListener(eventName, function() {
                        sigDropZone.style.background = '#ffedd5';
                        sigDropZone.style.borderColor = '#ea580c';
                    }, false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    sigDropZone.addEventListener(eventName, function() {
                        sigDropZone.style.background = '#fffaf0';
                        sigDropZone.style.borderColor = '#f97316';
                    }, false);
                });
                
                sigDropZone.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    if (files.length) {
                        sigFileInput.files = files;
                        previewUploadedSignature(sigFileInput);
                    }
                }, false);
            }
        });

        // Generate QR Code preview
        function updateQrPreview() {
            // Only update preview if in generate mode
            if (document.getElementById('qrMethodBrowse').checked) return;

            const nomor = document.getElementById('nomorSertifikat').value.trim();
            const customLink = document.getElementById('qrCode').value.trim();
            const canvas = document.getElementById('qrCanvas');
            const qrInfo = document.getElementById('qrInfo');

            const qrData = customLink || (nomor ? '<?= base_url('sertifikat/verifikasi/') ?>' + nomor : '');

            if (!qrData) {
                canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                qrInfo.textContent = 'Isi nomor sertifikat untuk generate QR';
                return;
            }

            QRCode.toCanvas(canvas, qrData, { width: 180, margin: 2, color: { dark: '#1e293b', light: '#ffffff' } }, function(error) {
                if (!error) {
                    qrInfo.textContent = customLink ? 'QR dari link custom' : 'QR dari nomor sertifikat';
                }
            });
        }

        // Submit Approve — FIXED: tidak pakai event.target global
        function submitApprove() {
            const nomor = document.getElementById('nomorSertifikat').value.trim();
            if (!nomor) {
                document.getElementById('nomorError').style.display = 'block';
                return;
            }

            // Tanda Tangan validation
            const select = document.getElementById('signatureSelect');
            const signatureId = select.value;
            const sigInput = document.getElementById('approveSignature');
            let signatureFile = null;

            if (!signatureId) {
                if (sigInput.files.length === 0) {
                    document.getElementById('signatureError').textContent = 'Gambar tanda tangan wajib diunggah atau pilih tanda tangan tersimpan';
                    document.getElementById('signatureError').style.display = 'block';
                    return;
                }
                signatureFile = sigInput.files[0];
            }
            document.getElementById('signatureError').style.display = 'none';

            const isBrowse = document.getElementById('qrMethodBrowse').checked;
            let qrFile = null;
            if (isBrowse) {
                const fileInput = document.getElementById('qrFile');
                if (fileInput.files.length === 0) {
                    document.getElementById('qrFileError').style.display = 'block';
                    return;
                }
                qrFile = fileInput.files[0];
            }

            // Get button by selector (fix: tidak pakai event.target)
            const btn = document.querySelector('#modalApprove .btn-success');
            const originalText = btn ? btn.innerHTML : '';
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                btn.disabled = true;
            }

            const formData = new FormData();
            formData.append('id', currentId);
            formData.append('action', 'approve');
            formData.append('nomor_sertifikat', nomor);
            if (signatureId) {
                formData.append('signature_id', signatureId);
            } else {
                formData.append('signature_file', signatureFile);
            }
            formData.append('catatan', document.getElementById('catatanApprove').value.trim());
            formData.append('qr_method', isBrowse ? 'browse' : 'generate');

            if (isBrowse) {
                formData.append('qr_file', qrFile);
            } else {
                const customLink = document.getElementById('qrCode').value.trim();
                const qrData = customLink || '<?= base_url('sertifikat/verifikasi/') ?>' + nomor;
                formData.append('qr_code', qrData);
            }

            fetch('<?= base_url("sertifikat/update_status") ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error('Server error ' + response.status + ': ' + text.substring(0, 300));
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalApprove'));
                    if (modal) modal.hide();
                    // Tampilkan notifikasi sukses
                    const alertEl = document.createElement('div');
                    alertEl.className = 'alert alert-success alert-dismissible fade show';
                    alertEl.innerHTML = '<i class="fas fa-check-circle me-2"></i><strong>Berhasil!</strong> Pengajuan sertifikat disetujui. QR Code telah disimpan. <button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                    document.querySelector('.admin-main').prepend(alertEl);
                    setTimeout(() => location.reload(), 1800);
                } else {
                    alert('Gagal: ' + (data.message || 'Unknown error'));
                    if (btn) { btn.innerHTML = originalText; btn.disabled = false; }
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error.message);
                if (btn) { btn.innerHTML = originalText; btn.disabled = false; }
            });
        }
        // Open Reject Modal
        function openReject(id, judul) {
            currentId = id;
            document.getElementById('rejectJudul').textContent = judul;
            document.getElementById('catatanReject').value = '';
            document.getElementById('rejectError').style.display = 'none';
            
            // Reset checkboxes
            document.querySelectorAll('.reject-field-check').forEach(chk => chk.checked = false);
            
            new bootstrap.Modal(document.getElementById('modalReject')).show();
        }

        // Submit Reject
        function submitReject() {
            const catatanText = document.getElementById('catatanReject').value.trim();
            if (!catatanText) {
                document.getElementById('rejectError').style.display = 'block';
                return;
            }

            // Get selected wrong fields
            const wrongFields = [];
            document.querySelectorAll('.reject-field-check:checked').forEach(chk => {
                wrongFields.push(chk.value);
            });

            // Construct structured JSON catatan
            const envelope = {
                catatan: catatanText,
                wrong_fields: wrongFields
            };

            const formData = new URLSearchParams();
            formData.append('id', currentId);
            formData.append('action', 'reject');
            formData.append('catatan', JSON.stringify(envelope));

            fetch('<?= base_url("sertifikat/update_status") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    bootstrap.Modal.getInstance(document.getElementById('modalReject')).hide();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan koneksi');
            });
        }

        // Show QR Code Modal
        let currentQrData = '';
        function showQR(qrData, nomor) {
            currentQrData = qrData;
            document.getElementById('qrModalNomor').textContent = nomor;
            document.getElementById('qrModalLink').textContent = qrData;

            const canvas = document.getElementById('qrModalCanvas');
            QRCode.toCanvas(canvas, qrData, {
                width: 220,
                margin: 2,
                color: { dark: '#1e293b', light: '#ffffff' }
            }, function(error) {
                if (error) console.error('QR Error:', error);
            });

            new bootstrap.Modal(document.getElementById('modalQR')).show();
        }

		document.querySelectorAll('.btn-show-qr').forEach(function(btn) {
			btn.addEventListener('click', function() {
				showQR(this.dataset.qr, this.dataset.nomor);
			});
		});

        // Download QR sebagai PNG
        function downloadQR() {
            const canvas = document.getElementById('qrModalCanvas');
            const nomor = document.getElementById('qrModalNomor').textContent.replace(/[^a-zA-Z0-9\-]/g, '_');
            const link = document.createElement('a');
            link.download = 'QR_' + nomor + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }

        // Auto hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 5000);
    </script>

    <!-- Modal Bikin Pengajuan Baru (Admin) -->
    <div class="modal fade" id="modalTambahPengajuan" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus me-2 text-warning"></i>Bikin Sertifikat Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('sertifikat/admin/tambah') ?>" method="POST">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <!-- Pilih Mahasiswa (Autocomplete) -->
                            <div class="col-md-6" id="autocompleteWrapper">
                                <label class="form-label font-weight-bold">Mahasiswa <span class="text-danger">*</span></label>
                                <input type="hidden" name="mahasiswa_id" id="mahasiswaIdHidden">
                                <div style="position: relative;">
                                    <input
                                        type="text"
                                        id="searchMahasiswa"
                                        class="form-control"
                                        placeholder="Ketik nama atau NIM mahasiswa..."
                                        autocomplete="new-password"
                                        readonly
                                        required
                                        onfocus="this.removeAttribute('readonly')"
                                        oninvalid="this.setCustomValidity('Pilih mahasiswa dari daftar')"
                                        oninput="this.setCustomValidity('')"
                                        style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;"
                                    >
                                    <div id="autocompleteDropdown" style="display:none; position:absolute; top:100%; left:0; right:0; background:#fff; border:2px solid #e2e8f0; border-top:none; border-radius:0 0 10px 10px; max-height:220px; overflow-y:auto; z-index:9999; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                                    </div>
                                </div>
                                <style>
                                    .autocomplete-item { padding: 9px 14px; cursor: pointer; font-size: 0.88rem; border-bottom: 1px solid #f1f5f9; }
                                    .autocomplete-item:hover { background: #f0f9ff; }
                                    .autocomplete-item:last-child { border-bottom: none; }
                                </style>
                            </div>
                            
                            <!-- Nama PIC -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Nama PIC <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_pic" placeholder="Contoh: Dosen Pembimbing" required style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;">
                            </div>

                            <!-- Judul Kegiatan -->
                            <div class="col-12">
                                <label class="form-label font-weight-bold">Judul Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="judul_kegiatan" placeholder="Contoh: Lomba Nasional UI/UX 2026" required style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;">
                            </div>

                            <!-- Deskripsi Kegiatan -->
                            <div class="col-12">
                                <label class="form-label font-weight-bold">Deskripsi Kegiatan</label>
                                <textarea class="form-control" name="deskripsi_kegiatan" rows="3" placeholder="Masukkan deskripsi singkat pencapaian..." style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;"></textarea>
                            </div>

                            <!-- Tanggal Kegiatan -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_kegiatan" required style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;">
                            </div>

                            <!-- Lokasi Kegiatan -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Lokasi Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="lokasi_kegiatan" placeholder="Contoh: Universitas Telkom, Bandung" required style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;">
                            </div>

                            <!-- Catatan Tambahan -->
                            <div class="col-12">
                                <label class="form-label font-weight-bold">Catatan Tambahan (Opsional)</label>
                                <textarea class="form-control" name="catatan_tambahan" rows="2" placeholder="Catatan internal admin..." style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 d-flex gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Batal</button>
                        <button type="submit" class="btn btn-success px-4" style="border-radius: 10px; font-weight: 600; background: #27ae60; border: none; padding: 0.55rem 1.5rem;">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import Excel (Admin) -->
    <div class="modal fade" id="modalImportExcel" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header bg-success text-white py-3">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-file-excel me-2 text-warning"></i>Import Penerima Sertifikat dari Excel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formImportExcel" onsubmit="submitImportExcel(event)">
                    <div class="modal-body p-4">
                        <div class="alert alert-info" style="border-radius: 10px; background-color: #f0fdf4; border-color: #bbf7d0; color: #15803d; font-size: 0.85rem;">
                            <i class="fas fa-info-circle me-2"></i>
                            Format kolom Excel/CSV yang wajib ada di baris pertama: <strong>NO, NAMA, NIM, JURUSAN, JABATAN</strong>.
                            <br><em>* Jika NIM mahasiswa belum terdaftar di sistem, akun mahasiswa baru akan otomatis dibuat dengan password default = NIM.</em>
                            <hr style="margin: 8px 0; border-top: 1px dashed #bbf7d0;">
                            <a href="<?= base_url('sertifikat/download_template') ?>" class="btn btn-xs btn-outline-success text-success fw-bold" style="padding: 2px 8px; font-size: 0.75rem; border-radius: 4px; text-decoration: none; border: 1px solid #27ae60; background: white; transition: all 0.2s; display: inline-block;">
                                <i class="fas fa-download me-1"></i> Download Template Excel/CSV
                            </a>
                        </div>
                        <div class="row g-3">
                            <!-- File Excel -->
                            <div class="col-12">
                                <label class="form-label font-weight-bold">File Excel / CSV <span class="text-danger">*</span></label>
                                <div id="excelDropZone" class="border border-2 border-dashed rounded-3 p-4 text-center cursor-pointer mb-2" style="border-style: dashed !important; border-color: #27ae60 !important; background: #f0f9ff; transition: all 0.2s; border-radius: 12px;">
                                    <i class="fas fa-file-excel fa-2x text-success mb-2"></i>
                                    <p class="mb-1 fw-bold">Tarik & Lepas file Excel/CSV di sini</p>
                                    <p class="text-muted small mb-2">atau</p>
                                    <button type="button" class="btn btn-sm btn-success px-3" onclick="document.getElementById('excelFileImport').click()" style="border-radius: 8px;">Pilih File</button>
                                    <input type="file" id="excelFileImport" accept=".xlsx, .xls, .csv" style="display: none;" onchange="handleExcelImportDirect(this)">
                                </div>
                                <input type="hidden" name="data_penerima" id="hiddenDataPenerima" required>
                                <div id="excelImportFeedback" class="mt-2 fw-bold text-success" style="display: none;"></div>
                            </div>

                            <!-- Nama PIC -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Nama PIC <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_pic" placeholder="Contoh: Dosen Pembimbing" required style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;">
                            </div>

                            <!-- Judul Kegiatan -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Judul Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="judul_kegiatan" placeholder="Contoh: Lomba Nasional UI/UX 2026" required style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;">
                            </div>

                            <!-- Deskripsi Kegiatan -->
                            <div class="col-12">
                                <label class="form-label font-weight-bold">Deskripsi Kegiatan</label>
                                <textarea class="form-control" name="deskripsi_kegiatan" rows="2" placeholder="Masukkan deskripsi singkat pencapaian..." style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;"></textarea>
                            </div>

                            <!-- Tanggal Kegiatan -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Tanggal Kegiatan <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_kegiatan" required style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;">
                            </div>

                            <!-- Lokasi Kegiatan -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Lokasi Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="lokasi_kegiatan" placeholder="Contoh: Universitas Telkom, Bandung" required style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;">
                            </div>

                            <!-- Opsi Status Sertifikat -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Status Sertifikat Setelah Import</label>
                                <select class="form-select" name="import_status" style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;">
                                    <option value="approved">Langsung Setujui (Approved)</option>
                                    <option value="submitted">Menunggu Persetujuan (Submitted)</option>
                                </select>
                            </div>

                            <!-- Pola Nomor Sertifikat Awal -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold">Nomor Sertifikat Awal (Opsional)</label>
                                <input type="text" class="form-control" name="nomor_sertifikat_start" placeholder="Contoh: 001/FIK/2026" style="border-radius: 10px; padding: 10px; border: 2px solid #e2e8f0;">
                                <small class="text-muted">Nomor urut numerik pertama akan otomatis bertambah (increment) tiap baris.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 d-flex gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Batal</button>
                        <button type="submit" id="btnSubmitImport" class="btn btn-success px-4" style="border-radius: 10px; font-weight: 600; background: #27ae60; border: none; padding: 0.55rem 1.5rem;">
                            <i class="fas fa-file-import me-2"></i>Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
</body>
</html>

<script>
// Autocomplete Mahasiswa — script ini diletakkan SETELAH modal HTML agar DOM sudah siap
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('modalTambahPengajuan');
    if (!modalEl) return;

    const mahasiswaData = [
        <?php if (!empty($mahasiswa_list)): ?>
            <?php foreach ($mahasiswa_list as $idx => $m): ?>
                {id: <?= $m['id'] ?>, nama: "<?= addslashes(htmlspecialchars($m['nama'])) ?>", nim: "<?= addslashes(htmlspecialchars($m['nim'] ?? '')) ?>"}
                <?= $idx < count($mahasiswa_list) - 1 ? ',' : '' ?>
            <?php endforeach; ?>
        <?php endif; ?>
    ];

    modalEl.addEventListener('shown.bs.modal', function() {
        const input    = document.getElementById('searchMahasiswa');
        const dropdown = document.getElementById('autocompleteDropdown');
        if (!input || !dropdown) return;

        // Reset state setiap kali modal dibuka
        input.value = '';
        document.getElementById('mahasiswaIdHidden').value = '';
        dropdown.style.display = 'none';

        function renderItems(items) {
            dropdown.innerHTML = '';
            if (items.length === 0) {
                dropdown.innerHTML = '<div style="padding:10px 14px;color:#94a3b8;font-size:0.85rem;">Tidak ada hasil ditemukan</div>';
                dropdown.style.display = 'block';
                return;
            }
            items.forEach(function(m) {
                const row = document.createElement('div');
                row.className = 'autocomplete-item';
                row.innerHTML =
                    '<i class="fas fa-user-graduate me-2" style="color:#E67E22;"></i>' +
                    '<strong>' + m.nama + '</strong>' +
                    (m.nim && m.nim !== '-' ? '<span style="color:#94a3b8;font-size:0.78rem;margin-left:8px;">' + m.nim + '</span>' : '');
                row.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    input.value = m.nama + (m.nim && m.nim !== '-' ? ' (' + m.nim + ')' : '');
                    document.getElementById('mahasiswaIdHidden').value = m.id;
                    dropdown.style.display = 'none';
                });
                dropdown.appendChild(row);
            });
            dropdown.style.display = 'block';
        }

        input.addEventListener('focus', function() {
            const q = this.value.toLowerCase().trim();
            renderItems(q ? mahasiswaData.filter(m => m.nama.toLowerCase().includes(q) || (m.nim && m.nim.toLowerCase().includes(q))) : mahasiswaData.slice(0, 8));
        });

        input.addEventListener('input', function() {
            document.getElementById('mahasiswaIdHidden').value = '';
            const q = this.value.toLowerCase().trim();
            if (!q) { dropdown.style.display = 'none'; return; }
            renderItems(mahasiswaData.filter(m => m.nama.toLowerCase().includes(q) || (m.nim && m.nim.toLowerCase().includes(q))));
        });
    });

    modalEl.addEventListener('hidden.bs.modal', function() {
        const dropdown = document.getElementById('autocompleteDropdown');
        if (dropdown) dropdown.style.display = 'none';
        
        // Bersihkan semua class is-invalid jika modal ditutup
        modalEl.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#autocompleteWrapper')) {
            const dropdown = document.getElementById('autocompleteDropdown');
            if (dropdown) dropdown.style.display = 'none';
        }
    });

    // Validasi form dan penandaan input salah
    const form = modalEl.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let firstInvalid = null;

            // Periksa input wajib
            form.querySelectorAll('[required]').forEach(el => {
                // Cek jika field kosong atau autocomplete mahasiswa belum memilih ID
                if (el.id === 'searchMahasiswa' && !document.getElementById('mahasiswaIdHidden').value) {
                    el.classList.add('is-invalid');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = el;
                } else if (!el.value.trim()) {
                    el.classList.add('is-invalid');
                    isValid = false;
                    if (!firstInvalid) firstInvalid = el;
                } else {
                    el.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                if (firstInvalid) firstInvalid.focus();
            }
        });

        // Hapus tanda merah ketika user mengetik/memilih kembali
        form.querySelectorAll('.form-control, .form-select').forEach(el => {
            el.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
            el.addEventListener('change', function() {
                this.classList.remove('is-invalid');
            });
        });
    }
});



// Custom SweetAlert2 Delete Confirmation
function confirmDelete(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data pengajuan sertifikat ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

// Parse Excel file client-side using SheetJS
function handleExcelImportDirect(input) {
    const file = input.files[0];
    if (!file) return;
    
    const feedback = document.getElementById('excelImportFeedback');
    feedback.style.display = 'block';
    feedback.className = 'mt-2 text-warning';
    feedback.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Membaca file Excel...';
    
    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            
            const rawRows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
            if (rawRows.length < 2) {
                feedback.className = 'mt-2 text-danger';
                feedback.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>File Excel kosong atau tidak memiliki data.';
                document.getElementById('hiddenDataPenerima').value = '';
                return;
            }
            
            // Cari header kolom
            const headers = rawRows[0].map(h => h ? String(h).toUpperCase().trim() : '');
            const idxNama = headers.indexOf('NAMA');
            const idxNim = headers.indexOf('NIM');
            const idxJurusan = headers.indexOf('JURUSAN');
            const idxJabatan = headers.indexOf('JABATAN');
            
            if (idxNama === -1 || idxNim === -1) {
                feedback.className = 'mt-2 text-danger';
                feedback.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Kolom NAMA dan NIM wajib ada di baris pertama Excel.';
                document.getElementById('hiddenDataPenerima').value = '';
                return;
            }
            
            const mappedData = [];
            for (let i = 1; i < rawRows.length; i++) {
                const row = rawRows[i];
                if (!row || row.length === 0) continue;
                
                const nama = row[idxNama] ? String(row[idxNama]).trim() : '';
                const nim = row[idxNim] ? String(row[idxNim]).trim() : '';
                const jurusan = idxJurusan !== -1 && row[idxJurusan] ? String(row[idxJurusan]).trim() : '';
                const jabatan = idxJabatan !== -1 && row[idxJabatan] ? String(row[idxJabatan]).trim() : '';
                
                if (nama && nim) {
                    mappedData.push({
                        nama: nama,
                        nim: nim,
                        jurusan: jurusan,
                        jabatan: jabatan
                    });
                }
            }
            
            if (mappedData.length === 0) {
                feedback.className = 'mt-2 text-danger';
                feedback.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Tidak ada baris data mahasiswa yang valid.';
                document.getElementById('hiddenDataPenerima').value = '';
                return;
            }
            
            document.getElementById('hiddenDataPenerima').value = JSON.stringify(mappedData);
            feedback.className = 'mt-2 text-success';
            feedback.innerHTML = `<i class="fas fa-check-circle me-2"></i>Berhasil membaca <strong>${mappedData.length}</strong> data penerima dari Excel.`;
        } catch (err) {
            feedback.className = 'mt-2 text-danger';
            feedback.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Gagal mengurai file Excel: ' + err.message;
            document.getElementById('hiddenDataPenerima').value = '';
        }
    };
    reader.onerror = function() {
        feedback.className = 'mt-2 text-danger';
        feedback.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Terjadi kesalahan saat membaca file.';
        document.getElementById('hiddenDataPenerima').value = '';
    };
    reader.readAsArrayBuffer(file);
}

// Submit Excel Import Form via AJAX
function submitImportExcel(event) {
    event.preventDefault();
    
    const hiddenData = document.getElementById('hiddenDataPenerima').value;
    if (!hiddenData) {
        alert('Silakan pilih file Excel yang valid terlebih dahulu.');
        return;
    }
    
    const form = document.getElementById('formImportExcel');
    const btn = document.getElementById('btnSubmitImport');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengimport...';
    btn.disabled = true;
    
    const formData = new FormData(form);
    
    fetch('<?= base_url("sertifikat/admin/import_excel") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error('Server error ' + response.status + ': ' + text.substring(0, 300));
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalImportExcel'));
            if (modal) modal.hide();
            
            // Tampilkan alert sukses
            const alertEl = document.createElement('div');
            alertEl.className = 'alert alert-success alert-dismissible fade show';
            alertEl.innerHTML = `<i class="fas fa-check-circle me-2"></i><strong>Berhasil!</strong> ${data.message} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            document.querySelector('.admin-main').prepend(alertEl);
            
            setTimeout(() => location.reload(), 2000);
        } else {
            alert('Gagal: ' + (data.message || 'Unknown error'));
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan: ' + error.message);
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>
