<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$password = "";
$dbname = "lifeismess_db"; 

$con = mysqli_connect($host, $user, $password, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>