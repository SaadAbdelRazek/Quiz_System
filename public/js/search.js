function searchQuizzes() {
    const query = document.getElementById('quizSearch').value.toLowerCase();
    const cards = document.querySelectorAll('.quiz-card');

    cards.forEach(card => {
        const title = card.getAttribute('data-title');
        if (title.includes(query)) {
            card.style.display = 'block'; // Show matching cards
        } else {
            card.style.display = 'none'; // Hide non-matching cards
        }
    });

    // Show "View All" button if there are matching cards
    const visibleCards = Array.from(cards).filter(card => card.style.display === 'block');
    document.getElementById('viewAllToggle').style.display = visibleCards.length > 6 ? 'block' : 'none';
}

// Optional: Toggle to show all quizzes when "View All" is clicked
document.getElementById('viewAllToggle')?.addEventListener('click', function() {
    const cards = document.querySelectorAll('.quiz-card');
    cards.forEach(card => {
        card.style.display = 'block'; // Show all cards
    });
    this.style.display = 'none'; // Hide the "View All" button
});
