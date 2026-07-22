<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Montserrat', sans-serif; background: #f0f2f5; }

        /* ── Sidebar ── */
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
        
        /* ── Main ── */
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
            border-radius: 25px; text-decoration: none; font-size: .85rem;
            transition: background .2s;
        }
        .logout-btn:hover { background: #c0392b; color: #fff; }

        /* ── Form Card ── */
        .form-card {
            background: white; border-radius: 14px; padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,.05); max-width: 800px;
        }

        .form-label { font-weight: 600; color: #2C3E50; font-size: 0.9rem; }
        .form-control, .form-select {
            border: 1px solid #dcdcdc; border-radius: 10px; padding: 0.65rem 1rem; font-size: 0.9rem;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #E67E22; box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
        }

        .img-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 2px solid #E67E22; margin-top: 10px; }

        .btn-save {
            background: linear-gradient(135deg, #E67E22, #d35400); color: white; border: none;
            padding: .65rem 1.8rem; border-radius: 10px; font-weight: 600; font-size: .9rem;
            cursor: pointer; transition: all .25s; text-decoration: none; display: inline-flex;
            align-items: center; gap: .5rem;
        }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230,126,34,.4); color: white; }
        .btn-cancel {
            background: #95a5a6; color: white; border: none;
            padding: .65rem 1.8rem; border-radius: 10px; font-weight: 600; font-size: .9rem;
            cursor: pointer; transition: all .25s; text-decoration: none; display: inline-flex;
            align-items: center; gap: .5rem;
        }
        .btn-cancel:hover { background: #7f8c8d; color: white; }

        /* === MOBILE RESPONSIVE === */
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; max-width: 100%; }
        .mobile-topbar { display: none; position: fixed; top: 0; left: 0; right: 0; z-index: 1100; background: linear-gradient(135deg, #2C3E50, #1a2632); box-shadow: 0 2px 12px rgba(0,0,0,0.3); }
        .topbar-inner { display: flex; align-items: center; justify-content: space-between; height: 54px; padding: 0 0.75rem; gap: 0.5rem; }
        .hamburger-btn { display: none; background: rgba(255,255,255,0.15); color: white; border: none; border-radius: 8px; width: 38px; height: 38px; align-items: center; justify-content: center; font-size: 1.1rem; cursor: pointer; flex-shrink: 0; }
        .hamburger-btn:hover { background: rgba(230,126,34,0.6); }
        .topbar-right { display: flex; align-items: center; gap: 0.5rem; flex: 1; min-width: 0; justify-content: flex-end; }
        .topbar-username { display: flex; align-items: center; gap: 0.35rem; color: rgba(255,255,255,0.9); font-size: 0.78rem; font-weight: 500; flex: 1; min-width: 0; }
        .topbar-username i { color: #E67E22; font-size: 1rem; flex-shrink: 0; }
        .topbar-username .name-text { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; min-width: 0; }
        .topbar-logout { background: #e74c3c; color: white; border: none; border-radius: 8px; padding: 0.38rem 0.8rem; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.3rem; white-space: nowrap; flex-shrink: 0; }
        .topbar-logout:hover { background: #c0392b; color: white; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; backdrop-filter: blur(2px); }
        .sidebar-overlay.active { display: block; }
        @media (max-width: 768px) {
            .mobile-topbar { display: block; } .hamburger-btn { display: flex; }
            .admin-sidebar { position: fixed !important; left: -280px !important; z-index: 1000; transition: left 0.3s ease; width: 280px !important; }
            .admin-sidebar.open { left: 0 !important; }
            .admin-main { margin-left: 0 !important; padding: 1rem !important; padding-top: 4.5rem !important; max-width: 100vw; overflow-x: hidden; }
            .admin-header { flex-direction: column !important; align-items: stretch !important; gap: 0.75rem; }
            .admin-header h1 { font-size: 1.3rem !important; word-break: break-word; }
            .admin-header .user-info > span, .admin-header .user-info .logout-btn { display: none; }
        }
    </style>
</head>
<body>

    <!-- Mobile Topbar -->
    <div class="mobile-topbar" id="mobileTopbar">
        <div class="topbar-inner">
            <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()" aria-label="Toggle Menu">
                <i class="fas fa-bars" id="hamburgerIcon"></i>
            </button>
            <div class="topbar-right">
                <span class="topbar-username">
                    <i class="fas fa-user-circle"></i>
                    <span class="name-text"><?= $this->session->userdata('nama') ?></span>
                </span>
                <a href="<?= base_url('login/logout') ?>" class="topbar-logout">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </a>
            </div>
        </div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <h3>Admin FIK</h3>
                <p>Testimoni Alumni</p>
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

                <a href="<?= base_url('admin/beasiswa') ?>">
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
                
                <a href="<?= base_url('admin/tentang_kami') ?>" class="active">
                    <i class="fas fa-info-circle"></i><span>Tentang Kami</span>
                </a>

                <a href="<?= base_url('admin/pedoman') ?>">
                    <i class="fas fa-book"></i><span>Pedoman &amp; Peraturan</span>
                </a>

                <div class="menu-divider"></div>

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
                <h1><?= $title ?></h1>
                <div class="user-info">
                    <span><i class="fas fa-user-circle me-2" style="color: #E67E22;"></i> <?= $this->session->userdata('nama') ?></span>
                    <a href="<?= base_url('login/logout') ?>" class="logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <?php
                $action = isset($item) ? 'admin/testimoni_edit/' . $item->id : 'admin/testimoni_tambah';
                echo form_open_multipart($action);
                ?>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label mb-1">Nama Alumni <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= set_value('nama', isset($item) ? $item->nama : '') ?>" placeholder="Contoh: Mitchela Smith" required>
                        <?= form_error('nama', '<small class="text-danger">', '</small>') ?>
                    </div>
                    <div class="col-md-6">
                        <label for="posisi" class="form-label mb-1">Pekerjaan / Program Studi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="posisi" name="posisi" value="<?= set_value('posisi', isset($item) ? $item->posisi : '') ?>" placeholder="Contoh: UI/UX Design / S1 Desain Komunikasi Visual" required>
                        <?= form_error('posisi', '<small class="text-danger">', '</small>') ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="testimoni" class="form-label mb-1">Pesan & Kesan / Testimoni <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="testimoni" name="testimoni" rows="4" placeholder="Tuliskan kata-kata alumni di sini..." required><?= set_value('testimoni', isset($item) ? $item->testimoni : '') ?></textarea>
                    <?= form_error('testimoni', '<small class="text-danger">', '</small>') ?>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="rating" class="form-label mb-1">Rating Bintang</label>
                        <select class="form-select" id="rating" name="rating">
                            <?php $curr_rating = isset($item) ? $item->rating : 5; ?>
                            <?php for($i=1; $i<=5; $i++): ?>
                                <option value="<?= $i ?>" <?= $curr_rating == $i ? 'selected' : '' ?>><?= $i ?> Bintang</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="urutan" class="form-label mb-1">Urutan Tampil</label>
                        <input type="number" class="form-control" id="urutan" name="urutan" value="<?= set_value('urutan', isset($item) ? $item->urutan : '0') ?>" min="0">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="aktif" name="aktif" value="1" <?= (!isset($item) || $item->aktif) ? 'checked' : '' ?>>
                            <label class="form-check-label form-label" for="aktif">
                                Tampilkan di Dashboard
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="linkedin" class="form-label mb-1">URL LinkedIn (Opsional)</label>
                        <input type="url" class="form-control" id="linkedin" name="linkedin" value="<?= set_value('linkedin', isset($item) ? $item->linkedin : '') ?>" placeholder="https://linkedin.com/in/username">
                    </div>
                    <div class="col-md-6">
                        <label for="pinterest" class="form-label mb-1">URL Pinterest / Website (Opsional)</label>
                        <input type="url" class="form-control" id="pinterest" name="pinterest" value="<?= set_value('pinterest', isset($item) ? $item->pinterest : '') ?>" placeholder="https://pinterest.com/username atau web personal">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="foto" class="form-label mb-1">Foto Profil Alumni</label>
                    <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
                    <small class="text-muted d-block mt-1">Format: JPG, PNG, WebP (Maks. 2MB). Kosongkan jika tidak ingin mengubah/menambahkan foto.</small>
                    
                    <?php if (isset($item) && $item->foto): ?>
                        <div class="mt-2">
                            <p class="mb-1 text-muted" style="font-size:0.8rem;">Foto saat ini:</p>
                            <img src="<?= base_url($item->foto) ?>" class="img-preview" alt="Preview Foto">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="<?= base_url('admin/testimoni') ?>" class="btn-cancel">
                        Batal
                    </a>
                </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>

    <script>
        // Mobile Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const icon = document.getElementById('hamburgerIcon');
            const isOpen = sidebar.classList.toggle('open');
            overlay.classList.toggle('active', isOpen);
            icon.className = isOpen ? 'fas fa-times' : 'fas fa-bars';
        }
    </script>
</body>
</html>
