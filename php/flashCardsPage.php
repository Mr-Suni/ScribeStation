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

<div class="flashcard-container">
    <div class="flashcard">
      <div class="front card-side">
        <div class="card-heading">Question:</div>
        <div class="card-content">Where is the moon?</div>
        <div class="card-actions">
          <button class="flip-button">Flip</button>
          <span class="material-icons options-icon" onclick="toggleOptions(this)">more_vert</span>
          
          <div class="card-options">
            <div class="option-item" onclick="markAsImportant(this)">Mark as Important</div>
            <div class="option-item" onclick="editFlashcard(this)">Edit</div>
            <div class="option-item" onclick="deleteFlashcard(this)">Delete</div>
          </div>
        </div>
      </div>
      <div class="back card-side">
        <div class="card-heading">Answer:</div>
        <div class="card-content">In space, orbiting the Earth.</div>
        <div class="card-actions">
          <button class="flip-button">Flip</button>
          <span class="material-icons options-icon" onclick="toggleOptions(this)">more_vert</span>
        
          <div class="card-options">
            <div class="option-item" onclick="markAsImportant(this)">Mark as Important</div>
            <div class="option-item" onclick="editFlashcard(this)">Edit</div>
            <div class="option-item" onclick="deleteFlashcard(this)">Delete</div>
          </div>
        </div>
      </div>
    </div>
  </div>















  <script src="../assets/js/flashCardsPage.js"></script>
</body>

</html>