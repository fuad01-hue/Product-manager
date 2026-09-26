<?php
require_once '../config/db.php';

$name = $category = "";
$price = $stock = 0;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $category = trim($_POST["category"] ?? "Umum");
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, "stock", FILTER_VALIDATE_INT);

    if (mb_strlen($name) < 3) $errors["name"] = "Nama minimal 3 karakter.";
    if ($price === false || $price <= 0) $errors["price"] = "Harga harus > 0.";
    if ($stock === false || $stock < 0) $errors["stock"] = "Stok tidak boleh negatif.";
    if (empty($category)) $errors["category"] = "Kategori tidak boleh kosong.";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock) VALUES (:name, :category, :price, :stock)");
            $stmt->execute(compact("name", "category", "price", "stock"));
            
            header("Location: index.php?status=created");
            exit; 
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors["name"] = "Nama produk sudah terdaftar (harus unik).";
            } else {
                $errors["db"] = "Kesalahan sistem database.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <div class="form-card">
            <div class="form-header">
                <h2>Tambah Produk</h2>
                <p>Masukkan detail produk baru di bawah ini.</p>
            </div>
            
            <form method="POST" class="crud-form">
                <?php if (isset($errors['db'])) echo "<div class='alert alert-danger'>{$errors['db']}</div>"; ?>
                
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" placeholder="Mis: Sepatu Sneakers" value="<?= htmlspecialchars($name, ENT_QUOTES) ?>" required>
                    <?php if (isset($errors['name'])) echo "<span class='error-text'>{$errors['name']}</span>"; ?>
                </div>

                <div class="form-group">
                    <label for="category">Kategori</label>
                    <input type="text" id="category" name="category" placeholder="Mis: Sepatu" value="<?= htmlspecialchars($category, ENT_QUOTES) ?>" required>
                    <?php if (isset($errors['category'])) echo "<span class='error-text'>{$errors['category']}</span>"; ?>
                </div>

                <div class="form-group">
                    <label for="price">Harga (Rp)</label>
                    <input type="number" id="price" name="price" placeholder="Mis: 150000" value="<?= $price ? htmlspecialchars((string)$price, ENT_QUOTES) : '' ?>" min="1" required>
                    <?php if (isset($errors['price'])) echo "<span class='error-text'>{$errors['price']}</span>"; ?>
                </div>

                <div class="form-group">
                    <label for="stock">Stok Awal</label>
                    <input type="number" id="stock" name="stock" placeholder="Mis: 10" value="<?= htmlspecialchars((string)$stock, ENT_QUOTES) ?>" min="0" required>
                    <?php if (isset($errors['stock'])) echo "<span class='error-text'>{$errors['stock']}</span>"; ?>
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>