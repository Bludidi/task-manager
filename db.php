<?php
session_start();
require_once 'config.php';

$db_host = "localhost";
$db_name = "task_manager";
$db_user = "postgres";
$db_password = DB_PASSWORD;
$db = pg_connect("host=$db_host dbname=$db_name user=$db_user password=$db_password");

if (!$db) {
    die("Connection failed: " . pg_last_error());
}
?>