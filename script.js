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

// ===== БУРГЕР-МЕНЮ =====
document.addEventListener('DOMContentLoaded', function() {
    const burger = document.querySelector('.burger-menu');
    const navWrapper = document.querySelector('.nav-wrapper');
    const body = document.body;
    
    if (burger && navWrapper) {
        burger.addEventListener('click', function(e) {
            e.stopPropagation();
            burger.classList.toggle('active');
            navWrapper.classList.toggle('active');
            body.classList.toggle('no-scroll');
        });
        
        // Закрытие меню при клике на ссылку
        const navLinks = navWrapper.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                burger.classList.remove('active');
                navWrapper.classList.remove('active');
                body.classList.remove('no-scroll');
            });
        });
        
        // Закрытие меню при клике вне его
        document.addEventListener('click', function(event) {
            if (!navWrapper.contains(event.target) && !burger.contains(event.target) && navWrapper.classList.contains('active')) {
                burger.classList.remove('active');
                navWrapper.classList.remove('active');
                body.classList.remove('no-scroll');
            }
        });
    }
});
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';  // Вместо 'block'
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
    }
};

// FAQ
document.addEventListener('DOMContentLoaded', function() {
    var faqItems = document.querySelectorAll('.faq-item');
    
    for (var i = 0; i < faqItems.length; i++) {
        var question = faqItems[i].querySelector('.faq-question');
        
        question.addEventListener('click', function(e) {
            var parent = this.parentElement;
            parent.classList.toggle('active');
        });
    }
});

// Управление header - простая и надёжная версия
document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('header');
    let lastScrollY = window.scrollY;
    let hoverTimeout;
    
    // Скрываем при скролле вниз, показываем при скролле вверх
    window.addEventListener('scroll', function() {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > lastScrollY && currentScrollY > 100) {
            // Скролл вниз - скрываем
            header.style.transform = 'translateY(-100%)';
        } else if (currentScrollY < lastScrollY) {
            // Скролл вверх - показываем
            header.style.transform = 'translateY(0)';
        }
        
        // В самом верху всегда показываем
        if (currentScrollY < 50) {
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollY = currentScrollY;
    });
    
    // При наведении в верхние 80px - показываем
    document.addEventListener('mousemove', function(e) {
        if (e.clientY < 80) {
            header.style.transform = 'translateY(0)';
            
            clearTimeout(hoverTimeout);
            hoverTimeout = setTimeout(function() {
                if (window.scrollY > 100) {
                    header.style.transform = 'translateY(-100%)';
                }
            }, 2000);
        }
    });
});