function flipCard(flashcard) {
    const frontSide = flashcard.querySelector('.front');
    const backSide = flashcard.querySelector('.back');
    frontSide.style.transform = frontSide.style.transform === 'rotateY(180deg)' ? 'rotateY(0deg)' : 'rotateY(180deg)';
    backSide.style.transform = backSide.style.transform === 'rotateY(0deg)' ? 'rotateY(180deg)' : 'rotateY(0deg)';
}

function toggleOptions(icon) {
    let optionsMenu = icon.nextElementSibling;
    optionsMenu.style.display = optionsMenu.style.display === 'block' ? 'none' : 'block';
}

function toggleImportant(flashcardId, isImportant) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'updateImportantStatus.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            let response = JSON.parse(this.responseText);
            if (!response.success) {
                console.error('Error updating important status:', response.error);
            }
        }
    };
    xhr.send(`flashcardId=${flashcardId}&isImportant=${isImportant}`);
}


function markAsImportant(bookmark, flashcardId) {
    let isImportant = bookmark.classList.toggle('visible');
    toggleImportant(flashcardId, isImportant);
}

function editFlashcard(contentDivs, editButton, flashcardId, flashcard) {
    let isEditable = contentDivs[0].isContentEditable;
    contentDivs.forEach(div => div.contentEditable = !isEditable);

    if (!isEditable) {
        editButton.textContent = 'Save';
    } else {
        let question = contentDivs[0].innerText;
        let answer = contentDivs[1].innerText;
        let isImportant = flashcard.querySelector('.bookmark').classList.contains('visible');

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'editFlashcard.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status === 200) {
                let response = JSON.parse(this.responseText);
                if (response.success) {
                    editButton.textContent = 'Edit';
                    contentDivs.forEach(div => div.contentEditable = false);
                } else {
                    console.error('Error saving flashcard:', response.error);
                }
            }
        };
        xhr.send(`flashcardId=${flashcardId}&question=${encodeURIComponent(question)}&answer=${encodeURIComponent(answer)}&isImportant=${isImportant}`);
    }
}

function deleteFlashcardFromServer(flashcardId, flashcard) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'deleteFlashcard.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            let response = JSON.parse(this.responseText);
            if (response.success) {
                flashcard.remove();
            } else {
                console.error('Error deleting flashcard:', response.error);
            }
        }
    };
    xhr.send('flashcardId=' + flashcardId);
}

document.addEventListener('click', function (event) {
    let target = event.target;
    let flashcard = target.closest('.flashcard');

    if (flashcard) {
        let flashcardId = flashcard.getAttribute('data-flashcardid');

        if (target.classList.contains('flip-button')) {
            flipCard(flashcard);
        } else if (target.classList.contains('options-icon')) {
            toggleOptions(target);
        } else if (target.classList.contains('mark-important')) {
            let bookmark = flashcard.querySelector('.bookmark');
            if (bookmark) {
                markAsImportant(bookmark, flashcardId);
            }
        } else if (target.classList.contains('edit-flashcard')) {
            let contentDivs = flashcard.querySelectorAll('.card-content');
            editFlashcard(contentDivs, target, flashcardId, flashcard);
        } else if (target.classList.contains('delete-flashcard')) {
            if (confirm('Are you sure you want to delete this flashcard?')) {
                deleteFlashcardFromServer(flashcardId, flashcard);
            }
        }
    }

    if (!target.matches('.options-icon')) {
        let dropdowns = document.getElementsByClassName('card-options');
        for (let i = 0; i < dropdowns.length; i++) {
            let openDropdown = dropdowns[i];
            if (openDropdown.style.display === 'block') {
                openDropdown.style.display = 'none';
            }
        }
    }
});

document.getElementById('createFlashcard').addEventListener('click', function () {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'createFlashcard.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            let response = JSON.parse(this.responseText);
            if (response.success && response.flashcardId) {
                addFlashcardToDOM(response.flashcardId);
            } else {
                console.error('Error creating flashcard:', response.error);
            }
        }
    };
    xhr.send('question=Type question...&answer=Type answer...');
});

function addFlashcardToDOM(flashcardId) {
    let container = document.querySelector('.flashcard-container');
    let newFlashcard = document.createElement('div');
    newFlashcard.className = 'flashcard';
    newFlashcard.setAttribute('data-flashcardid', flashcardId);
    newFlashcard.innerHTML = `
        <span class="bookmark"></span>
        <div class="front card-side">
            <div class="card-heading">Question:</div>
            <div class="card-content" contenteditable="true">Type question...</div>
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
            <div class="card-content" contenteditable="true">Type answer...</div>
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
    `;
    container.appendChild(newFlashcard);
}
