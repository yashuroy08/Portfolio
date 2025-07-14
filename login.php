<?php
require_once 'db.php';
session_start();

$email = $password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login | eLibrary</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        background-color:peachpuff;
        margin: 0;
        padding: 0;
        }
        .form-container {
        width: 100%;
        max-width: 400px;
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
        .form-container input[type="email"],
        .form-container input[type="password"] {
        width: calc(100% - 20px);
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #ccc;
        }
        .form-container button {
        width: 100%;
        padding: 10px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        }
        .form-container button:hover {
        background-color: #218838;
        }
        .error-box, .success-box {
        padding: 10px;
        margin-bottom: 15px;
        }
        .error-box {
        background-color: #f8d7da;
        color: #721c24;
        }
        .success-box {
        background-color: #d4edda;
        color: #155724;
        }
        a {
        color: #007bff;
        }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>User Login</h2>

    <?php if (isset($_GET['registered'])): ?>
      <div class="success-box">Registration successful. Please login.</div>
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

    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email Address" value="<?= htmlspecialchars($email) ?>" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>
</body>
</html>
