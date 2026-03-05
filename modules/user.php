<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/koneksi.php';

function find_user_by_username(string $username): ?array
{
    $username = trim($username);

    if ($username === '') {
        return null;
    }

    $stmt = db_connect()->prepare(
        'SELECT id, username, password, role
         FROM tbl_user
         WHERE username = :username
         LIMIT 1'
    );
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user === false) {
        return null;
    }

    return $user;
}

function authenticate_user(string $username, string $password): ?array
{
    $user = find_user_by_username($username);

    if ($user === null) {
        return null;
    }

    if (!password_verify($password, (string) $user['password'])) {
        return null;
    }

    return [
        'id' => (int) $user['id'],
        'username' => (string) $user['username'],
        'role' => (string) $user['role'],
    ];
}

function is_username_taken(string $username): bool
{
    return find_user_by_username($username) !== null;
}

function create_user(string $username, string $password, string $role): bool
{
    $username = trim($username);
    $role = trim($role);
    $allowedRoles = ['user', 'admin'];

    if ($username === '' || $password === '' || $role === '') {
        return false;
    }

    if (!in_array($role, $allowedRoles, true)) {
        return false;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = db_connect()->prepare(
        'INSERT INTO tbl_user (username, password, role)
         VALUES (:username, :password, :role)'
    );

    return $stmt->execute([
        'username' => $username,
        'password' => $hashedPassword,
        'role' => $role,
    ]);
}
