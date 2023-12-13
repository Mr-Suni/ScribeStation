<?php
session_start();
include 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

$flashcardId = isset($_POST['flashcardId']) ? (int)$_POST['flashcardId'] : 0;
$isImportant = isset($_POST['isImportant']) ? $_POST['isImportant'] : 'false';

$stmt = $conn->prepare("UPDATE flashcard SET IsImportant = ? WHERE FlashcardID = ? AND UserID = ?");
$isImportantFlag = $isImportant === 'true' ? 1 : 0;
$stmt->bind_param("iii", $isImportantFlag, $flashcardId, $_SESSION['id']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update important status']);
}
$stmt->close();
$conn->close();
?>
