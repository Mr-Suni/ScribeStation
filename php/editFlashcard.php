<?php
session_start();
include 'config.php';


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['success' => false, 'error' => 'Access denied: User not logged in']);
    exit;
}

$flashcardId = isset($_POST['flashcardId']) ? (int)$_POST['flashcardId'] : 0;
$question = isset($_POST['question']) ? trim($_POST['question']) : '';
$answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
$isImportant = isset($_POST['isImportant']) && $_POST['isImportant'] === 'true' ? 1 : 0;

error_log("Received update for Flashcard ID $flashcardId: Question - $question, Answer - $answer, Important - $isImportant");


$stmt = $conn->prepare("UPDATE flashcard SET Question = ?, Answer = ?, IsImportant = ? WHERE FlashcardID = ? AND UserID = ?");
$stmt->bind_param("ssiii", $question, $answer, $isImportant, $flashcardId, $_SESSION['id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
    error_log("Flashcard ID $flashcardId updated successfully.");
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update flashcard']);
    error_log("Failed to update Flashcard ID $flashcardId: " . $stmt->error);
}


$stmt->close();
$conn->close();
?>
