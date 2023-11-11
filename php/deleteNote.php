<?php
session_start();
include 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

if(!isset($_POST['noteId'])) {
    echo json_encode(['error' => 'No note ID provided']);
    exit;
}

$noteId = $_POST['noteId'];
$userId = $_SESSION['id'];

$stmt = $conn->prepare("DELETE FROM Note WHERE NoteID = ? AND UserID = ?");
$stmt->bind_param("ii", $noteId, $userId);
if($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to delete note']);
}

$stmt->close();
$conn->close();
?>
