<?php
require_once 'db.php';
require_once 'auth.php';

// Check if user is admin
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['is_admin'] != 1) {
    die("Access denied. Admins only.");
}

// Handle delete
if (isset($_GET['delete'])) {
    $file = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM books WHERE filename = ?");
    $stmt->execute([$file]);

    $filepath = 'books/' . basename($file);
    if (file_exists($filepath)) {
        unlink($filepath);
    }

    header("Location: admin_panel.php");
    exit;
}

// Get all books
$stmt = $conn->query("SELECT * FROM books ORDER BY uploaded_at DESC");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel | eLibrary</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<style>
body {
  font-family: Arial, sans-serif;
  background-color: #fdf6f0;
  margin: 0;
  padding: 0;
}
.form-container {
  width: 100%;
  max-width: 600px;
  margin: 60px auto;
  padding: 30px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.form-container h2 {
  text-align: center;
  margin-bottom: 20px;
}
.form-container .btn {
  display: inline-block;
  background: #1e90ff;
  color: white;
  padding: 12px 20px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.3s;
}
.form-container .btn:hover {
  background: #005bbb;
}
.form-container .logout {
  background: #ff4d4d;
}
.form-container .logout:hover {
  background: #cc0000;
}
.book-list {
  list-style: none;
  padding: 0;
}
.book-list li {
  margin: 15px 0;
  padding: 15px;
  background: #f9f9f9;
  border-radius: 6px;
}
.book-list li strong {
  display: block;
  font-size: 1.2em;
}
.book-list li small {
  display: block;
  color: #666;
}
.book-list li a {
  margin-right: 10px;
  text-decoration: none;
}
.book-list li a.btn {
  background: #28a745;
}
.book-list li a.btn:hover {
  background: #218838;
}
</style>
<body>
  <div class="form-container">
    <h2>👑 Admin Panel: Book Management</h2>

    <p><a href="upload.php" class="btn">📤 Upload New Book</a></p>

    <?php if (count($books) > 0): ?>
      <ul class="book-list">
        <?php foreach ($books as $book): ?>
          <li>
            <strong><?= htmlspecialchars($book['title']) ?></strong><br>
            <small>by <?= htmlspecialchars($book['author']) ?></small><br>
            <small>Downloads: <?= $book['download_count'] ?></small><br>
            <a href="books/<?= htmlspecialchars($book['filename']) ?>" target="_blank" class="btn">📄 View</a>
            <a href="download.php?file=<?= urlencode($book['filename']) ?>" class="btn">⬇️ Download</a>
            <a href="admin_panel.php?delete=<?= urlencode($book['filename']) ?>" class="btn logout" onclick="return confirm('Are you sure you want to delete this book?')">🗑️ Delete</a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No books found.</p>
    <?php endif; ?>

    <br>
    <a href="dashboard.php" class="btn">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
