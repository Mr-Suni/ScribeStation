<?php
session_start();
include 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    exit('Access denied');
}

$userId = $_SESSION['id'];
$question = 'Type question...';
$answer = 'Type answer...';

$stmt = $conn->prepare("INSERT INTO flashcard (UserID, Question, Answer) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $userId, $question, $answer);
if ($stmt->execute()) {
    $newId = $conn->insert_id;
    echo json_encode(['success' => true, 'flashcardId' => $newId]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to create flashcard']);
}
$stmt->close();
$conn->close();
?>
