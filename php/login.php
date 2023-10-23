<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password"])) {
            header("Location: /ScribeStation/notePage.html");
            exit;
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Username not found!";
    }

    $stmt->close();
    $conn->close();
}
?>
