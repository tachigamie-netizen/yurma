<?php
/*
Template Name: Все номера
*/
get_header(); ?>

<main>
    <section class="accommodation">
        <div class="container">
            <h2>Проживание</h2>
            
            <div class="card-grid">
                <?php
                // Запрос на вывод ВСЕХ номеров
                $rooms_query = new WP_Query(array(
                    'post_type' => 'rooms',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC'
                ));
                
                if ($rooms_query->have_posts()) :
                    while ($rooms_query->have_posts()) : $rooms_query->the_post();
                        $room_id = get_the_ID();
                        $price = get_post_meta($room_id, 'room_price', true);
                        $capacity = get_post_meta($room_id, 'room_capacity', true);
                        $features = get_post_meta($room_id, 'room_features', true);
                        $image_id = get_post_meta($room_id, 'room_image_id', true);
                        $features_list = $features ? explode("\n", $features) : array();
                        ?>
                        <div class="card room-card">
                            <?php if ($image_id) : ?>
                                <img src="<?php echo wp_get_attachment_url($image_id); ?>" alt="<?php the_title(); ?>" class="card_image">
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/resource/img/placeholder.jpg" alt="Номер" class="card_image">
                            <?php endif; ?>
                            
                            <div class="card_content">
                                <div class="card_header">
                                    <h3><?php the_title(); ?></h3>
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
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p>Номера пока не добавлены. Зайдите в админку и добавьте номера.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>