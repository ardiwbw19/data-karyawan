<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/koneksi.php';

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user']) && is_array($_SESSION['user']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

function validate_karyawan_input(array $input): array
{
    $errors = [];

    $nama = trim((string) ($input['nama'] ?? ''));
    $idJabatan = filter_var($input['id_jabatan'] ?? null, FILTER_VALIDATE_INT);
    $alamat = trim((string) ($input['alamat'] ?? ''));
    $status = trim((string) ($input['status'] ?? ''));

    if ($nama === '') {
        $errors['nama'] = 'Nama wajib diisi.';
    } elseif (mb_strlen($nama) < 3) {
        $errors['nama'] = 'Nama minimal 3 karakter.';
    } elseif (mb_strlen($nama) > 100) {
        $errors['nama'] = 'Nama maksimal 100 karakter.';
    }

    if ($idJabatan === false || $idJabatan < 1) {
        $errors['id_jabatan'] = 'Jabatan wajib dipilih.';
    } elseif (!jabatan_exists((int) $idJabatan)) {
        $errors['id_jabatan'] = 'Jabatan tidak valid.';
    }

    if ($alamat === '') {
        $errors['alamat'] = 'Alamat wajib diisi.';
    } elseif (mb_strlen($alamat) < 5) {
        $errors['alamat'] = 'Alamat minimal 5 karakter.';
    }

    if (!in_array($status, ['active', 'inactive'], true)) {
        $errors['status'] = 'Status tidak valid.';
    }

    return [
        'errors' => $errors,
        'clean' => [
            'nama' => $nama,
            'id_jabatan' => (int) $idJabatan,
            'alamat' => $alamat,
            'status' => $status,
        ],
    ];
}

function jabatan_exists(int $idJabatan): bool
{
    $stmt = db_connect()->prepare('SELECT COUNT(*) FROM tbl_jabatan WHERE id_jabatan = :id_jabatan');
    $stmt->execute(['id_jabatan' => $idJabatan]);

    return (int) $stmt->fetchColumn() > 0;
}

function upload_karyawan_foto(array $file, ?string $oldFile = null): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'filename' => $oldFile, 'error' => null];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['success' => false, 'filename' => $oldFile, 'error' => 'Gagal upload foto.'];
    }

    $maxSize = 2 * 1024 * 1024;
    if (($file['size'] ?? 0) > $maxSize) {
        return ['success' => false, 'filename' => $oldFile, 'error' => 'Ukuran foto maksimal 2MB.'];
    }

    $tmpName = $file['tmp_name'] ?? '';
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmpName);

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($allowed[$mime])) {
        return ['success' => false, 'filename' => $oldFile, 'error' => 'Format foto harus JPG, PNG, atau WEBP.'];
    }

    $newFileName = 'karyawan_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    $targetDir = __DIR__ . '/../uploads/karyawan';
    $targetPath = $targetDir . '/' . $newFileName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }

    if (!move_uploaded_file($tmpName, $targetPath)) {
        return ['success' => false, 'filename' => $oldFile, 'error' => 'File foto tidak dapat disimpan.'];
    }

    if ($oldFile !== null && $oldFile !== '') {
        delete_karyawan_foto($oldFile);
    }

    return ['success' => true, 'filename' => $newFileName, 'error' => null];
}

function delete_karyawan_foto(?string $fileName): void
{
    if ($fileName === null || $fileName === '') {
        return;
    }

    $fullPath = __DIR__ . '/../uploads/karyawan/' . $fileName;
    if (is_file($fullPath)) {
        unlink($fullPath);
    }
}

function get_all_jabatan(): array
{
    $stmt = db_connect()->query('SELECT id_jabatan, nama_jabatan FROM tbl_jabatan ORDER BY nama_jabatan ASC');
    return $stmt->fetchAll();
}

function get_jabatan_name_by_id(int $idJabatan): ?string
{
    $stmt = db_connect()->prepare('SELECT nama_jabatan FROM tbl_jabatan WHERE id_jabatan = :id_jabatan LIMIT 1');
    $stmt->execute(['id_jabatan' => $idJabatan]);
    $name = $stmt->fetchColumn();

    return $name === false ? null : (string) $name;
}

function get_karyawan(int $id): ?array
{
    $sql = 'SELECT k.id, k.nama, k.id_jabatan, j.nama_jabatan, k.alamat, k.foto, k.status
            FROM tbl_karyawan k
            INNER JOIN tbl_jabatan j ON j.id_jabatan = k.id_jabatan
            WHERE k.id = :id
            LIMIT 1';
    $stmt = db_connect()->prepare($sql);
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return $row ?: null;
}

function count_karyawan(string $keyword = '', ?int $filterJabatanId = null): int
{
    $sql = 'SELECT COUNT(*)
            FROM tbl_karyawan k
            INNER JOIN tbl_jabatan j ON j.id_jabatan = k.id_jabatan';

    $params = [];
    $conditions = [];
    if ($keyword !== '') {
        $conditions[] = '(k.nama LIKE :keyword_nama OR j.nama_jabatan LIKE :keyword_jabatan)';
        $params['keyword_nama'] = '%' . $keyword . '%';
        $params['keyword_jabatan'] = '%' . $keyword . '%';
    }

    if ($filterJabatanId !== null) {
        $conditions[] = 'k.id_jabatan = :filter_id_jabatan';
        $params['filter_id_jabatan'] = $filterJabatanId;
    }

    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $stmt = db_connect()->prepare($sql);
    $stmt->execute($params);

    return (int) $stmt->fetchColumn();
}

function get_karyawan_list(string $keyword = '', int $page = 1, int $perPage = 10, ?int $filterJabatanId = null): array
{
    $offset = ($page - 1) * $perPage;

    $sql = 'SELECT k.id, k.nama, j.nama_jabatan, k.alamat, k.foto, k.status, k.updated_at
            FROM tbl_karyawan k
            INNER JOIN tbl_jabatan j ON j.id_jabatan = k.id_jabatan';

    $params = [
        'limit' => $perPage,
        'offset' => $offset,
    ];

    $conditions = [];
    if ($keyword !== '') {
        $conditions[] = '(k.nama LIKE :keyword_nama OR j.nama_jabatan LIKE :keyword_jabatan)';
        $params['keyword_nama'] = '%' . $keyword . '%';
        $params['keyword_jabatan'] = '%' . $keyword . '%';
    }

    if ($filterJabatanId !== null) {
        $conditions[] = 'k.id_jabatan = :filter_id_jabatan';
        $params['filter_id_jabatan'] = $filterJabatanId;
    }

    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY k.id DESC LIMIT :limit OFFSET :offset';

    $stmt = db_connect()->prepare($sql);
    if ($keyword !== '') {
        $stmt->bindValue(':keyword_nama', $params['keyword_nama'], PDO::PARAM_STR);
        $stmt->bindValue(':keyword_jabatan', $params['keyword_jabatan'], PDO::PARAM_STR);
    }
    if ($filterJabatanId !== null) {
        $stmt->bindValue(':filter_id_jabatan', $params['filter_id_jabatan'], PDO::PARAM_INT);
    }
    $stmt->bindValue(':limit', $params['limit'], PDO::PARAM_INT);
    $stmt->bindValue(':offset', $params['offset'], PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function get_karyawan_export_list(string $keyword = '', ?int $filterJabatanId = null): array
{
    $sql = 'SELECT k.id, k.nama, j.nama_jabatan, k.alamat, k.status, k.created_at, k.updated_at
            FROM tbl_karyawan k
            INNER JOIN tbl_jabatan j ON j.id_jabatan = k.id_jabatan';

    $params = [];
    $conditions = [];
    if ($keyword !== '') {
        $conditions[] = '(k.nama LIKE :keyword_nama OR j.nama_jabatan LIKE :keyword_jabatan)';
        $params['keyword_nama'] = '%' . $keyword . '%';
        $params['keyword_jabatan'] = '%' . $keyword . '%';
    }

    if ($filterJabatanId !== null) {
        $conditions[] = 'k.id_jabatan = :filter_id_jabatan';
        $params['filter_id_jabatan'] = $filterJabatanId;
    }

    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY k.id DESC';

    $stmt = db_connect()->prepare($sql);
    if ($keyword !== '') {
        $stmt->bindValue(':keyword_nama', $params['keyword_nama'], PDO::PARAM_STR);
        $stmt->bindValue(':keyword_jabatan', $params['keyword_jabatan'], PDO::PARAM_STR);
    }
    if ($filterJabatanId !== null) {
        $stmt->bindValue(':filter_id_jabatan', $params['filter_id_jabatan'], PDO::PARAM_INT);
    }
    $stmt->execute();

    return $stmt->fetchAll();
}

function create_karyawan(array $data): bool
{
    $sql = 'INSERT INTO tbl_karyawan (nama, id_jabatan, alamat, foto, status)
            VALUES (:nama, :id_jabatan, :alamat, :foto, :status)';

    $stmt = db_connect()->prepare($sql);

    return $stmt->execute([
        'nama' => $data['nama'],
        'id_jabatan' => $data['id_jabatan'],
        'alamat' => $data['alamat'],
        'foto' => $data['foto'] ?? '',
        'status' => $data['status'],
    ]);
}

function update_karyawan(int $id, array $data): bool
{
    $sql = 'UPDATE tbl_karyawan
            SET nama = :nama,
                id_jabatan = :id_jabatan,
                alamat = :alamat,
                foto = :foto,
                status = :status,
                updated_at = NOW()
            WHERE id = :id';

    $stmt = db_connect()->prepare($sql);

    return $stmt->execute([
        'id' => $id,
        'nama' => $data['nama'],
        'id_jabatan' => $data['id_jabatan'],
        'alamat' => $data['alamat'],
        'foto' => $data['foto'] ?? '',
        'status' => $data['status'],
    ]);
}

function remove_karyawan(int $id): bool
{
    $existing = get_karyawan($id);
    if ($existing === null) {
        return false;
    }

    $stmt = db_connect()->prepare('DELETE FROM tbl_karyawan WHERE id = :id');
    $ok = $stmt->execute(['id' => $id]);

    if ($ok) {
        delete_karyawan_foto($existing['foto'] ?? '');
    }

    return $ok;
}
