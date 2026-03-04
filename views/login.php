<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Data Karyawan</title>
    <link rel="shortcut icon" href="assets/img/icons/icon-48x48.png">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<main class="d-flex w-100">
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-5 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                    <div class="text-center mt-4">
                        <h1 class="h2">Data Karyawan</h1>
                        <p class="lead">Silakan login untuk mengelola data karyawan.</p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-3">
                                <?php if (!empty($errors['general'])): ?>
                                    <div class="alert alert-danger" role="alert"><?= e($errors['general']); ?></div>
                                <?php endif; ?>

                                <form method="post" action="login.php" novalidate>
                                    <div class="mb-3">
                                        <label class="form-label" for="username">Username</label>
                                        <input class="form-control form-control-lg <?= isset($errors['username']) ? 'is-invalid' : ''; ?>" id="username" type="text" name="username" value="<?= e($username); ?>" placeholder="Masukkan username">
                                        <?php if (isset($errors['username'])): ?>
                                            <div class="invalid-feedback"><?= e($errors['username']); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <input class="form-control form-control-lg <?= isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" type="password" name="password" placeholder="Masukkan password">
                                        <?php if (isset($errors['password'])): ?>
                                            <div class="invalid-feedback"><?= e($errors['password']); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-lg btn-primary">Login</button>
                                    </div>
                                </form>

                                <div class="text-muted mt-3 small">
                                    Default login: <strong>admin</strong> / <strong>admin123</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<script src="assets/js/app.js"></script>
</body>
</html>
