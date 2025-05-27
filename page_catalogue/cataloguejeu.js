function openPopup(gameId) {
    const modal = document.getElementById(`modal-${gameId}`);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden'; // Empêche le scroll en arrière-plan
    }
}

function closePopup(gameId) {
    const modal = document.getElementById(`modal-${gameId}`);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto'; // Réactive le scroll
    }
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const activeModal = document.querySelector('.modal-overlay.active');
        if (activeModal) {
            activeModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }
});
