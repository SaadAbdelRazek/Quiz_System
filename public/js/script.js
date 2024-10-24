document.getElementById('menuToggle').addEventListener('click', function() {
    document.getElementById('navList').classList.toggle('show');
});

// ------------------------------------------
// Select the dropdown toggle element
const userDropdown = document.getElementById('userDropdown');
const dropdownMenu = document.getElementById('dropdownMenu');

// Toggle the visibility of the dropdown menu when the username is clicked
userDropdown.addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior
    dropdownMenu.classList.toggle('show'); // Toggle 'show' class
});

// Hide the dropdown menu if the user clicks outside of it
document.addEventListener('click', function(event) {
    if (!userDropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.remove('show');
    }
});

