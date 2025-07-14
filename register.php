<?php
require_once 'db.php';

$name = $email = $password = $confirm_password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validations
    if (empty($name)) $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email already registered.";
        }
    }

    // Insert user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $hashed_password])) {
            header("Location: login.php?registered=1");
            exit;
        } else {
            $errors[] = "Registration failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register | eLibrary</title>
  <style>
body {
  font-family: Arial, sans-serif;
  background-color:peachpuff;
  padding: 0;
  margin: 0;
  display: flex;
  justify-content: center;
}
  .form-container {
  width: 100%;
  max-width: 400px;
   background: #fdf6f0;  

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

.form-container input {
  width: 100%;
  padding: 12px;
  background:rgb(255, 255, 255);
  margin: 10px 0;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
}

.form-container button {
  width: 100%;
  padding: 12px;
  background: #ff8c42;
  color: white;
  font-weight: bold;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.form-container button:hover {
  background: #e06b19;
}

.error-box {
  background: #ffe2e2;
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 5px;
  color: #b30000;
}
    .error-box ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .error-box li {
        margin: 5px 0;
    }
    
    .form-container p {
        text-align: center;
        margin-top: 15px;
    }
    
    .form-container p a {
        color: #ff8c42;
        text-decoration: none;
    }
    
    .form-container p a:hover {
        text-decoration: underline;
    }
    </style>

</head>
<body>
  <div class="form-container">
    <h2>User Registration</h2>
    
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
      <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($name) ?>" required>
      <input type="email" name="email" placeholder="Email Address" value="<?= htmlspecialchars($email) ?>" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
</body>
</html>
