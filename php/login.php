<?php
session_start(); 
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password"])) {
            // Set session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["id"] = $row["id"];

            header("Location: /ScribeStation/php/notePage.php"); 
            exit;
        }
    }

    header("Location: /ScribeStation/loginPage.html?error=true");
    exit;

    $stmt->close();
    $conn->close();
}
?>
