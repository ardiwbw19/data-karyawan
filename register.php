<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/includes/fungsi.php';
require_once __DIR__ . '/modules/user.php';

if (is_logged_in()) {
    redirect('index.php');
}

$errors = [];
$allowedRoles = ['user', 'admin'];
$form = [
    'username' => '',
    'role' => 'user',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['username'] = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['password_confirmation'] ?? '');
    $form['role'] = trim((string) ($_POST['role'] ?? 'user'));

    if ($form['username'] === '') {
        $errors['username'] = 'Username wajib diisi.';
    } elseif (mb_strlen($form['username']) < 3) {
        $errors['username'] = 'Username minimal 3 karakter.';
    } elseif (mb_strlen($form['username']) > 50) {
        $errors['username'] = 'Username maksimal 50 karakter.';
    }

    if ($password === '') {
        $errors['password'] = 'Password wajib diisi.';
    } elseif (mb_strlen($password) < 6) {
        $errors['password'] = 'Password minimal 6 karakter.';
    }

    if ($confirmPassword === '') {
        $errors['password_confirmation'] = 'Konfirmasi password wajib diisi.';
    } elseif (!hash_equals($password, $confirmPassword)) {
        $errors['password_confirmation'] = 'Konfirmasi password tidak sama.';
    }

    if (!in_array($form['role'], $allowedRoles, true)) {
        $errors['role'] = 'Role tidak valid.';
    }

    if (empty($errors) && is_username_taken($form['username'])) {
        $errors['username'] = 'Username sudah digunakan.';
    }

    if (empty($errors)) {
        $saved = create_user($form['username'], $password, $form['role']);

        if ($saved) {
            set_flash('success', 'Registrasi berhasil. Silakan login.');
            redirect('login.php');
        }

        $errors['general'] = 'Registrasi gagal. Silakan coba lagi.';
    }
}

require __DIR__ . '/views/register.php';
