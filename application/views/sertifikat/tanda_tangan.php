<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | Admin Sertifikat</title>
    
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

        /* Card styles */
        .signature-card {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .signature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .signature-preview-box {
            background-color: #f8f9fa;
            background-image: 
                linear-gradient(45deg, #e9ecef 25%, transparent 25%), 
                linear-gradient(-45deg, #e9ecef 25%, transparent 25%), 
                linear-gradient(45deg, transparent 75%, #e9ecef 75%), 
                linear-gradient(-45deg, transparent 75%, #e9ecef 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .signature-preview-box img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .signature-card:hover .signature-preview-box img {
            transform: scale(1.05);
        }

        .signature-details {
            padding: 1.25rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .signature-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 0.25rem;
        }

        .signature-role {
            font-size: 0.85rem;
            color: #f97316;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .signature-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: auto;
        }

        /* Drag & Drop Area */
        .dropzone-area {
            border: 2px dashed #f97316;
            background: #fffaf0;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .dropzone-area:hover {
            background: #fff5e6;
            border-color: #ea580c;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php $this->load->view('partials/admin_sidebar', ['active_menu' => 'sertifikat_ttd']); ?>

        <!-- Main Content -->
        <div class="admin-main">
            <div class="admin-header">
                <h1>Manajemen Tanda Tangan</h1>
                <div class="user-info">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSignature" style="background:#f97316;color:white;padding:0.5rem 1.2rem;border-radius:25px;font-weight:600;font-size:0.85rem;transition:all 0.3s;border:none;">
                        <i class="fas fa-plus me-2"></i>Tambah Tanda Tangan
                    </button>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Signature List Grid -->
            <div class="row g-4">
                <?php if (empty($signatures)): ?>
                    <div class="col-12 text-center py-5">
                        <div class="card border-0 shadow-sm rounded-3 p-5">
                            <i class="fas fa-signature fa-4x text-muted mb-3"></i>
                            <h4 class="text-secondary fw-bold">Belum ada tanda tangan disimpan</h4>
                            <p class="text-muted">Simpan tanda tangan pejabat atau penanggung jawab sertifikat di sini untuk mempermudah persetujuan sertifikat.</p>
                            <div class="mt-3">
                                <button class="btn btn-warning text-white px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahSignature" style="border-radius:20px; font-weight:600;">
                                    Tambah Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($signatures as $sig): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="signature-card">
                                <div class="signature-preview-box">
                                    <img src="<?= base_url($sig['image_path']) ?>" alt="Tanda Tangan <?= htmlspecialchars($sig['nama']) ?>">
                                </div>
                                <div class="signature-details">
                                    <h5 class="signature-title"><?= htmlspecialchars($sig['nama']) ?></h5>
                                    <div class="signature-role"><?= htmlspecialchars($sig['jabatan'] ?? '-') ?></div>
                                    <div class="signature-actions">
                                        <button class="btn btn-sm btn-outline-primary w-50" onclick="editSignature(<?= htmlspecialchars(json_encode($sig)) ?>)" style="border-radius:8px;">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger w-50" onclick="confirmDelete(<?= $sig['id'] ?>, '<?= htmlspecialchars(addslashes($sig['nama'])) ?>')" style="border-radius:8px;">
                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Tanda Tangan -->
    <div class="modal fade" id="modalTambahSignature" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 16px; overflow:hidden; border:none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header bg-warning text-white" style="border-bottom:none; background: linear-gradient(135deg, #f97316, #ea580c) !important;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-signature me-2"></i>Tambah Tanda Tangan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('sertifikat/tanda_tangan_simpan') ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateAddForm()">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" required placeholder="Contoh: Prof. Dr. Budi Utomo" style="border-radius:8px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="jabatan" required placeholder="Contoh: Dekan Fakultas" style="border-radius:8px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block fw-bold">Pilihan Tanda Tangan <span class="text-danger">*</span></label>
                            <div class="btn-group w-100 mb-3" role="group" aria-label="Signature Add Method">
                                <input type="radio" class="btn-check" name="sig_method_add" id="sigMethodAddUpload" value="upload" checked onchange="toggleSigAddMethod()">
                                <label class="btn btn-outline-warning" for="sigMethodAddUpload">
                                    <i class="fas fa-upload me-2"></i>Unggah Gambar
                                </label>
                                <input type="radio" class="btn-check" name="sig_method_add" id="sigMethodAddDraw" value="draw" onchange="toggleSigAddMethod()">
                                <label class="btn btn-outline-warning" for="sigMethodAddDraw">
                                    <i class="fas fa-pencil-alt me-2"></i>Gambar Langsung
                                </label>
                            </div>

                            <input type="hidden" name="signature_data" id="sigDataAdd">

                            <!-- Upload Area -->
                            <div id="sigUploadAddContainer" class="mb-3">
                                <div class="dropzone-area" onclick="document.getElementById('fileSignatureAdd').click()">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-warning mb-2"></i>
                                    <p class="mb-1 fw-bold">Klik atau seret file di sini</p>
                                    <p class="text-muted small mb-0">Rekomendasi background transparan (PNG)</p>
                                    <input type="file" id="fileSignatureAdd" name="signature_file" accept="image/png, image/jpeg, image/jpg" style="display:none;" required onchange="previewAddImage(this)">
                                </div>
                                <div id="previewAddContainer" style="display:none;" class="mt-3 text-center">
                                    <div style="background-color: #f8f9fa; padding:10px; border-radius:8px; display:inline-block;">
                                        <img id="previewAddImg" style="max-height:80px; max-width:100%;" alt="Preview">
                                    </div>
                                </div>
                            </div>

                            <!-- Draw Canvas Area -->
                            <div id="sigDrawAddContainer" class="mb-3" style="display:none;">
                                <div class="signature-pad-container" style="border: 2px solid #fdba74; border-radius: 12px; background: #fff; position: relative; overflow: hidden; padding: 0;">
                                    <canvas id="signatureAddCanvas" width="450" height="200" style="display: block; width: 100%; height: 200px; cursor: crosshair; background: #fffaf5;"></canvas>
                                    <button type="button" class="btn btn-sm btn-secondary" id="clearAddCanvasBtn" onclick="clearAddSignatureCanvas()" style="position: absolute; right: 10px; bottom: 10px; border-radius: 6px; background-color: #ea580c; border: none; color: white;">Hapus</button>
                                </div>
                                <small class="text-muted">Gunakan mouse atau layar sentuh untuk mencoret tanda tangan di atas.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-3 bg-light" style="border-top:none;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;">Batal</button>
                        <button type="submit" class="btn btn-warning text-white" style="border-radius:8px; background-color:#f97316; border:none; font-weight:600;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tanda Tangan -->
    <div class="modal fade" id="modalEditSignature" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 16px; overflow:hidden; border:none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header bg-primary text-white" style="border-bottom:none; background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Edit Tanda Tangan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('sertifikat/tanda_tangan_update') ?>" method="POST" enctype="multipart/form-data" onsubmit="return validateEditForm()">
                    <input type="hidden" name="id" id="editId">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" id="editNama" required placeholder="Contoh: Prof. Dr. Budi Utomo" style="border-radius:8px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="jabatan" id="editJabatan" required placeholder="Contoh: Dekan Fakultas" style="border-radius:8px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block fw-bold">Pilihan Tanda Tangan <span class="text-danger">*</span></label>
                            <div class="btn-group w-100 mb-3" role="group" aria-label="Signature Edit Method">
                                <input type="radio" class="btn-check" name="sig_method_edit" id="sigMethodEditUpload" value="upload" checked onchange="toggleSigEditMethod()">
                                <label class="btn btn-outline-primary" for="sigMethodEditUpload">
                                    <i class="fas fa-upload me-2"></i>Gambar Saat Ini / Unggah
                                </label>
                                <input type="radio" class="btn-check" name="sig_method_edit" id="sigMethodEditDraw" value="draw" onchange="toggleSigEditMethod()">
                                <label class="btn btn-outline-primary" for="sigMethodEditDraw">
                                    <i class="fas fa-pencil-alt me-2"></i>Gambar Baru
                                </label>
                            </div>

                            <input type="hidden" name="signature_data" id="sigDataEdit">

                            <!-- Upload Area -->
                            <div id="sigUploadEditContainer" class="mb-3">
                                <div class="dropzone-area" onclick="document.getElementById('fileSignatureEdit').click()">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2" style="color: #3b82f6 !important;"></i>
                                    <p class="mb-1 fw-bold">Klik atau seret file baru di sini</p>
                                    <p class="text-muted small mb-0">Biarkan kosong jika tidak ingin mengubah gambar</p>
                                    <input type="file" id="fileSignatureEdit" name="signature_file" accept="image/png, image/jpeg, image/jpg" style="display:none;" onchange="previewEditImage(this)">
                                </div>
                                <div class="mt-3 text-center">
                                    <p class="text-muted small mb-1 fw-bold">Tanda Tangan Saat Ini / Baru:</p>
                                    <div style="background-color: #f8f9fa; padding:10px; border-radius:8px; display:inline-block;">
                                        <img id="previewEditImg" style="max-height:80px; max-width:100%;" alt="Preview">
                                    </div>
                                </div>
                            </div>

                            <!-- Draw Canvas Area -->
                            <div id="sigDrawEditContainer" class="mb-3" style="display:none;">
                                <div class="signature-pad-container" style="border: 2px solid #3b82f6; border-radius: 12px; background: #fff; position: relative; overflow: hidden; padding: 0;">
                                    <canvas id="signatureEditCanvas" width="450" height="200" style="display: block; width: 100%; height: 200px; cursor: crosshair; background: #f0f7ff;"></canvas>
                                    <button type="button" class="btn btn-sm btn-secondary" id="clearEditCanvasBtn" onclick="clearEditSignatureCanvas()" style="position: absolute; right: 10px; bottom: 10px; border-radius: 6px; background-color: #1d4ed8; border: none; color: white;">Hapus</button>
                                </div>
                                <small class="text-muted">Gunakan mouse atau layar sentuh untuk mencoret tanda tangan baru di atas.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-3 bg-light" style="border-top:none;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="border-radius:8px; background-color:#3b82f6; border:none; font-weight:600;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewAddImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewAddImg').src = e.target.result;
                    document.getElementById('previewAddContainer').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewEditImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewEditImg').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function editSignature(sig) {
            document.getElementById('editId').value = sig.id;
            document.getElementById('editNama').value = sig.nama;
            document.getElementById('editJabatan').value = sig.jabatan;
            document.getElementById('previewEditImg').src = '<?= base_url() ?>' + sig.image_path;
            
            const modal = new bootstrap.Modal(document.getElementById('modalEditSignature'));
            modal.show();
        }

        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Menghapus tanda tangan '" + nama + "' tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                borderRadius: '16px'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('sertifikat/tanda_tangan_hapus/') ?>' + id;
                }
            });
        }

        // Toggles and Drawing Canvas for Add Signature Modal
        function toggleSigAddMethod() {
            const isUpload = document.getElementById('sigMethodAddUpload').checked;
            const fileInput = document.getElementById('fileSignatureAdd');
            
            if (isUpload) {
                document.getElementById('sigUploadAddContainer').style.display = 'block';
                document.getElementById('sigDrawAddContainer').style.display = 'none';
                fileInput.required = true;
            } else {
                document.getElementById('sigUploadAddContainer').style.display = 'none';
                document.getElementById('sigDrawAddContainer').style.display = 'block';
                fileInput.required = false;
                initAddSignatureCanvas();
            }
        }

        let addSigCanvas = null;
        let addSigCtx = null;
        let addSigDrawing = false;
        let addSigHasDrawn = false;

        function initAddSignatureCanvas() {
            if (addSigCanvas) return; // already initialized
            
            addSigCanvas = document.getElementById('signatureAddCanvas');
            addSigCtx = addSigCanvas.getContext('2d');
            
            addSigCtx.strokeStyle = '#020617';
            addSigCtx.lineWidth = 3;
            addSigCtx.lineCap = 'round';
            addSigCtx.lineJoin = 'round';
            
            // Mouse Events
            addSigCanvas.addEventListener('mousedown', startAddDrawing);
            addSigCanvas.addEventListener('mousemove', drawAdd);
            addSigCanvas.addEventListener('mouseup', stopAddDrawing);
            addSigCanvas.addEventListener('mouseout', stopAddDrawing);
            
            // Touch Events
            addSigCanvas.addEventListener('touchstart', function(e) {
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent('mousedown', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                addSigCanvas.dispatchEvent(mouseEvent);
                e.preventDefault();
            }, { passive: false });
            
            addSigCanvas.addEventListener('touchmove', function(e) {
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent('mousemove', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                addSigCanvas.dispatchEvent(mouseEvent);
                e.preventDefault();
            }, { passive: false });
            
            addSigCanvas.addEventListener('touchend', function(e) {
                const mouseEvent = new MouseEvent('mouseup', {});
                addSigCanvas.dispatchEvent(mouseEvent);
                e.preventDefault();
            }, { passive: false });
        }

        function getAddMousePos(canvasDom, mouseEvent) {
            const rect = canvasDom.getBoundingClientRect();
            return {
                x: (mouseEvent.clientX - rect.left) * (canvasDom.width / rect.width),
                y: (mouseEvent.clientY - rect.top) * (canvasDom.height / rect.height)
            };
        }

        function startAddDrawing(e) {
            addSigDrawing = true;
            addSigCtx.beginPath();
            const pos = getAddMousePos(addSigCanvas, e);
            addSigCtx.moveTo(pos.x, pos.y);
        }

        function drawAdd(e) {
            if (!addSigDrawing) return;
            const pos = getAddMousePos(addSigCanvas, e);
            addSigCtx.lineTo(pos.x, pos.y);
            addSigCtx.stroke();
            addSigHasDrawn = true;
        }

        function stopAddDrawing() {
            addSigDrawing = false;
        }

        function clearAddSignatureCanvas() {
            if (!addSigCanvas) return;
            addSigCtx.clearRect(0, 0, addSigCanvas.width, addSigCanvas.height);
            addSigHasDrawn = false;
        }

        function validateAddForm() {
            const isDraw = document.getElementById('sigMethodAddDraw').checked;
            if (isDraw) {
                if (!addSigHasDrawn) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Silakan coret tanda tangan Anda di canvas terlebih dahulu!',
                        confirmButtonColor: '#ea580c',
                        borderRadius: '16px'
                    });
                    return false;
                }
                document.getElementById('sigDataAdd').value = addSigCanvas.toDataURL('image/png');
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modalEl = document.getElementById('modalTambahSignature');
            if (modalEl) {
                modalEl.addEventListener('show.bs.modal', function () {
                    document.getElementById('sigMethodAddUpload').checked = true;
                    document.getElementById('fileSignatureAdd').value = '';
                    document.getElementById('previewAddContainer').style.display = 'none';
                    document.getElementById('previewAddImg').src = '';
                    clearAddSignatureCanvas();
                    toggleSigAddMethod();
                });
            }

            const editModalEl = document.getElementById('modalEditSignature');
            if (editModalEl) {
                editModalEl.addEventListener('show.bs.modal', function () {
                    document.getElementById('sigMethodEditUpload').checked = true;
                    document.getElementById('fileSignatureEdit').value = '';
                    clearEditSignatureCanvas();
                    toggleSigEditMethod();
                });
            }
        });

        // Toggles and Drawing Canvas for Edit Signature Modal
        function toggleSigEditMethod() {
            const isUpload = document.getElementById('sigMethodEditUpload').checked;
            
            if (isUpload) {
                document.getElementById('sigUploadEditContainer').style.display = 'block';
                document.getElementById('sigDrawEditContainer').style.display = 'none';
            } else {
                document.getElementById('sigUploadEditContainer').style.display = 'none';
                document.getElementById('sigDrawEditContainer').style.display = 'block';
                initEditSignatureCanvas();
            }
        }

        let editSigCanvas = null;
        let editSigCtx = null;
        let editSigDrawing = false;
        let editSigHasDrawn = false;

        function initEditSignatureCanvas() {
            if (editSigCanvas) return; // already initialized
            
            editSigCanvas = document.getElementById('signatureEditCanvas');
            editSigCtx = editSigCanvas.getContext('2d');
            
            editSigCtx.strokeStyle = '#020617';
            editSigCtx.lineWidth = 3;
            editSigCtx.lineCap = 'round';
            editSigCtx.lineJoin = 'round';
            
            // Mouse Events
            editSigCanvas.addEventListener('mousedown', startEditDrawing);
            editSigCanvas.addEventListener('mousemove', drawEdit);
            editSigCanvas.addEventListener('mouseup', stopEditDrawing);
            editSigCanvas.addEventListener('mouseout', stopEditDrawing);
            
            // Touch Events
            editSigCanvas.addEventListener('touchstart', function(e) {
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent('mousedown', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                editSigCanvas.dispatchEvent(mouseEvent);
                e.preventDefault();
            }, { passive: false });
            
            editSigCanvas.addEventListener('touchmove', function(e) {
                const touch = e.touches[0];
                const mouseEvent = new MouseEvent('mousemove', {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                editSigCanvas.dispatchEvent(mouseEvent);
                e.preventDefault();
            }, { passive: false });
            
            editSigCanvas.addEventListener('touchend', function(e) {
                const mouseEvent = new MouseEvent('mouseup', {});
                editSigCanvas.dispatchEvent(mouseEvent);
                e.preventDefault();
            }, { passive: false });
        }

        function getEditMousePos(canvasDom, mouseEvent) {
            const rect = canvasDom.getBoundingClientRect();
            return {
                x: (mouseEvent.clientX - rect.left) * (canvasDom.width / rect.width),
                y: (mouseEvent.clientY - rect.top) * (canvasDom.height / rect.height)
            };
        }

        function startEditDrawing(e) {
            editSigDrawing = true;
            editSigCtx.beginPath();
            const pos = getEditMousePos(editSigCanvas, e);
            editSigCtx.moveTo(pos.x, pos.y);
        }

        function drawEdit(e) {
            if (!editSigDrawing) return;
            const pos = getEditMousePos(editSigCanvas, e);
            editSigCtx.lineTo(pos.x, pos.y);
            editSigCtx.stroke();
            editSigHasDrawn = true;
        }

        function stopEditDrawing() {
            editSigDrawing = false;
        }

        function clearEditSignatureCanvas() {
            if (!editSigCanvas) return;
            editSigCtx.clearRect(0, 0, editSigCanvas.width, editSigCanvas.height);
            editSigHasDrawn = false;
        }

        function validateEditForm() {
            const isDraw = document.getElementById('sigMethodEditDraw').checked;
            if (isDraw) {
                if (!editSigHasDrawn) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Silakan coret tanda tangan baru Anda di canvas terlebih dahulu!',
                        confirmButtonColor: '#3b82f6',
                        borderRadius: '16px'
                    });
                    return false;
                }
                document.getElementById('sigDataEdit').value = editSigCanvas.toDataURL('image/png');
            }
            return true;
        }
    </script>
</body>
</html>
