<?php
session_start();
include 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    exit('Access denied');
}

$flashcardId = $_POST['flashcardId'] ?? 0;

$stmt = $conn->prepare("DELETE FROM flashcard WHERE FlashcardID = ? AND UserID = ?");
$stmt->bind_param("ii", $flashcardId, $_SESSION['id']);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete flashcard']);
}
$stmt->close();
$conn->close();
?>
