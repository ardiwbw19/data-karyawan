<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
    <div>
        <h1 class="h3 mb-1">Data Karyawan</h1>
        <p class="text-muted mb-0">Kelola data karyawan dengan antarmuka yang konsisten dan ringkas.</p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="index.php?action=create" class="btn btn-primary">
            <i class="align-middle me-1" data-feather="plus"></i>
            Tambah Karyawan
        </a>
        <a href="export.php?type=excel<?= $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?><?= $filterJabatanId !== null ? '&id_jabatan=' . $filterJabatanId : ''; ?>" class="btn btn-success">
            <i class="align-middle me-1" data-feather="file-text"></i>
            Export Excel
        </a>
        <a href="export.php?type=pdf<?= $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?><?= $filterJabatanId !== null ? '&id_jabatan=' . $filterJabatanId : ''; ?>" target="_blank" class="btn btn-danger">
            <i class="align-middle me-1" data-feather="printer"></i>
            Export PDF
        </a>
    </div>
</div>

<?php if ($flash !== null): ?>
    <div class="alert alert-<?= e($flash['type']); ?> alert-dismissible" role="alert">
        <div class="alert-message"><?= e($flash['message']); ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <form class="row g-2 align-items-center" method="get" action="index.php">
            <div class="col-lg-5">
                <input type="text" name="q" class="form-control" value="<?= e($keyword); ?>" placeholder="Cari nama atau jabatan...">
            </div>
            <div class="col-lg-3">
                <select name="id_jabatan" class="form-select">
                    <option value="">Semua Jabatan</option>
                    <?php foreach ($jabatanOptions as $jabatan): ?>
                        <option
                            value="<?= (int) $jabatan['id_jabatan']; ?>"
                            <?= $filterJabatanId === (int) $jabatan['id_jabatan'] ? 'selected' : ''; ?>
                        >
                            <?= e($jabatan['nama_jabatan']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-4 d-flex gap-2 justify-content-lg-end">
                <button type="submit" class="btn btn-info text-white px-4">Cari</button>
                <a href="index.php" class="btn btn-outline-secondary px-4">Reset</a>
            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 72px;">Foto</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>Alamat</th>
                    <th style="width: 160px;">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">Belum ada data karyawan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $index => $row): ?>
                        <?php
                        $rowNumber = (($currentPage - 1) * $perPage) + $index + 1;
                        $fotoPath = 'uploads/karyawan/' . ($row['foto'] ?? '');
                        $fotoUrl = (!empty($row['foto']) && is_file(__DIR__ . '/../' . $fotoPath))
                            ? $fotoPath
                            : 'assets/img/avatars/avatar.jpg';
                        ?>
                        <tr>
                            <td><?= $rowNumber; ?></td>
                            <td>
                                <img src="<?= e($fotoUrl); ?>" alt="Foto" class="rounded" style="width:48px;height:48px;object-fit:cover;">
                            </td>
                            <td><?= e($row['nama']); ?></td>
                            <td><?= e($row['nama_jabatan']); ?></td>
                            <td>
                                <?php if (($row['status'] ?? '') === 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($row['alamat']); ?></td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="index.php?action=edit&id=<?= (int) $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger js-btn-delete"
                                        data-id="<?= (int) $row['id']; ?>"
                                        data-nama="<?= e($row['nama']); ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapusKaryawan"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($totalRows > 0): ?>
        <div class="card-footer d-flex justify-content-between align-items-center gap-2 flex-nowrap">
            <?php
            $startItem = (($currentPage - 1) * $perPage) + 1;
            $endItem = min($currentPage * $perPage, $totalRows);
            $buildPageUrl = static function (int $page) use ($keyword, $filterJabatanId): string {
                $query = ['page' => $page];
                if ($keyword !== '') {
                    $query['q'] = $keyword;
                }
                if ($filterJabatanId !== null) {
                    $query['id_jabatan'] = $filterJabatanId;
                }
                return 'index.php?' . http_build_query($query);
            };

            $windowStart = max(1, $currentPage - 2);
            $windowEnd = min($totalPages, $currentPage + 2);
            ?>

            <div class="text-muted small text-nowrap">
                Menampilkan <?= $startItem; ?> - <?= $endItem; ?> dari <?= $totalRows; ?> data
            </div>

            <?php if ($totalPages > 1): ?>
                <nav aria-label="Pagination Karyawan" class="ms-auto" style="overflow-x:auto;">
                    <ul class="pagination pagination-sm mb-0" style="display:flex;flex-direction:row;flex-wrap:nowrap;list-style:none;padding-left:0;margin:0;gap:6px;align-items:center;">
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : ''; ?>" style="list-style:none;margin:0 2px;">
                            <a class="page-link" href="<?= e($buildPageUrl($currentPage - 1)); ?>" aria-label="Previous">&laquo;</a>
                        </li>

                        <?php for ($page = $windowStart; $page <= $windowEnd; $page++): ?>
                            <li class="page-item <?= $page === $currentPage ? 'active' : ''; ?>" style="list-style:none;margin:0 2px;">
                                <a class="page-link" href="<?= e($buildPageUrl($page)); ?>"><?= $page; ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : ''; ?>" style="list-style:none;margin:0 2px;">
                            <a class="page-link" href="<?= e($buildPageUrl($currentPage + 1)); ?>" aria-label="Next">&raquo;</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="modalHapusKaryawan" tabindex="-1" aria-labelledby="modalHapusKaryawanLabel" aria-hidden="true" style="z-index:1080;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHapusKaryawanLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Data karyawan <strong id="namaKaryawanHapus">-</strong> akan dihapus permanen. Lanjutkan?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="linkKonfirmasiHapus" class="btn btn-danger">Ya, Hapus</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.js-btn-delete');
        const deleteName = document.getElementById('namaKaryawanHapus');
        const deleteLink = document.getElementById('linkKonfirmasiHapus');
        const deleteModal = document.getElementById('modalHapusKaryawan');

        if (deleteModal && deleteModal.parentElement !== document.body) {
            document.body.appendChild(deleteModal);
        }

        deleteButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const id = button.getAttribute('data-id') || '';
                const nama = button.getAttribute('data-nama') || '-';

                if (deleteName) {
                    deleteName.textContent = nama;
                }

                if (deleteLink) {
                    deleteLink.setAttribute('href', 'index.php?action=delete&id=' + encodeURIComponent(id));
                }
            });
        });
    });
</script>
