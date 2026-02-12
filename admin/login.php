<?php
session_start();
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Admins WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();

    if (password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid password.";
    }
} else {
    $error = "Admin not found.";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body class="auth-body">

<div class="auth-card">
  <h1>Candescént</h1>
  <p class="subtitle">Admin Portal</p>

  <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

  <form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
</div>

</body>
</html>
