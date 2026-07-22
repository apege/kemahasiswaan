<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedoman extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pedoman_model');
        $this->load->library('session');
    }

    /* ──────────────────────────────────────────────
       HALAMAN PUBLIK
    ────────────────────────────────────────────── */
    public function index() {
        $data['title']    = 'Pedoman & Peraturan | Kemahasiswaan FIK';
        $data['pedoman']  = $this->Pedoman_model->get_aktif();

        $is_logged_in = $this->session->userdata('logged_in');
        $data['user_data'] = $is_logged_in ? [
            'user_id'   => $this->session->userdata('user_id'),
            'username'  => $this->session->userdata('username'),
            'nama'      => $this->session->userdata('nama'),
            'nim'       => $this->session->userdata('nim'),
            'role'      => $this->session->userdata('role'),
            'foto'      => $this->session->userdata('foto'),
            'logged_in' => true
        ] : null;

        $this->load->view('pedoman', $data);
    }

    /* ──────────────────────────────────────────────
       DOWNLOAD PDF
       Jika peraturan punya file_pdf → serve file.
       Kalau tidak ada file → generate PDF sederhana
       menggunakan data HTML dari kolom `isi`.
    ────────────────────────────────────────────── */
    public function download($id) {
        $item = $this->Pedoman_model->get_by_id($id);
        if (!$item || !$item->aktif) {
            show_404();
        }

        // Jika admin upload file PDF manual, langsung serve
        if (!empty($item->file_pdf) && file_exists(FCPATH . $item->file_pdf)) {
            $filepath = FCPATH . $item->file_pdf;
            $filename = 'Pedoman_' . url_title($item->judul, '-', true) . '.pdf';
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        }

        // Fallback: generate PDF sederhana dari konten HTML menggunakan dompdf/mpdf
        // Karena tidak ada library PDF yang pasti ada, kita buat HTML yang bisa di-print sebagai PDF
        $data['item'] = $item;
        $this->load->view('pedoman_print', $data);
    }
}
