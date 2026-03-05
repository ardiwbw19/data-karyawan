<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/fungsi.php';
require_once __DIR__ . '/modules/user.php';

if (is_logged_in()) {
    redirect('index.php');
}

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '') {
        $errors['username'] = 'Username wajib diisi.';
    }

    if ($password === '') {
        $errors['password'] = 'Password wajib diisi.';
    }

    if (empty($errors)) {
        $authenticatedUser = authenticate_user($username, $password);

        if ($authenticatedUser !== null) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $authenticatedUser['id'],
                'name' => $authenticatedUser['username'],
                'username' => $authenticatedUser['username'],
                'role' => $authenticatedUser['role'],
            ];

            set_flash('success', 'Login berhasil.');
            redirect('index.php');
        }

        $errors['general'] = 'Username atau password salah.';
    }
}

require __DIR__ . '/views/login.php';
