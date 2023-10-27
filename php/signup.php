<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST["full-name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {    
        header("Location: /ScribeStation/signupPage.html?error=emailExists");
        exit;
    }
    
    $stmt = $conn->prepare("INSERT INTO users (full_name, username, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $username, $email, $password);
    if($stmt->execute()) {
        header("Location: /ScribeStation/loginPage.html?signup=success");
        exit();
    } else {
        header("Location: /ScribeStation/signupPage.html?error=unknownError");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
