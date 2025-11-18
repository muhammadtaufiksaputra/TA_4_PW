<?php
// config.php
// Session-based storage (no database)
session_start();

// inisialisasi penyimpanan kontak di session
if (!isset($_SESSION['contacts'])) {
    $_SESSION['contacts'] = [];
}
if (!isset($_SESSION['next_id'])) {
    $_SESSION['next_id'] = 1;
}

// simple session timeout (30 menit)
$timeout = 30 * 60;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['LAST_ACTIVITY'] = time();
