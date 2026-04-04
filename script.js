// ========== Модальное окно ==========
(function() {
    const modal = document.getElementById('orderModal');
    const closeBtn = document.querySelector('.modal-close');
    
    // Функция открытия
    window.openOrderModal = function() {
        if (modal) {
            modal.style.display = 'block';
        }
    };
    
    // Закрытие по крестику
    if (closeBtn) {
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        };
    }
    
    // Закрытие по клику вне окна
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
    
    // Закрытие по ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal && modal.style.display === 'block') {
            modal.style.display = 'none';
        }
    });
    
    // Автоматическое закрытие после отправки формы
    document.addEventListener('wpcf7mailsent', function(event) {
        setTimeout(function() {
            if (modal) {
                modal.style.display = 'none';
                const form = modal.querySelector('form');
                if (form) form.reset();
            }
        }, 2000);
    });
    // Защита от множественной отправки формы
document.addEventListener('wpcf7submit', function(event) {
    const form = event.detail.contactForm.el;
    const submitBtn = form.querySelector('input[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.value = 'Отправка...';
        
        // Разблокируем через 5 секунд на случай ошибки
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.value = 'Отправить';
        }, 5000);
    }
}, false);

// Очищаем старые ошибки перед новой отправкой
document.addEventListener('wpcf7invalid', function(event) {
    // Удаляем старые сообщения об ошибках
    const oldErrors = document.querySelectorAll('.wpcf7-not-valid-tip');
    oldErrors.forEach(function(error) {
        error.remove();
    });
});
})();