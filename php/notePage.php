<?php
session_start();
include 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$UserID = $_SESSION['id'] ?? 0;

$notes = [];
if ($UserID > 0) {
    $stmt = $conn->prepare("SELECT NoteID, Title, Content FROM Note WHERE UserID = ? AND IsArchived = FALSE ORDER BY NoteID DESC");
    $stmt->bind_param("i", $UserID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_note_title'], $_POST['new_note_content'])) {
    $newNoteTitle = filter_var(trim($_POST['new_note_title']), FILTER_SANITIZE_STRING);
    $newNoteContent = filter_var(trim($_POST['new_note_content']), FILTER_SANITIZE_STRING);

    $stmt = $conn->prepare("INSERT INTO Note (UserID, Title, Content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $UserID, $newNoteTitle, $newNoteContent);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$conn->close();
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
    <link rel="stylesheet" href="../assets/css/notePage.css" />
    <title>Notes</title>
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
        <a href="flashCardsPage.php">FlashCards</a>
        <a href="#tasks">Tasks</a>
        <a href="#study-tips">Study Tips</a>
        <a href="#archive">Archive</a>
    </div>

    <div class="note-page-container">
        <div class="sidebar">
            <div class="sidebar-header">
                Notes
            </div>
            <ul>
                <?php foreach ($notes as $note) : ?>
                    <li class="note-item" data-noteid="<?= $note['NoteID'] ?>">
                        <span onclick="fetchNoteContent(<?= $note['NoteID'] ?>)">
                            <?= htmlspecialchars($note['Title']) ?>
                        </span>
                        <span class="material-icons save-note-btn" onclick="saveNoteContent(<?= $note['NoteID'] ?>); event.stopPropagation();">save</span>
                        <span class="material-icons delete-note-btn" onclick="deleteNote(<?= $note['NoteID'] ?>); event.stopPropagation();">delete_outline</span>
                    </li>
                <?php endforeach; ?>
            </ul>




        </div>

        <div class="note-taking-section">
            <div class="note-toolbar">
                <button class="format_bold"><span class="material-icons">format_bold</span></button>
                <button class="format_italic"><span class="material-icons">format_italic</span></button>
                <button class="insert_link"><span class="material-icons">link</span></button>
                <button class="format_list_bulleted"><span class="material-icons">format_list_bulleted</span></button>
                <button class="format_list_numbered"><span class="material-icons">format_list_numbered</span></button>
                <button class="format_quote"><span class="material-icons">format_quote</span></button>
                <button class="highlight"><span class="material-icons">highlight</span></button>
                <button class="insert_photo"><span class="material-icons">insert_photo</span></button>
                <button class="format_header1"><span class="material-icons">title</span></button>
                <button class="format_header2"><span class="material-icons">subtitles</span></button>
                <button class="underline"><span class="material-icons">format_underlined</span></button>
                <button class="justify_left"><span class="material-icons">format_align_left</span></button>
                <button class="justify_center"><span class="material-icons">format_align_center</span></button>
                <button class="justify_right"><span class="material-icons">format_align_right</span></button>
                <button class="settings"><span class="material-icons">settings</span></button>
            </div>
            <div class="note-editor" contenteditable="true">

            </div>
        </div>
    </div>

    <div class="create-note-button">
        <button id="createNote">+</button>
    </div>

    <form id="newNoteForm" method="POST" action="notePage.php" style="display:none; position: fixed; bottom: 70px; right: 20px;">
        <input type="text" name="new_note_title" placeholder="Note Title">
        <textarea name="new_note_content" placeholder="Type your new note here..."></textarea>
        <button type="submit">Save Note</button>
    </form>




    <script src="../assets/js/notePage.js"></script>
    <script>
        document.getElementById('createNote').addEventListener('click', function() {
            var form = document.getElementById('newNoteForm');
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
        });
    </script>


</body>

</html>