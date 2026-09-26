<?php
session_start();
require_once '../config/db.php';
$_SESSION["csrf"] ??= bin2hex(random_bytes(32));

$q = trim($_GET["q"] ?? "");
if ($q !== "") {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :q OR category LIKE :q ORDER BY id DESC");
    $stmt->execute(["q" => "%$q%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk</title>
    <!-- Import Font Modern dari Google -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header class="page-header">
            <h1>Katalog Produk</h1>
            <p>Kelola data produk Anda dengan antarmuka yang nyaman.</p>
        </header>

        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                Berhasil! Data produk telah <b><?= htmlspecialchars($_GET['status'], ENT_QUOTES, "UTF-8") ?></b>.
            </div>
        <?php endif; ?>

        <div class="toolbar">
            <a href="create.php" class="btn btn-primary">Tambah Produk</a>
            <form method="GET" class="search-form">
                <input type="text" name="q" placeholder="Cari nama atau kategori..." value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn btn-secondary">🔍 Cari</button>
            </form>
        </div>

        <div class="products">
            <?php foreach ($products as $p): ?>
                <div class="card">
                    <div class="card-body">
                        <span class="badge"><?= htmlspecialchars($p["category"], ENT_QUOTES, "UTF-8") ?></span>
                        <h3><?= htmlspecialchars($p["name"], ENT_QUOTES, "UTF-8") ?></h3>
                        <p class="price">Rp <?= number_format($p["price"], 0, ",", ".") ?></p>
                        <p class="stock">Stok tersisa: <b><?= htmlspecialchars($p["stock"], ENT_QUOTES, "UTF-8") ?></b></p>
                    </div>
                    <div class="card-footer actions">
                        <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-warning">Edit</a>
                        <form method="POST" action="delete.php" style="margin:0; flex: 1;">
                            <input type="hidden" name="id" value="<?= $p["id"] ?>">
                            <input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">🗑️ Hapus</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <span></span>
                    <p>Belum ada data produk. Yuk tambahkan produk pertama Anda!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>