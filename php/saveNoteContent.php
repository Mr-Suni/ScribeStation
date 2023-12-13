<?php
session_start();
include 'config.php';


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

if (!isset($_POST['noteId'], $_POST['content'])) {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$noteId = $_POST['noteId'];
$content = $_POST['content'];
$userId = $_SESSION['id'];


$stmt = $conn->prepare("UPDATE Note SET Content = ? WHERE NoteID = ? AND UserID = ?");
$stmt->bind_param("sii", $content, $noteId, $userId);
if ($stmt->execute()) {
    echo json_encode(['success' => 'Note saved']);
} else {
    echo json_encode(['error' => 'Failed to save note']);
}
$stmt->close();
$conn->close();
?>