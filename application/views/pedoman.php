<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?? 'Pedoman & Peraturan' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f8fafc;
      color: #1f2937;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* ── HERO ── */
    .bg-orange-grad {
      background: linear-gradient(135deg, #f97316 0%, #fdba74 100%);
      position: relative;
      color: white;
      padding: 160px 0 6rem 0;
      text-align: center;
    }
    .bg-orange-grad::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image: radial-gradient(circle at 20% 30%, rgba(255,255,200,0.2) 2px, transparent 2.5px);
      background-size: 32px 32px;
      pointer-events: none;
    }

    /* ── CONTENT BOX ── */
    .content-box {
      background: white;
      border-radius: 16px;
      padding: 3rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
      margin-top: -4rem;
      position: relative;
      z-index: 10;
    }

    /* ── ACCORDION PERATURAN ── */
    .pedoman-accordion .accordion-item {
      border: 1px solid #e5e7eb;
      border-radius: 12px !important;
      margin-bottom: 14px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
      transition: box-shadow 0.3s ease;
    }
    .pedoman-accordion .accordion-item:hover {
      box-shadow: 0 6px 20px rgba(249,115,22,0.12);
    }
    .pedoman-accordion .accordion-button {
      font-weight: 700;
      font-size: 1.05rem;
      color: #1f2937;
      background: white;
      border-radius: 12px !important;
      padding: 1.2rem 1.5rem;
    }
    .pedoman-accordion .accordion-button:not(.collapsed) {
      background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
      color: #c2410c;
      box-shadow: none;
    }
    .pedoman-accordion .accordion-button::after {
      filter: invert(50%) sepia(80%) saturate(500%) hue-rotate(360deg);
    }
    .pedoman-accordion .accordion-button:not(.collapsed)::after {
      filter: invert(40%) sepia(90%) saturate(800%) hue-rotate(10deg);
    }
    .pedoman-accordion .accordion-body {
      background: #fafafa;
      border-top: 1px solid #f3f4f6;
      padding: 1.5rem;
      font-size: 0.96rem;
      line-height: 1.8;
      color: #4b5563;
    }
    .pedoman-accordion .accordion-body h1,
    .pedoman-accordion .accordion-body h2,
    .pedoman-accordion .accordion-body h3 {
      color: #1f2937;
      margin-top: 1.2rem;
      margin-bottom: 0.6rem;
      font-weight: 700;
    }
    .pedoman-accordion .accordion-body img {
      max-width: 100%;
      border-radius: 8px;
      height: auto;
    }

    /* ── BADGE NO URUTAN ── */
    .pedoman-num {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      background: linear-gradient(135deg, #f97316, #ea580c);
      color: white;
      border-radius: 50%;
      font-size: 0.8rem;
      font-weight: 700;
      flex-shrink: 0;
      margin-right: 12px;
    }

    /* ── DOWNLOAD BTN ── */
    .btn-download-pdf {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(135deg, #f97316, #ea580c);
      color: white;
      padding: 0.55rem 1.4rem;
      border-radius: 25px;
      font-weight: 600;
      font-size: 0.85rem;
      text-decoration: none;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      margin-top: 0.75rem;
    }
    .btn-download-pdf:hover {
      background: linear-gradient(135deg, #ea580c, #c2410c);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(249,115,22,0.3);
    }

    /* ── BACK BTN ── */
    .btn-back {
      background: white;
      color: #f97316;
      border: 2px solid #f97316;
      padding: 0.6rem 2rem;
      border-radius: 25px;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
      margin-top: 3rem;
      transition: all 0.3s;
    }
    .btn-back:hover {
      background: #f97316;
      color: white;
    }

    /* ── EMPTY STATE ── */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: #9ca3af;
    }
    .empty-state i {
      font-size: 3.5rem;
      margin-bottom: 1rem;
      color: #d1d5db;
    }

    @media (max-width: 768px) {
      .content-box { padding: 1.5rem; }
      .bg-orange-grad { padding-top: 120px; }
    }
  </style>
</head>
<body>

  <?php $this->load->view('partials/navbar', ['active_menu' => 'pedoman']); ?>

  <!-- HERO -->
  <div class="bg-orange-grad">
    <div class="container position-relative z-1">
      <h1 class="display-4 fw-bold mb-3">Pedoman &amp; Peraturan</h1>
      <p class="lead opacity-75">Kumpulan pedoman dan peraturan kemahasiswaan Fakultas Industri Kreatif</p>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="container mb-5 flex-grow-1">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="content-box">

          <?php if (!empty($pedoman)): ?>
            <div class="d-flex align-items-center gap-2 mb-4">
              <div style="width:4px; height:28px; background:linear-gradient(135deg,#f97316,#ea580c); border-radius:4px;"></div>
              <h2 class="fw-bold mb-0" style="font-size:1.5rem; color:#1f2937;">Daftar Peraturan</h2>
            </div>

            <div class="accordion pedoman-accordion" id="pedomanAccordion">
              <?php foreach ($pedoman as $i => $item): ?>
              <div class="accordion-item" id="item-<?= $item->id ?>">
                <h2 class="accordion-header" id="heading-<?= $item->id ?>">
                  <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>"
                          type="button"
                          data-bs-toggle="collapse"
                          data-bs-target="#collapse-<?= $item->id ?>"
                          aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>"
                          aria-controls="collapse-<?= $item->id ?>">
                    <span class="pedoman-num"><?= $i + 1 ?></span>
                    <?= htmlspecialchars($item->judul) ?>
                  </button>
                </h2>
                <div id="collapse-<?= $item->id ?>"
                     class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                     aria-labelledby="heading-<?= $item->id ?>"
                     data-bs-parent="#pedomanAccordion">
                  <div class="accordion-body">
                    <?php if (!empty($item->deskripsi)): ?>
                      <p class="text-muted mb-3" style="font-style:italic; font-size:0.92rem;">
                        <?= htmlspecialchars($item->deskripsi) ?>
                      </p>
                    <?php endif; ?>

                    <div class="content-html" id="content-raw-<?= $item->id ?>">
                      <?= $item->isi ?>
                    </div>

                    <!-- Tombol Download PDF -->
                    <div class="mt-3 pt-3 border-top">
                      <button type="button" class="btn-download-pdf" 
                              onclick="showPreview(<?= $item->id ?>, '<?= htmlspecialchars(addslashes($item->judul)) ?>', '<?= htmlspecialchars(addslashes($item->deskripsi ?? '')) ?>', '<?= !empty($item->file_pdf) ? base_url($item->file_pdf) : '' ?>')">
                        <i class="fas fa-file-pdf"></i>
                        Preview &amp; Download
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>

          <?php else: ?>
            <div class="empty-state">
              <i class="fas fa-book-open"></i>
              <h4 class="mt-2">Belum Ada Pedoman</h4>
              <p>Pedoman dan peraturan belum tersedia. Silakan hubungi admin untuk informasi lebih lanjut.</p>
            </div>
          <?php endif; ?>

          <div class="text-center">
            <a href="<?= base_url('forum_alumni') ?>" class="btn-back">
              <i class="fas fa-arrow-left me-2"></i> Kembali ke Ikatan Alumni
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-dark text-white text-center py-4 mt-auto">
    <div class="container">
      <p class="mb-0 opacity-75">&copy; <?= date('Y') ?> Fakultas Industri Kreatif. Telkom University.</p>
    </div>
  </footer>

  <!-- Modal Preview Pedoman -->
  <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
        <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem;">
          <h5 class="modal-title fw-bold" id="previewModalLabel" style="color: #1f2937;">
            <i class="fas fa-file-pdf text-danger me-2"></i> Preview &amp; Download Dokumen
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="padding: 1.5rem; background: #f8fafc; max-height: 70vh; overflow-y: auto;">
          <!-- Container for PDF or HTML preview -->
          <div id="previewContainer" class="bg-white p-4" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); border: 1px solid #e2e8f0; min-height: 400px;">
            <!-- Content will be injected dynamically -->
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 1rem 1.5rem; background: #fff; display: flex; justify-content: flex-end; gap: 8px;">
          <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal" style="font-weight: 600; font-size: 0.85rem;">Tutup</button>
          <button type="button" class="btn-download-pdf m-0" id="modalActionBtn">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Hidden Iframe for printing -->
  <iframe id="printIframe" style="display: none;"></iframe>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    function showPreview(id, judul, deskripsi, filePdf) {
      const container = document.getElementById('previewContainer');
      const actionBtn = document.getElementById('modalActionBtn');
      const iframe = document.getElementById('printIframe');
      
      // Reset modal content
      container.innerHTML = '';
      
      if (filePdf) {
        // If there is an uploaded PDF file, show it in an iframe
        container.innerHTML = `<iframe src="${filePdf}" width="100%" height="500px" style="border: none; border-radius: 8px;"></iframe>`;
        
        // Set action button to download/open the PDF
        actionBtn.innerHTML = '<i class="fas fa-download"></i> Download PDF';
        actionBtn.onclick = function() {
          const link = document.createElement('a');
          link.href = filePdf;
          link.download = filePdf.split('/').pop();
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        };
      } else {
        // If no PDF file, show HTML preview
        const rawContent = document.getElementById('content-raw-' + id).innerHTML;
        
        container.innerHTML = `
          <div style="font-family: 'Times New Roman', serif; color: #000; line-height: 1.6;">
            <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 20px;">
              <h4 style="font-size: 13pt; font-weight: bold; margin: 0 0 2px; text-transform: uppercase;">KEMAHASISWAAN FAKULTAS INDUSTRI KREATIF</h4>
              <p style="font-size: 10pt; margin: 0;">Telkom University</p>
            </div>
            <h3 style="font-size: 12pt; text-align: center; font-weight: bold; margin: 0 0 15px; text-transform: uppercase;">${judul}</h3>
            ${deskripsi ? `<p style="text-align: center; font-style: italic; color: #555; font-size: 10pt; margin-bottom: 20px;">${deskripsi}</p>` : ''}
            <div class="content-html-preview" style="font-size: 11pt;">
              ${rawContent}
            </div>
          </div>
        `;
        
        // Set action button to print
        actionBtn.innerHTML = '<i class="fas fa-print"></i> Cetak / Simpan PDF';
        actionBtn.onclick = function() {
          // Load the print URL into the hidden iframe and print once loaded
          iframe.src = '<?= base_url('pedoman/download/') ?>' + id;
          iframe.onload = function() {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
          };
        };
      }
      
      // Show the bootstrap modal
      const myModal = new bootstrap.Modal(document.getElementById('previewModal'));
      myModal.show();
    }
  </script>
</body>
</html>
