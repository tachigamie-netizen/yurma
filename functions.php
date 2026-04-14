<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ========== Базовые настройки темы ==========
add_theme_support('title-tag');
add_theme_support('post-thumbnails');

// ========== Подключаем CSS и JS на сайте ==========
function yurma_enqueue_assets() {
    wp_enqueue_style('yurma-style', get_stylesheet_uri(), array(), '1.0');
    
    if (file_exists(get_template_directory() . '/script.js')) {
        wp_enqueue_script('yurma-script', get_template_directory_uri() . '/script.js', array(), '1.0', true);
    }

    // Подключаем лайтбокс
    wp_enqueue_style('lightbox-css', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css', array(), '2.11.4');
    wp_enqueue_script('lightbox-js', 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js', array('jquery'), '2.11.4', true);
}
add_action('wp_enqueue_scripts', 'yurma_enqueue_assets');

// ========== Тип записей "Номера" ==========
function register_rooms_cpt() {
    $labels = array(
        'name'               => 'Номера',
        'singular_name'      => 'Номер',
        'menu_name'          => 'Номера',
        'add_new'            => 'Добавить номер',
        'add_new_item'       => 'Добавить новый номер',
        'edit_item'          => 'Редактировать номер',
        'new_item'           => 'Новый номер',
        'view_item'          => 'Просмотреть номер',
        'search_items'       => 'Искать номера',
        'not_found'          => 'Номера не найдены',
        'not_found_in_trash' => 'В корзине нет номеров',
    );
    
    // $args = array(
    //     'labels'              => $labels,
    //     'public'              => true,
    //     'publicly_queryable'  => true,
    //     'show_ui'             => true,
    //     'show_in_menu'        => true,
    //     'query_var'           => true,
    //     'rewrite'             => array('slug' => 'rooms'),
    //     'capability_type'     => 'post',
    //     'has_archive'         => true,
    //     'hierarchical'        => false,
    //     'menu_position'       => 5,
    //     'menu_icon'           => 'dashicons-building',
    //     'supports'            => array('title'),
    //     'show_in_rest'        => false,
    // );
     $args = array(
        'labels'       => $labels,
        'public'       => true,
        'menu_icon'    => 'dashicons-building',
        'supports'     => array('title'),
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'rooms'),
        'show_in_rest' => false,
    );   
    register_post_type('rooms', $args);
}
add_action('init', 'register_rooms_cpt');

// ========== Подключаем скрипты для админки ==========
function rooms_admin_scripts() {
    global $post_type;
    if ($post_type == 'rooms') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }
}
add_action('admin_enqueue_scripts', 'rooms_admin_scripts');

// ========== Кастомный метабокс для номеров ==========
function add_rooms_meta_boxes() {
    add_meta_box(
        'rooms_details',
        '🏨 Информация о номере',
        'render_rooms_meta_box',
        'rooms',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_rooms_meta_boxes');

function render_rooms_meta_box($post) {
    $price = get_post_meta($post->ID, 'room_price', true);
    $capacity = get_post_meta($post->ID, 'room_capacity', true);
    $features = get_post_meta($post->ID, 'room_features', true);
    $description = get_post_meta($post->ID, 'room_description', true);
    $image_id = get_post_meta($post->ID, 'room_image_id', true);
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    ?>
    <style>
        .rooms-form-group { margin-bottom: 25px; }
        .rooms-form-group label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; }
        .rooms-form-group input[type="text"],
        .rooms-form-group input[type="number"],
        .rooms-form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .rooms-form-group textarea { resize: vertical; }
        .rooms-two-columns { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .rooms-image-preview { margin-bottom: 10px; }
        .rooms-image-preview img { max-width: 300px; border-radius: 8px; border: 1px solid #ddd; }
        .rooms-help { color: #666; font-size: 12px; margin-top: 5px; }
        .rooms-save-btn { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; }
    </style>
    
    <div style="padding: 20px; background: #fff; border-radius: 8px;">
        
        <div class="rooms-form-group">
            <label>🏷️ Название номера</label>
            <input type="text" name="post_title" value="<?php echo esc_attr($post->post_title); ?>" placeholder="Например: Комфорт с балконом">
        </div>
        
        <div class="rooms-form-group">
            <label>📷 Фото номера</label>
            <input type="hidden" id="room_image_id" name="room_image_id" value="<?php echo esc_attr($image_id); ?>">
            <div id="room_image_preview" class="rooms-image-preview">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="Фото номера">
                <?php endif; ?>
            </div>
            <div>
                <button type="button" class="button" id="upload_room_image">📁 Выбрать фото</button>
                <button type="button" class="button" id="remove_room_image" style="display: <?php echo $image_url ? 'inline-block' : 'none'; ?>">🗑️ Удалить фото</button>
            </div>
            <div class="rooms-help">Загрузите основное фото номера</div>
        </div>
        
        <div class="rooms-two-columns">
            <div class="rooms-form-group">
                <label>💰 Цена (₽/сутки)</label>
                <input type="number" name="room_price" value="<?php echo esc_attr($price); ?>" placeholder="8800">
            </div>
            <div class="rooms-form-group">
                <label>👥 Вместимость</label>
                <input type="text" name="room_capacity" value="<?php echo esc_attr($capacity); ?>" placeholder="2 местный">
            </div>
        </div>
        
        <div class="rooms-form-group">
            <label>📋 Удобства</label>
            <textarea name="room_features" rows="5" placeholder="Двуспальная кровать&#10;TV Триколор&#10;Душ и туалет&#10;Балкон"><?php echo esc_textarea($features); ?></textarea>
            <div class="rooms-help">Каждое удобство с новой строки</div>
        </div>
        
        <div class="rooms-form-group">
            <label>📝 Описание номера</label>
            <textarea name="room_description" rows="8" placeholder="Подробное описание номера..."><?php echo esc_textarea($description); ?></textarea>
            <div class="rooms-help">Подробное описание для страницы номера</div>
        </div>
        
        <div class="rooms-save-btn">
            <button type="submit" class="button button-primary button-large">💾 Сохранить номер</button>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        
        $('#upload_room_image').click(function(e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media({
                title: 'Выберите фото номера',
                button: { text: 'Выбрать' },
                multiple: false
            });
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#room_image_id').val(attachment.id);
                $('#room_image_preview').html('<img src="' + attachment.url + '" alt="Фото номера">');
                $('#remove_room_image').show();
            });
            mediaUploader.open();
        });
        
        $('#remove_room_image').click(function(e) {
            e.preventDefault();
            $('#room_image_id').val('');
            $('#room_image_preview').html('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

// ========== Сохранение данных номеров ==========
function save_rooms_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (get_post_type($post_id) != 'rooms') {
        return;
    }
    
    if (isset($_POST['post_title']) && !empty($_POST['post_title'])) {
        remove_action('save_post', 'save_rooms_meta');
        wp_update_post(array(
            'ID' => $post_id,
            'post_title' => sanitize_text_field($_POST['post_title'])
        ));
        add_action('save_post', 'save_rooms_meta');
    }
    
    if (isset($_POST['room_price'])) {
        update_post_meta($post_id, 'room_price', sanitize_text_field($_POST['room_price']));
    }
    if (isset($_POST['room_capacity'])) {
        update_post_meta($post_id, 'room_capacity', sanitize_text_field($_POST['room_capacity']));
    }
    if (isset($_POST['room_features'])) {
        update_post_meta($post_id, 'room_features', sanitize_textarea_field($_POST['room_features']));
    }
    if (isset($_POST['room_description'])) {
        update_post_meta($post_id, 'room_description', sanitize_textarea_field($_POST['room_description']));
    }
    if (isset($_POST['room_image_id'])) {
        $image_id = intval($_POST['room_image_id']);
        update_post_meta($post_id, 'room_image_id', $image_id);
        if ($image_id) {
            set_post_thumbnail($post_id, $image_id);
        }
    }
}
add_action('save_post', 'save_rooms_meta');

// ========== Колонки в списке номеров ==========
function rooms_admin_columns($columns) {
    return array(
        'cb' => '<input type="checkbox" />',
        'thumbnail' => 'Фото',
        'title' => 'Название',
        'price' => 'Цена',
        'capacity' => 'Вместимость',
        'date' => 'Дата'
    );
}
add_filter('manage_rooms_posts_columns', 'rooms_admin_columns');

function rooms_admin_columns_data($column, $post_id) {
    switch ($column) {
        case 'thumbnail':
            $image_id = get_post_meta($post_id, 'room_image_id', true);
            if ($image_id) {
                echo wp_get_attachment_image($image_id, array(60, 60));
            } else {
                echo '<span style="color:#ccc;">📷 нет фото</span>';
            }
            break;
        case 'price':
            $price = get_post_meta($post_id, 'room_price', true);
            echo $price ? number_format($price, 0, '', ' ') . ' ₽' : '—';
            break;
        case 'capacity':
            echo get_post_meta($post_id, 'room_capacity', true) ?: '—';
            break;
    }
}
add_action('manage_rooms_posts_custom_column', 'rooms_admin_columns_data', 10, 2);

// ========== УНИВЕРСАЛЬНАЯ ФУНКЦИЯ ДЛЯ ЛЮБОГО ТИПА ПОСТОВ ==========
function get_posts_list_for_customizer($post_type, $orderby = 'title', $order = 'ASC') {
    $choices = array('' => '— Не выбрано —');
    $posts = get_posts(array(
        'post_type'      => $post_type,
        'numberposts'    => -1,
        'post_status'    => 'publish',
        'orderby'        => $orderby,
        'order'          => $order,
        'suppress_filters' => false,
    ));
    
    foreach ($posts as $post) {
        $choices[$post->ID] = $post->post_title;
    }
    return $choices;
}

// ========== ОСНОВНАЯ ФУНКЦИЯ РЕГИСТРАЦИИ НАСТРОЕК ==========
function yurma_customize_register($wp_customize) {
    
    // === 1. Номера (4 слота) ===
    $wp_customize->add_section('rooms_slots', array(
        'title'    => 'Номера на главной (4 слота)',
        'priority' => 31,
    ));
    
    for ($i = 1; $i <= 4; $i++) {
        $wp_customize->add_setting("room_slot_$i", array(
            'default' => '',
            'sanitize_callback' => 'absint'
        ));
        $wp_customize->add_control("room_slot_$i", array(
            'label'    => "Слот $i",
            'section'  => 'rooms_slots',
            'type'     => 'select',
            'choices'  => get_posts_list_for_customizer('rooms'),
        ));
    }
    
    // === 2. Экскурсии (4 слота) ===
    $wp_customize->add_section('tours_slots', array(
        'title'       => 'Экскурсии на главной (4 слота)',
        'priority'    => 32,
        'description' => 'Выберите экскурсии для отображения в 4 слотах на главной странице.',
    ));
    
    for ($i = 1; $i <= 4; $i++) {
        $wp_customize->add_setting("tour_slot_$i", array(
            'default' => '',
            'sanitize_callback' => 'absint'
        ));
        $wp_customize->add_control("tour_slot_$i", array(
            'label'    => "Слот $i",
            'section'  => 'tours_slots',
            'type'     => 'select',
            'choices'  => get_posts_list_for_customizer('tours'),
        ));
    }
    
    // === 3. Услуги (5 слотов) ===
    $wp_customize->add_section('services_slots', array(
        'title'    => 'Услуги на главной (5 слотов)',
        'priority' => 33,
    ));
    
    for ($i = 1; $i <= 5; $i++) {
        $wp_customize->add_setting("service_slot_$i", array(
            'default' => '',
            'sanitize_callback' => 'absint'
        ));
        $wp_customize->add_control("service_slot_$i", array(
            'label'    => "Слот $i",
            'section'  => 'services_slots',
            'type'     => 'select',
            'choices'  => get_posts_list_for_customizer('services'),
        ));
    }
    
    // === 4. Отзывы (4 слота) ===
    $wp_customize->add_section('reviews_slots', array(
        'title'       => 'Отзывы на главной (4 слота)',
        'priority'    => 34,
        'description' => 'Выберите отзывы для отображения в 4 слотах на главной странице.',
    ));
    
    for ($i = 1; $i <= 4; $i++) {
        $wp_customize->add_setting("review_slot_$i", array(
            'default' => '',
            'sanitize_callback' => 'absint'
        ));
        $wp_customize->add_control("review_slot_$i", array(
            'label'    => "Слот $i",
            'section'  => 'reviews_slots',
            'type'     => 'select',
            'choices'  => get_posts_list_for_customizer('reviews', 'date', 'DESC'),
        ));
    }
}
add_action('customize_register', 'yurma_customize_register');

// ========== Тип записей "Экскурсии" ==========
function register_tours_cpt() {
    $labels = array(
        'name'               => 'Экскурсии',
        'singular_name'      => 'Экскурсия',
        'menu_name'          => 'Экскурсии',
        'add_new'            => 'Добавить экскурсию',
        'add_new_item'       => 'Добавить новую экскурсию',
        'edit_item'          => 'Редактировать экскурсию',
        'new_item'           => 'Новая экскурсия',
        'view_item'          => 'Просмотреть экскурсию',
        'search_items'       => 'Искать экскурсии',
        'not_found'          => 'Экскурсии не найдены',
        'not_found_in_trash' => 'В корзине нет экскурсий',
    );
    
    $args = array(
        'labels'       => $labels,
        'public'       => true,
        'menu_icon'    => 'dashicons-palmtree',
        'supports'     => array('title', 'thumbnail'),
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'tours'),
        'show_in_rest' => false,
    );
    
    register_post_type('tours', $args);
}
add_action('init', 'register_tours_cpt');

// ========== Метаполя для экскурсий ==========
function add_tours_meta_boxes() {
    add_meta_box('tours_details', 'Детали экскурсии', 'render_tours_meta_box', 'tours', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_tours_meta_boxes');

function render_tours_meta_box($post) {
    $price = get_post_meta($post->ID, 'tour_price', true);
    $length = get_post_meta($post->ID, 'tour_length', true);
    $duration = get_post_meta($post->ID, 'tour_duration', true);
    $difficulty = get_post_meta($post->ID, 'tour_difficulty', true);
    $image_id = get_post_meta($post->ID, 'tour_image_id', true);
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    ?>
    <div style="padding: 15px; background: #f9f9f9;">
        <p>
            <label>💰 Цена (₽):</label><br>
            <input type="number" name="tour_price" value="<?php echo esc_attr($price); ?>" style="width: 200px;">
        </p>
        <p>
            <label>📍 Длина (км):</label><br>
            <input type="text" name="tour_length" value="<?php echo esc_attr($length); ?>" style="width: 200px;" placeholder="например: 25 км">
        </p>
        <p>
            <label>⏱ Время:</label><br>
            <input type="text" name="tour_duration" value="<?php echo esc_attr($duration); ?>" style="width: 200px;" placeholder="например: 2-2.5 ч">
        </p>
        <p>
            <label>📊 Сложность:</label><br>
            <select name="tour_difficulty" style="width: 200px;">
                <option value="■□□□□" <?php selected($difficulty, '■□□□□'); ?>>1/5 (легкий)</option>
                <option value="■■□□□" <?php selected($difficulty, '■■□□□'); ?>>2/5 (средний)</option>
                <option value="■■■□□" <?php selected($difficulty, '■■■□□'); ?>>3/5 (выше среднего)</option>
                <option value="■■■■□" <?php selected($difficulty, '■■■■□'); ?>>4/5 (сложный)</option>
                <option value="■■■■■" <?php selected($difficulty, '■■■■■'); ?>>5/5 (очень сложный)</option>
            </select>
        </p>
        <p>
            <label>📷 Фото:</label><br>
            <input type="hidden" name="tour_image_id" value="<?php echo esc_attr($image_id); ?>">
            <div class="tour-image-preview">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <button type="button" class="button tour-upload-btn">Выбрать фото</button>
            <button type="button" class="button tour-remove-btn" style="display: <?php echo $image_url ? 'inline-block' : 'none'; ?>">Удалить фото</button>
        </p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        
        $('.tour-upload-btn').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var container = btn.closest('div');
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media({
                title: 'Выберите фото экскурсии',
                button: { text: 'Выбрать' },
                multiple: false
            });
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                container.find('input[name="tour_image_id"]').val(attachment.id);
                container.find('.tour-image-preview').html('<img src="' + attachment.url + '" style="max-width: 200px;">');
                container.find('.tour-remove-btn').show();
            });
            mediaUploader.open();
        });
        
        $('.tour-remove-btn').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var container = btn.closest('div');
            container.find('input[name="tour_image_id"]').val('');
            container.find('.tour-image-preview').html('');
            btn.hide();
        });
    });
    </script>
    <?php
}

function save_tours_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (get_post_type($post_id) != 'tours') return;
    
    if (isset($_POST['tour_price'])) {
        update_post_meta($post_id, 'tour_price', sanitize_text_field($_POST['tour_price']));
    }
    if (isset($_POST['tour_length'])) {
        update_post_meta($post_id, 'tour_length', sanitize_text_field($_POST['tour_length']));
    }
    if (isset($_POST['tour_duration'])) {
        update_post_meta($post_id, 'tour_duration', sanitize_text_field($_POST['tour_duration']));
    }
    if (isset($_POST['tour_difficulty'])) {
        update_post_meta($post_id, 'tour_difficulty', sanitize_text_field($_POST['tour_difficulty']));
    }
    if (isset($_POST['tour_image_id'])) {
        $image_id = intval($_POST['tour_image_id']);
        update_post_meta($post_id, 'tour_image_id', $image_id);
        if ($image_id) {
            set_post_thumbnail($post_id, $image_id);
        }
    }
}
add_action('save_post', 'save_tours_meta');

// ========== Тип записей "Услуги" ==========
function register_services_cpt() {
    $labels = array(
        'name'               => 'Услуги',
        'singular_name'      => 'Услуга',
        'menu_name'          => 'Услуги',
        'add_new'            => 'Добавить услугу',
        'add_new_item'       => 'Добавить новую услугу',
        'edit_item'          => 'Редактировать услугу',
        'new_item'           => 'Новая услуга',
        'view_item'          => 'Просмотреть услугу',
        'search_items'       => 'Искать услуги',
        'not_found'          => 'Услуги не найдены',
        'not_found_in_trash' => 'В корзине нет услуг',
    );
    
    $args = array(
        'labels'       => $labels,
        'public'       => true,
        'menu_icon'    => 'dashicons-hammer',
        'supports'     => array('title', 'thumbnail'),
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'services'),
        'show_in_rest' => false,
    );
    
    register_post_type('services', $args);
}
add_action('init', 'register_services_cpt');

// ========== Метаполя для услуг ==========
function add_services_meta_boxes() {
    add_meta_box('services_details', 'Детали услуги', 'render_services_meta_box', 'services', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_services_meta_boxes');

function render_services_meta_box($post) {
    $price = get_post_meta($post->ID, 'service_price', true);
    $price_unit = get_post_meta($post->ID, 'service_price_unit', true);
    $image_id = get_post_meta($post->ID, 'service_image_id', true);
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    ?>
    <div style="padding: 15px; background: #f9f9f9;">
        <p>
            <label>💰 Цена:</label><br>
            <input type="text" name="service_price" value="<?php echo esc_attr($price); ?>" style="width: 150px;" placeholder="например: 2 500">
            <select name="service_price_unit" style="width: 120px;">
                <option value="₽/день" <?php selected($price_unit, '₽/день'); ?>>₽/день</option>
                <option value="₽/час" <?php selected($price_unit, '₽/час'); ?>>₽/час</option>
                <option value="₽" <?php selected($price_unit, '₽'); ?>>₽</option>
                <option value="от 500 ₽" <?php selected($price_unit, 'от 500 ₽'); ?>>от 500 ₽</option>
            </select>
        </p>
        <p>
            <label>📷 Фото:</label><br>
            <input type="hidden" name="service_image_id" value="<?php echo esc_attr($image_id); ?>">
            <div class="service-image-preview">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 200px;">
                <?php endif; ?>
            </div>
            <button type="button" class="button service-upload-btn">Выбрать фото</button>
            <button type="button" class="button service-remove-btn" style="display: <?php echo $image_url ? 'inline-block' : 'none'; ?>">Удалить фото</button>
        </p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        
        $('.service-upload-btn').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var container = btn.closest('div');
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media({
                title: 'Выберите фото услуги',
                button: { text: 'Выбрать' },
                multiple: false
            });
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                container.find('input[name="service_image_id"]').val(attachment.id);
                container.find('.service-image-preview').html('<img src="' + attachment.url + '" style="max-width: 200px;">');
                container.find('.service-remove-btn').show();
            });
            mediaUploader.open();
        });
        
        $('.service-remove-btn').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var container = btn.closest('div');
            container.find('input[name="service_image_id"]').val('');
            container.find('.service-image-preview').html('');
            btn.hide();
        });
    });
    </script>
    <?php
}

function save_services_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (get_post_type($post_id) != 'services') return;
    
    if (isset($_POST['service_price'])) {
        update_post_meta($post_id, 'service_price', sanitize_text_field($_POST['service_price']));
    }
    if (isset($_POST['service_price_unit'])) {
        update_post_meta($post_id, 'service_price_unit', sanitize_text_field($_POST['service_price_unit']));
    }
    if (isset($_POST['service_image_id'])) {
        $image_id = intval($_POST['service_image_id']);
        update_post_meta($post_id, 'service_image_id', $image_id);
        if ($image_id) {
            set_post_thumbnail($post_id, $image_id);
        }
    }
}
add_action('save_post', 'save_services_meta');

// ========== Тип записей "Галерея" ==========
function register_gallery_cpt() {
    $labels = array(
        'name'               => 'Галерея',
        'singular_name'      => 'Фото',
        'menu_name'          => 'Галерея',
        'add_new'            => 'Добавить фото',
        'add_new_item'       => 'Добавить новое фото',
        'edit_item'          => 'Редактировать фото',
        'new_item'           => 'Новое фото',
        'view_item'          => 'Просмотреть фото',
        'search_items'       => 'Искать фото',
        'not_found'          => 'Фото не найдены',
        'not_found_in_trash' => 'В корзине нет фото',
    );
    
    $args = array(
        'labels'       => $labels,
        'public'       => true,
        'menu_icon'    => 'dashicons-format-gallery',
        'supports'     => array('title', 'thumbnail'),
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'gallery'),
        'show_in_rest' => true,
    );
    
    register_post_type('gallery', $args);
}
add_action('init', 'register_gallery_cpt');

// ========== Тип записей "Отзывы" ==========
function register_reviews_cpt() {
    $labels = array(
        'name'               => 'Отзывы',
        'singular_name'      => 'Отзыв',
        'menu_name'          => 'Отзывы',
        'add_new'            => 'Добавить отзыв',
        'add_new_item'       => 'Добавить новый отзыв',
        'edit_item'          => 'Редактировать отзыв',
        'new_item'           => 'Новый отзыв',
        'view_item'          => 'Просмотреть отзыв',
        'search_items'       => 'Искать отзывы',
        'not_found'          => 'Отзывы не найдены',
        'not_found_in_trash' => 'В корзине нет отзывов',
    );
    
    $args = array(
        'labels'       => $labels,
        'public'       => true,
        'menu_icon'    => 'dashicons-star-filled',
        'supports'     => array('title', 'editor'),
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'reviews'),
        'show_in_rest' => true,
    );
    
    register_post_type('reviews', $args);
}
add_action('init', 'register_reviews_cpt');

// ========== Метаполя для отзывов ==========
function add_reviews_meta_boxes() {
    add_meta_box('reviews_details', 'Детали отзыва', 'render_reviews_meta_box', 'reviews', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_reviews_meta_boxes');

function render_reviews_meta_box($post) {
    $rating = get_post_meta($post->ID, 'review_rating', true);
    $author = get_post_meta($post->ID, 'review_author', true);
    ?>
    <div style="padding: 15px; background: #f9f9f9;">
        <p>
            <label>👤 Имя автора:</label><br>
            <input type="text" name="review_author" value="<?php echo esc_attr($author); ?>" style="width: 100%; max-width: 300px;" placeholder="Например: Алексей">
        </p>
        <p>
            <label>⭐ Оценка (1-5):</label><br>
            <select name="review_rating" style="width: 150px;">
                <option value="5" <?php selected($rating, '5'); ?>>★★★★★ (5)</option>
                <option value="4" <?php selected($rating, '4'); ?>>★★★★☆ (4)</option>
                <option value="3" <?php selected($rating, '3'); ?>>★★★☆☆ (3)</option>
                <option value="2" <?php selected($rating, '2'); ?>>★★☆☆☆ (2)</option>
                <option value="1" <?php selected($rating, '1'); ?>>★☆☆☆☆ (1)</option>
            </select>
        </p>
    </div>
    <?php
}

function save_reviews_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (get_post_type($post_id) != 'reviews') return;
    
    if (isset($_POST['review_author'])) {
        update_post_meta($post_id, 'review_author', sanitize_text_field($_POST['review_author']));
    }
    if (isset($_POST['review_rating'])) {
        update_post_meta($post_id, 'review_rating', sanitize_text_field($_POST['review_rating']));
    }
}
add_action('save_post', 'save_reviews_meta');

// ========== ОБРАБОТКА ФОРМЫ ОТЗЫВА ==========
function handle_review_submission() {
    if (isset($_POST['submit_review']) && isset($_POST['review_author']) && !empty($_POST['review_author'])) {
        
        $author = sanitize_text_field($_POST['review_author']);
        $rating = intval($_POST['review_rating']);
        $content = sanitize_textarea_field($_POST['review_content']);
        
        if (!empty($author) && !empty($content)) {
            $post_data = array(
                'post_title'   => 'Отзыв от ' . $author,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'reviews',
            );
            
            $post_id = wp_insert_post($post_data);
            
            if ($post_id) {
                update_post_meta($post_id, 'review_author', $author);
                update_post_meta($post_id, 'review_rating', $rating);
            }
        }
        
        $current_url = wp_get_referer();
        if (!$current_url) {
            $current_url = home_url('/reviews/');
        }
        
        wp_redirect($current_url . '?review_sent=1');
        exit;
    }
}
add_action('init', 'handle_review_submission');

// Уведомление об успешной отправке
add_action('wp_head', function() {
    if (isset($_GET['review_sent']) && $_GET['review_sent'] == 1) {
        echo '<script>alert("Спасибо за ваш отзыв!");</script>';
    }
});

// ========== ФУНКЦИЯ ДЛЯ ПОЛУЧЕНИЯ СТАТИСТИКИ ОТЗЫВОВ ==========
function get_reviews_statistics() {
    global $wpdb;
    
    $result = $wpdb->get_row("
        SELECT 
            ROUND(AVG(CAST(meta_value AS DECIMAL(10,1))), 1) as avg_rating,
            COUNT(*) as total_reviews
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE p.post_type = 'reviews' 
            AND p.post_status = 'publish'
            AND pm.meta_key = 'review_rating'
    ");
    
    return array(
        'avg_rating' => ($result && $result->avg_rating > 0) ? $result->avg_rating : 0,
        'total_reviews' => $result ? intval($result->total_reviews) : 0
    );
}

// ========== УНИВЕРСАЛЬНЫЕ ФУНКЦИИ ДЛЯ КАРТОЧЕК ==========
function get_post_card_data($post, $post_type) {
    $post_id = $post->ID;
    $image_id = get_post_meta($post_id, $post_type . '_image_id', true);
    
    $data = array(
        'id' => $post_id,
        'title' => get_the_title(),
        'image_url' => $image_id ? wp_get_attachment_url($image_id) : get_template_directory_uri() . '/resource/img/placeholder.jpg',
        'price' => null,
        'formatted_price' => null,
        'button_text' => 'заказать'
    );
    
    switch ($post_type) {
        case 'rooms':
            $data['price'] = get_post_meta($post_id, 'room_price', true);
            $data['formatted_price'] = $data['price'] ? number_format($data['price'], 0, '', ' ') . ' ₽' : null;
            $data['capacity'] = get_post_meta($post_id, 'room_capacity', true);
            $data['features'] = get_post_meta($post_id, 'room_features', true);
            $data['features_list'] = $data['features'] ? explode("\n", $data['features']) : array();
            break;
            
        case 'tours':
            $data['price'] = get_post_meta($post_id, 'tour_price', true);
            $data['formatted_price'] = $data['price'] ? number_format($data['price'], 0, '', ' ') . ' ₽' : null;
            $data['length'] = get_post_meta($post_id, 'tour_length', true);
            $data['duration'] = get_post_meta($post_id, 'tour_duration', true);
            $data['difficulty'] = get_post_meta($post_id, 'tour_difficulty', true);
            break;
            
        case 'services':
            $data['price'] = get_post_meta($post_id, 'service_price', true);
            $data['price_unit'] = get_post_meta($post_id, 'service_price_unit', true);
            $data['formatted_price'] = $data['price'] ? $data['price'] . ' ' . $data['price_unit'] : null;
            break;
    }
    
    return $data;
}

function get_posts_query($post_type, $args = array()) {
    $defaults = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    );
    
    $args = wp_parse_args($args, $defaults);
    
    if ($post_type == 'reviews') {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    }
    
    return new WP_Query($args);
}
?>