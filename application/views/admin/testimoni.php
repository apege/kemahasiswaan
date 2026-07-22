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

        /* ── Table Card ── */
        .table-card {
            background: white; border-radius: 14px; padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,.05); margin-top: 1rem;
        }
        
        table { width: 100%; border-collapse: collapse; margin-top: .5rem; }
        th { font-weight: 700; color: #7f8c8d; text-align: left; padding: 1rem .8rem; border-bottom: 2px solid #f0f2f5; font-size: .88rem; }
        td { padding: 1rem .8rem; border-bottom: 1px solid #f0f2f5; font-size: .9rem; vertical-align: middle; color: #2c3e50; }
        tr:hover td { background-color: #fafbfc; }

        .logo-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 2px solid #E67E22; }
        .logo-placeholder {
            width: 50px; height: 50px; background: #e8eaed; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #95a5a6;
            border: 2px solid #ccc;
        }

        .toggle-btn {
            width: 46px; height: 24px; border-radius: 20px; background: #bdc3c7;
            position: relative; border: none; cursor: pointer; transition: background .25s;
        }
        .toggle-btn::after {
            content: ''; position: absolute; width: 18px; height: 18px; border-radius: 50%;
            background: white; top: 3px; left: 3px; transition: transform .25s;
            box-shadow: 0 1px 3px rgba(0,0,0,.2);
        }
        .toggle-btn.on { background: #2ecc71; }
        .toggle-btn.on::after { transform: translateX(22px); }

        .action-btns { display: flex; gap: .4rem; }
        .btn-edit {
            background: rgba(230, 126, 34, 0.1); color: #E67E22; border: none;
            padding: .4rem .9rem; border-radius: 8px; font-size: .8rem; font-weight: 600;
            text-decoration: none; display: inline-flex; align-items: center; gap: .3rem; transition: all .2s;
        }
        .btn-edit:hover { background: #E67E22; color: white; }
        .btn-del {
            background: rgba(231, 76, 60, 0.1); color: #e74c3c; border: none;
            width: 32px; height: 32px; border-radius: 8px; font-size: .85rem;
            cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all .2s;
        }
        .btn-del:hover { background: #e74c3c; color: white; }

        .btn-primary-custom {
            background: linear-gradient(135deg, #E67E22, #d35400); color: white; border: none;
            padding: .55rem 1.4rem; border-radius: 10px; font-weight: 600; font-size: .88rem;
            cursor: pointer; transition: all .25s; text-decoration: none; display: inline-flex;
            align-items: center; gap: .5rem;
        }
        .btn-primary-custom:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230,126,34,.4); color: white; }

        .empty-state { text-align: center; padding: 3rem 1rem; color: #95a5a6; }
        .empty-state i { font-size: 3rem; margin-bottom: 1rem; color: #bdc3c7; }

        /* Stars Rating */
        .rating-stars { color: #f1c40f; font-size: 0.95rem; }

        /* Social Badges */
        .social-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 26px; height: 26px; border-radius: 50%; color: white; font-size: 0.8rem; margin-right: 4px;
        }
        .social-linkedin { background: #0077b5; }
        .social-pinterest { background: #bd081c; }

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
            .admin-sidebar { position: fixed !important; left: -280px !important; z-index: 1000; transition: left 0.3s ease; width: 280px !important; display: block !important; }
            .admin-sidebar.open { left: 0 !important; }
            .admin-main { margin-left: 0 !important; padding: 1rem !important; padding-top: 4.5rem !important; max-width: 100vw; overflow-x: hidden; }
            .admin-header { flex-direction: column !important; align-items: stretch !important; gap: 0.75rem; }
            .admin-header h1 { font-size: 1.3rem !important; word-break: break-word; }
            .admin-header .user-info > span, .admin-header .user-info .logout-btn { display: none; }
            
            .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; max-width: 100%; }

            .table-stackable thead { display: none; }
            .table-stackable tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 0;
                background: #fff;
                box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            }
            .table-stackable td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #f1f5f9;
                padding: 0.8rem 1rem;
                font-size: 0.85rem;
                text-align: right;
                gap: 1rem;
                white-space: normal;
            }
            .table-stackable td::before {
                content: attr(data-label);
                font-weight: 700;
                text-transform: uppercase;
                font-size: 0.72rem;
                color: #64748b;
                text-align: left;
                flex-shrink: 0;
            }
            .table-stackable td:last-child { border-bottom: 0; justify-content: flex-end; }
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

                <a href="<?= base_url('admin/testimoni') ?>" class="active">
                    <i class="fas fa-comments"></i>
                    <span>Testimoni Alumni</span>
                </a>
                
                <a href="<?= base_url('admin/tentang_kami') ?>">
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
                <h1>Kelola Testimoni Alumni</h1>
                <div class="user-info">
                    <span><i class="fas fa-user-circle me-2" style="color: #E67E22;"></i> <?= $this->session->userdata('nama') ?></span>
                    <a href="<?= base_url('login/logout') ?>" class="logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>

            <!-- Flash Message -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-muted mb-0">Daftar Testimoni yang Tampil di Dashboard</h5>
            </div>

            <!-- Table Card -->
            <div class="table-card">
                <div class="table-responsive">
                    <?php if (!empty($testimoni_list)): ?>
                        <table class="table-stackable">
                            <thead>
                                <tr>
                                    <th width="80">Urutan</th>
                                    <th width="80">Foto</th>
                                    <th>Nama & Posisi</th>
                                    <th>Kutipan Testimoni</th>
                                    <th>Rating</th>
                                    <th>Social Media</th>
                                    <th width="100">Status</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($testimoni_list as $row): ?>
                                    <tr>
                                        <td data-label="Urutan"><strong><?= $row->urutan ?></strong></td>
                                        <td data-label="Foto">
                                            <?php if ($row->foto): ?>
                                                <img src="<?= base_url($row->foto) ?>" class="logo-thumb" alt="Foto <?= htmlspecialchars($row->nama) ?>">
                                            <?php else: ?>
                                                <div class="logo-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Nama & Posisi">
                                            <div style="text-align: right; min-width: 0; word-wrap: break-word;">
                                                <strong><?= htmlspecialchars($row->nama) ?></strong><br>
                                                <small class="text-muted" style="white-space: normal;"><?= htmlspecialchars($row->posisi) ?></small>
                                            </div>
                                        </td>
                                        <td data-label="Kutipan">
                                            <span style="font-size: 0.85rem; line-height: 1.4; display: block; white-space: normal; word-break: break-word; text-align: right; min-width: 0;">
                                                "<?= htmlspecialchars($row->testimoni) ?>"
                                            </span>
                                        </td>
                                        <td data-label="Rating">
                                            <div class="rating-stars">
                                                <?php for($i=1; $i<=5; $i++): ?>
                                                    <i class="<?= $i <= $row->rating ? 'fas' : 'far' ?> fa-star"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                        <td data-label="Social Media">
                                            <div style="text-align: right;">
                                                <?php if ($row->linkedin): ?>
                                                    <a href="<?= htmlspecialchars($row->linkedin) ?>" target="_blank" class="social-badge social-linkedin" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                                <?php endif; ?>
                                                <?php if ($row->pinterest): ?>
                                                    <a href="<?= htmlspecialchars($row->pinterest) ?>" target="_blank" class="social-badge social-pinterest" title="Pinterest / Website"><i class="fab fa-pinterest-p"></i></a>
                                                <?php endif; ?>
                                                <?php if (!$row->linkedin && !$row->pinterest): ?>
                                                    <span class="text-muted" style="font-size:0.8rem;">-</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td data-label="Status">
                                            <button class="toggle-btn <?= $row->aktif ? 'on' : 'off' ?>" onclick="toggleStatus(<?= $row->id ?>)" aria-label="Toggle Tampilan"></button>
                                        </td>
                                        <td data-label="Aksi" class="text-center">
                                            <div class="action-btns justify-content-end">
                                                <a href="<?= base_url('admin/testimoni_edit/' . $row->id) ?>" class="btn-edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button class="btn-del" onclick="confirmDelete(<?= $row->id ?>, '<?= htmlspecialchars(addslashes($row->nama)) ?>')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-comments"></i>
                            <p>Belum ada data testimoni alumni.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Status Aktif/Tampil
        function toggleStatus(id) {
            const btn = event.currentTarget;
            fetch('<?= base_url("admin/testimoni_toggle/") ?>' + id, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.aktif) {
                        btn.className = 'toggle-btn on';
                    } else {
                        btn.className = 'toggle-btn off';
                    }
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(() => alert('Terjadi kesalahan koneksi.'));
        }

        // Confirm delete
        function confirmDelete(id, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus testimoni alumni dari "${name}"?`)) {
                window.location.href = '<?= base_url("admin/testimoni_hapus/") ?>' + id;
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
