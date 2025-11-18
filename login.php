<?php
// login.php
require_once 'config.php';

// Jika sudah login langsung ke index
if (!empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}

$errors = [];

/*
  Creds default:
  username: admin
  password: admin123
  (Karena tanpa DB, keterangan ini hard-coded — ubah jika mau)
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === 'admin123') {
        // sukses login
        session_regenerate_id(true);
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = 'admin';
        header('Location: index.php');
        exit;
    } else {
        $errors[] = 'Username atau password salah.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Login — Sistem Kontak</title>

<!-- Tailwind CDN (internal styling via CDN) -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
  /* sedikit kustom untuk sentuhan */
  body { background: linear-gradient(180deg,#eef2ff 0%, #f8fafc 100%); }
</style>
</head>
<body class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
    <h1 class="text-2xl font-bold text-sky-700 mb-4">Login Admin</h1>

    <?php if ($errors): ?>
      <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-3 rounded">
        <?php foreach ($errors as $err) echo '<div class="text-sm">'.e($err).'</div>'; ?>
      </div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
      <div>
        <label class="block text-sm font-semibold mb-1">Username</label>
        <input name="username" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-sky-300" />
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1">Password</label>
        <input type="password" name="password" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-sky-300" />
      </div>

      <div class="flex items-center justify-between">
        <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-md">Login</button>
        <a class="text-sm text-sky-600 hover:underline" href="#">Butuh bantuan?</a>
      </div>
    </form>

    <p class="mt-4 text-xs text-gray-500">Gunakan <strong>admin</strong> / <strong>admin123</strong></p>
  </div>
</body>
</html>
