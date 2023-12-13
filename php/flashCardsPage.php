<?php
session_start();
include 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$UserID = $_SESSION['id'] ?? 0;

$flashcards = [];
$stmt = $conn->prepare("SELECT FlashcardID, Question, Answer, IsImportant FROM flashcard WHERE UserID = ?");
$stmt->bind_param("i", $UserID);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $flashcards[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <meta name="description" content="Sign Up to Scribe Station" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../assets/css/styles.css" />
    <link rel="stylesheet" href="../assets/css/flashCardsPage.css" />
    <title> Flashcards </title>
</head>

<body class="main-pages">
    <div class="header">
        <a href="../index.html">
            <img src="../assets/images/logo.png" alt="Scribe Station Logo" class="header-logo" />
        </a>
        <div class="icons">
            <span class="material-icons">fullscreen</span>
            <span class="material-icons">dark_mode</span>
            <span class="material-icons">share</span>
            <span class="material-icons">timer</span>
            <span class="material-icons">person</span>
        </div>
    </div>
    <div class="navbar">
        <a href="#dashboard">Dashboard</a>
        <a href="#community">Study Hub</a>
        <a href="#calendar">Calendar</a>
        <a href="notePage.php">Notes</a>
        <a class="active" href="flashCardsPage.php">FlashCards</a>
        <a href="#tasks">Tasks</a>
        <a href="#study-tips">Study Tips</a>
        <a href="#archive">Archive</a>
    </div>

    <div class="flashcard-container">
        <?php foreach ($flashcards as $flashcard) : ?>
            <div class="flashcard" data-flashcardid="<?php echo htmlspecialchars($flashcard['FlashcardID']); ?>">
                <span class="bookmark <?php echo $flashcard['IsImportant'] ? 'visible' : ''; ?>"></span>
                <div class="front card-side">
                    <div class="card-heading">Question:</div>
                    <div class="card-content"><?php echo htmlspecialchars($flashcard['Question']); ?></div>
                    <div class="card-actions">
                        <button class="flip-button">Flip</button>
                        <span class="material-icons options-icon">more_vert</span>
                        <div class="card-options" style="display: none;">
                            <div class="option-item mark-important">Mark as Important</div>
                            <div class="option-item edit-flashcard">Edit</div>
                            <div class="option-item delete-flashcard">Delete</div>
                        </div>
                    </div>
                </div>
                <div class="back card-side">
                    <div class="card-heading">Answer:</div>
                    <div class="card-content"><?php echo htmlspecialchars($flashcard['Answer']); ?></div>
                    <div class="card-actions">
                        <button class="flip-button">Flip</button>
                        <span class="material-icons options-icon">more_vert</span>
                        <div class="card-options" style="display: none;">
                            <div class="option-item mark-important">Mark as Important</div>
                            <div class="option-item edit-flashcard">Edit</div>
                            <div class="option-item delete-flashcard">Delete</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button id="createFlashcard">+</button>

    <script src="../assets/js/flashCardsPage.js"></script>
</body>

</html>