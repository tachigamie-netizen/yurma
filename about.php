<?php
/*
Template Name: О нас
*/
get_header(); ?>

<main>
    <!-- Секция "О нас" -->
    <section class="section about">
        <div class="container">
            <h2>О нас</h2>
            
            <div class="about-grid">
                <div class="about-content">
                    <p>Клуб активного отдыха «ЮРМА» расположен в самом сердце Южного Урала — края бескрайних лесов, живописных гор и кристально чистых озёр. У подножья хребта Юрма, где начинаются настоящие приключения.</p>
                    
                    <p>Леса, заснеженные долины, горные реки и таинственные тропы — Южный Урал поражает своей суровой красотой. Здесь каждый найдёт свой идеальный маршрут. Неважно, лето или зима за окном — у нас есть техника для любого сезона.</p>
                    
                    <p>Каждая поездка проходит в сопровождении опытного гида, который не только обеспечит безопасность, но и покажет самые захватывающие места. Качественная техника, индивидуальный подход к каждому гостю.</p>
                    
                    <p>В 2017 году у нас отдыхал победитель ралли Dakar — Сергей Карякин. Для нас это знак качества, но главное — тысячи довольных гостей, которые возвращаются снова, чтобы насладиться живописными пейзажами Южного Урала.</p>
                </div>
                
                <div class="about-image">
                    <img src="<?php echo get_template_directory_uri(); ?>/resource/img/dakar.png" alt="Сергей Карякин на базе ЮРМА">
                </div>
            </div>
        </div>
    </section>

    <!-- Секция "Контакты" -->
    <section class="section contacts">
        <div class="container">
            <h2>Контакты</h2>
            
            <div class="contacts-grid">
                <div class="contact-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/resource/img/geo.png" alt="Адрес">
                        <a href="#map" class="address-link">Большие Егусты, Челябинская область</a>
                </div>
                
                <div class="contact-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/resource/img/tel.png" alt="Телефон">
                    <p><a href="tel:+79227150546">+7 922 715-05-46</a></p>
                </div>
                
                <div class="contact-item">
                    <img src="<?php echo get_template_directory_uri(); ?>/resource/img/mail.png" alt="Email">
                    <p><a href="mailto:yurma@active.ru">yurma@active.ru</a></p>
                </div>
            </div>
        </div>
    </section>

<!-- Секция "FAQ" -->
<section class="section faq">
    <div class="container">
        <h2>FAQ</h2>
        
        <div class="faq-grid">
            <?php
            $faq_query = new WP_Query(array(
                'post_type' => 'faq',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'ASC'
            ));
            
            if ($faq_query->have_posts()) :
                while ($faq_query->have_posts()) : $faq_query->the_post();
                    ?>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><?php the_title(); ?></h3>
                            <button class="faq-toggle"></button>
                        </div>
                        <div class="faq-answer">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else : ?>
                <p class="second">Вопросы пока не добавлены.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Секция "Карта" -->
     <div class="container" id="map">
         <h2>Расположение</h2>
        </div>

<div class="fullwidth-map">
    <iframe 
        src="https://yandex.ru/map-widget/v1/?l=sat%2Cskl&ll=60.557473%2C55.722186&mode=routes&rtext=55.714181%2C60.509947~55.631032%2C60.071424&rtt=auto&ruri=~ymapsbm1%3A%2F%2Forg%3Foid%3D156121608094&um=constructor%3Ab6f6049b67c3ed00725fcdddb6139c60877a3b910ffe8a47add07b2d0e533d59&z=13" 
        width="100%" 
        height="450" 
        frameborder="0" 
        allowfullscreen>
    </iframe>
</div>

</main>

<?php get_footer(); ?>