<footer class="footer">
    <div class="container">
        <!-- Информ -->
        <div class="footer_content">
 
            <div class="footer_about">
                <div class="footer_logo">
                <a href="<?php echo home_url('/'); ?>" class="logo footer">
                <img src="<?php echo get_template_directory_uri(); ?>/resource/img/logo 2.svg" alt="Логотип">
                </a></div>
                <div class="footer_text">
                <p>Прокат квадроциклов и снегоходов в горах Южного Урала.</p>
                </div>
            </div>

            <!-- Меню -->
            <ul class="footer_menu">
                <div class="menu_col">
                    <li><a href="<?php echo home_url(); ?>">Главная</a></li>
                    <li><a href="<?php echo home_url('/проживание/'); ?>">Проживание</a></li>
                    <li><a href="<?php echo home_url('/маршруты/'); ?>">Экскурсии</a></li> 
                    <li><a href="<?php echo home_url('/услуги/'); ?>">Услуги</a></li>
                </div>
                <div class="menu_col">
                    <li><a href="<?php echo home_url('/галерея/'); ?>">Галерея</a></li>
                    <li><a href="<?php echo home_url('/отзывы/'); ?>">Отзывы</a></li>
                    <li><a href="<?php echo home_url('/о-нас/'); ?>">О нас</a></li>
            </ul>
            
            <!-- Контакты -->
            <div class="footer_contacts">
                <a href="tel:+79227150546" class="footer_phone">+7 (922) 715-05-46</a>
                <a href="mailto:yurma@active.ru" class="footer_email">yurma@active.ru</a>
                <div class="footer_social">
<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/resource/img/social-vk.svg" alt="ВК"></a>
<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/resource/img/social-tg.svg" alt="TG"></a>
<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/resource/img/social-wa.svg" alt="WA"></a>
                </div>
            </div>
        </div>
        
        <!-- Служебная -->
        <div class="footer_bottom">
            <p>© 2026 Клуб активного отдыха «ЮРМА»</p>
            <div class="second">
                <!-- <a href="#">Договор оферты</a>     
                <span class="separator">|</span> -->
                <a href="#">Политика конфиденциальности</a>
            </div>
        </div>
    </div>
</footer>

<!-- Модальное окно для заказа -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <h3>Оставить заявку</h3>
        <?php echo do_shortcode('[contact-form-7 id="9130443" title="форма заказа"]'); ?>
    </div>
</div>

<script>
function openOrderModal() {
    document.getElementById('orderModal').style.display = 'block';
}
</script>
<?php wp_footer(); ?>
</body>
</html>