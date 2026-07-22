<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | Admin Beasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

        /* Sidebar */
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2C3E50, #1a2632);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .sidebar-header p {
            margin: 0.5rem 0 0;
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(230, 126, 34, 0.2);
            color: white;
            border-left-color: #E67E22;
        }

        .sidebar-menu a i {
            width: 20px;
            color: #E67E22;
        }

        .sidebar-menu .menu-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 1rem 0;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 280px;
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
            text-decoration: none;
            display: inline-block;
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
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 100%;
            background: #e0e0e0;
        }

        .stat-card.stat-pending::after { background: #f1c40f; }
        .stat-card.stat-process::after { background: #3498db; }
        .stat-card.stat-approved::after { background: #2ecc71; }
        .stat-card.stat-rejected::after { background: #e74c3c; }

        .stat-icon {
            font-size: 2rem;
            color: #bdc3c7;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #2c3e50;
            margin: 0.2rem 0;
        }

        .stat-detail {
            font-size: 0.75rem;
            color: #95a5a6;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .table-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 1.5rem;
        }

        .table {
            vertical-align: middle;
        }

        .table th {
            font-weight: 700;
            color: #7f8c8d;
            border-bottom: 2px solid #eee;
            padding: 1rem 0.75rem;
        }

        .table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #eee;
        }

        .badge-status {
            padding: 0.35rem 0.8rem;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            display: inline-block;
        }

        .badge-pending { background: #fef9c3; color: #a16207; }
        .badge-process { background: #dbeafe; color: #1e40af; }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }

        .btn-action-custom {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            color: #2C3E50;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-action-custom:hover {
            background: #E67E22;
            color: white;
            border-color: #E67E22;
        }

        /* Detail Modal */
        .detail-section {
            border-bottom: 1px solid #eee;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .detail-section-title {
            font-weight: 700;
            font-size: 1rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .detail-item .label {
            font-size: 0.8rem;
            color: #95a5a6;
            margin-bottom: 2px;
        }

        .detail-item .value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .detail-text-block {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            font-size: 0.9rem;
            line-height: 1.6;
            white-space: pre-line;
            border: 1px solid #eef2f5;
        }

        .file-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #eff6ff;
            color: #2563eb;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid #dbeafe;
            transition: all 0.2s ease;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        .file-link-btn:hover {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        /* === MOBILE RESPONSIVE CSS === */
        html, body { overflow-x: hidden; max-width: 100%; }

        .mobile-topbar {
            display: none; position: fixed; top: 0; left: 0; right: 0; z-index: 1100;
            background: linear-gradient(135deg, #2C3E50, #1a2632);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
        }
        .topbar-inner { display: flex; align-items: center; justify-content: space-between; height: 54px; padding: 0 0.75rem; }
        .hamburger-btn {
            display: none; background: rgba(255, 255, 255, 0.15); color: white; border: none;
            border-radius: 8px; width: 38px; height: 38px; align-items: center; justify-content: center;
            font-size: 1.1rem; cursor: pointer;
        }
        .topbar-right { display: flex; align-items: center; gap: 0.5rem; flex: 1; justify-content: flex-end; }
        .topbar-username { display: flex; align-items: center; gap: 0.35rem; color: rgba(255, 255, 255, 0.9); font-size: 0.78rem; font-weight: 500; }
        .topbar-username i { color: #E67E22; font-size: 1rem; }
        .topbar-logout {
            background: #e74c3c; color: white; border: none; border-radius: 8px;
            padding: 0.38rem 0.8rem; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.3rem;
        }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 999; backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active { display: block; }

        @media (max-width: 768px) {
            .mobile-topbar { display: block; }
            .hamburger-btn { display: flex; }
            .admin-sidebar {
                position: fixed !important; left: -280px !important; z-index: 1000;
                transition: left 0.3s ease; width: 280px !important; display: block !important;
            }
            .admin-sidebar.open { left: 0 !important; }
            .admin-main {
                margin-left: 0 !important; padding: 1rem !important; padding-top: 4.5rem !important; max-width: 100vw;
            }
            .admin-header { flex-direction: column !important; align-items: stretch !important; gap: 0.75rem; margin-bottom: 1.5rem; }
            .admin-header h1 { font-size: 1.5rem; }
            .admin-header .user-info { display: none; }
            .table-card { padding: 1.2rem; overflow-x: auto; }
            .stats-grid { grid-template-columns: 1fr; }
            .filter-bar .row > div { margin-bottom: 1rem; }
        }
    </style>
</head>
<body>
    <!-- Mobile Topbar -->
    <div class="mobile-topbar" id="mobileTopbar">
        <div class="topbar-inner">
            <div class="d-flex align-items-center gap-2">
                <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()">
                    <i class="fas fa-bars" id="hamburgerIcon"></i>
                </button>
                <span class="topbar-username">
                    <i class="fas fa-user-circle"></i>
                    <span><?= htmlspecialchars($this->session->userdata('nama') ?? 'Admin') ?></span>
                </span>
            </div>
            <a href="<?= base_url('login/logout') ?>" class="topbar-logout">
                <i class="fas fa-sign-out-alt"></i>Logout
            </a>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <h3>Admin FIK</h3>
                <p>Manajemen Beasiswa</p>
            </div>
            
            <div class="sidebar-menu">
                <a href="<?= base_url('admin/edit_hero') ?>" class="<?= ($this->uri->segment(2) == 'edit_hero') ? 'active' : '' ?>">
                    <i class="fas fa-desktop"></i>
                    <span>Dashboard</span>
                </a>

                <a href="<?= base_url('admin/proposal') ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>Proposal</span>
                </a>

                <a href="<?= base_url('admin/beasiswa') ?>"  class="active">
                    <i class="fas fa-graduation-cap"></i><span>Beasiswa</span>
                </a>

                <a href="<?= base_url('sertifikat/admin') ?>">
                    <i class="fas fa-certificate"></i>
                    <span>Sertifikat</span>
                </a>
                <a href="<?= base_url('tak_admin') ?>">
                    <i class="fas fa-file-signature"></i>
                    <span>TAK</span>
                </a>
                <a href="<?= base_url('berita/admin') ?>">
                    <i class="fas fa-newspaper"></i>
                    <span>Berita</span>
                </a>
                <a href="<?= base_url('admin/organisasi') ?>">
                    <i class="fas fa-users"></i>
                    <span>Organisasi</span>
                </a>

                <a href="<?= base_url('admin/direktorat') ?>">
                    <i class="fas fa-building"></i>
                    <span>Direktorat</span>
                </a>

                <a href="<?= base_url('admin/mitra') ?>">
                    <i class="fas fa-handshake"></i>
                    <span>Mitra & Recog</span>
                </a>

                <a href="<?= base_url('admin/testimoni') ?>">
                    <i class="fas fa-comments"></i>
                    <span>Testimoni Alumni</span>
                </a>

                <a href="<?= base_url('admin/tentang_kami') ?>" >
                    <i class="fas fa-info-circle"></i><span>Tentang Kami</span>
                </a>

                <a href="<?= base_url('admin/pedoman') ?>">
                    <i class="fas fa-book"></i><span>Pedoman &amp; Peraturan</span>
                </a>

                <div class="menu-divider"></div>

                <a href="<?= base_url('admin/forum_alumni') ?>">
                    <i class="fas fa-comments"></i>
                    <span>Forum Alumni</span>
                    <?php 
                    $CI =& get_instance();
                    $pending_posts = $CI->db->where('status', 'pending')->count_all_results('forum_alumni_posts');
                    if ($pending_posts > 0): 
                    ?>
                        <span class="badge bg-danger ms-auto"><?= $pending_posts ?></span>
                    <?php endif; ?>
                </a>

                <a href="<?= base_url('admin/history_log') ?>">
                    <i class="fas fa-history"></i>
                    <span>History Log</span>
                </a>

                <div class="menu-divider"></div>

                <a href="<?= base_url('dashboard') ?>">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
            <div class="admin-header">
                <h1>Manajemen Pendaftaran Beasiswa</h1>
                <div class="user-info">
                    <span><i class="fas fa-user-circle me-2" style="color: #E67E22;"></i> <?= $this->session->userdata('nama') ?></span>
                    <a href="<?= base_url('login/logout') ?>" class="logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px; margin-bottom:1.5rem;">
                <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px; margin-bottom:1.5rem;">
                <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-label">Total Pendaftaran</div>
                    <div class="stat-value"><?= array_sum($stat_counts) ?></div>
                    <div class="stat-detail">Semua pendaftar</div>
                </div>

                <div class="stat-card stat-pending">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-label">Menunggu Review</div>
                    <div class="stat-value"><?= $stat_counts['pending'] ?? 0 ?></div>
                    <div class="stat-detail"><span class="text-warning">Perlu divalidasi</span></div>
                </div>

                <div class="stat-card stat-approved">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-label">Diterima</div>
                    <div class="stat-value"><?= $stat_counts['diterima'] ?? 0 ?></div>
                    <div class="stat-detail"><span class="text-success">Lolos seleksi</span></div>
                </div>

                <div class="stat-card stat-rejected">
                    <div class="stat-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-label">Ditolak</div>
                    <div class="stat-value"><?= $stat_counts['ditolak'] ?? 0 ?></div>
                    <div class="stat-detail"><span class="text-danger">Tidak lolos seleksi</span></div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <form method="get" action="">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="q" placeholder="Cari nama, email, asal sekolah..." value="<?= htmlspecialchars($search ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" <?= ($status_filter ?? '') == 'pending' ? 'selected' : '' ?>>Menunggu Review</option>
                                <option value="diproses" <?= ($status_filter ?? '') == 'diproses' ? 'selected' : '' ?>>Sedang Diproses</option>
                                <option value="diterima" <?= ($status_filter ?? '') == 'diterima' ? 'selected' : '' ?>>Diterima (Lolos)</option>
                                <option value="ditolak" <?= ($status_filter ?? '') == 'ditolak' ? 'selected' : '' ?>>Ditolak (Tidak Lolos)</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn-filter w-100">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <?php if (!empty($search) || !empty($status_filter)): ?>
                                <a href="<?= base_url('admin/beasiswa') ?>" class="btn-reset">Reset</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="table-card">
                <h3><i class="fas fa-inbox me-2" style="color: #E67E22;"></i>Daftar Pendaftar</h3>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Lengkap</th>
                                <th>Email / No HP</th>
                                <th>Jenis Beasiswa</th>
                                <th>Asal Sekolah</th>
                                <th>Nilai Rapor</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pendaftaran)): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($pendaftaran as $p): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($p->nama) ?></strong></td>
                                    <td>
                                        <div style="font-size:0.85rem; color:#666;"><?= htmlspecialchars($p->email) ?></div>
                                        <div style="font-size:0.8rem; color:#888;"><?= htmlspecialchars($p->no_hp) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= ucfirst(htmlspecialchars($p->jenis_beasiswa)) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($p->asal_sekolah) ?></td>
                                    <td><?= number_format($p->nilai_rapor, 2) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($p->tanggal_daftar)) ?></td>
                                    <td>
                                        <span class="badge-status badge-<?= $p->status ?>">
                                            <?= htmlspecialchars($p->status) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn-action-custom" onclick="openDetail(<?= $p->id ?>)">
                                                <i class="fas fa-search me-1"></i> Detail
                                            </button>
                                            <a href="<?= base_url('admin/beasiswa/hapus/' . $p->id) ?>" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center" style="border-radius:8px; padding:0.4rem 0.8rem; font-weight:600; font-size:0.85rem;" title="Hapus" onclick="confirmDelete(event, '<?= base_url('admin/beasiswa/hapus/' . $p->id) ?>')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">Data pendaftaran beasiswa tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 20px;">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-graduation-cap me-2 text-primary"></i> Detail Pendaftaran Beasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between" style="background:#f8fafc; border-radius: 0 0 20px 20px;">
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                    <div id="modalActionButtons">
                        <button type="button" class="btn btn-danger" id="modalRejectBtn" style="border-radius:10px;">Tolak</button>
                        <button type="button" class="btn btn-success" id="modalApproveBtn" style="border-radius:10px;">Setujui</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="modalApprove" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Setujui Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui pendaftaran beasiswa untuk <strong id="approveNama"></strong>?</p>
                    <div class="mb-3">
                        <label for="catatanApprove" class="form-label font-weight-bold">Catatan Admin (Opsional)</label>
                        <textarea class="form-control" id="catatanApprove" rows="3" placeholder="Masukkan catatan konfirmasi atau info lainnya..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="submitApprove()">Ya, Setujui</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="modalReject" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Tolak Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menolak pendaftaran beasiswa untuk <strong id="rejectNama"></strong>?</p>
                    <div class="mb-3">
                        <label for="catatanReject" class="form-label font-weight-bold text-danger">Alasan Penolakan (Wajib)</label>
                        <textarea class="form-control" id="catatanReject" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                        <div class="text-danger mt-1" id="rejectError" style="display:none; font-size:0.85rem;">Alasan penolakan harus diisi.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="submitReject()">Ya, Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview File Modal -->
    <div class="modal fade" id="modalPreviewFile" tabindex="-1" style="z-index: 1070;">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.25);">
                <div class="modal-header" style="border-bottom: 1px solid #e2e8f0; background: #f8fafc; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title font-weight-bold" id="previewFileTitle" style="color: #1e293b; font-size: 1.1rem;">
                        <i class="fas fa-file-alt me-2 text-primary"></i>Pratinjau Berkas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" id="previewFileBody" style="height: 75vh; background: #0f172a; display: flex; justify-content: center; align-items: center;">
                    <!-- content dynamically injected -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentApproveId = null;
        let currentRejectId = null;

        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const icon = document.getElementById('hamburgerIcon');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            
            if (sidebar.classList.contains('open')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }

        function previewBerkas(fileUrl, fileTitle) {
            document.getElementById('previewFileTitle').innerHTML = '<i class="fas fa-file-alt me-2 text-primary"></i>' + fileTitle;
            const body = document.getElementById('previewFileBody');
            body.innerHTML = `
                <div class="text-center py-5 text-white">
                    <div class="spinner-border text-light" role="status"></div>
                </div>
            `;
            
            const ext = fileUrl.split('.').pop().toLowerCase();
            
            setTimeout(() => {
                if (ext === 'pdf') {
                    body.innerHTML = `<iframe src="${fileUrl}" style="width:100%; height:100%; border:none; background:#ffffff;"></iframe>`;
                } else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    body.innerHTML = `<div style="width:100%; height:100%; display:flex; justify-content:center; align-items:center; overflow:auto; padding:20px;">
                        <img src="${fileUrl}" style="max-width:100%; max-height:100%; object-fit:contain; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.5);">
                    </div>`;
                } else {
                    body.innerHTML = `<div class="text-center py-5 text-white">
                        <i class="fas fa-file-download fa-3x mb-3 text-warning"></i>
                        <h5>Format file tidak dapat dipratinjau langsung</h5>
                        <p class="text-muted small">Silakan unduh file untuk melihat isi berkas.</p>
                        <a href="${fileUrl}" class="btn btn-primary btn-sm px-4 mt-2" download><i class="fas fa-download me-1"></i> Unduh Berkas</a>
                    </div>`;
                }
            }, 250);
            
            const previewModal = new bootstrap.Modal(document.getElementById('modalPreviewFile'));
            previewModal.show();
        }

        function openDetail(id) {
            const myModal = new bootstrap.Modal(document.getElementById('modalDetail'));
            myModal.show();
            
            document.getElementById('detailBody').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            `;
            
            fetch('<?= base_url("admin/beasiswa/detail/") ?>' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    const p = res.data;
                    const files = res.files || [];
                    
                    let fileHtml = '';
                    if (p.file_nilai) {
                        const url = `<?= base_url('uploads/beasiswa/') ?>${p.tanggal_daftar ? p.tanggal_daftar.substring(0, 7).replace('-', '/') : ''}/${p.file_nilai}`;
                        fileHtml += `<button type="button" onclick="previewBerkas('${url}', 'Transkrip Rapor - ${p.nama}')" class="file-link-btn" style="border:none; cursor:pointer; background:rgba(37,99,235,0.08); color:#2563eb; padding:6px 14px; border-radius:8px; font-weight:600; font-size:0.8rem; display:inline-flex; align-items:center; gap:6px; transition:all 0.2s ease; margin-right:8px;"><i class="fas fa-file-pdf"></i> Transkrip Rapor</button>`;
                    }
                    if (p.file_foto) {
                        const url = `<?= base_url('uploads/beasiswa/') ?>${p.tanggal_daftar ? p.tanggal_daftar.substring(0, 7).replace('-', '/') : ''}/${p.file_foto}`;
                        fileHtml += `<button type="button" onclick="previewBerkas('${url}', 'Foto Diri - ${p.nama}')" class="file-link-btn" style="border:none; cursor:pointer; background:rgba(16,185,129,0.08); color:#10b981; padding:6px 14px; border-radius:8px; font-weight:600; font-size:0.8rem; display:inline-flex; align-items:center; gap:6px; transition:all 0.2s ease; margin-right:8px;"><i class="fas fa-file-image"></i> Foto Diri</button>`;
                    }
                    if (p.file_rekomendasi) {
                        const url = `<?= base_url('uploads/beasiswa/') ?>${p.tanggal_daftar ? p.tanggal_daftar.substring(0, 7).replace('-', '/') : ''}/${p.file_rekomendasi}`;
                        fileHtml += `<button type="button" onclick="previewBerkas('${url}', 'Surat Rekomendasi - ${p.nama}')" class="file-link-btn" style="border:none; cursor:pointer; background:rgba(139,92,246,0.08); color:#8b5cf6; padding:6px 14px; border-radius:8px; font-weight:600; font-size:0.8rem; display:inline-flex; align-items:center; gap:6px; transition:all 0.2s ease; margin-right:8px;"><i class="fas fa-file-signature"></i> Surat Rekomendasi</button>`;
                    }
                    
                    if (fileHtml === '') {
                        fileHtml = '<p class="text-muted">Tidak ada berkas yang diunggah.</p>';
                    }

                    document.getElementById('detailBody').innerHTML = `
                        <div class="detail-section">
                            <div class="detail-section-title"><i class="fas fa-user text-primary"></i> Data Diri Pengaju</div>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="label">Nama Lengkap</div>
                                    <div class="value">${p.nama || '-'}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="label">Email / No HP</div>
                                    <div class="value">${p.email || '-'} / ${p.no_hp || '-'}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="label">Tempat, Tanggal Lahir</div>
                                    <div class="value">${p.tempat_lahir || '-'}, ${p.tanggal_lahir || '-'}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="label">Alamat</div>
                                    <div class="value">${p.alamat || '-'}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <div class="detail-section-title"><i class="fas fa-school text-primary"></i> Latar Belakang Pendidikan</div>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="label">Asal Sekolah</div>
                                    <div class="value">${p.asal_sekolah || '-'}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="label">Jurusan & Tahun Lulus</div>
                                    <div class="value">${p.jurusan || '-'} (${p.tahun_lulus || '-'})</div>
                                </div>
                                <div class="detail-item">
                                    <div class="label">Rata-rata Nilai Rapor</div>
                                    <div class="value">${p.nilai_rapor || '-'}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="label">Peringkat Kelas</div>
                                    <div class="value">${p.peringkat || '-'}</div>
                                </div>
                            </div>
                        </div>

                        <div class="detail-section">
                            <div class="detail-section-title"><i class="fas fa-trophy text-primary"></i> Prestasi Akademik / Non-Akademik</div>
                            <div class="detail-text-block">${p.prestasi || 'Tidak ada data prestasi.'}</div>
                        </div>

                        <div class="detail-section">
                            <div class="detail-section-title"><i class="fas fa-paperclip text-primary"></i> Berkas Pendukung</div>
                            <div class="mt-2">${fileHtml}</div>
                        </div>

                        ${p.catatan_admin ? `
                        <div class="detail-section">
                            <div class="detail-section-title"><i class="fas fa-comment-dots text-primary"></i> Catatan Evaluasi Admin</div>
                            <div class="detail-text-block" style="background:#fffbeb; border-color:#fde68a;">${p.catatan_admin}</div>
                        </div>
                        ` : ''}
                    `;

                    // Setup buttons
                    const approveBtn = document.getElementById('modalApproveBtn');
                    const rejectBtn = document.getElementById('modalRejectBtn');
                    
                    if (p.status === 'pending' || p.status === 'diproses') {
                        approveBtn.style.display = 'inline-block';
                        rejectBtn.style.display = 'inline-block';
                        
                        approveBtn.onclick = () => {
                            myModal.hide();
                            openApprove(p.id, p.nama);
                        };
                        
                        rejectBtn.onclick = () => {
                            myModal.hide();
                            openReject(p.id, p.nama);
                        };
                    } else {
                        approveBtn.style.display = 'none';
                        rejectBtn.style.display = 'none';
                    }
                } else {
                    document.getElementById('detailBody').innerHTML = `<p class="text-danger">${res.message}</p>`;
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('detailBody').innerHTML = '<p class="text-danger">Gagal memuat data detail.</p>';
            });
        }

        function openApprove(id, nama) {
            currentApproveId = id;
            document.getElementById('approveNama').textContent = nama;
            document.getElementById('catatanApprove').value = '';
            new bootstrap.Modal(document.getElementById('modalApprove')).show();
        }

        function submitApprove() {
            if (!currentApproveId) return;
            const catatan = document.getElementById('catatanApprove').value;
            
            const params = new URLSearchParams();
            params.append('catatan', catatan);

            fetch('<?= base_url("admin/beasiswa/setujui/") ?>' + currentApproveId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: params.toString()
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            });
        }

        function openReject(id, nama) {
            currentRejectId = id;
            document.getElementById('rejectNama').textContent = nama;
            document.getElementById('catatanReject').value = '';
            document.getElementById('rejectError').style.display = 'none';
            new bootstrap.Modal(document.getElementById('modalReject')).show();
        }

        function submitReject() {
            if (!currentRejectId) return;
            const catatan = document.getElementById('catatanReject').value.trim();
            if (!catatan) {
                document.getElementById('rejectError').style.display = 'block';
                return;
            }
            
            const params = new URLSearchParams();
            params.append('catatan', catatan);

            fetch('<?= base_url("admin/beasiswa/tolak/") ?>' + currentRejectId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: params.toString()
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            });
        }

        // Custom SweetAlert2 Delete Confirmation
        function confirmDelete(event, url) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pendaftaran beasiswa ini akan dihapus permanen beserta berkasnya!",
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
    </script>
</body>
</html>
