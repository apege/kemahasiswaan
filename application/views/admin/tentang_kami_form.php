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

        .form-card {
            background: white; border-radius: 16px; padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }
        .form-card h4 {
            font-weight: 700; color: #2C3E50; margin-bottom: 1.5rem;
            padding-bottom: 1rem; border-bottom: 2px solid #f0f2f5;
            display: flex; align-items: center; gap: .6rem;
        }
        .form-card h4 i { color: #E67E22; }

        .form-group { margin-bottom: 1.4rem; }
        .form-group label {
            display: block; font-size: .85rem; font-weight: 600; color: #444; margin-bottom: .45rem;
        }
        .form-control-dir {
            width: 100%; padding: .65rem 1rem; border: 2px solid #e8eaed; border-radius: 10px;
            font-size: .9rem; font-family: inherit; transition: border .2s; background: #fafbfc;
        }
        .form-control-dir:focus { outline: none; border-color: #E67E22; background: white; }

        .form-actions {
            display: flex; gap: .8rem; margin-top: 1.5rem; padding-top: 1.2rem;
            border-top: 2px solid #f0f2f5;
        }
        .btn-submit {
            background: #E67E22; color: white; border: none; padding: .6rem 2rem;
            border-radius: 25px; font-size: .9rem; font-weight: 600; cursor: pointer;
            transition: background .2s;
        }
        .btn-submit:hover { background: #d35400; }

        .gambar-preview {
            margin-top: .6rem; display: flex; align-items: center; gap: 1rem;
        }
        .gambar-preview img {
            max-width: 200px; max-height: 120px; border-radius: 10px;
            border: 2px solid #e8eaed; object-fit: cover;
        }
        .gambar-preview .text-muted-sm { color: #999; font-size: .82rem; }

        .flash-msg {
            padding: .85rem 1.2rem; border-radius: 12px; margin-bottom: 1.2rem;
            font-size: .88rem; font-weight: 500;
        }
        .flash-msg.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash-msg.error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

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
            .admin-header .user-info { display: none; }
            .form-card { padding: 1.2rem; }
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
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1><i class="fas fa-info-circle" style="color:#E67E22;margin-right:.6rem;"></i><?= $title ?></h1>
                <div class="user-info">
                    <span><i class="fas fa-user"></i> <?= htmlspecialchars($nama_user ?? 'Admin') ?></span>
                    <a href="<?= base_url('login/logout') ?>" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="flash-msg error"><i class="fas fa-exclamation-circle"></i> <?= $this->session->flashdata('error') ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success')): ?>
                <div class="flash-msg success"><i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?></div>
            <?php endif; ?>

            <div class="form-card">
                <h4><i class="fas fa-edit"></i> Form Edit Tentang Kami</h4>

                <form method="post" action="<?= base_url('admin/tentang_kami') ?>">
                    <div class="form-group">
                        <label>Judul Halaman <span style="color:#e74c3c;">*</span></label>
                        <input type="text" name="judul" class="form-control-dir" required
                               value="<?= isset($item) ? htmlspecialchars($item->judul) : '' ?>">
                    </div>

                    <div class="form-group">
                        <label>Isi Konten Tentang Kami</label>
                        <textarea name="isi" id="summernote"><?= isset($item) ? htmlspecialchars($item->isi) : '' ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Tulis isi tentang kami di sini...',
                tabsize: 2,
                height: 300,
                toolbar: [
                  ['style', ['style']],
                  ['font', ['bold', 'underline', 'clear', 'italic']],
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
    </script>
</body>
</html>
