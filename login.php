<?php
require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];


  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = pg_query($db, $query);

  if ($result && pg_num_rows($result) > 0) {
    $user = pg_fetch_assoc($result); 
    
    if (password_verify($password, $user['password'])) {
        
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php"); 
        exit();
    } else {
        echo "Incorrect password!";
    }
} else {
    echo "Username not found!";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don’t have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>