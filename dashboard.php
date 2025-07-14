<?php
require_once 'db.php';
require_once 'auth.php'; // ensures session is active
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard | eLibrary</title>
 <style>
    .dashboard-links {
  margin-top: 30px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.btn {
  display: inline-block;
  background: #1e90ff;
  color: white;
  padding: 12px 20px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.3s;
  text-align: center;
}

.btn:hover {
  background: #005bbb;
}

.logout {
  background: #ff4d4d;
}

.logout:hover {
  background: #cc0000;
}
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
    
    .form-container p {
        margin-bottom: 30px;
    }
    </style>
</head>
<body>
  <div class="form-container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h2>
    <p>You have successfully logged in to your eLibrary dashboard.</p>

    <div class="dashboard-links">
      <a href="book_list.php" class="btn">📚 View Books</a>
      <a href="logout.php" class="btn logout"> Logout</a>
    </div>
  </div>
</body>
</html>
