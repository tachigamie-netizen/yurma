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
<section class="section room">
    <div class="container">
        <h2>Проживание</h2>
        
        <div class="card-grid">
            <?php
            // Получаем ID выбранных номеров
            $slot_1 = get_theme_mod('room_slot_1', '');
            $slot_2 = get_theme_mod('room_slot_2', '');
            $slot_3 = get_theme_mod('room_slot_3', '');
            $slot_4 = get_theme_mod('room_slot_4', '');
            
            // Собираем только выбранные (не пустые) номера
            $slots = array();
            if (!empty($slot_1)) $slots[] = $slot_1;
            if (!empty($slot_2)) $slots[] = $slot_2;
            if (!empty($slot_3)) $slots[] = $slot_3;
            if (!empty($slot_4)) $slots[] = $slot_4;
            
            foreach ($slots as $slot_id) {
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
                                <button class="btn btn-primary btn-card" onclick="openOrderModal()">Забронировать</button>
                            </div>
                        </div>
                    </div>
                    <?php
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
            <h2>Маршруты</h2>
            <p class="tours_description">Бескрайние леса, заснеженные вершины, горные реки и скалистые хребты</p>
            <p class="tours_text">Каждая поездка проходит в сопровождении опытного гида. От спокойных прогулок для новичков до дневных треков для опытных райдеров. Техника рассчитана на двоих, второй участник — 1 000 ₽</p>
        </div>
        
        <div class="card-grid">
            <?php
            // Получаем ID выбранных экскурсий
            $tour_slot_1 = get_theme_mod('tour_slot_1', '');
            $tour_slot_2 = get_theme_mod('tour_slot_2', '');
            $tour_slot_3 = get_theme_mod('tour_slot_3', '');
            $tour_slot_4 = get_theme_mod('tour_slot_4', '');
            
            // Собираем только выбранные (не пустые) экскурсии
            $tour_slots = array();
            if (!empty($tour_slot_1)) $tour_slots[] = $tour_slot_1;
            if (!empty($tour_slot_2)) $tour_slots[] = $tour_slot_2;
            if (!empty($tour_slot_3)) $tour_slots[] = $tour_slot_3;
            if (!empty($tour_slot_4)) $tour_slots[] = $tour_slot_4;
            
            foreach ($tour_slots as $slot_id) {
                $tour = get_post($slot_id);
                if ($tour) {
                    $price = get_post_meta($slot_id, 'tour_price', true);
                    $length = get_post_meta($slot_id, 'tour_length', true);
                    $duration = get_post_meta($slot_id, 'tour_duration', true);
                    $difficulty = get_post_meta($slot_id, 'tour_difficulty', true);
                    $image_id = get_post_meta($slot_id, 'tour_image_id', true);
                    ?>
                    <div class="card route-card">
                        <?php if ($image_id) : ?>
                            <img src="<?php echo wp_get_attachment_url($image_id); ?>" alt="<?php echo esc_attr($tour->post_title); ?>" class="card_image">
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/resource/img/placeholder.jpg" alt="Экскурсия" class="card_image">
                        <?php endif; ?>
                        
                        <div class="card_content">
                            <div class="card_header">
                                <h3><?php echo esc_html($tour->post_title); ?></h3>
                            </div>
                            
                            <ul class="card_list">
                                <li>📍 Длина: <?php echo esc_html($length); ?></li>
                                <li>⏱ Время: <?php echo esc_html($duration); ?></li>
                                <li>📊 Сложность: <?php echo esc_html($difficulty); ?></li>
                            </ul>
                            
                            <div class="card_footer">
                                <?php if ($price) : ?>
                                    <span class="price"><?php echo number_format($price, 0, '', ' '); ?> ₽</span>
                                <?php endif; ?>
                                <button class="btn btn-primary btn-card" onclick="openOrderModal()">Забронировать</button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <div class="section_footer">
            <a href="<?php echo home_url('//маршруты/'); ?>" class="btn-link">Все маршруты</a>
        </div>
    </div>
</section>
        

    
<!-- Услуги -->
<section class="section services">
    <div class="container">
        <h2>Услуги</h2>
        
        <div class="card-grid services-grid">
            <?php
            // Получаем ID выбранных услуг (5 слотов)
            $service_slot_1 = get_theme_mod('service_slot_1', '');
            $service_slot_2 = get_theme_mod('service_slot_2', '');
            $service_slot_3 = get_theme_mod('service_slot_3', '');
            $service_slot_4 = get_theme_mod('service_slot_4', '');
            $service_slot_5 = get_theme_mod('service_slot_5', '');
            
            // Собираем только выбранные (не пустые) услуги
            $service_slots = array();
            if (!empty($service_slot_1)) $service_slots[] = $service_slot_1;
            if (!empty($service_slot_2)) $service_slots[] = $service_slot_2;
            if (!empty($service_slot_3)) $service_slots[] = $service_slot_3;
            if (!empty($service_slot_4)) $service_slots[] = $service_slot_4;
            if (!empty($service_slot_5)) $service_slots[] = $service_slot_5;
            
            foreach ($service_slots as $slot_id) {
                $service = get_post($slot_id);
                if ($service) {
                    $price = get_post_meta($slot_id, 'service_price', true);
                    $price_unit = get_post_meta($slot_id, 'service_price_unit', true);
                    $image_id = get_post_meta($slot_id, 'service_image_id', true);
                    ?>
                    <div class="card service-card">
                        <?php if ($image_id) : ?>
                            <div class="service-card_image">
                                <img src="<?php echo wp_get_attachment_url($image_id); ?>" alt="<?php echo esc_attr($service->post_title); ?>">
                            </div>
                        <?php else : ?>
                            <div class="service-card_image">
                                <img src="<?php echo get_template_directory_uri(); ?>/resource/img/placeholder.jpg" alt="Услуга">
                            </div>
                        <?php endif; ?>
                        <div class="card_content">
                            <h3><?php echo esc_html($service->post_title); ?></h3>
                            <span class="price"><?php echo esc_html($price); ?> <?php echo esc_html($price_unit); ?></span>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <div class="section_footer">
            <a href="<?php echo home_url('/услуги/'); ?>" class="btn-link">Все услуги</a>
        </div>
    </div>
</section>
    
<!-- Галерея -->
<section class="section gallery">
    <div class="container">
        <h2>Галерея</h2>
        
        <div class="gallery-grid">
            <?php
            $gallery_query = new WP_Query(array(
                'post_type' => 'gallery',
                'posts_per_page' => 5,
                'post_status' => 'publish'
            ));
            
            if ($gallery_query->have_posts()) :
                $counter = 0;
                while ($gallery_query->have_posts()) : $gallery_query->the_post();
                    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    if (!$image_url) {
                        $image_url = get_template_directory_uri() . '/resource/img/placeholder.jpg';
                    }
                    
                    if ($counter === 0) : ?>
                        <div class="gallery-large">
                            <a href="<?php echo esc_url($image_url); ?>" data-lightbox="gallery" data-title="<?php the_title(); ?>">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>">
                            </a>
                        </div>
                    <?php else : 
                        if ($counter === 1) echo '<div class="gallery-small-grid">';
                        ?>
                        <div class="gallery-small">
                            <a href="<?php echo esc_url($image_url); ?>" data-lightbox="gallery" data-title="<?php the_title(); ?>">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>">
                            </a>
                        </div>
                    <?php 
                    endif;
                    $counter++;
                endwhile;
                if ($counter > 1) echo '</div>';
                wp_reset_postdata();
            else : ?>
                <p>Фотографии пока не добавлены. Зайдите в админку → Галерея → Добавить фото</p>
            <?php endif; ?>
        </div>
    
        
        <div class="section_footer">
            <a href="<?php echo home_url('/галерея/'); ?>" class="btn-link">Все фотографии</a>
        </div>
    </div>
</section>
    
<!-- Отзывы на главной -->
<section class="section reviews">
    <div class="container">
        <h2>Отзывы</h2>
        
        <div class="card-grid reviews-grid">
            <?php
            // Получаем ID выбранных отзывов
            $review_slot_1 = get_theme_mod('review_slot_1', '');
            $review_slot_2 = get_theme_mod('review_slot_2', '');
            $review_slot_3 = get_theme_mod('review_slot_3', '');
            $review_slot_4 = get_theme_mod('review_slot_4', '');
            
            // Собираем только выбранные (не пустые) отзывы
            $review_slots = array();
            if (!empty($review_slot_1)) $review_slots[] = $review_slot_1;
            if (!empty($review_slot_2)) $review_slots[] = $review_slot_2;
            if (!empty($review_slot_3)) $review_slots[] = $review_slot_3;
            if (!empty($review_slot_4)) $review_slots[] = $review_slot_4;
            
            foreach ($review_slots as $slot_id) {
                $review = get_post($slot_id);
                if ($review) {
                    $author = get_post_meta($slot_id, 'review_author', true);
                    $rating = get_post_meta($slot_id, 'review_rating', true);
                    if (!$author) $author = 'Аноним';
                    if (!$rating) $rating = 5;
                    
                    // Звезды
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $stars .= ($i <= $rating) ? '★' : '☆';
                    }
                    
                    // Берём текст отзыва из $review->post_content
                    $review_text = $review->post_content;
                    if (empty($review_text)) {
                        $review_text = 'Отзыв без текста';
                    }
                    ?>
                    <div class="card review-card">
                        <img src="<?php echo get_template_directory_uri(); ?>/resource/img/quote.svg" alt="" class="review-card_background">
                        <div class="card_content">
                            <div class="review_header">
                                <h3><?php echo esc_html($author); ?></h3>
                                <div class="review_rating"><?php echo $stars; ?></div>
                            </div>
                            <p class="review_text">
                                <?php echo wp_trim_words($review_text, 30, '...'); ?>
                            </p>
                            <span class="second review_date"><?php echo get_the_date('F Y', $slot_id); ?></span>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <div class="section_footer">
            <a href="<?php echo home_url('/отзывы/'); ?>" class="btn-link">Все отзывы</a>                    
        </div>
    </div>
</section>
            

</main>

<?php get_footer(); ?>