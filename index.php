<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/fungsi.php';

require_login();

$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$keyword = trim((string) ($_GET['q'] ?? ''));
$filterJabatanId = null;
if (isset($_GET['id_jabatan']) && $_GET['id_jabatan'] !== '') {
    $candidateJabatanId = (int) $_GET['id_jabatan'];
    if ($candidateJabatanId > 0 && jabatan_exists($candidateJabatanId)) {
        $filterJabatanId = $candidateJabatanId;
    }
}
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$currentPage = max($currentPage, 1);
$perPage = 10;

$errors = [];
$formData = [
    'nama' => '',
    'id_jabatan' => '',
    'alamat' => '',
    'status' => 'active',
    'foto' => '',
];

$jabatanOptions = get_all_jabatan();

if ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $validation = validate_karyawan_input($_POST);
    $errors = $validation['errors'];
    $formData = array_merge($formData, $validation['clean']);

    if (empty($errors)) {
        $upload = upload_karyawan_foto($_FILES['foto'] ?? []);
        if (!$upload['success']) {
            $errors['foto'] = $upload['error'];
        } else {
            $formData['foto'] = $upload['filename'] ?? '';
            $saved = create_karyawan($formData);

            if ($saved) {
                set_flash('success', 'Data karyawan berhasil ditambahkan.');
                redirect('index.php');
            }

            $errors['general'] = 'Data karyawan gagal disimpan.';
        }
    }

    $action = 'create';
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $existing = get_karyawan($id);

    if ($existing === null) {
        set_flash('danger', 'Data karyawan tidak ditemukan.');
        redirect('index.php');
    }

    $validation = validate_karyawan_input($_POST);
    $errors = $validation['errors'];
    $formData = array_merge($existing, $validation['clean']);
    $formData['foto'] = $existing['foto'] ?? '';

    if (empty($errors)) {
        $upload = upload_karyawan_foto($_FILES['foto'] ?? [], $existing['foto'] ?? '');
        if (!$upload['success']) {
            $errors['foto'] = $upload['error'];
        } else {
            $formData['foto'] = $upload['filename'] ?? '';
            $saved = update_karyawan($id, $formData);

            if ($saved) {
                set_flash('success', 'Data karyawan berhasil diperbarui.');
                redirect('index.php');
            }

            $errors['general'] = 'Data karyawan gagal diperbarui.';
        }
    }

    $action = 'edit';
}

if ($action === 'delete' && $id > 0) {
    $deleted = remove_karyawan($id);
    if ($deleted) {
        set_flash('success', 'Data karyawan berhasil dihapus.');
    } else {
        set_flash('danger', 'Data karyawan gagal dihapus atau tidak ditemukan.');
    }
    redirect('index.php');
}

if ($action === 'edit' && $id > 0 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $existing = get_karyawan($id);
    if ($existing === null) {
        set_flash('danger', 'Data karyawan tidak ditemukan.');
        redirect('index.php');
    }

    $formData = array_merge($formData, $existing);
}

$flash = get_flash();
$pageTitle = 'Data Karyawan';
$activePage = 'karyawan';

require_once __DIR__ . '/includes/header.php';

if ($action === 'create' || $action === 'edit') {
    require __DIR__ . '/views/karyawan-form.php';
} else {
    $totalRows = count_karyawan($keyword, $filterJabatanId);
    $totalPages = (int) ceil($totalRows / $perPage);
    $totalPages = max($totalPages, 1);

    if ($currentPage > $totalPages) {
        $currentPage = $totalPages;
    }

    $rows = get_karyawan_list($keyword, $currentPage, $perPage, $filterJabatanId);
    require __DIR__ . '/views/karyawan-list.php';
}

require_once __DIR__ . '/includes/footer.php';
