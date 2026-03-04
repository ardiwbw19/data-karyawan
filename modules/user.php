<?php

declare(strict_types=1);

function authenticate_user(string $username, string $password): bool
{
    $username = trim($username);

    return hash_equals('admin', $username) && hash_equals('admin123', $password);
}
