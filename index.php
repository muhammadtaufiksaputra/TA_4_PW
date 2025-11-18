<?php
// index.php
require_once 'functions.php';
require_login();

$contacts = $_SESSION['contacts'];
$flash = flash_get('success');
$csrf = generate_csrf_token();
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Daftar Kontak — Sistem Kontak</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
/* internal small tweaks */
.table-wrap { overflow-x:auto; }
.card { background: linear-gradient(180deg,#ffffff,#fbfdff); }
</style>
</head>
<body class="bg-slate-50 min-h-screen p-6">
  <div class="max-w-5xl mx-auto">
    <header class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-sky-700">Sistem Manajemen Kontak</h1>
      <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600">Halo, <strong><?= e($_SESSION['user'] ?? 'Admin') ?></strong></span>
        <a href="add_contact.php" class="bg-sky-600 text-white px-3 py-2 rounded-md hover:bg-sky-700">Tambah Kontak</a>
        <a href="logout.php" class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600">Logout</a>
      </div>
    </header>

    <?php if ($flash): ?>
      <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">
        <?= e($flash) ?>
      </div>
    <?php endif; ?>

    <div class="card rounded-xl shadow p-4">
      <div class="mb-4 flex items-center justify-between">
        <form method="get" class="flex items-center gap-2">
          <input name="q" placeholder="Cari nama / email / telepon" class="px-3 py-2 border rounded-md" value="<?= e($_GET['q'] ?? '') ?>" />
          <button type="submit" class="px-3 py-2 bg-sky-600 text-white rounded-md">Cari</button>
          <a href="index.php" class="px-3 py-2 bg-gray-100 rounded-md text-sm">Reset</a>
        </form>
        <div class="text-sm text-gray-500">Total: <strong><?= count($contacts) ?></strong></div>
      </div>

      <div class="table-wrap">
        <table class="min-w-full divide-y">
          <thead>
            <tr class="bg-sky-600 text-white">
              <th class="px-4 py-3 text-left text-sm">#</th>
              <th class="px-4 py-3 text-left text-sm">Nama</th>
              <th class="px-4 py-3 text-left text-sm">Email</th>
              <th class="px-4 py-3 text-left text-sm">Telepon</th>
              <th class="px-4 py-3 text-left text-sm">Alamat</th>
              <th class="px-4 py-3 text-left text-sm">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y">
            <?php if (empty($contacts)): ?>
              <tr><td colspan="6" class="p-4 text-center text-gray-500">Belum ada kontak.</td></tr>
            <?php else: ?>
              <?php foreach ($contacts as $id => $c): ?>
              <tr>
                <td class="px-4 py-3 text-sm align-top"><?= e($id) ?></td>
                <td class="px-4 py-3 text-sm align-top"><?= e($c['name']) ?></td>
                <td class="px-4 py-3 text-sm align-top"><?= e($c['email']) ?></td>
                <td class="px-4 py-3 text-sm align-top"><?= e($c['phone']) ?></td>
                <td class="px-4 py-3 text-sm align-top"><?= e($c['address']) ?></td>
                <td class="px-4 py-3 text-sm align-top">
                  <div class="flex gap-2">
                    <a href="edit_contact.php?id=<?= e($id) ?>" class="px-2 py-1 bg-yellow-400 text-white rounded text-sm hover:opacity-90">Edit</a>

                    <form method="post" action="delete_contact.php" onsubmit="return confirm('Yakin ingin menghapus?');" class="inline">
                      <input type="hidden" name="id" value="<?= e($id) ?>">
                      <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
                      <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded text-sm">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <footer class="mt-6 text-xs text-gray-500">
      Aplikasi sederhana — data tersimpan di session (sementara).
    </footer>
  </div>
</body>
</html>
