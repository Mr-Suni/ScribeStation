<?php
session_start();
include 'config.php';


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}


if(!isset($_GET['noteId'])) {
    echo json_encode(['error' => 'No note ID provided']);
    exit;
}

$noteId = $_GET['noteId'];
$userId = $_SESSION['id'];

$stmt = $conn->prepare("SELECT Content FROM Note WHERE NoteID = ? AND UserID = ? AND IsArchived = FALSE");
$stmt->bind_param("ii", $noteId, $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    echo json_encode(['content' => $row['Content']]);
} else {
    echo json_encode(['error' => 'Note not found']);
}

$stmt->close();
$conn->close();
