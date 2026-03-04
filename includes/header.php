<?php

declare(strict_types=1);

$pageTitle = $pageTitle ?? 'Data Karyawan';
$activePage = $activePage ?? 'karyawan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= e($pageTitle); ?> - Data Karyawan</title>
    <link rel="shortcut icon" href="assets/img/icons/icon-48x48.png">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="wrapper">
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="index.php">
                <span class="align-middle">Data Karyawan</span>
            </a>

            <ul class="sidebar-nav">
                <li class="sidebar-header">Menu</li>
                <li class="sidebar-item <?= $activePage === 'karyawan' ? 'active' : ''; ?>">
                    <a class="sidebar-link" href="index.php">
                        <i class="align-middle" data-feather="users"></i>
                        <span class="align-middle">Karyawan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="logout.php">
                        <i class="align-middle" data-feather="log-out"></i>
                        <span class="align-middle">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main">
        <nav class="navbar navbar-expand navbar-light navbar-bg">
            <a class="sidebar-toggle js-sidebar-toggle">
                <i class="hamburger align-self-center"></i>
            </a>

            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                            <i class="align-middle" data-feather="settings"></i>
                        </a>
                        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                            <img src="assets/img/avatars/avatar.jpg" class="avatar img-fluid rounded me-1" alt="Admin">
                            <span class="text-dark"><?= e($_SESSION['user']['name'] ?? 'Admin'); ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="logout.php"><i class="align-middle me-1" data-feather="log-out"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="content">
            <div class="container-fluid p-0">
