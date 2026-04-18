<footer class="footer">
    <div class="container">
        <h3>Клуб активного отдыха «ЮРМА»</h3>
        
        <div class="footer-content">
            <!-- Левая колонка: Логотип + О нас -->
            <div class="footer-info">
                <!-- <a href="<?php echo home_url('/'); ?>" class="logo">
                    <img src="<?php echo get_template_directory_uri(); ?>/resource/img/logo (1).svg" alt="Логотип">
                </a> -->
                <div class="footer-about">
                    <p>Прокат квадроциклов и снегоходов в горах Южного Урала. Более 10 лет показываем гостям настоящую природу.</p>
                </div>
            </div>
            
            <!-- Средняя колонка: Меню -->
            <ul class="footer-menu">
                <div class="menu-col">
                    <li><a href="<?php echo home_url(); ?>">Главная</a></li>
                    <li><a href="<?php echo home_url('/проживание/'); ?>">Проживание</a></li>
                    <li><a href="<?php echo home_url('/услуги/'); ?>">Услуги</a></li>
                    <li><a href="<?php echo home_url('/маршруты/'); ?>">Экскурсии</a></li> 
                </div>
                <div class="menu-col">
                    <li><a href="<?php echo home_url('/галерея/'); ?>">Галерея</a></li>
                    <li><a href="<?php echo home_url('/отзывы/'); ?>">Отзывы</a></li>
                    <li><a href="<?php echo home_url('/о-нас/'); ?>">О нас</a></li>
                </div>
            </ul>
            
            <!-- Правая колонка: Контакты -->
            <div class="footer-contacts">
                <a href="tel:+79227150546" class="footer-phone">+7 (922) 715-05-46</a>
                <a href="mailto:yurma@active.ru" class="footer-email">yurma@active.ru</a>
                <div class="footer-social">
                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/resource/img/social-vk.svg" alt="ВК"></a>
                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/resource/img/social-tg.svg" alt="TG"></a>
                    <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/resource/img/social-wa.svg" alt="WA"></a>
                </div>
            </div>
        </div>
        
        <!-- Служебная -->
        <div class="footer-bottom">
            <p>© 2026 Клуб активного отдыха «ЮРМА»</p>
            <div class="second">
                <a href="#">Договор оферты</a>     
                <span class="separator">|</span>
                <a href="#">Политика конфиденциальности</a>
            </div>
        </div>
    </div>
</footer>