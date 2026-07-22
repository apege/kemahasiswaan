<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Montserrat', sans-serif; background: #f0f2f5; }

        .admin-wrapper { display: flex; min-height: 100vh; }
        .admin-sidebar {
            width: 280px; background: linear-gradient(135deg, #2C3E50, #1a2632);
            color: white; position: fixed; height: 100vh; overflow-y: auto; z-index: 100;
        }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-header h3 { margin: 0; font-size: 1.3rem; font-weight: 700; }
        .sidebar-header p  { margin: 0.5rem 0 0; font-size: 0.8rem; opacity: 0.7; }
        .sidebar-menu { padding: 1.5rem 0; }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 1rem; padding: 0.8rem 1.5rem;
            color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active { background: rgba(230, 126, 34, 0.2); color: white; border-left-color: #E67E22; }
        .sidebar-menu a i { width: 20px; color: #E67E22; }
        .sidebar-menu .menu-divider { height: 1px; background: rgba(255,255,255,0.1); margin: 1rem 0; }

        .admin-main { flex: 1; margin-left: 280px; padding: 2rem; }
        .admin-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 2rem; padding-bottom: 1.2rem; border-bottom: 2px solid #e8eaed;
        }
        .admin-header h1 { font-size: 1.8rem; font-weight: 700; color: #2C3E50; }
        .admin-header .user-info { display: flex; align-items: center; gap: 1rem; }
        .admin-header .user-info span { color: #666; font-size: .88rem; }
        .logout-btn {
            background: #e74c3c; color: white; padding: .45rem 1.3rem;
            border-radius: 25px; text-decoration: none; font-size: .85rem; transition: background .2s;
        }
        .logout-btn:hover { background: #c0392b; color: #fff; }

        /* ── Cards ── */
        .content-card {
            background: white; border-radius: 16px; padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,.08); margin-bottom: 1.5rem;
        }
        .content-card h4 {
            font-weight: 700; color: #2C3E50; margin-bottom: 1.5rem;
            padding-bottom: 1rem; border-bottom: 2px solid #f0f2f5;
            display: flex; align-items: center; gap: .6rem;
        }
        .content-card h4 i { color: #E67E22; }

        /* ── Form ── */
        .form-group { margin-bottom: 1.3rem; }
        .form-group label { display: block; font-size: .85rem; font-weight: 600; color: #444; margin-bottom: .45rem; }
        .form-ctrl {
            width: 100%; padding: .65rem 1rem; border: 2px solid #e8eaed; border-radius: 10px;
            font-size: .9rem; font-family: inherit; transition: border .2s; background: #fafbfc;
        }
        .form-ctrl:focus { outline: none; border-color: #E67E22; background: white; }

        .btn-submit {
            background: #E67E22; color: white; border: none; padding: .6rem 2rem;
            border-radius: 25px; font-size: .9rem; font-weight: 600; cursor: pointer; transition: background .2s;
        }
        .btn-submit:hover { background: #d35400; }
        .btn-cancel {
            background: #95a5a6; color: white; border: none; padding: .6rem 1.5rem;
            border-radius: 25px; font-size: .9rem; font-weight: 600; cursor: pointer; text-decoration: none;
            transition: background .2s;
        }
        .btn-cancel:hover { background: #7f8c8d; color: white; }

        /* ── Table ── */
        .table-custom { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-custom thead tr { background: linear-gradient(135deg, #2C3E50, #34495e); }
        .table-custom thead th {
            color: white; padding: .85rem 1rem; font-size: .8rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .5px;
        }
        .table-custom thead th:first-child { border-radius: 10px 0 0 0; }
        .table-custom thead th:last-child  { border-radius: 0 10px 0 0; }
        .table-custom tbody tr { transition: background .2s; border-bottom: 1px solid #f3f4f6; }
        .table-custom tbody tr:hover { background: #fafbfc; }
        .table-custom tbody td { padding: .9rem 1rem; font-size: .85rem; color: #555; vertical-align: middle; }

        .badge-aktif   { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-nonaktif{ background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .badge-status  { font-size: .75rem; font-weight: 600; padding: .3rem .8rem; border-radius: 20px; }

        .btn-sm-action {
            font-size: .75rem; padding: .35rem .8rem; border-radius: 20px;
            font-weight: 600; border: none; cursor: pointer; transition: all .2s; text-decoration: none;
            display: inline-flex; align-items: center; gap: .3rem;
        }
        .btn-edit   { background: #3498db; color: white; }
        .btn-edit:hover   { background: #2980b9; color: white; }
        .btn-hapus  { background: #e74c3c; color: white; }
        .btn-hapus:hover  { background: #c0392b; color: white; }
        .btn-toggle { background: #f39c12; color: white; }
        .btn-toggle:hover { background: #e67e22; color: white; }
        .btn-tambah {
            background: linear-gradient(135deg, #E67E22, #d35400); color: white;
            padding: .6rem 1.5rem; border-radius: 25px; font-weight: 600; font-size: .9rem;
            text-decoration: none; display: inline-flex; align-items: center; gap: .5rem;
            transition: all .2s;
        }
        .btn-tambah:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(230,126,34,.3); color: white; }

        /* ── Flash ── */
        .flash-msg { padding: .85rem 1.2rem; border-radius: 12px; margin-bottom: 1.2rem; font-size: .88rem; font-weight: 500; }
        .flash-msg.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash-msg.error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* ── Stats ── */
        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { background: white; border-radius: 14px; padding: 1.3rem; box-shadow: 0 4px 15px rgba(0,0,0,.07); display: flex; align-items: center; gap: 1rem; }
        .stat-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
        .stat-icon.orange { background: #fff3e0; color: #E67E22; }
        .stat-icon.green  { background: #e8f5e9; color: #27ae60; }
        .stat-icon.gray   { background: #fafafa; color: #7f8c8d; border: 1px solid #e8eaed; }
        .stat-num  { font-size: 1.8rem; font-weight: 800; color: #2C3E50; line-height: 1; }
        .stat-label{ font-size: .78rem; color: #7f8c8d; font-weight: 500; }

        /* ── Mobile ── */
        html, body { overflow-x: hidden; max-width: 100%; }
        .mobile-topbar {
            display: none; position: fixed; top: 0; left: 0; right: 0; z-index: 1100;
            background: linear-gradient(135deg, #2C3E50, #1a2632);
            box-shadow: 0 2px 12px rgba(0,0,0,.3);
        }
        .topbar-inner { display: flex; align-items: center; justify-content: space-between; height: 54px; padding: 0 .75rem; }
        .hamburger-btn {
            display: none; background: rgba(255,255,255,.15); color: white; border: none;
            border-radius: 8px; width: 38px; height: 38px; align-items: center; justify-content: center;
            font-size: 1.1rem; cursor: pointer;
        }
        .topbar-username { display: flex; align-items: center; gap: .35rem; color: rgba(255,255,255,.9); font-size: .78rem; font-weight: 500; }
        .topbar-username i { color: #E67E22; }
        .topbar-logout {
            background: #e74c3c; color: white; border: none; border-radius: 8px;
            padding: .38rem .8rem; font-size: .75rem; font-weight: 600; text-decoration: none;
            display: flex; align-items: center; gap: .3rem;
        }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999; backdrop-filter: blur(2px); }
        .sidebar-overlay.active { display: block; }

        @media (max-width: 768px) {
            .mobile-topbar { display: block; }
            .hamburger-btn { display: flex; }
            .admin-sidebar { position: fixed !important; left: -280px !important; z-index: 1000; transition: left .3s ease; }
            .admin-sidebar.open { left: 0 !important; }
            .admin-main { margin-left: 0 !important; padding: 1rem !important; padding-top: 4.5rem !important; }
            .stats-row { grid-template-columns: 1fr 1fr; }
            .admin-header { flex-direction: column; align-items: stretch; gap: .75rem; }
            .admin-header .user-info { display: none; }
            .content-card { padding: 1.2rem; }
        }
        @media (max-width: 480px) {
            .stats-row { grid-template-columns: 1fr; }
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
                    <span><?= htmlspecialchars($nama_user ?? 'Admin') ?></span>
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
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
                <p>Fakultas Industri Kreatif</p>
            </div>
            <div class="sidebar-menu">
                <a href="<?= base_url('admin/edit_hero') ?>" class="<?= ($this->uri->segment(2) == 'edit_hero') ? 'active' : '' ?>">
                    <i class="fas fa-desktop"></i><span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/proposal') ?>">
                    <i class="fas fa-file-alt"></i><span>Proposal</span>
                </a>
                <a href="<?= base_url('admin/beasiswa') ?>">
                    <i class="fas fa-graduation-cap"></i><span>Beasiswa</span>
                </a>
                <a href="<?= base_url('sertifikat/admin') ?>">
                    <i class="fas fa-certificate"></i><span>Sertifikat</span>
                </a>
                <a href="<?= base_url('tak_admin') ?>">
                    <i class="fas fa-file-signature"></i><span>TAK</span>
                </a>
                <a href="<?= base_url('berita/admin') ?>">
                    <i class="fas fa-newspaper"></i><span>Berita</span>
                </a>
                <a href="<?= base_url('admin/organisasi') ?>">
                    <i class="fas fa-users"></i><span>Organisasi</span>
                </a>
                <a href="<?= base_url('admin/direktorat') ?>">
                    <i class="fas fa-building"></i><span>Direktorat</span>
                </a>
                <a href="<?= base_url('admin/mitra') ?>">
                    <i class="fas fa-handshake"></i><span>Mitra &amp; Recog</span>
                </a>
                <a href="<?= base_url('admin/testimoni') ?>">
                    <i class="fas fa-comments"></i><span>Testimoni Alumni</span>
                </a>
                <a href="<?= base_url('admin/tentang_kami') ?>">
                    <i class="fas fa-info-circle"></i><span>Tentang Kami</span>
                </a>
                <a href="<?= base_url('admin/pedoman') ?>" class="active">
                    <i class="fas fa-book"></i><span>Pedoman &amp; Peraturan</span>
                </a>
                <div class="menu-divider"></div>
                <a href="<?= base_url('admin/history_log') ?>">
                    <i class="fas fa-history"></i><span>History Log</span>
                </a>
                <div class="menu-divider"></div>
                <a href="<?= base_url('dashboard') ?>">
                    <i class="fas fa-arrow-left"></i><span>Kembali ke Dashboard</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1><i class="fas fa-book" style="color:#E67E22; margin-right:.6rem;"></i><?= $title ?></h1>
                <div class="user-info">
                    <span><i class="fas fa-user"></i> <?= htmlspecialchars($nama_user ?? 'Admin') ?></span>
                    <a href="<?= base_url('login/logout') ?>" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="flash-msg error"><i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success')): ?>
                <div class="flash-msg success"><i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?></div>
            <?php endif; ?>

            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-book"></i></div>
                    <div>
                        <div class="stat-num"><?= $total ?></div>
                        <div class="stat-label">Total Peraturan</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="stat-num"><?= $total_aktif ?></div>
                        <div class="stat-label">Aktif Ditampilkan</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gray"><i class="fas fa-eye-slash"></i></div>
                    <div>
                        <div class="stat-num"><?= $total - $total_aktif ?></div>
                        <div class="stat-label">Disembunyikan</div>
                    </div>
                </div>
            </div>

            <!-- Form Tambah / Edit Peraturan -->
            <div class="content-card" id="formCard">
                <h4>
                    <i class="fas fa-<?= isset($edit_item) ? 'edit' : 'plus-circle' ?>"></i>
                    <?= isset($edit_item) ? 'Edit Peraturan' : 'Tambah Peraturan Baru' ?>
                </h4>

                <form method="post" action="<?= base_url(isset($edit_item) ? 'admin/pedoman_update/' . $edit_item->id : 'admin/pedoman_store') ?>"
                      enctype="multipart/form-data">

                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Judul Peraturan <span style="color:#e74c3c">*</span></label>
                                <input type="text" name="judul" class="form-ctrl" required
                                       placeholder="Contoh: Peraturan Akademik 2025"
                                       value="<?= isset($edit_item) ? htmlspecialchars($edit_item->judul) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Urutan Tampil</label>
                                <input type="number" name="urutan" class="form-ctrl" min="0" max="999"
                                       placeholder="0"
                                       value="<?= isset($edit_item) ? (int)$edit_item->urutan : 0 ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi Singkat <span style="color:#999; font-weight:400;">(opsional)</span></label>
                        <input type="text" name="deskripsi" class="form-ctrl"
                               placeholder="Keterangan singkat tentang peraturan ini..."
                               value="<?= isset($edit_item) ? htmlspecialchars($edit_item->deskripsi ?? '') : '' ?>">
                    </div>

                    <div class="form-group">
                        <label>Isi Peraturan <span style="color:#e74c3c">*</span></label>
                        <textarea name="isi" id="summernote"><?= isset($edit_item) ? $edit_item->isi : '' ?></textarea>
                    </div>

                    <div class="row g-3">
                        <!-- Feature Upload PDF Di-comment sesuai permintaan -->
                        <!--
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Upload File PDF <span style="color:#999; font-weight:400;">(opsional – jika ada, akan dipakai saat download)</span></label>
                                <input type="file" name="file_pdf" class="form-ctrl" accept=".pdf">
                                <?php if (isset($edit_item) && !empty($edit_item->file_pdf)): ?>
                                    <div class="mt-2" style="font-size:.8rem; color:#27ae60;">
                                        <i class="fas fa-file-pdf"></i>
                                        File PDF saat ini: <a href="<?= base_url($edit_item->file_pdf) ?>" target="_blank"><?= basename($edit_item->file_pdf) ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="aktif" class="form-ctrl">
                                    <option value="1" <?= (!isset($edit_item) || $edit_item->aktif) ? 'selected' : '' ?>>✅ Aktif – Tampil di halaman publik</option>
                                    <option value="0" <?= (isset($edit_item) && !$edit_item->aktif) ? 'selected' : '' ?>>❌ Nonaktif – Disembunyikan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i>
                            <?= isset($edit_item) ? 'Simpan Perubahan' : 'Tambah Peraturan' ?>
                        </button>
                        <?php if (isset($edit_item)): ?>
                            <a href="<?= base_url('admin/pedoman') ?>" class="btn-cancel">
                                <i class="fas fa-times"></i> Batal Edit
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Tabel Daftar Peraturan -->
            <div class="content-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="mb-0"><i class="fas fa-list"></i> Daftar Peraturan</h4>
                    <a href="<?= base_url('pedoman') ?>" target="_blank" class="btn-tambah" style="font-size:.8rem; padding:.5rem 1.2rem;">
                        <i class="fas fa-eye"></i> Lihat Halaman Publik
                    </a>
                </div>

                <?php if (!empty($pedoman_list)): ?>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th width="40">No</th>
                                <th width="50">Urutan</th>
                                <th>Judul Peraturan</th>
                                <th>Deskripsi</th>
                                <!-- <th width="80">PDF</th> -->
                                <th width="80">Status</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedoman_list as $i => $p): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td class="text-center">
                                    <span style="font-weight:700; color:#E67E22;"><?= (int)$p->urutan ?></span>
                                </td>
                                <td>
                                    <div style="font-weight:600; color:#2C3E50;"><?= htmlspecialchars($p->judul) ?></div>
                                    <?php if (!empty($p->deskripsi)): ?>
                                        <div style="font-size:.75rem; color:#7f8c8d; margin-top:3px;"><?= htmlspecialchars(mb_strimwidth($p->deskripsi, 0, 60, '...')) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    <?= htmlspecialchars($p->deskripsi ?? '-') ?>
                                </td>
                                <!--
                                <td class="text-center">
                                    <?php if (!empty($p->file_pdf)): ?>
                                        <a href="<?= base_url($p->file_pdf) ?>" target="_blank" title="Lihat PDF" style="color:#e74c3c;">
                                            <i class="fas fa-file-pdf fa-lg"></i>
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#ccc;font-size:.75rem;">–</span>
                                    <?php endif; ?>
                                </td>
                                -->
                                <td>
                                    <span class="badge-status <?= $p->aktif ? 'badge-aktif' : 'badge-nonaktif' ?>">
                                        <?= $p->aktif ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="<?= base_url('admin/pedoman_edit/' . $p->id) ?>" class="btn-sm-action btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="<?= base_url('admin/pedoman_toggle/' . $p->id) ?>"
                                           class="btn-sm-action btn-toggle"
                                           onclick="return confirm('Ubah status peraturan ini?')">
                                            <i class="fas fa-toggle-on"></i> Toggle
                                        </a>
                                        <a href="<?= base_url('admin/pedoman_hapus/' . $p->id) ?>"
                                           class="btn-sm-action btn-hapus"
                                           onclick="return confirm('Yakin menghapus peraturan ini? Tindakan tidak dapat dibatalkan.')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="text-center py-5" style="color:#9ca3af;">
                        <i class="fas fa-book fa-3x mb-3" style="color:#e5e7eb;"></i>
                        <h5>Belum Ada Peraturan</h5>
                        <p style="font-size:.85rem;">Tambah peraturan baru menggunakan form di atas.</p>
                    </div>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Tulis isi peraturan di sini...',
                tabsize: 2,
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'italic', 'strikethrough', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const icon    = document.getElementById('hamburgerIcon');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            if (sidebar.classList.contains('open')) {
                icon.classList.replace('fa-bars', 'fa-times');
            } else {
                icon.classList.replace('fa-times', 'fa-bars');
            }
        }
    </script>
</body>
</html>
