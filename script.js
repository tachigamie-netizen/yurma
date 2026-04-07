// ========== УНИВЕРСАЛЬНОЕ УПРАВЛЕНИЕ МОДАЛЬНЫМИ ОКНАМИ ==========
(function() {
    // Функция открытия модального окна по ID
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
        }
    };
    
    // Функция закрытия модального окна
    window.closeModal = function(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'none';
        }
    };
    
    // Закрытие по крестику для всех модальных окон
    document.addEventListener('click', function(e) {
        if (e.target.classList && e.target.classList.contains('modal-close')) {
            const modal = e.target.closest('.modal');
            if (modal) {
                modal.style.display = 'none';
            }
        }
    });
    
    // Закрытие по клику вне модального окна
    document.addEventListener('click', function(e) {
        if (e.target.classList && e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });
    
    // Закрытие по клавише ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                if (modal.style.display === 'block') {
                    modal.style.display = 'none';
                }
            });
        }
    });
})();

// ========== ОТКРЫТИЕ КОНКРЕТНЫХ МОДАЛЬНЫХ ОКОН ==========
function openOrderModal() {
    openModal('orderModal');
}

function openReviewModal() {
    openModal('reviewModal');
}

// ========== ЗАЩИТА ОТ МНОЖЕСТВЕННОЙ ОТПРАВКИ ФОРМЫ ==========
document.addEventListener('wpcf7submit', function(event) {
    const form = event.detail.contactForm.el;
    const submitBtn = form.querySelector('input[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.value = 'Отправка...';
        
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.value = 'Отправить';
        }, 5000);
    }
}, false);

// Очищаем старые ошибки
document.addEventListener('wpcf7invalid', function(event) {
    const oldErrors = document.querySelectorAll('.wpcf7-not-valid-tip');
    oldErrors.forEach(function(error) {
        error.remove();
    });
});

// Закрытие модального окна после успешной отправки Contact Form 7
document.addEventListener('wpcf7mailsent', function(event) {
    const modal = document.getElementById('orderModal');
    if (modal) {
        setTimeout(function() {
            modal.style.display = 'none';
            const form = modal.querySelector('form');
            if (form) form.reset();
        }, 2000);
    }
});