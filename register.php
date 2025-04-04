<?php
require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    $result = pg_query($db, $query);

    if ($result) {
      echo "Registration successful! <a href='login.php'>Login here</a>";
  } else {
      echo "Error: " . pg_last_error($db);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>Register</h2>
    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
    <p class="center_text">Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>