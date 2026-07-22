<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login']                         = 'Login/index';
$route['login/proses']                  = 'Login/proses';
$route['login/register']                = 'Login/register';
$route['login/proses_register']         = 'Login/proses_register';
$route['logout']                        = 'Login/logout';
$route['login/microsoft']                 = 'Login/microsoft';
$route['admin/history_log/tambah_sso']       = 'admin/tambah_sso_whitelist';
$route['admin/history_log/hapus_sso/(:num)'] = 'admin/hapus_sso_whitelist/$1';
$route['admin/history_log/import_sso'] = 'admin/import_sso_whitelist';
$route['admin/forum_alumni'] = 'admin/forum_alumni';
$route['admin/forum_alumni/approve/(:num)'] = 'admin/approve_forum_post/$1';
$route['admin/forum_alumni/reject/(:num)'] = 'admin/reject_forum_post/$1';
$route['admin/forum_alumni/delete/(:num)'] = 'admin/delete_forum_post/$1';
$route['admin/forum_alumni/comments/(:num)'] = 'admin/get_forum_comments/$1';
$route['admin/forum_alumni/delete_comment/(:num)'] = 'admin/delete_forum_comment/$1';

// Sertifikat routes
$route['sertifikat/upload_template']         = 'sertifikat/upload_template';
$route['sertifikat/admin/tambah']            = 'sertifikat/admin_tambah_pengajuan';
$route['sertifikat/admin/import_excel']      = 'sertifikat/import_excel';
$route['sertifikat/admin/hapus/(:num)']      = 'sertifikat/admin_hapus/$1';

$route['proposal']                      = 'Proposal/index';
$route['proposal/buat']                 = 'Proposal/buat';
$route['proposal/edit/(:num)']          = 'Proposal/edit/$1';
$route['proposal/detail/(:num)']        = 'Proposal/detail/$1';
$route['proposal/hapus/(:num)']         = 'Proposal/hapus/$1';
$route['proposal/pdf/(:num)']           = 'Proposal/pdf/$1';

$route['proposal/simpan_draft']         = 'Proposal/simpan_draft';
$route['proposal/submit']               = 'Proposal/submit';
$route['proposal/upload_file']          = 'Proposal/upload_file';
$route['proposal/get_data_json']        = 'Proposal/get_data_json';

$route['proposal/setujui/(:num)']       = 'Proposal/setujui/$1';
$route['proposal/tolak/(:num)']         = 'Proposal/tolak/$1';
$route['proposal/pdf/(:num)']          = 'Proposal/pdf/$1';
$route['proposal/download_pdf/(:num)'] = 'Proposal/download_pdf/$1';
  $route['proposal/notifications']       = 'Proposal/notifications';
  $route['proposal/get_notifications']   = 'Proposal/get_notifications';
  $route['proposal/count_unread']        = 'Proposal/count_unread';
  $route['proposal/mark_read/(:num)']    = 'Proposal/mark_read/$1';
  $route['proposal/mark_all_read']       = 'Proposal/mark_all_read';
  $route['admin']                       = 'admin/proposal';
$route['admin/dashboard']             = 'admin/dashboard';
// Admin routes
$route['admin/proposal'] = 'admin/proposal';
$route['admin/detail/(:num)'] = 'admin/detail/$1';
$route['admin/get_proposal_detail/(:num)'] = 'admin/get_proposal_detail/$1';
$route['admin/setujui/(:num)'] = 'admin/setujui/$1';
$route['admin/tolak/(:num)'] = 'admin/tolak/$1';
$route['admin/proposal/hapus/(:num)'] = 'admin/hapus_proposal/$1';

$route['admin/beasiswa'] = 'admin/beasiswa';
$route['admin/beasiswa/detail/(:num)'] = 'admin/get_beasiswa_detail/$1';
$route['admin/beasiswa/setujui/(:num)'] = 'admin/setujui_beasiswa/$1';
$route['admin/beasiswa/tolak/(:num)'] = 'admin/tolak_beasiswa/$1';
$route['admin/beasiswa/hapus/(:num)'] = 'admin/hapus_beasiswa/$1';

$route['berita'] = 'berita/index';
$route['berita/kategori/(:any)'] = 'berita/kategori/$1';
$route['berita/detail/(:any)'] = 'berita/detail/$1';
$route['berita/search'] = 'berita/search';
$route['berita/archive/([0-9]{4})/([0-9]{2})'] = 'berita/archive/$1/$2';
$route['berita/add_komentar'] = 'berita/add_komentar';
$route['berita/admin'] = 'berita/admin';
$route['berita/admin_list'] = 'berita/admin_list';
$route['berita/create'] = 'berita/create';
$route['berita/edit/(:num)'] = 'berita/edit/$1';
$route['berita/delete/(:num)'] = 'berita/delete/$1';
$route['berita/toggle_featured/(:num)'] = 'berita/toggle_featured/$1';
$route['berita/komentar'] = 'berita/komentar';
$route['berita/update_komentar/(:num)'] = 'berita/update_komentar/$1';
$route['berita/delete_komentar/(:num)'] = 'berita/delete_komentar/$1';

$route['beasiswa'] = 'beasiswa/index';
$route['beasiswa/submit'] = 'beasiswa/submit';
$route['beasiswa/status'] = 'beasiswa/status';
$route['beasiswa/cek_status'] = 'beasiswa/cek_status';
$route['beasiswa/download/(:num)'] = 'beasiswa/download/$1';

$route['login/register'] = 'Login/register';
$route['login/proses_register'] = 'Login/proses_register';

// Forum Routes
$route['forum_alumni'] = 'forum_alumni/index';
$route['forum_alumni/create_post'] = 'forum_alumni/create_post';
$route['forum_alumni/delete_post/(:num)'] = 'forum_alumni/delete_post/$1';
$route['forum_alumni/toggle_like'] = 'forum_alumni/toggle_like';
$route['forum_alumni/add_comment'] = 'forum_alumni/add_comment';
$route['forum_alumni/delete_comment'] = 'forum_alumni/delete_comment';
$route['forum_alumni/get_comments'] = 'forum_alumni/get_comments';
$route['forum_alumni/create'] = 'forum_alumni/create';
$route['forum_alumni/store'] = 'forum_alumni/store';
$route['forum_alumni/get_likes'] = 'forum_alumni/get_likes';

// Profile routes
$route['profile'] = 'profile/index';
$route['profile/edit'] = 'profile/edit';
$route['profile/update'] = 'profile/update';
$route['profile/update_ajax'] = 'profile/update_ajax';
$route['profile/update_photo'] = 'profile/update_photo';
$route['profile/update_photo_ajax'] = 'profile/update_photo_ajax';
$route['profile/change_password'] = 'profile/change_password';
$route['profile/change_password_ajax'] = 'profile/change_password_ajax';
$route['profile/delete_account'] = 'profile/delete_account';
$route['profile/get_activities'] = 'profile/get_activities';

// Pedoman & Peraturan – Publik
$route['pedoman']                       = 'pedoman/index';
$route['pedoman/download/(:num)']       = 'pedoman/download/$1';

// Pedoman & Peraturan – Admin
$route['admin/pedoman']                 = 'admin/pedoman';
$route['admin/pedoman_store']           = 'admin/pedoman_store';
$route['admin/pedoman_edit/(:num)']     = 'admin/pedoman_edit/$1';
$route['admin/pedoman_update/(:num)']   = 'admin/pedoman_update/$1';
$route['admin/pedoman_toggle/(:num)']   = 'admin/pedoman_toggle/$1';
$route['admin/pedoman_hapus/(:num)']    = 'admin/pedoman_hapus/$1';