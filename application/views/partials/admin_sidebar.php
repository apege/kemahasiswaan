<?php
/**
 * Admin Sidebar Partial
 * Path: application/views/partials/admin_sidebar.php
 *
 * Usage: $this->load->view('partials/admin_sidebar', ['active_menu' => 'nama_menu']);
 *
 * active_menu values:
 *   'dashboard'   => admin/edit_hero
 *   'proposal'    => admin/proposal
 *   'beasiswa'    => admin/beasiswa
 *   'sertifikat'  => sertifikat/admin
 *   'tak'         => tak_admin
 *   'berita'      => berita/admin
 *   'organisasi'  => admin/organisasi
 *   'direktorat'  => admin/direktorat
 *   'mitra'       => admin/mitra
 *   'testimoni'   => admin/testimoni
 *   'tentang'     => admin/tentang_kami
 *   'pedoman'     => admin/pedoman
 *   'forum'       => admin/forum_alumni
 *   'logs'        => admin/history_log
 */

$active = isset($active_menu) ? $active_menu : '';
$nama   = $this->session->userdata('nama') ?? 'Admin';
?>

<?php /* ===================== CSS SIDEBAR ===================== */ ?>
<style>
/* ── Sidebar Core ─────────────────────────────────────────── */
.admin-sidebar {
    width: 270px;
    background: linear-gradient(160deg, #1e2d3d 0%, #2C3E50 60%, #1a2632 100%);
    color: #fff;
    position: fixed;
    top: 0; left: 0;
    height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 24px rgba(0,0,0,0.25);
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.15) transparent;
    transition: left 0.3s cubic-bezier(.4,0,.2,1), box-shadow 0.3s;
}
.admin-sidebar::-webkit-scrollbar { width: 4px; }
.admin-sidebar::-webkit-scrollbar-track { background: transparent; }
.admin-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }

/* ── Sidebar Header ────────────────────────────────────────── */
.sidebar-header {
    padding: 1.6rem 1.4rem 1.2rem;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    background: rgba(0,0,0,0.15);
    flex-shrink: 0;
}
.sidebar-logo-wrap {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}
.sidebar-logo-icon {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, #E67E22, #f39c12);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(230,126,34,0.4);
}
.sidebar-header h3 {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    color: #fff;
}
.sidebar-header p {
    margin: 0;
    font-size: 0.72rem;
    opacity: 0.55;
    letter-spacing: 0.2px;
}
.sidebar-user-strip {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.9rem;
    padding: 0.5rem 0.7rem;
    background: rgba(255,255,255,0.07);
    border-radius: 8px;
}
.sidebar-user-strip i { color: #E67E22; font-size: 1rem; }
.sidebar-user-strip span {
    font-size: 0.78rem;
    font-weight: 500;
    opacity: 0.9;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ── Sidebar Menu ──────────────────────────────────────────── */
.sidebar-menu { padding: 1rem 0; flex: 1; }
.sidebar-section-label {
    padding: 0.5rem 1.4rem 0.3rem;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.35);
    margin-top: 0.5rem;
}
.sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.7rem 1.4rem;
    color: rgba(255,255,255,0.72);
    text-decoration: none;
    border-left: 3px solid transparent;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background 0.2s, color 0.2s, border-color 0.2s;
    position: relative;
}
.sidebar-menu a .menu-icon {
    width: 20px;
    text-align: center;
    font-size: 0.9rem;
    color: rgba(230,126,34,0.7);
    transition: color 0.2s;
    flex-shrink: 0;
}
.sidebar-menu a:hover {
    background: rgba(255,255,255,0.07);
    color: #fff;
    border-left-color: rgba(230,126,34,0.5);
}
.sidebar-menu a:hover .menu-icon { color: #E67E22; }
.sidebar-menu a.active {
    background: rgba(230,126,34,0.18);
    color: #fff;
    border-left-color: #E67E22;
    font-weight: 600;
}
.sidebar-menu a.active .menu-icon { color: #E67E22; }
.sidebar-menu a.active::before {
    content: '';
    position: absolute;
    right: 0; top: 50%;
    transform: translateY(-50%);
    width: 4px; height: 60%;
    background: #E67E22;
    border-radius: 4px 0 0 4px;
    opacity: 0.5;
}
.sidebar-badge {
    margin-left: auto;
    background: #e74c3c;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.15rem 0.45rem;
    border-radius: 20px;
    line-height: 1.4;
    flex-shrink: 0;
}
.sidebar-divider {
    height: 1px;
    background: rgba(255,255,255,0.08);
    margin: 0.6rem 1.2rem;
}

/* ── Sidebar Footer / Logout ───────────────────────────────── */
.sidebar-footer {
    padding: 1rem 1.2rem;
    border-top: 1px solid rgba(255,255,255,0.08);
    flex-shrink: 0;
}
.sidebar-footer a.sidebar-logout {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 0.9rem;
    background: rgba(231,76,60,0.15);
    color: #ff8578;
    border-radius: 10px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid rgba(231,76,60,0.25);
    transition: background 0.2s, color 0.2s;
}
.sidebar-footer a.sidebar-logout:hover { background: #e74c3c; color: #fff; }
.sidebar-footer a.sidebar-logout i { font-size: 0.95rem; }

/* ── Mobile Topbar ─────────────────────────────────────────── */
.admin-mobile-topbar {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 54px;
    background: linear-gradient(135deg, #2C3E50, #1a2632);
    z-index: 1100;
    box-shadow: 0 2px 12px rgba(0,0,0,0.35);
    align-items: center;
    justify-content: space-between;
    padding: 0 0.85rem;
}
.topbar-left { display: flex; align-items: center; gap: 0.6rem; }
.hamburger-btn {
    display: flex;
    align-items: center; justify-content: center;
    width: 38px; height: 38px;
    background: rgba(255,255,255,0.12);
    border: none; border-radius: 9px;
    color: #fff; font-size: 1.05rem;
    cursor: pointer;
    transition: background 0.2s;
}
.hamburger-btn:hover { background: rgba(255,255,255,0.22); }
.topbar-brand { font-size: 0.9rem; font-weight: 700; color: #fff; letter-spacing: 0.3px; }
.topbar-brand span { color: #E67E22; }
.topbar-right-mob { display: flex; align-items: center; gap: 0.5rem; }
.topbar-user-mob {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.8);
    display: flex; align-items: center; gap: 0.35rem;
}
.topbar-user-mob i { color: #E67E22; }
.topbar-logout-btn {
    display: flex; align-items: center; gap: 0.3rem;
    background: #e74c3c; color: #fff;
    border: none; border-radius: 8px;
    padding: 0.38rem 0.7rem;
    font-size: 0.75rem; font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
}
.topbar-logout-btn:hover { background: #c0392b; color: #fff; }

/* ── Overlay ───────────────────────────────────────────────── */
.sidebar-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 999;
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
}
.sidebar-overlay.active { display: block; }

/* ── Main Content Offset ───────────────────────────────────── */
.admin-wrapper { display: flex; min-height: 100vh; }
.admin-main {
    flex: 1;
    margin-left: 270px;
    min-height: 100vh;
    transition: margin-left 0.3s;
}

/* ── Responsive ────────────────────────────────────────────── */
@media (max-width: 991px) {
    .admin-sidebar {
        left: -270px;
        box-shadow: none;
    }
    .admin-sidebar.sidebar-open {
        left: 0;
        box-shadow: 6px 0 30px rgba(0,0,0,0.4);
    }
    .admin-mobile-topbar { display: flex; }
    .admin-main {
        margin-left: 0 !important;
        padding-top: 54px;
    }
    .admin-header { flex-direction: column !important; align-items: stretch !important; gap: 0.75rem; }
    .admin-header h1 { font-size: 1.5rem !important; }
    .admin-header .user-info { display: none !important; }
}
@media (max-width: 576px) {
    .topbar-user-mob { display: none; }
    .admin-main { padding: 1rem !important; padding-top: 54px !important; }
    .table-card { padding: 1rem !important; overflow-x: auto; }
    .stats-grid { grid-template-columns: 1fr !important; }
}
</style>

<?php /* ==================== MOBILE TOPBAR ==================== */ ?>
<div class="admin-mobile-topbar" id="adminMobileTopbar">
    <div class="topbar-left">
        <button class="hamburger-btn" id="hamburgerBtn" onclick="adminSidebarToggle()" aria-label="Toggle Sidebar">
            <i class="fas fa-bars" id="hamburgerIcon"></i>
        </button>
        <span class="topbar-brand">Admin <span>FIK</span></span>
    </div>
    <div class="topbar-right-mob">
        <span class="topbar-user-mob">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($nama) ?>
        </span>
        <a href="<?= base_url('login/logout') ?>" class="topbar-logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<?php /* ======================= OVERLAY ======================= */ ?>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="adminSidebarToggle()"></div>

<?php /* ======================= SIDEBAR ======================= */ ?>
<aside class="admin-sidebar" id="adminSidebar">

    <!-- Header -->
    <div class="sidebar-header">
        <div class="sidebar-logo-wrap">
            <div class="sidebar-logo-icon">
                <i class="fas fa-university"></i>
            </div>
            <div>
                <h3>Admin FIK</h3>
                <p>Fakultas Industri Kreatif</p>
            </div>
        </div>
        <div class="sidebar-user-strip">
            <i class="fas fa-user-circle"></i>
            <span><?= htmlspecialchars($nama) ?></span>
        </div>
    </div>

    <!-- Menu -->
    <div class="sidebar-menu">

        <div class="sidebar-section-label">Main</div>

        <a href="<?= base_url('admin/edit_hero') ?>" class="<?= ($active === 'dashboard') ? 'active' : '' ?>">
            <i class="fas fa-desktop menu-icon"></i>
            <span>Dashboard</span>
        </a>

        <div class="sidebar-section-label">Konten</div>

        <a href="<?= base_url('admin/proposal') ?>" class="<?= ($active === 'proposal') ? 'active' : '' ?>">
            <i class="fas fa-file-alt menu-icon"></i>
            <span>Proposal</span>
        </a>

        <a href="<?= base_url('admin/beasiswa') ?>" class="<?= ($active === 'beasiswa') ? 'active' : '' ?>">
            <i class="fas fa-graduation-cap menu-icon"></i>
            <span>Beasiswa</span>
        </a>

        <a href="<?= base_url('sertifikat/admin') ?>" class="<?= ($active === 'sertifikat' || $active === 'sertifikat_generate' || $active === 'sertifikat_excel' || $active === 'sertifikat_ttd') ? 'active' : '' ?>">
            <i class="fas fa-certificate menu-icon"></i>
            <span>Sertifikat</span>
        </a>
        <?php if ($active === 'sertifikat' || $active === 'sertifikat_generate' || $active === 'sertifikat_excel' || $active === 'sertifikat_ttd'): ?>
        <a href="<?= base_url('sertifikat/generate') ?>" class="<?= ($active === 'sertifikat_generate') ? 'active' : '' ?>" style="padding-left: 2.5rem; font-size: 0.82rem;">
            <i class="fas fa-magic menu-icon"></i>
            <span>Generate Sertifikat</span>
        </a>
        <a href="<?= base_url('sertifikat/tanda_tangan') ?>" class="<?= ($active === 'sertifikat_ttd') ? 'active' : '' ?>" style="padding-left: 2.5rem; font-size: 0.82rem;">
            <i class="fas fa-signature menu-icon"></i>
            <span>Tanda Tangan</span>
        </a>
        <a href="<?= base_url('sertifikat/export_excel_canva') ?>" class="<?= ($active === 'sertifikat_excel') ? 'active' : '' ?>" style="padding-left: 2.5rem; font-size: 0.82rem;">
            <i class="fas fa-file-excel menu-icon"></i>
            <span>Export Excel Canva</span>
        </a>
        <?php endif; ?>

        <a href="<?= base_url('tak_admin') ?>" class="<?= ($active === 'tak') ? 'active' : '' ?>">
            <i class="fas fa-file-signature menu-icon"></i>
            <span>TAK</span>
        </a>

        <a href="<?= base_url('berita/admin') ?>" class="<?= ($active === 'berita' || $active === 'berita_create' || $active === 'berita_komentar') ? 'active' : '' ?>">
            <i class="fas fa-newspaper menu-icon"></i>
            <span>Berita</span>
        </a>
        <?php if ($active === 'berita' || $active === 'berita_create' || $active === 'berita_komentar'): ?>
        <a href="<?= base_url('berita/create') ?>" class="<?= ($active === 'berita_create') ? 'active' : '' ?>" style="padding-left: 2.5rem; font-size: 0.82rem;">
            <i class="fas fa-pen menu-icon"></i>
            <span>Tulis Berita</span>
        </a>
        <a href="<?= base_url('berita/komentar') ?>" class="<?= ($active === 'berita_komentar') ? 'active' : '' ?>" style="padding-left: 2.5rem; font-size: 0.82rem;">
            <i class="fas fa-comments menu-icon"></i>
            <span>Komentar</span>
        </a>
        <?php endif; ?>

        <div class="sidebar-section-label">Manajemen</div>

        <a href="<?= base_url('admin/organisasi') ?>" class="<?= ($active === 'organisasi') ? 'active' : '' ?>">
            <i class="fas fa-users menu-icon"></i>
            <span>Organisasi</span>
        </a>

        <a href="<?= base_url('admin/direktorat') ?>" class="<?= ($active === 'direktorat') ? 'active' : '' ?>">
            <i class="fas fa-building menu-icon"></i>
            <span>Direktorat</span>
        </a>

        <a href="<?= base_url('admin/mitra') ?>" class="<?= ($active === 'mitra') ? 'active' : '' ?>">
            <i class="fas fa-handshake menu-icon"></i>
            <span>Mitra &amp; Recog</span>
        </a>

        <a href="<?= base_url('admin/testimoni') ?>" class="<?= ($active === 'testimoni') ? 'active' : '' ?>">
            <i class="fas fa-star menu-icon"></i>
            <span>Testimoni Alumni</span>
        </a>

        <a href="<?= base_url('admin/tentang_kami') ?>" class="<?= ($active === 'tentang') ? 'active' : '' ?>">
            <i class="fas fa-info-circle menu-icon"></i>
            <span>Tentang Kami</span>
        </a>

        <a href="<?= base_url('admin/pedoman') ?>" class="<?= ($active === 'pedoman') ? 'active' : '' ?>">
            <i class="fas fa-book menu-icon"></i>
            <span>Pedoman &amp; Peraturan</span>
        </a>

        <div class="sidebar-section-label">Lainnya</div>

        <a href="<?= base_url('admin/forum_alumni') ?>" class="<?= ($active === 'forum') ? 'active' : '' ?>">
            <i class="fas fa-comments menu-icon"></i>
            <span>Forum Alumni</span>
            <?php
                $CI =& get_instance();
                $pending_posts = $CI->db->where('status', 'pending')->count_all_results('forum_alumni_posts');
                if ($pending_posts > 0):
            ?>
                <span class="sidebar-badge"><?= $pending_posts ?></span>
            <?php endif; ?>
        </a>

        <a href="<?= base_url('admin/history_log') ?>" class="<?= ($active === 'logs') ? 'active' : '' ?>">
            <i class="fas fa-history menu-icon"></i>
            <span>History Log</span>
        </a>

        <div class="sidebar-divider"></div>

        <a href="<?= base_url('dashboard') ?>">
            <i class="fas fa-arrow-left menu-icon"></i>
            <span>Kembali ke Dashboard</span>
        </a>

    </div><!-- /.sidebar-menu -->

    <!-- Footer Logout -->
    <div class="sidebar-footer">
        <a href="<?= base_url('login/logout') ?>" class="sidebar-logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>

</aside>

<?php /* ======================= JS SIDEBAR ==================== */ ?>
<script>
(function () {
    function adminSidebarToggle() {
        var sidebar = document.getElementById('adminSidebar');
        var overlay = document.getElementById('sidebarOverlay');
        var icon    = document.getElementById('hamburgerIcon');
        if (!sidebar) return;
        var isOpen = sidebar.classList.toggle('sidebar-open');
        overlay.classList.toggle('active', isOpen);
        if (icon) {
            icon.classList.toggle('fa-bars',  !isOpen);
            icon.classList.toggle('fa-times',  isOpen);
        }
    }
    window.adminSidebarToggle = adminSidebarToggle;

    document.addEventListener('DOMContentLoaded', function () {
        // Auto-close sidebar on menu link click (mobile)
        document.querySelectorAll('.admin-sidebar .sidebar-menu a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 991) adminSidebarToggle();
            });
        });
        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                var sb = document.getElementById('adminSidebar');
                if (sb && sb.classList.contains('sidebar-open')) adminSidebarToggle();
            }
        });
    });
})();
</script>
