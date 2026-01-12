// OPEN AND CLOSE FOR ALL MODAL
// Get modal and buttons
const modal = document.getElementById('modalBox');
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');

// Open the modal when the "open" button is clicked
openModalBtn.onclick = function () {
    modal.classList.remove('hidden'); // Show the modal
}

// Close the modal when the "close" button is clicked
closeModalBtn.onclick = function () {
    modal.classList.add('hidden'); // Hide the modal
}

// Close the modal if the user clicks outside of the modal content
window.onclick = function (event) {
    if (event.target === modal) {
        modal.classList.add('hidden'); // Hide the modal
    }
}