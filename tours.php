<?php
/*
Template Name: Все экскурсии
*/
get_header();

// Получаем параметры фильтрации из URL
$current_season = isset($_GET['season']) ? sanitize_text_field($_GET['season']) : '';
$length_filter = isset($_GET['length']) ? sanitize_text_field($_GET['length']) : '';
$time_filter = isset($_GET['time']) ? sanitize_text_field($_GET['time']) : '';
$difficulty_filter = isset($_GET['difficulty']) ? sanitize_text_field($_GET['difficulty']) : '';

// Функция фильтрации длины
function filter_by_length($length, $filter) {
    if (empty($filter)) return true;
    
    // Извлекаем число из строки (поддерживает "15", "15 км", "10-15")
    preg_match_all('/\d+(?:\.\d+)?/', $length, $matches);
    $length_num = !empty($matches[0]) ? max($matches[0]) : 0;
    
    if ($filter == 'short') return $length_num <= 10;
    if ($filter == 'medium') return $length_num > 10 && $length_num <= 20;
    if ($filter == 'long') return $length_num > 20;
    return true;
}

// Функция фильтрации времени
function filter_by_time($duration, $filter) {
    if (empty($filter)) return true;
    
    preg_match_all('/\d+(?:\.\d+)?/', $duration, $matches);
    $time_num = !empty($matches[0]) ? max($matches[0]) : 0;
    
    if ($filter == 'short') return $time_num <= 2;
    if ($filter == 'medium') return $time_num > 2 && $time_num <= 4;
    if ($filter == 'long') return $time_num > 4;
    return true;
}

// Функция фильтрации сложности
function filter_by_difficulty($difficulty, $filter) {
    if (empty($filter)) return true;
    
    $difficulty_levels = [
        'easy' => ['■□□□□'],
        'medium' => ['■■□□□'],
        'hard' => ['■■■□□', '■■■■□', '■■■■■']
    ];
    
    return in_array($difficulty, $difficulty_levels[$filter]);
}

// Функция для получения активного класса
function active_class($condition) {
    return $condition ? 'active' : '';
}
?>

<main>
    <section class="section tours">
        <div class="container">
            <h2>Маршруты</h2>
            
                <!-- Фильтр по сезонам -->
                <div class="season-tags">
                    <a href="?season=" class="season-tag <?php echo active_class(empty($current_season)); ?>">Все</a>
                    <a href="?season=winter" class="season-tag <?php echo active_class($current_season == 'winter'); ?>">Зима</a>
                    <a href="?season=summer" class="season-tag <?php echo active_class($current_season == 'summer'); ?>">Лето</a>
                </div>
<div class="tours-filters">
    <!-- Длина -->
    <div class="filter-item">
        <span class="filter-icon">📏</span>
        <div class="filter-buttons">
            <a href="?length=&time=<?php echo $time_filter; ?>&season=<?php echo $current_season; ?>&difficulty=<?php echo $difficulty_filter; ?>" 
               class="filter-pill <?php echo empty($length_filter) ? 'active' : ''; ?>">Любая</a>
            <a href="?length=short&time=<?php echo $time_filter; ?>&season=<?php echo $current_season; ?>&difficulty=<?php echo $difficulty_filter; ?>" 
               class="filter-pill <?php echo $length_filter == 'short' ? 'active' : ''; ?>">До 10км</a>
            <a href="?length=medium&time=<?php echo $time_filter; ?>&season=<?php echo $current_season; ?>&difficulty=<?php echo $difficulty_filter; ?>" 
               class="filter-pill <?php echo $length_filter == 'medium' ? 'active' : ''; ?>">10-20км</a>
            <a href="?length=long&time=<?php echo $time_filter; ?>&season=<?php echo $current_season; ?>&difficulty=<?php echo $difficulty_filter; ?>" 
               class="filter-pill <?php echo $length_filter == 'long' ? 'active' : ''; ?>">20+км</a>
        </div>
    </div>
    
    <!-- Время -->
    <div class="filter-item">
        <span class="filter-icon">⏱</span>
        <div class="filter-buttons">
            <a href="?time=&length=<?php echo $length_filter; ?>&season=<?php echo $current_season; ?>&difficulty=<?php echo $difficulty_filter; ?>" 
               class="filter-pill <?php echo empty($time_filter) ? 'active' : ''; ?>">Любое</a>
            <a href="?time=short&length=<?php echo $length_filter; ?>&season=<?php echo $current_season; ?>&difficulty=<?php echo $difficulty_filter; ?>" 
               class="filter-pill <?php echo $time_filter == 'short' ? 'active' : ''; ?>">До 2ч</a>
            <a href="?time=medium&length=<?php echo $length_filter; ?>&season=<?php echo $current_season; ?>&difficulty=<?php echo $difficulty_filter; ?>" 
               class="filter-pill <?php echo $time_filter == 'medium' ? 'active' : ''; ?>">2-4ч</a>
            <a href="?time=long&length=<?php echo $length_filter; ?>&season=<?php echo $current_season; ?>&difficulty=<?php echo $difficulty_filter; ?>" 
               class="filter-pill <?php echo $time_filter == 'long' ? 'active' : ''; ?>">4+ч</a>
        </div>
    </div>
    
    <!-- Сложность -->
    <div class="filter-item">
        <span class="filter-icon">📊</span>
        <div class="filter-buttons">
            <a href="?difficulty=&season=<?php echo $current_season; ?>&length=<?php echo $length_filter; ?>&time=<?php echo $time_filter; ?>" 
               class="filter-pill <?php echo empty($difficulty_filter) ? 'active' : ''; ?>">Любая</a>
            <a href="?difficulty=easy&season=<?php echo $current_season; ?>&length=<?php echo $length_filter; ?>&time=<?php echo $time_filter; ?>" 
               class="filter-pill <?php echo $difficulty_filter == 'easy' ? 'active' : ''; ?>">Легкий</a>
            <a href="?difficulty=medium&season=<?php echo $current_season; ?>&length=<?php echo $length_filter; ?>&time=<?php echo $time_filter; ?>" 
               class="filter-pill <?php echo $difficulty_filter == 'medium' ? 'active' : ''; ?>">Средний</a>
            <a href="?difficulty=hard&season=<?php echo $current_season; ?>&length=<?php echo $length_filter; ?>&time=<?php echo $time_filter; ?>" 
               class="filter-pill <?php echo $difficulty_filter == 'hard' ? 'active' : ''; ?>">Сложный</a>
        </div>
    </div>
    
    <!-- Сброс -->
    <a href="?" class="reset-pill">Сбросить</a>
</div>
            
            <!-- Карточки экскурсий -->
            <div class="card-grid">
                <?php
                $args = array(
                    'post_type' => 'tours',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC'
                );
                
                if (!empty($current_season)) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'tour_season',
                            'field'    => 'slug',
                            'terms'    => $current_season,
                        )
                    );
                }
                
                $tours_query = new WP_Query($args);
                $display_count = 0;
                
                if ($tours_query->have_posts()) :
                    while ($tours_query->have_posts()) : $tours_query->the_post();
                        $tour_id = get_the_ID();
                        $price = get_post_meta($tour_id, 'tour_price', true);
                        $length = get_post_meta($tour_id, 'tour_length', true);
                        $duration = get_post_meta($tour_id, 'tour_duration', true);
                        $difficulty = get_post_meta($tour_id, 'tour_difficulty', true);
                        $image_id = get_post_meta($tour_id, 'tour_image_id', true);
                        
                        // Применяем фильтры
                        if (!filter_by_length($length, $length_filter)) continue;
                        if (!filter_by_time($duration, $time_filter)) continue;
                        if (!filter_by_difficulty($difficulty, $difficulty_filter)) continue;
                        
                        $display_count++;
                        ?>
                        <div class="card route-card">
                            <img src="<?php echo $image_id ? wp_get_attachment_url($image_id) : get_template_directory_uri() . '/resource/img/placeholder.jpg'; ?>" 
                                 alt="<?php the_title(); ?>" 
                                 class="card_image">
                            <div class="card_content">
                                <h3><?php the_title(); ?></h3>
                                <ul class="card_list">
                                    <li>📍 Длина: <?php echo esc_html($length); ?> км</li>
                                    <li>⏱ Время: <?php echo esc_html($duration); ?> ч</li>
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
                    
                    if ($display_count === 0) : ?>
                        <p class="no-results">😕 Экскурсии не найдены. Попробуйте изменить параметры фильтра.</p>
                    <?php endif;
                else : ?>
                    <p>😕 Экскурсии пока не добавлены.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>