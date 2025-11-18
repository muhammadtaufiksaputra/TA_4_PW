<?php
// delete_contact.php
require_once 'functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = $_POST['id'] ?? null;
$token = $_POST['csrf_token'] ?? '';

if ($id === null || !isset($_SESSION['contacts'][$id])) {
    header('Location: index.php');
    exit;
}

if (!verify_csrf_token($token)) {
    die('Token CSRF tidak valid');
}

unset($_SESSION['contacts'][$id]);
flash_set('success', 'Kontak berhasil dihapus.');
header('Location: index.php');
exit;
