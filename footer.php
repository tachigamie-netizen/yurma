<footer class="footer">
    <div class="container">
        <!-- Информ -->
        <h3>Клуб активного отдыха «ЮРМА»</h3>
        <div class="footer_content">
            <div class="footer_about">
                <p>Прокат квадроциклов и снегоходов в горах Южного Урала. Более 10 лет показываем гостям настоящую природу.</p>
            </div>

            <!-- Меню -->
            <ul class="footer_menu">
                <div class="menu_col">
                    <li><a href="#">Главная</a></li>
                    <li><a href="#">Проживание</a></li>
                    <li><a href="#">Услуги</a></li>
                    <li><a href="#">Экскурсии</a></li>
                </div>
                <div class="menu_col">
                    <li><a href="#">Галерея</a></li>
                    <li><a href="#">Отзывы</a></li>
                    <li><a href="#">О нас</a></li>
                    <li><a href="#">FAQ</a></li>
                </div>
            </ul>
            
            <!-- Контакты -->
            <div class="footer_contacts">
                <a href="tel:+79227150546" class="footer_phone">+7 (922) 715-05-46</a>
                <a href="mailto:yurma@active.ru" class="footer_email">yurma@active.ru</a>
                <div class="footer_social">
                    <a href="#"><img src="img/social-vk.svg" alt="ВК"></a>
                    <a href="#"><img src="img/social-tg.svg" alt="TG"></a>
                    <a href="#"><img src="img/social-wa.svg" alt="WA"></a>
                </div>
            </div>
        </div>
        
        <!-- Служебная -->
        <div class="footer_bottom">
            <p>© 2026 Клуб активного отдыха «ЮРМА»</p>
            <div class="second">
                <a href="#">Договор оферты</a>     
                <span class="separator">|</span>
                <a href="#">Политика конфиденциальности</a>
            </div>
        </div>
    </div>
</footer>

<!-- Модальное окно -->
<div id="orderModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:99999;">
    <div style="background:white; width:90%; max-width:450px; margin:50px auto; padding:25px; border-radius:16px; position:relative;">
        <span style="position:absolute; top:10px; right:15px; font-size:24px; cursor:pointer;" onclick="document.getElementById('orderModal').style.display='none';">&times;</span>
        <h3 style="margin-top:0;">Оставить заявку</h3>
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