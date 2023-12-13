<?php
session_start();
include 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$flashcardId = $_POST['flashcardId'] ?? 0;
$question = $_POST['question'] ?? '';
$answer = $_POST['answer'] ?? '';
$userId = $_SESSION['id'];

$stmt = $conn->prepare("UPDATE flashcard SET Question = ?, Answer = ? WHERE FlashcardID = ? AND UserID = ?");
$stmt->bind_param("ssii", $question, $answer, $flashcardId, $userId);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update flashcard']);
}
$stmt->close();
$conn->close();
?>
