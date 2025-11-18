<?php
// add_contact.php
require_once 'functions.php';
require_login();

$errors = [];
$old = ['name'=>'','email'=>'','phone'=>'','address'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token CSRF tidak valid.';
    } else {
        list($errorsVal, $clean) = validate_contact($_POST);
        if (empty($errorsVal)) {
            $id = $_SESSION['next_id']++;
            $_SESSION['contacts'][$id] = $clean;
            flash_set('success', 'Kontak berhasil ditambahkan.');
            header('Location: index.php');
            exit;
        } else {
            $errors = $errorsVal;
            $old = $_POST;
        }
    }
}

$csrf = generate_csrf_token();
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Tambah Kontak</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  body { background:#f8fafc; }
</style>
</head>
<body class="min-h-screen p-6 flex items-start justify-center">
  <div class="w-full max-w-2xl bg-white rounded-2xl shadow p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold text-sky-700">Tambah Kontak</h2>
      <a href="index.php" class="text-sm text-gray-600 hover:underline">Kembali ke daftar</a>
    </div>

    <?php if ($errors): ?>
      <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
        <?php foreach ($errors as $err) echo '<div class="text-sm">'.e($err).'</div>'; ?>
      </div>
    <?php endif; ?>

    <form method="post" class="space-y-4">
      <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

      <div>
        <label class="block text-sm font-medium mb-1">Nama *</label>
        <input name="name" required value="<?= e($old['name'] ?? '') ?>" class="w-full px-3 py-2 border rounded-md" />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input name="email" type="email" value="<?= e($old['email'] ?? '') ?>" class="w-full px-3 py-2 border rounded-md" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Telepon</label>
          <input name="phone" value="<?= e($old['phone'] ?? '') ?>" class="w-full px-3 py-2 border rounded-md" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Alamat</label>
        <textarea name="address" class="w-full px-3 py-2 border rounded-md"><?= e($old['address'] ?? '') ?></textarea>
      </div>

      <div class="flex items-center gap-3">
        <button class="bg-sky-600 text-white px-4 py-2 rounded-md">Simpan</button>
        <a href="index.php" class="px-4 py-2 bg-gray-100 rounded-md text-gray-700">Batal</a>
      </div>
    </form>
  </div>
</body>
</html>
