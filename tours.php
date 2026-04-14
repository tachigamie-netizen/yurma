<?php
/*
Template Name: Все экскурсии
*/
get_header(); ?>

<main>
    <section class="section tours">
        <div class="container">
            <h2>Маршруты</h2>
            
            <div class="card-grid">
                <?php
                $tours_query = new WP_Query(array(
                    'post_type' => 'tours',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC'
                ));
                
                if ($tours_query->have_posts()) :
                    while ($tours_query->have_posts()) : $tours_query->the_post();
                        $tour_id = get_the_ID();
                        $price = get_post_meta($tour_id, 'tour_price', true);
                        $length = get_post_meta($tour_id, 'tour_length', true);
                        $duration = get_post_meta($tour_id, 'tour_duration', true);
                        $difficulty = get_post_meta($tour_id, 'tour_difficulty', true);
                        $image_id = get_post_meta($tour_id, 'tour_image_id', true);
                        ?>
                        <div class="card route-card">
                            <?php if ($image_id) : ?>
                                <img src="<?php echo wp_get_attachment_url($image_id); ?>" alt="<?php the_title(); ?>" class="card_image">
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/resource/img/placeholder.jpg" alt="Экскурсия" class="card_image">
                            <?php endif; ?>
                            <div class="card_content">
                                <h3><?php the_title(); ?></h3>
                                <ul class="card_list">
                                    <li>📍 Длина: <?php echo esc_html($length); ?></li>
                                    <li>⏱ Время: <?php echo esc_html($duration); ?></li>
                                    <li>📊 Сложность: <?php echo esc_html($difficulty); ?></li>
                                </ul>
                                <div class="card_footer">
                                    <span class="price"><?php echo number_format($price, 0, '', ' '); ?> ₽</span>
                                    <button class="btn btn-primary btn-card" onclick="openOrderModal()">Забронировать</button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p>Экскурсии пока не добавлены.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>