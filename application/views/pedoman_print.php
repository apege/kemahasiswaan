<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($item->judul ?? 'Pedoman') ?> – Kemahasiswaan FIK</title>
  <style>
    @page { margin: 2cm; }
    body {
      font-family: 'Times New Roman', serif;
      font-size: 12pt;
      color: #000;
      margin: 0;
      padding: 0;
    }
    .header-print {
      text-align: center;
      border-bottom: 2px solid #000;
      padding-bottom: 12px;
      margin-bottom: 24px;
    }
    .header-print h1 { font-size: 14pt; font-weight: bold; margin: 0 0 4px; }
    .header-print p  { font-size: 10pt; margin: 0; }
    h2 { font-size: 13pt; text-align: center; margin: 0 0 24px; }
    .content { line-height: 1.8; }
    .content h1, .content h2, .content h3 { font-size: 12pt; font-weight: bold; margin: 16px 0 8px; }
    .content p { margin: 0 0 10px; }
    .content ul, .content ol { margin: 0 0 10px 20px; }
    .footer-print { text-align: center; font-size: 9pt; color: #555; border-top: 1px solid #999; margin-top: 32px; padding-top: 8px; }
    @media print {
      .no-print { display: none !important; }
    }
  </style>
</head>
<body onload="window.print()">

  <!-- Print Header -->
  <div class="header-print">
    <h1>KEMAHASISWAAN FAKULTAS INDUSTRI KREATIF</h1>
    <p>Telkom University</p>
  </div>

  <h2><?= htmlspecialchars($item->judul ?? '') ?></h2>

  <?php if (!empty($item->deskripsi)): ?>
    <p style="text-align:center; font-style:italic; color:#555; margin-bottom:20px;">
      <?= htmlspecialchars($item->deskripsi) ?>
    </p>
  <?php endif; ?>

  <div class="content">
    <?= $item->isi ?? '' ?>
  </div>

  <div class="footer-print">
    Dicetak pada <?= date('d F Y H:i') ?> | Kemahasiswaan FIK Telkom University
  </div>

  <!-- Tombol print manual jika auto-print gagal -->
  <div class="no-print" style="text-align:center; margin-top:30px; padding:20px; background:#f8f9fa;">
    <p style="font-family:sans-serif; color:#555;">Klik tombol di bawah untuk mencetak / simpan sebagai PDF:</p>
    <button onclick="window.print()" style="background:#f97316; color:white; border:none; padding:10px 28px; border-radius:20px; font-size:14px; cursor:pointer; font-weight:600;">
      🖨️ Cetak / Simpan PDF
    </button>
    &nbsp;
    <a href="<?= base_url('pedoman') ?>" style="background:#6b7280; color:white; border:none; padding:10px 24px; border-radius:20px; font-size:14px; cursor:pointer; font-weight:600; text-decoration:none;">
      ← Kembali
    </a>
  </div>
</body>
</html>
