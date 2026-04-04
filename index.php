<?php get_header(); ?>

<main>
    
    <!-- Hero -->
    <section class="hero">
        <div class="hero_background">
            <?php 
            $hero_bg = get_theme_mod('hero_background');
            if ($hero_bg) : ?>
                <img src="<?php echo esc_url($hero_bg); ?>" alt="Хребет Юрма">
            <?php else : ?>
                <img src="<?php echo get_template_directory_uri(); ?>/resource/img/bg_image_1.png" alt="Хребет Юрма">
            <?php endif; ?>
        </div>
        <div class="container hero_container">
            <h1><?php echo esc_html(get_theme_mod('hero_title', 'ХРЕБЕТ ЮРМА')); ?></h1>
            <p><?php echo esc_html(get_theme_mod('hero_subtitle', 'активный отдых на южном урале')); ?></p>
        </div>
    </section>

<!-- Проживание -->
<section class="accommodation">
    <div class="container">
        <h2>Проживание</h2>
        
        <div class="card-grid">
            <?php
            // Получаем ID выбранных номеров для 4 слотов
            $slot_1 = get_theme_mod('room_slot_1', '');
            $slot_2 = get_theme_mod('room_slot_2', '');
            $slot_3 = get_theme_mod('room_slot_3', '');
            $slot_4 = get_theme_mod('room_slot_4', '');
            
            $slots = array($slot_1, $slot_2, $slot_3, $slot_4);
            
            foreach ($slots as $slot_id) {
                if (empty($slot_id)) {
                    // Если номер не выбран, показываем заглушку
                    ?>
                    <div class="card room-card placeholder">
                        <div class="card_content">
                            <div class="card_header">
                                <h3>Скоро здесь будет номер</h3>
                            </div>
                            <p>Выберите номер в настройках темы</p>
                        </div>
                    </div>
                    <?php
                } else {
                    // Получаем данные номера
                    $room = get_post($slot_id);
                    if ($room) {
                        $price = get_post_meta($slot_id, 'room_price', true);
                        $capacity = get_post_meta($slot_id, 'room_capacity', true);
                        $features = get_post_meta($slot_id, 'room_features', true);
                        $image_id = get_post_meta($slot_id, 'room_image_id', true);
                        $features_list = $features ? explode("\n", $features) : array();
                        ?>
                        <div class="card room-card">
                            <?php if ($image_id) : ?>
                                <img src="<?php echo wp_get_attachment_url($image_id); ?>" alt="<?php echo esc_attr($room->post_title); ?>" class="card_image">
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/resource/img/placeholder.jpg" alt="Номер" class="card_image">
                            <?php endif; ?>
                            
                            <div class="card_content">
                                <div class="card_header">
                                    <h3><?php echo esc_html($room->post_title); ?></h3>
                                    <?php if ($capacity) : ?>
                                        <span class="second"><?php echo esc_html($capacity); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (!empty($features_list)) : ?>
                                    <ul class="card_list">
                                        <?php foreach ($features_list as $feature) : ?>
                                            <?php $feature = trim($feature); ?>
                                            <?php if (!empty($feature)) : ?>
                                                <li><?php echo esc_html($feature); ?></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                
                                <div class="card_footer">
                                    <?php if ($price) : ?>
                                        <span class="price"><?php echo number_format($price, 0, '', ' '); ?> ₽</span>
                                    <?php endif; ?>
                                    <button class="btn btn-primary" onclick="openOrderModal()">заказать</button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
        
        <div class="section_footer">
            <a href="<?php echo home_url('/проживание/'); ?>" class="btn-link">Все варианты размещения</a>
        </div>
    </div>
</section>
    
    <!-- Экскурсии -->
    <section class="section tours">
        <div class="container">
            <div class="tours_header">
                <h2>Экскурсии</h2>
                <p class="tours_description">Бескрайние леса, заснеженные вершины, горные реки и скалистые хребты</p>
                <p class="tours_text">Каждая поездка проходит в сопровождении опытного гида. От спокойных прогулок для новичков до дневных треков для опытных райдеров. Техника рассчитана на двоих, второй участник — 1 000 ₽</p>
            </div>
            
            <div class="card-grid">
                <!-- Маршрут 1 -->
                <div class="card route-card">
                    <img src="resource/img/tour-1.jpg" alt="По хребту Юрма" class="card_image">
                    <div class="card_content">
                        <h3>По хребту Юрма</h3>
                        <ul class="card_list">
                            <li>📍 Длина: 25 км</li>
                            <li>⏱ Время: 2-2.5 ч</li>
                            <li>📊 Сложность: ■■□□□</li>
                        </ul>
                        <div class="card_footer">
                            <span class="price">10 000 ₽</span>
                            <button class="btn btn-primary">заказать</button>
                        </div>
                    </div>
                </div>
                
                <!-- Маршрут 2 -->
                <div class="card route-card">
                    <img src="resource/img/tour-2.jpg" alt="Соколиная сопка" class="card_image">
                    <div class="card_content">
                        <h3>Соколиная сопка</h3>
                        <ul class="card_list">
                            <li>📍 Длина: 35 км</li>
                            <li>⏱ Время: 3-4 ч</li>
                            <li>📊 Сложность: ■■■□□</li>
                        </ul>
                        <div class="card_footer">
                            <span class="price">14 000 ₽</span>
                            <button class="btn btn-primary">заказать</button>
                        </div>
                    </div>
                </div>
                
                <!-- Маршрут 3 -->
                <div class="card route-card">
                    <img src="resource/img/tour-3.jpg" alt="Горное озеро" class="card_image">
                    <div class="card_content">
                        <h3>Горное озеро</h3>
                        <ul class="card_list">
                            <li>📍 Длина: 18 км</li>
                            <li>⏱ Время: 2 ч</li>
                            <li>📊 Сложность: ■□□□□</li>
                        </ul>
                        <div class="card_footer">
                            <span class="price">8 000 ₽</span>
                            <button class="btn btn-primary">заказать</button>
                        </div>
                    </div>
                </div>
                
                <!-- Маршрут 4 -->
                <div class="card route-card">
                    <img src="resource/img/tour-4.jpg" alt="Ночной тур" class="card_image">
                    <div class="card_content">
                        <h3>Ночной тур</h3>
                        <ul class="card_list">
                            <li>📍 Длина: 20 км</li>
                            <li>⏱ Время: 2.5 ч</li>
                            <li>📊 Сложность: ■■□□□</li>
                        </ul>
                        <div class="card_footer">
                            <span class="price">12 000 ₽</span>
                            <button class="btn btn-primary">заказать</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="section_footer">
                <a href="#" class="btn-link">Все маршруты</a>
            </div>
        </div>
    </section>
    
    <!-- Услуги -->
    <section class="section services">
        <div class="container">
            <h2>Услуги</h2>
            
            <div class="card-grid">
                <!-- Услуга 1 -->
                <div class="card service-card">
                    <div class="service-card_image">
                        <img src="resource/img/Frame%20172.png" alt="Русская баня">
                    </div>
                    <div class="card_content">
                        <h3>Русская баня</h3>
                        <span class="price">2 500 ₽/день</span>
                    </div>
                </div>
                
                <!-- Услуга 2 -->
                <div class="card service-card">
                    <div class="service-card_image">
                        <img src="resource/img/Frame%20173.png" alt="Квадроциклы">
                    </div>
                    <div class="card_content">
                        <h3>Квадроциклы</h3>
                        <span class="price">3 000 ₽/час</span>
                    </div>
                </div>
                
                <!-- Услуга 3 -->
                <div class="card service-card">
                    <div class="service-card_image">
                        <img src="resource/img/Frame%20174.png" alt="Снегоходы">
                    </div>
                    <div class="card_content">
                        <h3>Снегоходы</h3>
                        <span class="price">3 500 ₽/час</span>
                    </div>
                </div>
                
                <!-- Услуга 4 -->
                <div class="card service-card">
                    <div class="service-card_image">
                        <img src="resource/img/Frame%20175.png" alt="Рыбалка">
                    </div>
                    <div class="card_content">
                        <h3>Рыбалка</h3>
                        <span class="price">1 500 ₽/день</span>
                    </div>
                </div>
                
                <!-- Услуга 5 -->
                <div class="card service-card">
                    <div class="service-card_image">
                        <img src="resource/img/Frame%20176.png" alt="Прокат снаряжения">
                    </div>
                    <div class="card_content">
                        <h3>Прокат снаряжения</h3>
                        <span class="price">от 500 ₽</span>
                    </div>
                </div>
            </div>
            
            <div class="section_footer">
                <a href="#" class="btn-link">Все услуги</a>
            </div>
        </div>
    </section>
    
    <!-- Галерея -->
    <section class="section gallery">
        <div class="container">
            <h2>Галерея</h2>
            
            <div class="gallery-grid">
                <div class="gallery-large">
                    <img src="resource/img/bg_image_1.png" alt="Галерея">
                </div>
                <div class="gallery-small-grid">
                    <div class="gallery-small">
                        <img src="resource/img/room_1.jpg" alt="Галерея">
                    </div>
                    <div class="gallery-small">
                        <img src="resource/img/room_2.jpg" alt="Галерея">
                    </div>
                    <div class="gallery-small">
                        <img src="resource/img/room_3.jpg" alt="Галерея">
                    </div>
                    <div class="gallery-small">
                        <img src="resource/img/room_4.jpg" alt="Галерея">
                    </div>
                </div>
            </div>
            
            <div class="section_footer">
                <a href="#" class="btn-link">Все фотографии</a>
            </div>
        </div>
    </section>
    
    <!-- Отзывы -->
    <section class="section reviews">
        <div class="container">
            <h2>Отзывы</h2>
            
            <div class="card-grid reviews-grid">
                <!-- Отзыв 1 -->
                <div class="card review-card">
                    <img src="resource/img/quote.svg" alt="" class="review-card_background">
                    <div class="card_content">
                        <div class="review_header">
                            <h3>Алексей</h3>
                            <div class="review_rating">★ ★ ★ ★ ★</div>
                        </div>
                        <p class="review_text">
                            Отдыхали на новогодних праздниках. Брали снегоходы, маршрут на Соколиную сопку — полный восторг! Техника новая, шлемы выдали тёплые, гид Дмитрий показал такие места, куда пешком не дойдёшь. Баня после катания — сказка. Обязательно вернёмся летом на квадроциклах. 
                        </p>
                        <span class="second review_date">Январь 2026</span>
                    </div>
                </div>
                
                <!-- Отзыв 2 -->
                <div class="card review-card">
                    <img src="resource/img/quote.svg" alt="" class="review-card_background">
                    <div class="card_content">
                        <div class="review_header">
                            <h3>Екатерина</h3>
                            <div class="review_rating">★ ★ ★ ★ ★</div>
                        </div>
                        <p class="review_text">
                            Отличное место для активного отдыха! Жили в номере Комфорт с балконом — очень уютно, чисто, есть всё необходимое. Отдельное спасибо повару — кормили очень вкусно, домашние обеды после катания заходили на ура. Обязательно приедем еще!
                        </p>
                        <span class="second review_date">Февраль 2026</span>
                    </div>
                </div>
                
                <!-- Отзыв 3 -->
                <div class="card review-card">
                    <img src="resource/img/quote.svg" alt="" class="review-card_background">
                    <div class="card_content">
                        <div class="review_header">
                            <h3>Сергей</h3>
                            <div class="review_rating">★ ★ ★ ★ ★</div>
                        </div>
                        <p class="review_text">
                            Ездили с сыном (10 лет) на учебном маршруте. Ребёнок был счастлив! Инструктор внимательный, всё объяснил, показал. Дорога нормальная, доехали без проблем. Из минусов — слабый интернет, но в горах это даже плюс, чтобы отдохнуть от телефона. В следующий раз возьмем маршрут подлиннее.
                        </p>
                        <span class="second review_date">Март 2025</span>
                    </div>
                </div>
                
                <!-- Отзыв 4 -->
                <div class="card review-card">
                    <img src="resource/img/quote.svg" alt="" class="review-card_background">
                    <div class="card_content">
                        <div class="review_header">
                            <h3>Дмитрий</h3>
                            <div class="review_rating">★ ★ ★ ★ ★</div>
                        </div>
                        <p class="review_text">
                            Большая компания брали квадроциклы на весь день. Организация на высоте: гид знает своё дело, маршрут интересный с разными препятствиями, были на нескольких вершинах. Вечером баня и шашлык — идеальный день! Отдельное спасибо за вкусный обед на природе.
                        </p>
                        <span class="second review_date">Август 2025</span>
                    </div>
                </div>
            </div>
            
            <div class="section_footer">
                <a href="#" class="btn-link">Все отзывы</a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>