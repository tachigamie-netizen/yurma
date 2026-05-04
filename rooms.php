<?php
/*
Template Name: Все номера
*/
get_header();

// Получаем выбранные удобства из URL
$selected_features = isset($_GET['features']) ? (array)$_GET['features'] : array();
$selected_features = array_map('sanitize_text_field', $selected_features);
?>

<main>
    <section class="section accommodation">
        <div class="container">
            <h2>Проживание</h2>
            
            <!-- Фильтр по удобствам -->
            <div class="features-filter">
                <?php
                $all_features = get_terms(array(
                    'taxonomy' => 'room_feature',
                    'hide_empty' => false,
                ));
                
                if (!empty($all_features) && !is_wp_error($all_features)) : ?>
                    <div class="features-tags">
                        <a href="?<?php echo http_build_query(array('features' => array())); ?>" 
                           class="feature-tag <?php echo empty($selected_features) ? 'active' : ''; ?>">
                            Все
                        </a>
                        <?php foreach ($all_features as $feature) : 
                            $is_active = in_array($feature->name, $selected_features);
                            
                            $new_features = $selected_features;
                            if ($is_active) {
                                $new_features = array_diff($new_features, array($feature->name));
                            } else {
                                $new_features[] = $feature->name;
                            }
                            $query_args = array('features' => $new_features);
                            ?>
                            <a href="?<?php echo http_build_query($query_args); ?>" 
                               class="feature-tag <?php echo $is_active ? 'active' : ''; ?>">
                                <?php echo esc_html($feature->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-grid">
                <?php
                $args = array(
                    'post_type' => 'rooms',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC'
                );
                
                if (!empty($selected_features)) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'room_feature',
                            'field'    => 'name',
                            'terms'    => $selected_features,
                            'operator' => 'AND'
                        )
                    );
                }
                
                $rooms_query = new WP_Query($args);
                
                if ($rooms_query->have_posts()) :
                    while ($rooms_query->have_posts()) : $rooms_query->the_post();
                        $room_id = get_the_ID();
                        $price = get_post_meta($room_id, 'room_price', true);
                        $capacity = get_post_meta($room_id, 'room_capacity', true);
                        $features_text = get_post_meta($room_id, 'room_features', true); // текстовые удобства
                        $image_id = get_post_meta($room_id, 'room_image_id', true);
                        
                        // Получаем удобства из таксономии
                        $features_tax = wp_get_post_terms($room_id, 'room_feature', array('fields' => 'names'));
                        
                        // Объединяем оба списка (текстовый + таксономия)
                        $features_list = array();
                        
                        // Добавляем удобства из таксономии
                        foreach ($features_tax as $feature) {
                            $features_list[] = $feature;
                        }
                        
                        // Добавляем удобства из текстового поля (если они не дублируются)
                        if ($features_text) {
                            $text_features = explode("\n", $features_text);
                            foreach ($text_features as $feature) {
                                $feature = trim($feature);
                                if (!empty($feature) && !in_array($feature, $features_list)) {
                                    $features_list[] = $feature;
                                }
                            }
                        }
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
                                            <li><?php echo esc_html($feature); ?></li>
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
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p>Номера не найдены. Попробуйте выбрать другие удобства.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>