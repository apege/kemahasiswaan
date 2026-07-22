<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Sertifikat | Admin Sertifikat</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar (sama persis dengan admin.php) ── */
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #2C3E50, #1a2632);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
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

        /* ── Main Content ── */
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
            font-size: 1.5rem;
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

        /* ── Steps Bar ── */
        .steps-bar {
            display: flex;
            background: white;
            border-radius: 15px;
            padding: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            gap: 0.3rem;
        }

        .step-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            border: none;
            background: transparent;
            color: #adb5bd;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s;
        }

        .step-btn .step-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #e9ecef;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #adb5bd;
            flex-shrink: 0;
        }

        .step-btn.active {
            background: linear-gradient(135deg, #E67E22, #d35400);
            color: white;
            box-shadow: 0 4px 15px rgba(230,126,34,0.35);
        }

        .step-btn.active .step-num {
            background: rgba(255,255,255,0.25);
            color: white;
        }

        .step-btn.done {
            color: #27ae60;
        }

        .step-btn.done .step-num {
            background: #d4edda;
            color: #27ae60;
        }

        .step-divider {
            width: 1px;
            background: #e9ecef;
            margin: 0.5rem 0;
        }

        /* ── Panels / Cards ── */
        .panel {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .panel-title {
            font-size: 1rem;
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .panel-title i { color: #E67E22; }

        /* ── Table (sama style dengan admin.php) ── */
        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        thead th {
            background: #2C3E50;
            color: white;
            padding: 0.9rem 1rem;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        tbody tr {
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: all 0.2s;
        }

        tbody tr:hover { background: #fff8f0; }

        tbody tr.selected {
            background: #fff3e4;
            border-left: 3px solid #E67E22;
        }

        tbody td {
            padding: 0.9rem 1rem;
            color: #444;
            vertical-align: middle;
        }

        .radio-col { width: 40px; text-align: center; }

        .badge-nomor {
            background: rgba(230,126,34,0.12);
            color: #E67E22;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .selected-indicator { color: #27ae60; display: none; }
        tr.selected .selected-indicator { display: inline; }
        tr.selected .unselected-indicator { display: none; }

        /* ── Search (sama style dengan filter-bar admin.php) ── */
        .filter-bar {
            background: white;
            border-radius: 15px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .filter-bar .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            font-family: 'Montserrat', sans-serif;
        }

        .filter-bar .form-control:focus {
            border-color: #E67E22;
            box-shadow: 0 0 0 3px rgba(230,126,34,0.1);
        }

        /* ── Template Grid ── */
        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.2rem;
        }

        .template-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            background: #f8f9fa;
        }

        .template-card:hover {
            border-color: #E67E22;
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(230,126,34,0.15);
        }

        .template-card.selected {
            border-color: #E67E22;
            box-shadow: 0 0 0 3px rgba(230,126,34,0.2);
        }

        .template-card img {
            width: 100%;
            display: block;
            height: 170px;
            object-fit: cover;
        }

        .template-card-info {
            padding: 0.9rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
        }

        .template-card-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #2C3E50;
        }

        .template-card-desc {
            font-size: 0.75rem;
            color: #888;
        }

        .template-check {
            width: 26px; height: 26px;
            min-width: 26px; min-height: 26px;
            border-radius: 50%;
            background: #e9ecef;
            border: 2px solid #dee2e6;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 0.75rem;
            flex-shrink: 0;
            transition: all 0.25s;
        }

        .template-card.selected .template-check {
            background: #E67E22;
            border-color: #E67E22;
        }

        .template-label {
            position: absolute; top: 8px; left: 8px;
            background: rgba(44,62,80,0.85);
            color: white; font-size: 0.7rem; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
        }

        /* ── Selected info bar ── */
        .selected-bar {
            background: #fff8f0;
            border: 1px solid rgba(230,126,34,0.25);
            border-left: 4px solid #E67E22;
            border-radius: 10px;
            padding: 0.9rem 1.2rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .selected-bar .sel-name { font-weight: 700; color: #2C3E50; font-size: 0.92rem; }
        .selected-bar .sel-meta { color: #888; font-size: 0.8rem; }

        /* ── Preview area ── */
        #cert-preview-wrap {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 380px;
        }

        #cert-canvas-container {
            position: relative;
            display: inline-block;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border-radius: 4px;
            overflow: hidden;
            container-type: inline-size;
            width: 100%;
            max-width: 660px;
        }

        #cert-template-img {
            display: block;
            width: 100%;
            height: auto;
        }

        #cert-text-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .draggable-cert-element {
            position: absolute !important;
            pointer-events: auto !important;
            cursor: move !important;
            user-select: none;
            transition: outline 0.15s ease, box-shadow 0.15s ease;
            outline: 1.5px dashed transparent;
            outline-offset: 3px;
            border-radius: 4px;
        }

        .draggable-cert-element:hover {
            outline-color: rgba(249, 115, 22, 0.8) !important;
            background: rgba(249, 115, 22, 0.05) !important;
        }

        .draggable-cert-element.is-dragging {
            outline-color: #f97316 !important;
            outline-style: solid !important;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.35);
            background: rgba(249, 115, 22, 0.12) !important;
            z-index: 9999 !important;
        }

        .cert-intro  { font-size: 1.8cqw; color: #374151; margin-bottom: 6px; font-family: 'Montserrat', sans-serif; }
        .cert-name   { font-size: 4.6cqw; font-weight: 800; color: #1e3a5f; margin-bottom: 4px; font-family: 'Montserrat', sans-serif; text-align: center; }
        .cert-nim    { font-size: 1.9cqw; color: #6b7280; margin-bottom: 14px; font-family: 'Montserrat', sans-serif; }
        .cert-desc   { font-size: 1.85cqw; color: #374151; margin-bottom: 5px; font-family: 'Montserrat', sans-serif; }
        .cert-event  { font-size: 2.8cqw; font-weight: 700; color: #1f2937; margin-bottom: 5px; font-family: 'Montserrat', sans-serif; text-align: center; }
        .cert-date   { font-size: 1.8cqw; color: #6b7280; margin-bottom: 14px; font-family: 'Montserrat', sans-serif; }
        .cert-nomor  { font-size: 1.6cqw; color: #9ca3af; letter-spacing: 1px; font-family: 'Montserrat', sans-serif; }

        /* ── Signature block (Dean of School) ── */
        #cert-signature-wrap {
            position: relative;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            font-family: 'Montserrat', sans-serif;
            margin-top: 1.5cqw;
        }
        #cert-signature-wrap .sig-role {
            font-size: 1.8cqw;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #d35400;
            text-transform: uppercase;
            margin-bottom: 0.6cqw;
        }
        /* Ruang kosong buat tempel gambar tanda tangan (upload logo/ttd manual) */
        #cert-signature-wrap .sig-space {
            width: 30cqw;
            height: 10cqw;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0.5cqw 0;
        }
        #cert-signature-wrap .sig-space img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        #cert-signature-wrap .sig-name {
            font-size: 1.85cqw;
            font-weight: 700;
            color: #2C3E50;
            white-space: nowrap;
            margin-top: 0.3cqw;
        }
        #cert-signature-wrap .sig-nip {
            font-size: 1.5cqw;
            color: #6b7280;
            margin-top: 0.25cqw;
        }

        /* ── QR Code overlay responsif ── */
        #cert-qr-wrap {
            position: absolute;
            bottom: 5%;
            left: 4%;
            background: white;
            padding: 0.8cqw !important;
            border-radius: 0.8cqw !important;
            box-shadow: 0 0.3cqw 1.2cqw rgba(0,0,0,0.12) !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.2cqw !important;
            border: 0.1cqw solid #ddd !important;
            width: 11cqw !important;
            height: auto !important;
        }
        #cert-qr-canvas {
            width: 100% !important;
            height: auto !important;
            display: block;
        }
        #cert-qr-label {
            font-size: 0.8cqw !important;
            margin-top: 0.1cqw;
            color: #7f8c8d;
            font-family: monospace;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 700;
        }

        /* ── Buttons (sama style dengan admin.php) ── */
        .btn-orange {
            background: linear-gradient(135deg, #E67E22, #d35400);
            color: white; border: none;
            padding: 0.6rem 1.5rem; border-radius: 25px;
            font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-orange:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(230,126,34,0.4); }
        .btn-orange:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .btn-secondary-custom {
            background: #95a5a6; color: white; border: none;
            padding: 0.6rem 1.5rem; border-radius: 25px;
            font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-secondary-custom:hover { background: #7f8c8d; }

        .btn-green-custom {
            background: #27ae60; color: white; border: none;
            padding: 0.6rem 1.5rem; border-radius: 25px;
            font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-green-custom:hover { background: #1e8449; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(39,174,96,0.35); }

        .btn-group-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1.2rem;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* ── Info alert ── */
        .info-alert {
            background: #fff8f0;
            border: 1px solid rgba(230,126,34,0.3);
            border-radius: 10px;
            padding: 0.8rem 1.2rem;
            margin-bottom: 1.2rem;
            font-size: 0.85rem;
            color: #7f4c00;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-alert i { color: #E67E22; flex-shrink: 0; }

        /* ── Adjuster controls ── */
        .text-controls {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
            align-items: flex-end;
        }
        .text-controls label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #888;
            display: block;
            margin-bottom: 4px;
        }

        /* ── Step Panel animation ── */
        .step-panel { display: none; }
        .step-panel.active { display: block; animation: fadeIn .25s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        /* ── Loading overlay ── */
        #download-loading {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 9999;
            align-items: center; justify-content: center;
            flex-direction: column; gap: 16px;
        }
        #download-loading.show { display: flex; }
        #download-loading p { color: white; font-size: 1rem; font-weight: 600; margin: 0; }
        .spinner-circle {
            width: 50px; height: 50px;
            border: 4px solid rgba(255,255,255,0.2);
            border-top-color: #E67E22;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* === MOBILE RESPONSIVE === */
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; max-width: 100%; }
        .mobile-topbar { display: none; position: fixed; top: 0; left: 0; right: 0; z-index: 1100; background: linear-gradient(135deg, #2C3E50, #1a2632); box-shadow: 0 2px 12px rgba(0,0,0,0.3); }
        .topbar-inner { display: flex; align-items: center; justify-content: space-between; height: 54px; padding: 0 0.75rem; gap: 0.5rem; }
        .hamburger-btn { display: none; background: rgba(255,255,255,0.15); color: white; border: none; border-radius: 8px; width: 38px; height: 38px; align-items: center; justify-content: center; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; flex-shrink: 0; }
        .hamburger-btn:hover { background: rgba(230,126,34,0.6); }
        .topbar-right { display: flex; align-items: center; gap: 0.5rem; flex: 1; min-width: 0; justify-content: flex-end; }
        .topbar-username { display: flex; align-items: center; gap: 0.35rem; color: rgba(255,255,255,0.9); font-size: 0.78rem; font-weight: 500; flex: 1; min-width: 0; }
        .topbar-username i { color: #E67E22; font-size: 1rem; flex-shrink: 0; }
        .topbar-username .name-text { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; min-width: 0; }
        .topbar-logout { background: #e74c3c; color: white; border: none; border-radius: 8px; padding: 0.38rem 0.8rem; font-size: 0.75rem; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 0.3rem; white-space: nowrap; transition: background 0.2s; flex-shrink: 0; }
        .topbar-logout:hover { background: #c0392b; color: white; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; backdrop-filter: blur(2px); }
        .sidebar-overlay.active { display: block; }
        @media (max-width: 768px) {
            .mobile-topbar { display: block; }
            .hamburger-btn { display: flex; }
            .admin-sidebar { position: fixed !important; left: -280px !important; z-index: 1000; transition: left 0.3s ease; width: 280px !important; display: block !important; }
            .admin-sidebar.open { left: 0 !important; }
            .admin-main { margin-left: 0 !important; padding: 1rem !important; padding-top: 4.5rem !important; max-width: 100vw; overflow-x: hidden; }
            .admin-header { flex-direction: column !important; align-items: stretch !important; gap: 0.75rem; margin-bottom: 1.5rem; }
            .admin-header h1 { font-size: 1.3rem !important; word-break: break-word; }
            .admin-header .user-info > span, .admin-header .user-info .logout-btn { display: none; }
            .admin-header .user-info { width: 100%; justify-content: stretch; }
            .admin-header .user-info .btn { flex: 0 0 100%; text-align: center; padding: 0.65rem 1rem; border-radius: 12px; }
            .template-grid { grid-template-columns: 1fr 1fr; }
            .step-btn {
                padding: 0.6rem 0.4rem !important;
                font-size: 0.72rem !important;
                gap: 6px !important;
            }
            .step-btn .step-num {
                width: 22px !important;
                height: 22px !important;
                font-size: 0.7rem !important;
            }
        }

        /* ── Override styles for Mahasiswa ── */
        <?php if ($this->session->userdata('role') === 'mahasiswa'): ?>
        .admin-main {
            margin-left: 0 !important;
            padding: 2rem 0 !important;
            max-width: 100% !important;
        }
        .hero-sertifikat {
            background: linear-gradient(135deg, #f97316 0%, #fdba74 100%);
            padding: 200px 0 100px;
            position: relative;
            overflow: hidden;
            clip-path: polygon(0 0, 100% 0, 100% 88%, 0 100%);
            margin-top: -54px;
            margin-bottom: 30px;
        }
        .hero-sertifikat h1 {
            font-size: clamp(2.2rem, 4vw, 3.5rem);
            font-weight: 800;
            color: white;
            text-align: center;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            font-family: 'Playfair Display', serif;
        }
        .hero-sertifikat p {
            color: rgba(255,255,255,0.95);
            font-size: 1.1rem;
            text-align: center;
            max-width: 700px;
            margin: 16px auto 0;
            position: relative;
            z-index: 1;
        }
        .hero-sertifikat .floating-elements {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .hero-sertifikat .floating-elements .element {
            position: absolute;
            opacity: 0.15;
            color: white;
            animation: float 6s ease-in-out infinite;
        }
        .hero-sertifikat .floating-elements .element:nth-child(1) { top: 20%; left: 10%; font-size: 2rem; }
        .hero-sertifikat .floating-elements .element:nth-child(2) { top: 60%; left: 15%; font-size: 1.5rem; }
        .hero-sertifikat .floating-elements .element:nth-child(3) { top: 30%; right: 12%; font-size: 2.2rem; }
        .hero-sertifikat .floating-elements .element:nth-child(4) { top: 70%; right: 20%; font-size: 1.8rem; }
        .hero-sertifikat .wave-bottom {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            line-height: 0;
        }
        .container-custom {
            width: min(100% - 3rem, 1280px);
            margin-inline: auto;
        }
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            color: #f97316;
            padding: 10px 24px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 2px solid #f97316;
        }
        .back-button:hover {
            background: #f97316;
            color: white;
            transform: translateX(-4px);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
        }
        .footer {
            background: linear-gradient(115deg, #152b4e 0%, #0f172a 100%);
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }
        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer a:hover {
            color: #f97316;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.85rem;
        }
        <?php endif; ?>
    </style>
</head>
<body>
<?php if ($this->session->userdata('role') === 'mahasiswa'): ?>
    <?php $this->load->view('partials/navbar', ['active_menu' => 'layanan']); ?>
    <!-- ========== HERO SECTION ========== -->
    <section class="hero-sertifikat">
        <div class="floating-elements">
            <div class="element"><i class="fas fa-certificate"></i></div>
            <div class="element"><i class="fas fa-file-pdf"></i></div>
            <div class="element"><i class="fas fa-file-excel"></i></div>
            <div class="element"><i class="fas fa-stamp"></i></div>
        </div>
        <div class="container-custom">
            <h1 data-aos="fade-up">Cetak Sertifikat Kegiatan</h1>
            <p data-aos="fade-up" data-aos-delay="100">Cetak sertifikat resmi untuk kegiatan yang telah disetujui oleh admin</p>
        </div>
        <div class="wave-bottom">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" style="width:100%;">
                <path fill="#ffffff" fill-opacity="1" d="M0,64L80,74.7C160,85,320,107,480,106.7C640,107,800,85,960,80C1120,75,1280,85,1360,90.7L1440,96L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
            </svg>
        </div>
    </section>

    <div class="container-custom py-4">
        <!-- Tombol Kembali -->
        <a href="<?= base_url('sertifikat') ?>" class="back-button mb-4" data-aos="fade-right">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Pengajuan Sertifikat
        </a>
<?php else: ?>
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

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="admin-wrapper">
    <!-- Sidebar -->
    <div class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <h3>Admin FIK</h3>
            <p>Manajemen Sertifikat</p>
        </div>
        
        <div class="sidebar-menu">
            <a href="<?= base_url('admin/edit_hero') ?>" class="<?= ($this->uri->segment(2) == 'edit_hero') ? 'active' : '' ?>">
                <i class="fas fa-desktop"></i>
                <span>Dashboard</span>
            </a>

            <a href="<?= base_url('admin/proposal') ?>" class="<?= ($this->uri->segment(2) == 'proposal') ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i>
                <span>Proposal</span>
            </a>

            <a href="<?= base_url('admin/beasiswa') ?>" class="<?= ($this->uri->segment(2) == 'beasiswa') ? 'active' : '' ?>">
                <i class="fas fa-graduation-cap"></i>
                <span>Beasiswa</span>
            </a>

            <a href="<?= base_url('sertifikat/admin') ?>" class="<?= ($this->uri->segment(1) == 'sertifikat' && $this->uri->segment(2) != 'generate' && $this->uri->segment(2) != 'export_excel_canva') ? 'active' : '' ?>">
                <i class="fas fa-certificate"></i>
                <span>Sertifikat</span>
            </a>
            
            <?php if ($this->uri->segment(1) == 'sertifikat'): ?>
            <a href="<?= base_url('sertifikat/generate') ?>" class="<?= ($this->uri->segment(2) == 'generate') ? 'active' : '' ?>" style="padding-left: 2.5rem; font-size: 0.85rem;">
                <i class="fas fa-magic"></i>
                <span>Generate Sertifikat</span>
            </a>
            <a href="<?= base_url('sertifikat/export_excel_canva') ?>" class="<?= ($this->uri->segment(2) == 'export_excel_canva') ? 'active' : '' ?>" style="padding-left: 2.5rem; font-size: 0.85rem;">
                <i class="fas fa-file-excel"></i>
                <span>Export Excel Canva</span>
            </a>
            <?php endif; ?>

            <a href="<?= base_url('tak_admin') ?>" class="<?= ($this->uri->segment(1) == 'tak_admin') ? 'active' : '' ?>">
                <i class="fas fa-file-signature"></i>
                <span>TAK</span>
            </a>
            
            <a href="<?= base_url('berita/admin') ?>" class="<?= ($this->uri->segment(1) == 'berita') ? 'active' : '' ?>">
                <i class="fas fa-newspaper"></i>
                <span>Berita</span>
            </a>
            
            <a href="<?= base_url('admin/organisasi') ?>" class="<?= ($this->uri->segment(2) == 'organisasi') ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Organisasi</span>
            </a>

            <a href="<?= base_url('admin/direktorat') ?>" class="<?= ($this->uri->segment(2) == 'direktorat') ? 'active' : '' ?>">
                <i class="fas fa-building"></i>
                <span>Direktorat</span>
            </a>

            <a href="<?= base_url('admin/mitra') ?>" class="<?= ($this->uri->segment(2) == 'mitra') ? 'active' : '' ?>">
                <i class="fas fa-handshake"></i>
                <span>Mitra & Recog</span>
            </a>

            <a href="<?= base_url('admin/testimoni') ?>" class="<?= ($this->uri->segment(2) == 'testimoni') ? 'active' : '' ?>">
                <i class="fas fa-comments"></i>
                <span>Testimoni Alumni</span>
            </a>

            <a href="<?= base_url('admin/tentang_kami') ?>" class="<?= ($this->uri->segment(2) == 'tentang_kami') ? 'active' : '' ?>">
                <i class="fas fa-info-circle"></i>
                <span>Tentang Kami</span>
            </a>

            <div class="menu-divider"></div>

            <a href="<?= base_url('admin/forum_alumni') ?>" class="<?= ($this->uri->segment(2) == 'forum_alumni') ? 'active' : '' ?>">
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

            <a href="<?= base_url('admin/history_log') ?>" class="<?= ($this->uri->segment(2) == 'history_log') ? 'active' : '' ?>">
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
    <?php endif; ?>

    <!-- Main Content -->
    <div class="admin-main <?= ($this->session->userdata('role') === 'mahasiswa') ? 'p-0' : '' ?>">

        <!-- Header -->
        <?php if ($this->session->userdata('role') !== 'mahasiswa'): ?>
        <div class="admin-header">
            <h1>Generate Sertifikat PDF</h1>
            <div class="user-info">
                <span><i class="fas fa-user-circle me-2" style="color:#E67E22;"></i><?= $this->session->userdata('nama') ?></span>
                <a href="<?= base_url('login/logout') ?>" class="logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Steps Bar -->
        <div class="steps-bar">
            <button class="step-btn active" id="btn-step-1" onclick="goStep(1)">
                <span class="step-num">1</span> Pilih Data
            </button>
            <div class="step-divider"></div>
            <button class="step-btn" id="btn-step-2" onclick="if(selectedRow) goStep(2)">
                <span class="step-num">2</span> Pilih Template
            </button>
            <div class="step-divider"></div>
            <button class="step-btn" id="btn-step-3" onclick="if(selectedTemplate) goStep(3)">
                <span class="step-num">3</span> Preview & Download
            </button>
        </div>

        <!-- ─── STEP 1: Pilih Data ─── -->
        <div class="step-panel active" id="step-1">

            <!-- Filter / Search -->
            <div class="filter-bar">
                <div class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text" style="border-color:#e0e0e0;background:#fff;">
                                <i class="fas fa-search" style="color:#E67E22;"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput"
                                placeholder="Cari nama mahasiswa, NIM, judul kegiatan, nomor sertifikat..."
                                oninput="filterTable()" style="border-left:none;">
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <small class="text-muted">Total: <strong><?= count($sertifikat_list) ?></strong> sertifikat disetujui</small>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-title">
                    <i class="fas fa-list-check"></i> Data Sertifikat yang Sudah Disetujui
                </div>

                <div class="info-alert">
                    <i class="fas fa-info-circle"></i>
                    Klik salah satu baris data, lalu klik tombol <strong>Generate</strong> untuk lanjut ke pemilihan template.
                </div>

                <div class="table-container">
                    <table id="mainTable">
                        <thead>
                            <tr>
                                <th class="radio-col" style="text-align:center; cursor: pointer;" onclick="toggleSelectAll()">
                                    <i class="far fa-square select-all-icon" style="font-size:1.15rem;"></i>
                                </th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM / Prodi</th>
                                <th>Judul Kegiatan</th>
                                <th>Tgl Kegiatan</th>
                                <th>Nomor Sertifikat</th>
                                <th>Disetujui</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($sertifikat_list)): ?>
                            <tr>
                                <td colspan="7" style="text-align:center;padding:40px;color:#aaa;">
                                    <i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>
                                    Belum ada sertifikat yang disetujui
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($sertifikat_list as $s): ?>
                            <tr class="cert-row" data-json="<?= htmlspecialchars(json_encode($s), ENT_QUOTES) ?>" onclick="selectRow(this, <?= htmlspecialchars(json_encode($s), ENT_QUOTES) ?>)">
                                <td class="radio-col" style="text-align:center;">
                                    <i class="far fa-square unselected-indicator" style="color:#dee2e6; font-size:1.15rem;"></i>
                                    <i class="fas fa-check-square selected-indicator" style="color:#27ae60; font-size:1.15rem;"></i>
                                </td>
                                <td><strong><?= htmlspecialchars($s['nama_mahasiswa']) ?></strong></td>
                                <td>
                                    <?= htmlspecialchars($s['nim'] ?? '-') ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($s['prodi'] ?? '-') ?></small>
                                </td>
                                <td style="max-width:200px;">
                                    <?= htmlspecialchars(mb_strimwidth($s['judul_kegiatan'], 0, 55, '...')) ?>
                                    <br><small class="text-muted">PIC: <?= htmlspecialchars($s['nama_pic'] ?? '-') ?></small>
                                </td>
                                <td><?= date('d/m/Y', strtotime($s['tanggal_kegiatan'])) ?></td>
                                <td><span class="badge-nomor"><?= htmlspecialchars($s['nomor_sertifikat'] ?? '-') ?></span></td>
                                <td><?= date('d M Y', strtotime($s['approved_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="btn-group-actions">
                    <span id="sel-info" style="color:#aaa;font-size:.85rem;">
                        <i class="fas fa-hand-pointer me-1"></i> Belum ada yang dipilih
                    </span>
                    <button class="btn-orange" id="btn-next-1" onclick="goStep(2)" disabled>
                        <i class="fas fa-magic"></i> Generate Sertifikat
                    </button>
                </div>
            </div>
        </div>

        <!-- ─── STEP 2: Pilih Template ─── -->
        <div class="step-panel" id="step-2">
            <div class="panel">
                <div class="panel-title d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-palette text-warning me-2"></i>Pilih Template Sertifikat</span>

                    <!-- Tombol Import Template Sendiri -->
                    <div>
                        <input type="file" id="customTemplateInput" accept="image/png, image/jpeg, image/jpg" style="display:none;" onchange="handleCustomTemplateUpload(this)">
                        <button class="btn btn-sm btn-outline-secondary" id="btn-import-template" onclick="document.getElementById('customTemplateInput').click()" style="border-radius:20px; font-family:'Montserrat'; font-size:0.8rem; font-weight:600;">
                            <i class="fas fa-upload me-1" style="color:#E67E22;"></i> Import Template Sendiri
                        </button>
                    </div>
                </div>

                <div class="selected-bar" id="sel-data-bar"></div>

                <div class="template-grid" id="templateGridContainer">
                    <?php
                    $templates = [
                        ['id'=>1, 'name'=>'Steel Wave',  'desc'=>'Elegan biru abu modern'],
                        ['id'=>2, 'name'=>'Black Gold',  'desc'=>'Eksklusif hitam & emas'],
                        ['id'=>3, 'name'=>'Blue Bubble', 'desc'=>'Segar biru dinamis'],
                        ['id'=>4, 'name'=>'Navy Grid',   'desc'=>'Profesional navy modern'],
                        ['id'=>5, 'name'=>'Royal Blue',  'desc'=>'Formal biru royal & emas'],
                    ];
                    foreach ($templates as $t): ?>
                    <div class="template-card" id="tpl-card-<?= $t['id'] ?>"
                         onclick="selectTemplate(<?= $t['id'] ?>, '<?= $t['name'] ?>', false)">
                        <span class="template-label">Template <?= $t['id'] ?></span>
                        <img src="<?= base_url('templates/sertifikat/template'.$t['id'].'.png') ?>"
                             alt="<?= $t['name'] ?>" loading="lazy">
                        <div class="template-card-info">
                            <div>
                                <div class="template-card-name"><?= $t['name'] ?></div>
                                <div class="template-card-desc"><?= $t['desc'] ?></div>
                            </div>
                            <div class="template-check"><i class="fas fa-check"></i></div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php
                    // Template custom yang sudah pernah diupload sebelumnya (tersimpan permanen di server)
                    // $custom_templates dikirim dari controller, berisi array ['id'=>..., 'name'=>..., 'file'=>...]
                    if (!empty($custom_templates)):
                        foreach ($custom_templates as $ct): ?>
                    <div class="template-card" id="tpl-card-custom-<?= $ct['id'] ?>"
                         onclick="selectTemplate('custom-<?= $ct['id'] ?>', '<?= htmlspecialchars($ct['name']) ?>', true, '<?= base_url('templates/sertifikat/custom/'.$ct['file']) ?>')">
                        <span class="template-label" style="background:#27ae60;">Custom Upload</span>
                        <img src="<?= base_url('templates/sertifikat/custom/'.$ct['file']) ?>"
                             alt="<?= htmlspecialchars($ct['name']) ?>" loading="lazy">
                        <div class="template-card-info">
                            <div>
                                <div class="template-card-name"><?= htmlspecialchars($ct['name']) ?></div>
                                <div class="template-card-desc">Template yang diunggah</div>
                            </div>
                            <div class="template-check"><i class="fas fa-check"></i></div>
                        </div>
                    </div>
                    <?php endforeach; endif; ?>
                </div>

                <div class="btn-group-actions">
                    <button class="btn-secondary-custom" onclick="goStep(1)">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                    <button class="btn-orange" id="btn-next-2" onclick="goStep(3)" disabled>
                        <i class="fas fa-eye"></i> Lihat Preview
                    </button>
                </div>
            </div>
        </div>

        <!-- ─── STEP 3: Preview & Download ─── -->
        <div class="step-panel" id="step-3">
            <div class="panel">
                <div class="panel-title"><i class="fas fa-eye"></i> Preview Sertifikat</div>

                <div class="selected-bar" id="sel-data-bar-3"></div>

                <!-- Selector untuk memilih data yang dipreview -->
                <div class="preview-selector-wrap mb-4 p-3 bg-light rounded" id="previewSelectorContainer" style="display:none; border: 1px solid #e2e8f0; margin-top: 15px;">
                    <label for="previewSelector" class="form-label font-weight-bold" style="font-size:0.9rem; color:#2C3E50; font-weight:700;">
                        <i class="fas fa-users text-warning me-2"></i> Pilih Mahasiswa untuk Preview Sertifikat:
                    </label>
                    <select class="form-select mt-1" id="previewSelector" onchange="changePreviewStudent(this.value)" style="border-radius:10px; padding: 0.6rem 1rem; border: 1px solid #cbd5e1; font-weight: 600;">
                        <!-- Options filled by JS -->
                    </select>
                </div>

                <!-- Preview -->
                <div id="cert-preview-wrap">
                    <div id="cert-canvas-container" style="position:relative;display:inline-block;">
                        <img id="cert-template-img" src="" alt="Template">
                        <div id="cert-text-overlay">
                            <!-- Judul CERTIFICATE -->
                            <div class="cert-title-main draggable-cert-element" style="top:18%; left:50%; transform:translate(-50%,-50%); font-size: 4.8cqw; font-weight: 800; color: #d35400; letter-spacing: 0.3cqw; text-transform: uppercase;">Certificate</div>

                            <!-- Nomor Sertifikat -->
                            <div class="cert-nomor draggable-cert-element" id="prev-nomor" style="top:25%; left:50%; transform:translate(-50%,-50%); font-size: 1.5cqw; font-weight: 700; color: #2C3E50; letter-spacing: 0.15cqw; text-transform: uppercase; white-space:nowrap;">Certificate Number: 020/S-AKD05/IK-DEK/2026</div>

                            <!-- Kalimat pembuka -->
                            <div class="cert-intro draggable-cert-element" style="top:32%; left:50%; transform:translate(-50%,-50%); font-size: 1.65cqw; font-style: italic; color: #7f8c8d; white-space:nowrap;">THIS CERTIFICATE IS PRESENT TO</div>

                            <!-- Nama Penerima -->
                            <div class="cert-name-container draggable-cert-element" style="top:42%; left:50%; transform:translate(-50%,-50%); border-bottom: 0.3cqw solid #E67E22; padding-bottom: 0.4cqw; min-width: 40cqw; text-align: center;">
                                <div class="cert-name" id="prev-nama" style="font-size: 3.8cqw; font-weight: 800; color: #2C3E50; font-family: 'Montserrat', sans-serif;">TEGUH AKBAR, ST</div>
                            </div>

                            <!-- Keterangan Partisipasi -->
                            <div class="cert-desc draggable-cert-element" id="prev-desc" style="top:53%; left:50%; transform:translate(-50%,-50%); font-size: 1.65cqw; color: #7f8c8d; white-space:nowrap;">For the Participation as <strong style="color: #d35400;" id="prev-role">Committee</strong> at :</div>

                            <!-- Judul Kegiatan -->
                            <div class="cert-event draggable-cert-element" id="prev-judul" style="top:61%; left:50%; transform:translate(-50%,-50%); font-size: 2.5cqw; font-weight: 700; color: #2C3E50; text-transform: uppercase; text-align: center; max-width: 90%;">BERWARA ADVERTISING EXHIBITION & AWARD</div>

                            <!-- Waktu & Tempat -->
                            <div class="cert-date draggable-cert-element" id="prev-tanggal" style="top:69%; left:50%; transform:translate(-50%,-50%); font-size: 1.55cqw; color: #7f8c8d; text-transform: uppercase; font-weight: 600; white-space:nowrap;">HELD AT SCHOOL OF CREATIVE INDUSTRIES</div>

                            <!-- Tanda tangan -->
                            <div id="cert-signature-wrap" class="draggable-cert-element" style="top:82%; left:50%; transform:translate(-50%,-50%);">
                                <div class="sig-role" id="sig-role">Dean of School</div>
                                <div class="sig-space" id="sig-space">
                                    <img id="sig-image" src="" alt="Tanda tangan" style="display:none;">
                                </div>
                                <div class="sig-name" id="sig-nama">Dandi Yunidar, S.Sn., M.Ds., Ph.D.</div>
                                <div class="sig-nip" id="sig-nip">NIP: 14760039</div>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div id="cert-qr-wrap" class="draggable-cert-element" style="top:84%; left:10%; transform:translate(-50%,-50%);" <?php if ($this->session->userdata('role') !== 'mahasiswa') echo 'style="display: none !important;"'; ?>>
                            <canvas id="cert-qr-canvas"></canvas>
                            <span id="cert-qr-label">verify</span>
                        </div>
                    </div>
                </div>

                <!-- Text controls -->
                <div class="text-controls">
                    <div>
                        <label>Posisi Teks (Atas ↕ Bawah)</label>
                        <input type="range" id="posY" min="15" max="85" value="50" style="width:180px;" oninput="adjustText()">
                        <span style="color:#888;font-size:.8rem;" id="posY-val">50%</span>
                    </div>
                    <?php if ($this->session->userdata('role') === 'mahasiswa'): ?>
                    <div>
                        <label>Posisi QR Code (Horizontal X)</label>
                        <input type="range" id="qrPosX" min="0" max="95" value="4" style="width:180px;" oninput="adjustQrPosition()">
                        <span style="color:#888;font-size:.8rem;" id="qrPosX-val">4%</span>
                    </div>
                    <div>
                        <label>Posisi QR Code (Vertikal Y)</label>
                        <input type="range" id="qrPosY" min="0" max="95" value="82" style="width:180px;" oninput="adjustQrPosition()">
                        <span style="color:#888;font-size:.8rem;" id="qrPosY-val">82%</span>
                    </div>
                    <?php endif; ?>
                    <div style="display:flex; flex-direction:column; gap:4px;">
                        <label><i class="fas fa-arrows-alt me-1 text-warning"></i> Tata Letak Teks & QR Code</label>
                        <button type="button" onclick="resetCertLayout()" class="btn btn-sm btn-outline-secondary" style="border-radius:8px; font-size:0.8rem; font-weight:600; padding:4px 12px;">
                            <i class="fas fa-undo me-1"></i> Reset Posisi Tata Letak
                        </button>
                    </div>
                    <div>
                        <label>Warna Nama</label>
                        <input type="color" id="nameColor" value="#1e3a5f" oninput="adjustText()">
                    </div>
                    <div>
                        <label>Warna Teks Isi</label>
                        <input type="color" id="textColor" value="#374151" oninput="adjustText()">
                    </div>

                    <?php if ($this->session->userdata('role') !== 'mahasiswa'): ?>
                    <div>
                        <label>Jabatan Penandatangan</label>
                        <input type="text" id="deanRole" value="Dean of School" style="border:1px solid #e0e0e0;border-radius:8px;padding:6px 10px;font-size:0.8rem;font-family:'Montserrat';width:200px;" oninput="updateSignature()">
                    </div>
                    <div>
                        <label>Nama Dean / Penandatangan</label>
                        <input type="text" id="deanName" value="Dandi Yunidar, S.Sn., M.Ds., Ph.D." style="border:1px solid #e0e0e0;border-radius:8px;padding:6px 10px;font-size:0.8rem;font-family:'Montserrat';width:230px;" oninput="updateSignature()">
                    </div>
                    <div>
                        <label>NIP</label>
                        <input type="text" id="deanNip" value="14760039" style="border:1px solid #e0e0e0;border-radius:8px;padding:6px 10px;font-size:0.8rem;font-family:'Montserrat';width:140px;" oninput="updateSignature()">
                    </div>
                    <div>
                        <label>Gambar Tanda Tangan (opsional)</label>
                        <div class="drag-drop-zone" id="sigDragDropZone" style="border: 2px dashed #E67E22; border-radius: 12px; padding: 1.2rem; text-align: center; background: #fdfaf7; cursor: pointer; transition: all 0.3s ease; margin-top: 5px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <div id="sigDragDropPrompt" style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <i class="fas fa-signature" style="font-size: 1.8rem; color: #E67E22;"></i>
                                <span style="font-size: 0.8rem; font-weight: 600; color: #2C3E50;">Tarik & Lepas gambar tanda tangan di sini</span>
                                <span style="font-size: 0.72rem; color: #888;">atau klik untuk pilih berkas (PNG / JPG)</span>
                            </div>
                            <div id="sigDragDropPreviewContainer" style="display: none; flex-direction: column; align-items: center; gap: 8px;">
                                <img id="sigDragDropPreviewImg" src="" style="max-height: 80px; object-fit: contain; border-radius: 8px; border: 1px solid #ddd; padding: 4px; background: white;">
                                <span style="font-size: 0.75rem; color: #27ae60; font-weight: 600;"><i class="fas fa-check-circle me-1"></i> Tanda tangan berhasil dimuat!</span>
                                <span style="font-size: 0.7rem; color: #e74c3c; cursor: pointer; text-decoration: underline;" onclick="removeSignaturePreview(event)">Hapus & Ganti</span>
                            </div>
                            <input type="file" id="sigImageInput" accept="image/png, image/jpeg" style="display:none;" onchange="handleSignatureImageUpload(this)">
                        </div>
                    </div>
                    <?php else: ?>
                    <input type="hidden" id="deanRole" value="Dean of School">
                    <input type="hidden" id="deanName" value="Dandi Yunidar, S.Sn., M.Ds., Ph.D.">
                    <input type="hidden" id="deanNip" value="14760039">
                    <input type="file" id="sigImageInput" style="display:none;">
                    <?php endif; ?>

                </div>

                <div class="btn-group-actions">
                    <button class="btn-secondary-custom" onclick="goStep(2)">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                    <div style="display:flex;gap:10px;">
                        <button class="btn-orange" onclick="downloadPDF()">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </button>
                        <button class="btn-green-custom" onclick="downloadPNG()">
                            <i class="fas fa-image"></i> Download PNG
                        </button>
                    </div>
                </div>
            </div>
        </div>

<?php if ($this->session->userdata('role') === 'mahasiswa'): ?>
        </div><!-- /admin-main -->
    </div><!-- /container-custom -->

    <!-- ========== FOOTER ========== -->
    <footer class="footer">
        <div class="container-custom">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="mb-3" style="color: #f97316;">Fakultas Industri Kreatif</h4>
                    <p style="opacity: 0.8; font-size: 0.9rem;">Menjadi pusat unggulan pendidikan industri kreatif yang menghasilkan lulusan berdaya saing global.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in fa-lg"></i></a>
                        <a href="#"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h4 class="mb-3" style="color: #f97316;">Tautan Cepat</h4>
                    <ul class="list-unstyled" style="font-size: 0.9rem; line-height: 1.8;">
                        <li class="mb-2"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="mb-2"><a href="<?= base_url('berita') ?>">Berita</a></li>
                        <li class="mb-2"><a href="#">Layanan Mahasiswa</a></li>
                        <li class="mb-2"><a href="#">Forum Alumni</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h4 class="mb-3" style="color: #f97316;">Kontak</h4>
                    <ul class="list-unstyled" style="font-size: 0.9rem; line-height: 1.8;">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Bandung, Jawa Barat</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> fik@telkomuniversity.ac.id</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> (022) 756 5923</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">&copy; <?= date('Y') ?> Fakultas Industri Kreatif - Telkom University. All rights reserved.</p>
            </div>
        </div>
    </footer>
<?php else: ?>
    </div><!-- /admin-main -->
</div><!-- /admin-wrapper -->
<?php endif; ?>

<!-- Loading overlay -->
<div id="download-loading">
    <div class="spinner-circle"></div>
    <p id="loading-text">Sedang membuat file, harap tunggu...</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const BASE_URL = '<?= base_url() ?>';
    let selectedRows     = [];
    let selectedRow      = null;
    let selectedTemplate = null;   // id template (angka, atau 'custom-<id>')
    let selectedTemplateUrl = null; // url gambar template yang sedang dipakai (kalau custom)
    let isAllSelected = false;

    /* ── Step navigation ── */
    function goStep(n) {
        if (n === 2 && selectedRows.length === 0) { alert('Pilih data sertifikat terlebih dahulu!'); return; }
        if (n === 3 && !selectedTemplate) { alert('Pilih template terlebih dahulu!'); return; }

        document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('step-' + n).classList.add('active');

        [1,2,3].forEach(i => {
            const btn = document.getElementById('btn-step-' + i);
            btn.classList.remove('active','done');
            if (i < n) btn.classList.add('done');
            if (i === n) btn.classList.add('active');
        });

        if (n === 2) renderSelBar();
        if (n === 3) renderPreview();
    }

    /* ── Pilih baris ── */
    function selectRow(tr, data) {
        tr.classList.toggle('selected');
        const icon = tr.querySelector('.select-icon');
        
        const idx = selectedRows.findIndex(r => r.id === data.id);
        if (idx > -1) {
            selectedRows.splice(idx, 1);
            if (icon) {
                icon.className = 'far fa-square select-icon';
                icon.style.color = '#cbd5e1';
            }
        } else {
            selectedRows.push(data);
            if (icon) {
                icon.className = 'fas fa-check-square select-icon';
                icon.style.color = '#27ae60';
            }
        }
        
        selectedRow = selectedRows.length > 0 ? selectedRows[0] : null;
        updateSelectionUI();
    }

    /* ── Select All ── */
    function toggleSelectAll() {
        const trs = document.querySelectorAll('#mainTable tbody tr.cert-row');
        isAllSelected = !isAllSelected;
        
        const icon = document.querySelector('.select-all-icon');
        if (isAllSelected) {
            icon.className = 'fas fa-check-square select-all-icon';
            icon.style.color = '#27ae60';
            
            selectedRows = [];
            trs.forEach(tr => {
                if (tr.style.display !== 'none') {
                    tr.classList.add('selected');
                    const data = JSON.parse(tr.getAttribute('data-json'));
                    selectedRows.push(data);
                }
            });
        } else {
            icon.className = 'far fa-square select-all-icon';
            icon.style.color = '';
            
            trs.forEach(tr => {
                tr.classList.remove('selected');
            });
            selectedRows = [];
        }
        
        selectedRow = selectedRows.length > 0 ? selectedRows[0] : null;
        updateSelectionUI();
    }

    /* ── Update Selection UI ── */
    function updateSelectionUI() {
        const btnNext = document.getElementById('btn-next-1');
        const selInfo = document.getElementById('sel-info');
        
        if (selectedRows.length > 0) {
            selInfo.innerHTML = `<i class="fas fa-check-circle" style="color:#27ae60;margin-right:6px;"></i>
                                 Dipilih: <strong>${selectedRows.length}</strong> sertifikat`;
            btnNext.disabled = false;
        } else {
            selInfo.innerHTML = `<i class="fas fa-hand-pointer me-1"></i> Belum ada yang dipilih`;
            btnNext.disabled = true;
            
            const icon = document.querySelector('.select-all-icon');
            if (icon) {
                icon.className = 'far fa-square select-all-icon';
                icon.style.color = '';
                isAllSelected = false;
            }
        }
    }

    /* ── Pilih template ──
       id            : id template (angka untuk bawaan, 'custom-<id>' untuk hasil upload)
       name          : nama template
       isCustom      : true kalau ini template hasil upload
       customUrl     : url gambar template di server (khusus custom)
    */
    function selectTemplate(id, name, isCustom, customUrl) {
        document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
        const cardId = isCustom ? ('tpl-card-' + id) : ('tpl-card-' + id);
        const card = document.getElementById(cardId);
        if (card) card.classList.add('selected');

        selectedTemplate    = id;
        selectedTemplateUrl = isCustom ? customUrl : (BASE_URL + 'templates/sertifikat/template' + id + '.png');
        document.getElementById('btn-next-2').disabled = false;
    }

    /* ── Handle Custom Template Upload ──
       File langsung diupload ke server (endpoint: sertifikat/upload_template) supaya
       benar-benar tersimpan permanen dan tidak hilang saat pindah step / reload halaman.
       Lihat catatan controller di bagian bawah chat.
    */
    function handleCustomTemplateUpload(input) {
        const file = input.files[0];
        if (!file) return;

        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar (PNG/JPG)!');
            return;
        }

        const btn = document.getElementById('btn-import-template');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mengunggah...';

        const formData = new FormData();
        formData.append('template_file', file);
        formData.append('template_name', file.name.replace(/\.[^/.]+$/, ''));

        fetch(BASE_URL + 'sertifikat/upload_template', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(resp => {
            if (!resp.success) {
                throw new Error(resp.message || 'Upload gagal');
            }

            // resp harus berisi: { success: true, id: <id_baru>, name: <nama>, url: <url_gambar_di_server> }
            const container = document.getElementById('templateGridContainer');
            const cardHtml = `
                <div class="template-card selected" id="tpl-card-custom-${resp.id}"
                     onclick="selectTemplate('custom-${resp.id}', '${resp.name}', true, '${resp.url}')">
                    <span class="template-label" style="background:#27ae60;">Custom Upload</span>
                    <img src="${resp.url}" alt="${resp.name}">
                    <div class="template-card-info">
                        <div>
                            <div class="template-card-name">${resp.name}</div>
                            <div class="template-card-desc">Template yang diunggah</div>
                        </div>
                        <div class="template-check"><i class="fas fa-check"></i></div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('afterbegin', cardHtml);

            selectTemplate('custom-' + resp.id, resp.name, true, resp.url);
        })
        .catch(err => {
            alert('Gagal mengunggah template: ' + err.message);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            input.value = ''; // reset input supaya bisa upload file yang sama lagi kalau perlu
        });
    }

    /* ── Render info bar ── */
    function renderSelBar() {
        if (selectedRows.length === 0) return;
        const count = selectedRows.length;
        const names = selectedRows.map(r => r.nama_mahasiswa).slice(0, 3).join(', ') + (count > 3 ? '...' : '');
        
        const html = `
            <div>
                <div class="sel-name">Multi-select (${count} Mahasiswa)</div>
                <div class="sel-meta">${names}</div>
            </div>
            <span class="badge-nomor">${count} Berkas</span>`;
        document.querySelectorAll('#sel-data-bar, #sel-data-bar-3').forEach(el => el.innerHTML = html);
    }

    /* ── Render preview ── */
    function renderPreview() {
        renderSelBar();
        
        // Populate selector dropdown
        const container = document.getElementById('previewSelectorContainer');
        const selector = document.getElementById('previewSelector');
        
        if (selectedRows.length > 1) {
            container.style.display = 'block';
            selector.innerHTML = '';
            selectedRows.forEach((r, idx) => {
                const opt = document.createElement('option');
                opt.value = idx;
                opt.textContent = `${r.nama_mahasiswa} (${r.nim}) - ${r.nomor_sertifikat}`;
                selector.appendChild(opt);
            });
        } else {
            container.style.display = 'none';
        }
        
        const img = document.getElementById('cert-template-img');
        img.onload = function() { renderQR(); };

        img.src = selectedTemplateUrl;
        if (img.complete) renderQR();

        // Default preview the first student
        changePreviewStudent(0);
    }

    /* ── Ganti mahasiswa yang dipreview ── */
    function changePreviewStudent(index) {
        const idx = parseInt(index);
        if (isNaN(idx) || idx < 0 || idx >= selectedRows.length) return;
        
        selectedRow = selectedRows[idx];
        
        const d = selectedRow;
        document.getElementById('prev-nama').textContent  = d.nama_mahasiswa;
        document.getElementById('prev-judul').textContent = d.judul_kegiatan;

        const months = ['January','February','March','April','May','June','July',
                        'August','September','October','November','December'];

        function formatSingleDate(dateStr) {
            const dt = new Date(dateStr);
            return `${months[dt.getMonth()].toUpperCase()} ${dt.getDate()}, ${dt.getFullYear()}`;
        }

        let tanggalStr = '';
        if (d.tanggal_kegiatan) {
            if (d.tanggal_kegiatan_selesai && d.tanggal_kegiatan_selesai !== d.tanggal_kegiatan) {
                const start = new Date(d.tanggal_kegiatan);
                const end   = new Date(d.tanggal_kegiatan_selesai);
                if (start.getMonth() === end.getMonth() && start.getFullYear() === end.getFullYear()) {
                    tanggalStr = `FROM ${months[start.getMonth()].toUpperCase()} ${start.getDate()} – ${end.getDate()}, ${end.getFullYear()}`;
                } else {
                    tanggalStr = `FROM ${formatSingleDate(d.tanggal_kegiatan)} TO ${formatSingleDate(d.tanggal_kegiatan_selesai)}`;
                }
            } else {
                tanggalStr = `ON ${formatSingleDate(d.tanggal_kegiatan)}`;
            }
        }

        const lokasi = d.lokasi_kegiatan ? d.lokasi_kegiatan.toUpperCase() : 'SCHOOL OF CREATIVE INDUSTRIES';
        document.getElementById('prev-tanggal').textContent =
            tanggalStr ? `HELD AT ${lokasi} ${tanggalStr}` : `HELD AT ${lokasi}`;

        document.getElementById('prev-nomor').textContent = 'CERTIFICATE NUMBER: ' + (d.nomor_sertifikat ?? '-');

        const role = d.prodi ? d.prodi : 'Committee';
        const roleCapitalized = role.charAt(0).toUpperCase() + role.slice(1);
        document.getElementById('prev-desc').innerHTML = `For the Participation as <strong style="color: #d35400;" id="prev-role">${roleCapitalized}</strong> at :`;

        renderQR();
        updateSignature();
        
        // Render approved signature image if present in database
        const sigImg = document.getElementById('sig-image');
        if (sigImg) {
            if (d.signature_image) {
                sigImg.src = BASE_URL + d.signature_image;
                sigImg.style.display = 'block';
            } else {
                sigImg.src = '';
                sigImg.style.display = 'none';
            }
        }
        
        adjustText();
    }

    /* ── Render QR Code di pojok kiri bawah sertifikat ── */
    function renderQR() {
        const d = selectedRow;
        const qrUrl = d.qr_code
            ? d.qr_code
            : BASE_URL + 'sertifikat/verifikasi/' + encodeURIComponent(d.nomor_sertifikat ?? '');

        document.getElementById('cert-qr-label').textContent = d.nomor_sertifikat ?? 'verify';

        const img  = document.getElementById('cert-template-img');
        const size = Math.max(70, Math.round(img.clientWidth * 0.10));

        const canvas = document.getElementById('cert-qr-canvas');
        
        const isImage = qrUrl.match(/\.(jpeg|jpg|png|gif)$/i) || qrUrl.includes('uploads/');
        if (isImage) {
            const qrImg = new Image();
            qrImg.onload = function() {
                canvas.width = size;
                canvas.height = size;
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, size, size);
                ctx.drawImage(qrImg, 0, 0, size, size);
            };
            qrImg.onerror = function() {
                console.error('Failed to load QR image:', qrUrl);
            };
            qrImg.src = qrUrl.startsWith('uploads/') ? BASE_URL + qrUrl : qrUrl;
        } else {
            QRCode.toCanvas(canvas, qrUrl, {
                width:  size,
                margin: 1,
                color: { dark: '#000000', light: '#ffffff' }
            }, function(err) {
                if (err) console.error('QR render error:', err);
            });
        }
    }

    /* ── Update tanda tangan Dean of School (jabatan + nama + NIP) ── */
    function updateSignature() {
        const role = document.getElementById('deanRole').value || 'Dean of School';
        const nama = document.getElementById('deanName').value || 'Dandi Yunidar, S.Sn., M.Ds., Ph.D.';
        const nip  = document.getElementById('deanNip').value || '14760039';
        document.getElementById('sig-role').textContent = role;
        document.getElementById('sig-nama').textContent = nama;
        document.getElementById('sig-nip').textContent  = 'NIP: ' + nip;
    }

    /* ── Upload gambar tanda tangan (ditampilkan langsung di ruang kosong tanda tangan) ── */
    function handleSignatureImageUpload(input) {
        const file = input.files[0];
        if (!file) return;

        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar (PNG/JPG)!');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            // Update certificate canvas signature
            const img = document.getElementById('sig-image');
            img.src = e.target.result;
            img.style.display = 'block';

            // Update drag & drop zone preview
            const prompt = document.getElementById('sigDragDropPrompt');
            const previewContainer = document.getElementById('sigDragDropPreviewContainer');
            const previewImg = document.getElementById('sigDragDropPreviewImg');
            if (prompt && previewContainer && previewImg) {
                prompt.style.display = 'none';
                previewContainer.style.display = 'flex';
                previewImg.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }

    /* ── Hapus & Reset Preview Tanda Tangan ── */
    function removeSignaturePreview(event) {
        if (event) {
            event.stopPropagation(); // prevent triggering dropZone click
        }
        const sigInput = document.getElementById('sigImageInput');
        const img = document.getElementById('sig-image');
        const prompt = document.getElementById('sigDragDropPrompt');
        const previewContainer = document.getElementById('sigDragDropPreviewContainer');
        const previewImg = document.getElementById('sigDragDropPreviewImg');

        if (sigInput) sigInput.value = '';
        if (img) {
            img.src = '';
            img.style.display = 'none';
        }
        if (prompt && previewContainer && previewImg) {
            prompt.style.display = 'flex';
            previewContainer.style.display = 'none';
            previewImg.src = '';
        }
    }

    /* ── Adjust teks posisi & warna ── */
    function adjustText() {
        const posY = parseInt(document.getElementById('posY').value);
        document.getElementById('posY-val').textContent = posY + '%';

        const overlay = document.getElementById('cert-text-overlay');
        overlay.style.justifyContent = posY < 38 ? 'flex-start'
                                     : posY > 62 ? 'flex-end' : 'center';
        overlay.style.paddingTop    = posY < 38 ? posY + '%' : '0';
        overlay.style.paddingBottom = posY > 62 ? (100 - posY) + '%' : '0';

        const nc = document.getElementById('nameColor').value;
        const tc = document.getElementById('textColor').value;
        document.getElementById('prev-nama').style.color  = nc;
        document.getElementById('prev-judul').style.color = nc;
        document.querySelectorAll('.cert-intro, .cert-desc, .cert-date, .cert-nomor')
            .forEach(el => el.style.color = tc);
    }

    /* ── Adjust posisi QR Code ── */
    function adjustQrPosition() {
        const sliderX = document.getElementById('qrPosX');
        const sliderY = document.getElementById('qrPosY');
        if (!sliderX || !sliderY) return;

        const x = sliderX.value;
        const y = sliderY.value;
        
        const qrWrap = document.getElementById('cert-qr-wrap');
        if (qrWrap) {
            qrWrap.style.left = x + '%';
            qrWrap.style.top = y + '%';
            qrWrap.style.bottom = 'auto';
        }
        
        document.getElementById('qrPosX-val').textContent = x + '%';
        document.getElementById('qrPosY-val').textContent = y + '%';
    }

    /* ── Make All Elements Draggable ── */
    function makeAllElementsDraggable() {
        const container = document.getElementById('cert-canvas-container');
        if (!container) return;

        const draggables = container.querySelectorAll('.draggable-cert-element');

        draggables.forEach(el => {
            let isDragging = false;
            let startX, startY;
            let startLeftPct, startTopPct;

            el.addEventListener('mousedown', dragStart);
            el.addEventListener('touchstart', dragStart, { passive: false });

            function dragStart(e) {
                if (e.type === 'mousedown' && e.button !== 0) return;

                e.preventDefault();
                e.stopPropagation();

                isDragging = true;
                el.classList.add('is-dragging');

                const containerRect = container.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;

                startX = clientX;
                startY = clientY;

                const styleLeft = el.style.left;
                const styleTop = el.style.top;

                if (styleLeft && styleLeft.endsWith('%')) {
                    startLeftPct = parseFloat(styleLeft);
                } else {
                    const elRect = el.getBoundingClientRect();
                    const centerX = elRect.left + (elRect.width / 2) - containerRect.left;
                    startLeftPct = (centerX / containerRect.width) * 100;
                }

                if (styleTop && styleTop.endsWith('%')) {
                    startTopPct = parseFloat(styleTop);
                } else {
                    const elRect = el.getBoundingClientRect();
                    const centerY = elRect.top + (elRect.height / 2) - containerRect.top;
                    startTopPct = (centerY / containerRect.height) * 100;
                }

                window.addEventListener('mousemove', dragMove);
                window.addEventListener('touchmove', dragMove, { passive: false });
                window.addEventListener('mouseup', dragEnd);
                window.addEventListener('touchend', dragEnd);
            }

            function dragMove(e) {
                if (!isDragging) return;
                e.preventDefault();

                const containerRect = container.getBoundingClientRect();
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                const clientY = e.touches ? e.touches[0].clientY : e.clientY;

                const deltaX = clientX - startX;
                const deltaY = clientY - startY;

                const deltaLeftPct = (deltaX / containerRect.width) * 100;
                const deltaTopPct = (deltaY / containerRect.height) * 100;

                let newLeft = Math.max(2, Math.min(98, startLeftPct + deltaLeftPct));
                let newTop = Math.max(2, Math.min(98, startTopPct + deltaTopPct));

                el.style.left = newLeft.toFixed(1) + '%';
                el.style.top = newTop.toFixed(1) + '%';
                el.style.transform = 'translate(-50%, -50%)';

                if (el.id === 'cert-qr-wrap') {
                    const sliderX = document.getElementById('qrPosX');
                    const sliderY = document.getElementById('qrPosY');
                    if (sliderX) sliderX.value = Math.round(newLeft);
                    if (sliderY) sliderY.value = Math.round(newTop);
                    const labelX = document.getElementById('qrPosX-val');
                    const labelY = document.getElementById('qrPosY-val');
                    if (labelX) labelX.textContent = Math.round(newLeft) + '%';
                    if (labelY) labelY.textContent = Math.round(newTop) + '%';
                }
            }

            function dragEnd() {
                if (isDragging) {
                    isDragging = false;
                    el.classList.remove('is-dragging');
                    window.removeEventListener('mousemove', dragMove);
                    window.removeEventListener('touchmove', dragMove);
                    window.removeEventListener('mouseup', dragEnd);
                    window.removeEventListener('touchend', dragEnd);
                }
            }
        });
    }

    /* ── Reset Posisi Tata Letak ── */
    function resetCertLayout() {
        const defaults = {
            '.cert-title-main': { top: '18%', left: '50%' },
            '#prev-nomor': { top: '25%', left: '50%' },
            '.cert-intro': { top: '32%', left: '50%' },
            '.cert-name-container': { top: '42%', left: '50%' },
            '#prev-desc': { top: '53%', left: '50%' },
            '#prev-judul': { top: '61%', left: '50%' },
            '#prev-tanggal': { top: '69%', left: '50%' },
            '#cert-signature-wrap': { top: '82%', left: '50%' },
            '#cert-qr-wrap': { top: '84%', left: '10%' }
        };

        for (let selector in defaults) {
            const el = document.querySelector(selector);
            if (el) {
                el.style.top = defaults[selector].top;
                el.style.left = defaults[selector].left;
                el.style.transform = 'translate(-50%, -50%)';
            }
        }

        const sliderX = document.getElementById('qrPosX');
        const sliderY = document.getElementById('qrPosY');
        if (sliderX) sliderX.value = 10;
        if (sliderY) sliderY.value = 84;
        const labelX = document.getElementById('qrPosX-val');
        const labelY = document.getElementById('qrPosY-val');
        if (labelX) labelX.textContent = '10%';
        if (labelY) labelY.textContent = '84%';
    }

    /* ── Filter tabel ── */
    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#mainTable tbody tr').forEach(tr => {
            tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    /* ── Download PDF ── */
    async function downloadPDF() {
        const loading = document.getElementById('download-loading');
        loading.classList.add('show');
        
        try {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
            
            for (let i = 0; i < selectedRows.length; i++) {
                const d = selectedRows[i];
                document.getElementById('loading-text').textContent = `Sedang memproses PDF (${i + 1}/${selectedRows.length}): ${d.nama_mahasiswa}...`;
                
                // 1. Update preview values for this student
                document.getElementById('prev-nama').textContent  = d.nama_mahasiswa;
                document.getElementById('prev-judul').textContent = d.judul_kegiatan;
                
                // Date formatting
                const months = ['January','February','March','April','May','June','July',
                                'August','September','October','November','December'];

                function formatSingleDate(dateStr) {
                    const dt = new Date(dateStr);
                    return `${months[dt.getMonth()].toUpperCase()} ${dt.getDate()}, ${dt.getFullYear()}`;
                }

                let tanggalStr = '';
                if (d.tanggal_kegiatan) {
                    if (d.tanggal_kegiatan_selesai && d.tanggal_kegiatan_selesai !== d.tanggal_kegiatan) {
                        const start = new Date(d.tanggal_kegiatan);
                        const end   = new Date(d.tanggal_kegiatan_selesai);
                        if (start.getMonth() === end.getMonth() && start.getFullYear() === end.getFullYear()) {
                            tanggalStr = `FROM ${months[start.getMonth()].toUpperCase()} ${start.getDate()} – ${end.getDate()}, ${end.getFullYear()}`;
                        } else {
                            tanggalStr = `FROM ${formatSingleDate(d.tanggal_kegiatan)} TO ${formatSingleDate(d.tanggal_kegiatan_selesai)}`;
                        }
                    } else {
                        tanggalStr = `ON ${formatSingleDate(d.tanggal_kegiatan)}`;
                    }
                }

                const lokasi = d.lokasi_kegiatan ? d.lokasi_kegiatan.toUpperCase() : 'SCHOOL OF CREATIVE INDUSTRIES';
                document.getElementById('prev-tanggal').textContent =
                    tanggalStr ? `HELD AT ${lokasi} ${tanggalStr}` : `HELD AT ${lokasi}`;

                document.getElementById('prev-nomor').textContent = 'CERTIFICATE NUMBER: ' + (d.nomor_sertifikat ?? '-');

                // Role/Prodi
                const role = d.prodi ? d.prodi : 'Committee';
                const roleCapitalized = role.charAt(0).toUpperCase() + role.slice(1);
                document.getElementById('prev-desc').innerHTML = `For the Participation as <strong style="color: #d35400;">${roleCapitalized}</strong> at :`;

                // Update QR Code
                const qrUrl = d.qr_code
                    ? d.qr_code
                    : BASE_URL + 'sertifikat/verifikasi/' + encodeURIComponent(d.nomor_sertifikat ?? '');
                document.getElementById('cert-qr-label').textContent = d.nomor_sertifikat ?? 'verify';

                // Render QR code onto canvas synchronously
                await new Promise((resolve) => {
                    const canvas = document.getElementById('cert-qr-canvas');
                    const img = document.getElementById('cert-template-img');
                    const size = Math.max(70, Math.round(img.clientWidth * 0.10));
                    
                    const isImage = qrUrl.match(/\.(jpeg|jpg|png|gif)$/i) || qrUrl.includes('uploads/');
                    if (isImage) {
                        const qrImg = new Image();
                        qrImg.onload = function() {
                            canvas.width = size;
                            canvas.height = size;
                            const ctx = canvas.getContext('2d');
                            ctx.clearRect(0, 0, size, size);
                            ctx.drawImage(qrImg, 0, 0, size, size);
                            resolve();
                        };
                        qrImg.onerror = function() {
                            console.error('Failed to load QR image:', qrUrl);
                            resolve();
                        };
                        qrImg.src = qrUrl.startsWith('uploads/') ? BASE_URL + qrUrl : qrUrl;
                    } else {
                        QRCode.toCanvas(canvas, qrUrl, {
                            width:  size,
                            margin: 1,
                            color: { dark: '#000000', light: '#ffffff' }
                        }, function(err) {
                            if (err) console.error('QR render error:', err);
                            resolve();
                        });
                    }
                });

                // Let the browser paint the DOM updates
                await new Promise(resolve => setTimeout(resolve, 150));

                // 2. Generate Canvas page
                const canvas = await html2canvas(document.getElementById('cert-canvas-container'), {
                    scale: 3, useCORS: true, allowTaint: true, backgroundColor: '#fff', logging: false
                });
                
                if (i > 0) {
                    pdf.addPage();
                }
                
                pdf.addImage(canvas.toDataURL('image/jpeg', 0.97), 'JPEG', 0, 0,
                    pdf.internal.pageSize.getWidth(), pdf.internal.pageSize.getHeight());
            }
            
            // 3. Save final multi-page PDF
            document.getElementById('loading-text').textContent = 'Menyimpan file PDF...';
            pdf.save('Sertifikat_Massal_' + new Date().toISOString().slice(0, 10) + '.pdf');
            
            // 4. Restore preview back to current selector choice
            const currentSelIdx = document.getElementById('previewSelector').value || 0;
            changePreviewStudent(currentSelIdx);
            
        } catch(e) {
            alert('Gagal membuat PDF: ' + e.message);
        } finally {
            loading.classList.remove('show');
        }
    }

    /* ── Download PNG (ZIP) ── */
    async function downloadPNG() {
        const loading = document.getElementById('download-loading');
        loading.classList.add('show');
        
        try {
            const zip = new JSZip();
            
            for (let i = 0; i < selectedRows.length; i++) {
                const d = selectedRows[i];
                document.getElementById('loading-text').textContent = `Sedang memproses gambar (${i + 1}/${selectedRows.length}): ${d.nama_mahasiswa}...`;
                
                // 1. Update preview values for this student
                document.getElementById('prev-nama').textContent  = d.nama_mahasiswa;
                document.getElementById('prev-judul').textContent = d.judul_kegiatan;
                
                // Date formatting
                const months = ['January','February','March','April','May','June','July',
                                'August','September','October','November','December'];

                function formatSingleDate(dateStr) {
                    const dt = new Date(dateStr);
                    return `${months[dt.getMonth()].toUpperCase()} ${dt.getDate()}, ${dt.getFullYear()}`;
                }

                let tanggalStr = '';
                if (d.tanggal_kegiatan) {
                    if (d.tanggal_kegiatan_selesai && d.tanggal_kegiatan_selesai !== d.tanggal_kegiatan) {
                        const start = new Date(d.tanggal_kegiatan);
                        const end   = new Date(d.tanggal_kegiatan_selesai);
                        if (start.getMonth() === end.getMonth() && start.getFullYear() === end.getFullYear()) {
                            tanggalStr = `FROM ${months[start.getMonth()].toUpperCase()} ${start.getDate()} – ${end.getDate()}, ${end.getFullYear()}`;
                        } else {
                            tanggalStr = `FROM ${formatSingleDate(d.tanggal_kegiatan)} TO ${formatSingleDate(d.tanggal_kegiatan_selesai)}`;
                        }
                    } else {
                        tanggalStr = `ON ${formatSingleDate(d.tanggal_kegiatan)}`;
                    }
                }

                const lokasi = d.lokasi_kegiatan ? d.lokasi_kegiatan.toUpperCase() : 'SCHOOL OF CREATIVE INDUSTRIES';
                document.getElementById('prev-tanggal').textContent =
                    tanggalStr ? `HELD AT ${lokasi} ${tanggalStr}` : `HELD AT ${lokasi}`;

                document.getElementById('prev-nomor').textContent = 'CERTIFICATE NUMBER: ' + (d.nomor_sertifikat ?? '-');

                const role = d.prodi ? d.prodi : 'Committee';
                const roleCapitalized = role.charAt(0).toUpperCase() + role.slice(1);
                document.getElementById('prev-desc').innerHTML = `For the Participation as <strong style="color: #d35400;">${roleCapitalized}</strong> at :`;

                // Update QR Code
                const qrUrl = d.qr_code
                    ? d.qr_code
                    : BASE_URL + 'sertifikat/verifikasi/' + encodeURIComponent(d.nomor_sertifikat ?? '');
                document.getElementById('cert-qr-label').textContent = d.nomor_sertifikat ?? 'verify';

                await new Promise((resolve) => {
                    const canvas = document.getElementById('cert-qr-canvas');
                    const img = document.getElementById('cert-template-img');
                    const size = Math.max(70, Math.round(img.clientWidth * 0.10));
                    
                    const isImage = qrUrl.match(/\.(jpeg|jpg|png|gif)$/i) || qrUrl.includes('uploads/');
                    if (isImage) {
                        const qrImg = new Image();
                        qrImg.onload = function() {
                            canvas.width = size;
                            canvas.height = size;
                            const ctx = canvas.getContext('2d');
                            ctx.clearRect(0, 0, size, size);
                            ctx.drawImage(qrImg, 0, 0, size, size);
                            resolve();
                        };
                        qrImg.onerror = function() {
                            console.error('Failed to load QR image:', qrUrl);
                            resolve();
                        };
                        qrImg.src = qrUrl.startsWith('uploads/') ? BASE_URL + qrUrl : qrUrl;
                    } else {
                        QRCode.toCanvas(canvas, qrUrl, {
                            width:  size,
                            margin: 1,
                            color: { dark: '#000000', light: '#ffffff' }
                        }, function(err) {
                            if (err) console.error('QR render error:', err);
                            resolve();
                        });
                    }
                });

                await new Promise(resolve => setTimeout(resolve, 150));

                // 2. Generate PNG image
                const canvas = await html2canvas(document.getElementById('cert-canvas-container'), {
                    scale: 3, useCORS: true, allowTaint: true, backgroundColor: '#fff', logging: false
                });
                
                const imgData = canvas.toDataURL('image/png').split(',')[1];
                const filename = 'Sertifikat_' + d.nama_mahasiswa.replace(/\s+/g,'_') + 
                                 '_' + (d.nomor_sertifikat ?? '').replace(/[\/\s]/g,'-') + '.png';
                
                zip.file(filename, imgData, {base64: true});
            }
            
            // 3. Generate ZIP download
            document.getElementById('loading-text').textContent = 'Membuat file ZIP sertifikat...';
            zip.generateAsync({type:"blob"}).then(function(content) {
                saveAs(content, "Sertifikat_Massal_" + new Date().toISOString().slice(0, 10) + ".zip");
            });
            
            const currentSelIdx = document.getElementById('previewSelector').value || 0;
            changePreviewStudent(currentSelIdx);
            
        } catch(e) {
            alert('Gagal membuat gambar ZIP: ' + e.message);
        } finally {
            loading.classList.remove('show');
        }
    }

    // Mobile Sidebar Toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const icon = document.getElementById('hamburgerIcon');
        const isOpen = sidebar.classList.toggle('open');
        overlay.classList.toggle('active', isOpen);
        if (icon) {
            icon.className = isOpen ? 'fas fa-times' : 'fas fa-bars';
        }
    }

    // Drag & Drop for Signature
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('sigDragDropZone');
        const sigInput = document.getElementById('sigImageInput');

        if (dropZone && sigInput) {
            dropZone.addEventListener('click', () => sigInput.click());

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.style.background = '#f7ebdf';
                dropZone.style.borderColor = '#d35400';
            });

            ['dragleave', 'dragend'].forEach(type => {
                dropZone.addEventListener(type, () => {
                    dropZone.style.background = '#fdfaf7';
                    dropZone.style.borderColor = '#E67E22';
                });
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.style.background = '#fdfaf7';
                dropZone.style.borderColor = '#E67E22';
                
                if (e.dataTransfer.files.length) {
                    sigInput.files = e.dataTransfer.files;
                    handleSignatureImageUpload(sigInput);
                }
            });
        }
        
        // Initialize positioning and draggable behavior for all canvas elements
        adjustQrPosition();
        makeAllElementsDraggable();

        // Auto-select student's approved certificate and template if ?id= is set
        const urlParams = new URLSearchParams(window.location.search);
        const certId = urlParams.get('id');
        if (certId) {
            const rows = Array.from(document.querySelectorAll('.cert-row'));
            const matchedRow = rows.find(tr => {
                const data = JSON.parse(tr.getAttribute('data-json'));
                return String(data.id) === String(certId);
            });
            if (matchedRow) {
                const data = JSON.parse(matchedRow.getAttribute('data-json'));
                selectRow(matchedRow, data);
                
                // Render Signature
                if (data.signature_image) {
                    const sigImg = document.getElementById('sig-image');
                    if (sigImg) {
                        sigImg.src = BASE_URL + data.signature_image;
                        sigImg.style.display = 'block';
                    }
                }
                
                // Redirect straight to Step 2 (Pilih Template) so student can select it
                setTimeout(() => {
                    goStep(2);
                }, 200);
            }
        }
    });
</script>
</body>
</html>
