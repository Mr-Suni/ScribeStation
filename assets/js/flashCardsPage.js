
function flipCard(flashcard) {
    const frontSide = flashcard.querySelector('.front');
    const backSide = flashcard.querySelector('.back');
  
    if (frontSide.style.transform === 'rotateY(180deg)') {
      frontSide.style.transform = 'rotateY(0deg)';
      backSide.style.transform = 'rotateY(180deg)';
    } else {
      frontSide.style.transform = 'rotateY(180deg)';
      backSide.style.transform = 'rotateY(0deg)';
    }
  }
  
  
  document.querySelectorAll('.flip-button').forEach(function(button) {
    button.addEventListener('click', function() {
      var flashcard = button.closest('.flashcard');
      flipCard(flashcard);
    });
  });
  



function toggleOptions(element) {
  let optionsMenu = element.nextElementSibling;
  optionsMenu.style.display = optionsMenu.style.display === 'block' ? 'none' : 'block';
}

function markAsImportant(element) {
  // Logic to mark a flashcard as important
  alert('Marked as important!');
}

function editFlashcard(element) {
  // Logic to edit a flashcard
  alert('Edit flashcard!');
}

function deleteFlashcard(element) {
  // Logic to delete a flashcard
  alert('Flashcard deleted!');
}
