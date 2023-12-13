/*________________________________________________ */
/* tool bar button functionality:   ---------------------------------------------------- */
function toggleBold() {
    document.execCommand('bold', false, '');
}

function toggleItalic() {
    document.execCommand('italic', false, '');
}

function insertLink() {
    var url = prompt("Enter the URL");
    document.execCommand('createLink', false, url);
}

function toggleBulletedList() {
    document.execCommand('insertUnorderedList', false, '');
}

function toggleNumberedList() {
    document.execCommand('insertOrderedList', false, '');
}

function toggleQuote() {
    document.execCommand('formatBlock', false, 'blockquote');
}

function toggleHighlight() {
    // not compatible with all browsers :(
    var color = prompt("Enter a color code or name, e.g., yellow or #FFFF00");
    document.execCommand('hiliteColor', false, color);
}

function insertImage() {
    var imageUrl = prompt("Enter the image URL");
    document.execCommand('insertImage', false, imageUrl);
}

function formatHeader(headerSize) {
    document.execCommand('formatBlock', false, headerSize);
}

function toggleUnderline() {
    document.execCommand('underline', false, '');
}

function justifyLeft() {
    document.execCommand('justifyLeft', false, '');
}

function justifyCenter() {
    document.execCommand('justifyCenter', false, '');
}

function justifyRight() {
    document.execCommand('justifyRight', false, '');
}

function insertImage() {
    var imageUrl = prompt("Enter the image URL");
    if (imageUrl) {
        var img = new Image();
        img.src = imageUrl;
        img.style.maxWidth = '200px';
        img.style.height = 'auto';
        document.execCommand('insertHTML', false, img.outerHTML);
    }
}


function toggleActiveTool(button) {

    document.querySelectorAll('.note-toolbar button').forEach(function (btn) {
        btn.classList.remove('active');
    });

    button.classList.add('active');
}


document.querySelectorAll('.note-toolbar button').forEach(function (button) {
    button.addEventListener('click', function () {
        toggleActiveTool(this);
    });
});



document.querySelector('.format_bold').addEventListener('click', toggleBold);
document.querySelector('.format_italic').addEventListener('click', toggleItalic);
document.querySelector('.insert_link').addEventListener('click', insertLink);
document.querySelector('.format_list_bulleted').addEventListener('click', toggleBulletedList);
document.querySelector('.format_list_numbered').addEventListener('click', toggleNumberedList);
document.querySelector('.format_quote').addEventListener('click', toggleQuote);
document.querySelector('.highlight').addEventListener('click', toggleHighlight);
document.querySelector('.insert_photo').addEventListener('click', insertImage);
document.querySelector('.format_header1').addEventListener('click', function () { formatHeader('h1'); });
document.querySelector('.format_header2').addEventListener('click', function () { formatHeader('h2'); });
document.querySelector('.underline').addEventListener('click', toggleUnderline);
document.querySelector('.justify_left').addEventListener('click', justifyLeft);
document.querySelector('.justify_center').addEventListener('click', justifyCenter);
document.querySelector('.justify_right').addEventListener('click', justifyRight);
document.querySelector('.insert_photo').addEventListener('click', insertImage);

/*________________________________________________ */
/* save, delete, fetch functionality:   ---------------------------------------------------- */

document.querySelectorAll('.note-item').forEach(function (noteItem) {
    noteItem.addEventListener('click', function () {
        var noteId = this.getAttribute('data-noteid');
        fetchNoteContent(noteId);
    });
});


function fetchNoteContent(noteId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'getNoteContent.php?noteId=' + noteId, true);
    xhr.onload = function () {
        if (this.status === 200) {
            var response = JSON.parse(this.responseText);
            console.log(response);
            if (response.error) {
                console.error("Error fetching note: ", response.error);
            } else {
                var noteEditor = document.querySelector('.note-editor');
                noteEditor.innerHTML = response.content;
            }
        }
    };
    xhr.onerror = function () {
        console.error("Request failed");
    };
    xhr.send();
}


function deleteNote(event, noteId) {
    event.stopPropagation();

    if (confirm('Are you sure you want to delete this note?')) {

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'deleteNote.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status === 200) {
                var response = JSON.parse(this.responseText);
                if (response.success) {
                    event.target.closest('.note-item').remove();
                } else {
                    console.error('Error deleting note:', response.error);
                }
            }
        };
        xhr.onerror = function () {
            console.error("Request failed");
        };
        xhr.send('noteId=' + noteId);
    }
}

document.querySelectorAll('.delete-note-btn').forEach(function (deleteButton) {
    deleteButton.addEventListener('click', function (event) {
        var noteId = this.closest('.note-item').dataset.noteid;
        deleteNote(event, noteId);
    });
});

function saveNoteContent(noteId) {
    var content = document.querySelector('.note-editor').innerHTML;

    if (!confirm("Are you sure you want to save changes to this note?")) {
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'saveNoteContent.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status === 200) {
            var response = JSON.parse(this.responseText);
            if (response.success) {
                alert('Note saved successfully!');
            } else {
                alert('Error saving note: ' + response.error);
            }
        } else {
            alert('An error occurred while saving the note. Status: ' + this.status);
        }
    };
    xhr.onerror = function () {
        alert("Request failed to reach the server. Please check the server and file path.");
    };
    xhr.send('noteId=' + encodeURIComponent(noteId) + '&content=' + encodeURIComponent(content));
}

