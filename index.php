<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

echo "Welcome to the Task Manager! User ID " . $_SESSION['user_id'];
?>