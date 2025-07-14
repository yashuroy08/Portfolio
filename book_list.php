<?php
require_once 'db.php';
require_once 'auth.php';

$search = '';
$books = [];

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY uploaded_at DESC");
    $stmt->execute(["%$search%", "%$search%"]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $conn->query("SELECT * FROM books ORDER BY uploaded_at DESC");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Book List | eLibrary</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<style>
body {
  font-family: Arial, sans-serif;
  background-color: peachpuff;
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
.book-list {
  list-style: none;
  padding: 0;
}
.book-list li {
  margin-bottom: 15px;
  padding: 10px;
  background: #f9f9f9;
  border-radius: 5px;
}
.book-list li strong {
  display: block;
  font-size: 1.2em;
}
.book-list li small {
  color: #555;
}
.btn {
  display: inline-block;
  background: #1e90ff;
  color: white;
  padding: 10px 15px;
  border-radius: 5px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.3s;
}
.btn:hover {
  background: #005bbb;
}
</style>
<body>
  <div class="form-container">
    <h2>📚 Available Books</h2>

    <!-- 🔍 Search Form -->
    <form method="GET" action="book_list.php" style="margin-bottom: 20px;">
      <input type="text" name="search" placeholder="Search by title or author"
             value="<?= htmlspecialchars($search) ?>"
             style="padding: 10px; width: 250px; border-radius: 5px; border: 1px solid #ccc;">
      <button type="submit" class="btn">Search</button>
    </form>

    <!-- 📖 Book Results -->
    <?php if (count($books) > 0): ?>
      <ul class="book-list">
        <?php foreach ($books as $book): ?>
          <li>
            <strong><?= htmlspecialchars($book['title']) ?></strong><br>
            <small>by <?= htmlspecialchars($book['author']) ?></small><br>
            <a href="books/<?= htmlspecialchars($book['filename']) ?>" target="_blank" class="btn">📄 View</a>
            <a href="books/<?= htmlspecialchars($book['filename']) ?>" download class="btn">⬇️ Download</a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p>No books found<?= $search ? " for '$search'" : '' ?>.</p>
    <?php endif; ?>

    <br>
    <a href="dashboard.php" class="btn">⬅️ Back to Dashboard</a>
  </div>
</body>
</html>
