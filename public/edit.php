<?php
require_once '../config/db.php';

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data existing
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(["id" => $id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit;
}

$errors = [];
$name = $product['name'];
$category = $product['category'];
$price = $product['price'];
$stock = $product['stock'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $update_id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $name = trim($_POST["name"] ?? "");
    $category = trim($_POST["category"] ?? "Umum");
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, "stock", FILTER_VALIDATE_INT);

    if (mb_strlen($name) < 3) $errors["name"] = "Nama minimal 3 karakter.";
    if ($price === false || $price <= 0) $errors["price"] = "Harga harus > 0.";
    if ($stock === false || $stock < 0) $errors["stock"] = "Stok tidak boleh negatif.";
    
    if (empty($errors)) {
        try {
            $updateStmt = $pdo->prepare("UPDATE products SET name=:name, category=:category, price=:price, stock=:stock WHERE id=:id");
            $updateStmt->execute(compact("name", "category", "price", "stock") + ["id" => $update_id]);
            header("Location: index.php?status=updated");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) $errors["name"] = "Nama produk sudah terdaftar (harus unik).";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <h2>Edit Produk</h2>
        <form method="POST" class="crud-form">
            <input type="hidden" name="id" value="<?= $id ?>">
            
            <label for="name">Nama Produk</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES) ?>" required>
            <?php if (isset($errors['name'])) echo "<span class='error-text'>{$errors['name']}</span>"; ?>

            <label for="category">Kategori</label>
            <input type="text" id="category" name="category" value="<?= htmlspecialchars($category, ENT_QUOTES) ?>" required>

            <label for="price">Harga</label>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars((string)$price, ENT_QUOTES) ?>" min="1" required>
            <?php if (isset($errors['price'])) echo "<span class='error-text'>{$errors['price']}</span>"; ?>

            <label for="stock">Stok</label>
            <input type="number" id="stock" name="stock" value="<?= htmlspecialchars((string)$stock, ENT_QUOTES) ?>" min="0" required>
            <?php if (isset($errors['stock'])) echo "<span class='error-text'>{$errors['stock']}</span>"; ?>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-warning">Update</button>
                <a href="index.php" class="btn" style="background:#cbd5e1; color:black;">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>