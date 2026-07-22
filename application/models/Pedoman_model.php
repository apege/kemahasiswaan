<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedoman_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->_check_and_create_table();
    }

    private function _check_and_create_table() {
        if (!$this->db->table_exists('pedoman')) {
            $sql = "CREATE TABLE `pedoman` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `judul` varchar(255) NOT NULL,
                `deskripsi` text DEFAULT NULL,
                `isi` longtext NOT NULL,
                `urutan` int(11) NOT NULL DEFAULT 0,
                `aktif` tinyint(1) NOT NULL DEFAULT 1,
                `file_pdf` varchar(255) DEFAULT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->query($sql);
        }
    }

    /* ─── READ ─── */

    public function get_all($aktif = null) {
        if ($aktif !== null) {
            $this->db->where('aktif', (int)$aktif);
        }
        $this->db->order_by('urutan', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get('pedoman')->result();
    }

    public function get_aktif() {
        return $this->get_all(1);
    }

    public function get_by_id($id) {
        $query = $this->db->where('id', $id)->get('pedoman');
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    public function count_all()   { return $this->db->count_all('pedoman'); }
    public function count_aktif() { return $this->db->where('aktif', 1)->count_all_results('pedoman'); }

    /* ─── WRITE ─── */

    public function insert($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert('pedoman', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id)->update('pedoman', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete($id) {
        // Hapus file PDF jika ada
        $item = $this->get_by_id($id);
        if ($item && !empty($item->file_pdf) && file_exists(FCPATH . $item->file_pdf)) {
            @unlink(FCPATH . $item->file_pdf);
        }
        $this->db->where('id', $id)->delete('pedoman');
        return $this->db->affected_rows() > 0;
    }

    public function toggle_aktif($id) {
        $item = $this->get_by_id($id);
        if (!$item) return false;
        $new_status = $item->aktif ? 0 : 1;
        return $this->update($id, ['aktif' => $new_status]);
    }
}
