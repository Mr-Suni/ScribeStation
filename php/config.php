<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "ScribeStation";


$conn = new mysqli($server, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully to the database!";
}


$conn->close();
?>
