<?php
require_once 'db.php';
require_once 'auth.php'; // user must be logged in

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $file = $_FILES['book_pdf'];

    // Check fields
    if (empty($title) || empty($author)) {
        $errors[] = "Title and Author are required.";
    }

    // Validate and move file
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['application/pdf'];
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = "Only PDF files are allowed.";
        }

        $filename = basename($file['name']);
        $targetPath = 'books/' . $filename;

        if (empty($errors)) {
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Insert into DB
                $stmt = $conn->prepare("INSERT INTO books (title, author, filename) VALUES (?, ?, ?)");
                if ($stmt->execute([$title, $author, $filename])) {
                    $success = "Book uploaded successfully!";
                } else {
                    $errors[] = "Failed to save book info.";
                }
            } else {
                $errors[] = "Failed to upload file.";
            }
        }
    } else {
        $errors[] = "Please select a PDF file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Upload Book | eLibrary</title>

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
  max-width: 500px;
  margin: 60px auto;
  padding: 30px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  text-align: center;
}
.form-container h2 {
  margin-bottom: 20px;
}
.form-container input[type="text"],
.form-container input[type="file"] {
  width: calc(100% - 20px);
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 5px;
  border: 1px solid #ccc;
}
.form-container button {
  width: 100%;
  padding: 12px;
  background: #1e90ff;
  color: white;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.form-container button:hover {
  background: #005bbb;
}
.success-box, .error-box {
  padding: 10px;
  margin-bottom: 15px;
}
.success-box {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}
.error-box {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}
</style>
<body>
  <div class="form-container">
    <h2>📤 Upload New Book</h2>

    <?php if (!empty($success)): ?>
      <div class="success-box"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="error-box">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
      <input type="text" name="title" placeholder="Book Title" required>
      <input type="text" name="author" placeholder="Author Name" required>
      <input type="file" name="book_pdf" accept="application/pdf" required>
      <button type="submit">Upload Book</button>
    </form>

    <p><a href="dashboard.php" class="btn">⬅️ Back to Dashboard</a></p>
  </div>
</body>
</html>
