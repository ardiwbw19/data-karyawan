<?php
$isEdit = $action === 'edit';
$formAction = $isEdit ? ('index.php?action=update&id=' . (int) ($id ?? 0)) : 'index.php?action=store';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1"><?= $isEdit ? 'Edit Karyawan' : 'Tambah Karyawan'; ?></h1>
        <p class="text-muted mb-0">Lengkapi data dengan benar sebelum menyimpan.</p>
    </div>
    <a href="index.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= e($errors['general']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= e($formAction); ?>" enctype="multipart/form-data" novalidate>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        class="form-control <?= isset($errors['nama']) ? 'is-invalid' : ''; ?>"
                        value="<?= e($formData['nama'] ?? ''); ?>"
                        maxlength="100"
                    >
                    <?php if (isset($errors['nama'])): ?>
                        <div class="invalid-feedback"><?= e($errors['nama']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="form-select <?= isset($errors['gender']) ? 'is-invalid' : ''; ?>">
                        <option value="Laki-Laki" <?= (($formData['gender'] ?? 'Laki-Laki') === 'Laki-Laki') ? 'selected' : ''; ?>>Laki-Laki</option>
                        <option value="Perempuan" <?= (($formData['gender'] ?? '') === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                    <?php if (isset($errors['gender'])): ?>
                        <div class="invalid-feedback"><?= e($errors['gender']); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_jabatan" class="form-label">Jabatan</label>
                    <select id="id_jabatan" name="id_jabatan" class="form-select <?= isset($errors['id_jabatan']) ? 'is-invalid' : ''; ?>">
                        <option value="">-- Pilih Jabatan --</option>
                        <?php foreach ($jabatanOptions as $jabatan): ?>
                            <option
                                value="<?= (int) $jabatan['id_jabatan']; ?>"
                                <?= ((string) ($formData['id_jabatan'] ?? '') === (string) $jabatan['id_jabatan']) ? 'selected' : ''; ?>
                            >
                                <?= e($jabatan['nama_jabatan']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['id_jabatan'])): ?>
                        <div class="invalid-feedback"><?= e($errors['id_jabatan']); ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select <?= isset($errors['status']) ? 'is-invalid' : ''; ?>">
                        <option value="active" <?= (($formData['status'] ?? '') === 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?= (($formData['status'] ?? '') === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                    <?php if (isset($errors['status'])): ?>
                        <div class="invalid-feedback"><?= e($errors['status']); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea
                    id="alamat"
                    name="alamat"
                    rows="3"
                    class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : ''; ?>"
                ><?= e($formData['alamat'] ?? ''); ?></textarea>
                <?php if (isset($errors['alamat'])): ?>
                    <div class="invalid-feedback"><?= e($errors['alamat']); ?></div>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="foto" class="form-label">Foto (opsional)</label>
                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" class="form-control <?= isset($errors['foto']) ? 'is-invalid' : ''; ?>">
                    <?php if (isset($errors['foto'])): ?>
                        <div class="invalid-feedback"><?= e($errors['foto']); ?></div>
                    <?php else: ?>
                        <div class="form-text">Maksimal 2MB, format JPG/PNG/WEBP.</div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($formData['foto'])): ?>
                <div class="mb-3">
                    <div class="text-muted mb-2">Foto saat ini:</div>
                    <img src="uploads/karyawan/<?= e($formData['foto']); ?>" alt="Foto Karyawan" class="rounded" style="width:90px;height:90px;object-fit:cover;">
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update' : 'Simpan'; ?></button>
            <a href="index.php" class="btn btn-light">Batal</a>
        </form>
    </div>
</div>
