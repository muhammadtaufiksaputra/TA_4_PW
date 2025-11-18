<?php
// functions.php
require_once 'config.php';

/* ---------- AUTH ---------- */
function require_login() {
    if (empty($_SESSION['logged_in'])) {
        header('Location: login.php');
        exit;
    }
}

/* ---------- CSRF ---------- */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/* ---------- SANITIZE / HELPERS ---------- */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function flash_set($key, $msg) {
    $_SESSION['flash'][$key] = $msg;
}
function flash_get($key) {
    if (isset($_SESSION['flash'][$key])) {
        $m = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $m;
    }
    return null;
}

/* ---------- VALIDATION ---------- */
function validate_contact($input) {
    $errors = [];
    $data = [];

    $name = trim($input['name'] ?? '');
    if ($name === '') {
        $errors[] = "Nama harus diisi.";
    } elseif (mb_strlen($name) > 150) {
        $errors[] = "Nama terlalu panjang (maks 150 karakter).";
    } else {
        $data['name'] = $name;
    }

    $email = trim($input['email'] ?? '');
    if ($email !== '') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid.";
        } else {
            $data['email'] = $email;
        }
    } else {
        $data['email'] = '';
    }

    $phone = trim($input['phone'] ?? '');
    $data['phone'] = $phone;

    $address = trim($input['address'] ?? '');
    $data['address'] = $address;

    return [$errors, $data];
}
