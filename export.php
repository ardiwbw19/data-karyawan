<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/fungsi.php';

require_login();

$type = $_GET['type'] ?? '';
$keyword = trim((string) ($_GET['q'] ?? ''));
$filterJabatanId = null;
if (isset($_GET['id_jabatan']) && $_GET['id_jabatan'] !== '') {
    $candidateJabatanId = (int) $_GET['id_jabatan'];
    if ($candidateJabatanId > 0 && jabatan_exists($candidateJabatanId)) {
        $filterJabatanId = $candidateJabatanId;
    }
}

$rows = get_karyawan_export_list($keyword, $filterJabatanId);
$timestamp = date('Ymd_His');

$filterParts = [];
if ($keyword !== '') {
    $filterParts[] = 'Kata kunci: ' . $keyword;
}
if ($filterJabatanId !== null) {
    $filterJabatanName = get_jabatan_name_by_id($filterJabatanId);
    if ($filterJabatanName !== null) {
        $filterParts[] = 'Jabatan: ' . $filterJabatanName;
    }
}
$filterSummary = empty($filterParts) ? 'Semua data' : implode(' | ', $filterParts);

if ($type === 'excel') {
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment; filename="data_karyawan_' . $timestamp . '.xls"');

    echo "\xEF\xBB\xBF";
    ?>
    <table border="1">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Status</th>
            <th>Alamat</th>
            <th>Dibuat</th>
            <th>Diperbarui</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan="7">Tidak ada data.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= e($row['nama']); ?></td>
                    <td><?= e($row['nama_jabatan']); ?></td>
                    <td><?= e(ucfirst($row['status'])); ?></td>
                    <td><?= e($row['alamat']); ?></td>
                    <td><?= e((string) ($row['created_at'] ?? '-')); ?></td>
                    <td><?= e((string) ($row['updated_at'] ?? '-')); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <?php
    exit;
}

if ($type === 'pdf') {
    ?>
    <!doctype html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Export PDF Data Karyawan</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 24px; color: #1f2937; }
            h2 { margin: 0 0 6px; }
            p { margin: 0 0 14px; color: #4b5563; }
            table { width: 100%; border-collapse: collapse; font-size: 12px; }
            th, td { border: 1px solid #d1d5db; padding: 6px 8px; vertical-align: top; }
            th { background: #f3f4f6; text-align: left; }
            .meta { margin-top: 10px; font-size: 11px; color: #6b7280; }
        </style>
    </head>
    <body>
    <h2>Data Karyawan</h2>
    <p>Hasil export: <?= e($filterSummary); ?></p>

    <table>
        <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Status</th>
            <th>Alamat</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan="5">Tidak ada data.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= e($row['nama']); ?></td>
                    <td><?= e($row['nama_jabatan']); ?></td>
                    <td><?= e(ucfirst($row['status'])); ?></td>
                    <td><?= e($row['alamat']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="meta">Dicetak: <?= e(date('d-m-Y H:i:s')); ?></div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
    </body>
    </html>
    <?php
    exit;
}

redirect('index.php');
