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
            box-shadow: 0 4px 15px rgba(0,0,0,.05); max-width: 700px; margin: 0 auto;
        }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { font-weight: 600; color: #2C3E50; margin-bottom: .5rem; font-size: .9rem; display: block; }
        .form-control {
            border: 1.5px solid #e0e0e0; border-radius: 10px; padding: .65rem 1rem;
            font-size: .9rem; font-family: inherit; transition: border .2s;
        }
        .form-control:focus { outline: none; border-color: #E67E22; box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1); }
        
        .logo-preview-box {
            display: flex; align-items: center; gap: 1.5rem; margin-top: .8rem;
            padding: 1rem; background: #f8f9fa; border-radius: 10px; border: 1px dashed #cbd5e1;
        }
        .logo-preview-img { max-height: 60px; max-width: 150px; object-fit: contain; }
        
        .btn-submit {
            background: linear-gradient(135deg, #E67E22, #d35400); color: white; border: none;
            padding: .65rem 2rem; border-radius: 10px; font-weight: 600; font-size: .9rem;
            cursor: pointer; transition: all .25s; display: inline-flex; align-items: center; gap: .5rem;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230,126,34,.4); }
        .btn-cancel {
            background: #e8eaed; color: #555; border: none; padding: .65rem 1.5rem;
            border-radius: 10px; font-weight: 600; font-size: .9rem; cursor: pointer;
            text-decoration: none; display: inline-flex; align-items: center; transition: background .2s;
        }
        .btn-cancel:hover { background: #dde; color: #333; }

        .checkbox-container {
            display: flex; align-items: center; gap: .5rem; cursor: pointer; font-size: .9rem; color: #2C3E50;
        }
        .checkbox-container input { cursor: pointer; width: 18px; height: 18px; }

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
                <p>Manajemen Mitra & Recog</p>
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

                <a href="<?= base_url('admin/mitra') ?>" class="active">
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
        <main class="admin-main">
            <!-- Header -->
            <div class="admin-header">
                <h1><?= $title ?></h1>
                <div class="user-info">
                    <span><i class="fas fa-user-circle me-2" style="color: #E67E22;"></i> <?= $this->session->userdata('nama') ?></span>
                    <a href="<?= base_url('login/logout') ?>" class="logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="form-card">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama</label>
                        <input type="text" name="name" id="name" class="form-control w-100" value="<?= isset($item) ? htmlspecialchars($item->name) : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="logo">Logo Baru (Optional)</label>
                        <input type="file" name="logo" id="logo" class="form-control w-100" accept="image/*" onchange="previewImage(event)">
                        <small class="text-muted d-block mt-1">Format yang didukung: JPG, JPEG, PNG, GIF, SVG, WEBP. Maksimal 2MB.</small>
                        
                        <div class="logo-preview-box">
                            <div>
                                <span class="form-label" style="font-size: 0.8rem; margin:0;">Preview Logo:</span>
                                <?php if(isset($item) && $item->logo): ?>
                                    <img id="preview" src="<?= base_url($item->logo) ?>" class="logo-preview-img mt-2">
                                <?php else: ?>
                                    <img id="preview" src="#" class="logo-preview-img mt-2" style="display:none;">
                                    <span id="no-preview" style="font-size:0.8rem; color:#95a5a6;">Belum ada file/gambar terpilih</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="default_icon">Fallback Icon (FontAwesome class)</label>
                        <input type="text" name="default_icon" id="default_icon" class="form-control w-100" placeholder="Contoh: fas fa-handshake atau fas fa-trophy" value="<?= isset($item) ? htmlspecialchars($item->default_icon) : ($type == 'mitra' ? 'fas fa-handshake' : 'fas fa-trophy') ?>">
                        <small class="text-muted">Digunakan jika logo gagal dimuat atau tidak diupload.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="urutan">Nomor Urutan Tampil</label>
                        <input type="number" name="urutan" id="urutan" class="form-control w-100" value="<?= isset($item) ? $item->urutan : '1' ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-container">
                            <input type="checkbox" name="aktif" value="1" <?= (!isset($item) || $item->aktif) ? 'checked' : '' ?>>
                            <span>Tampilkan data ini di Halaman Utama</span>
                        </label>
                    </div>

                    <div class="d-flex gap-3 justify-content-end mt-4">
                        <a href="<?= base_url('admin/mitra') ?>" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Preview image upload
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const noPreview = document.getElementById('no-preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (noPreview) noPreview.style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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
