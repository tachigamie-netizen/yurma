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
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'rooms'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-building',
        'supports'            => array('title'), // Убираем стандартный редактор
        'show_in_rest'        => false,
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

// ========== Кастомный метабокс ==========
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
        
        <!-- Название номера -->
        <div class="rooms-form-group">
            <label>🏷️ Название номера</label>
            <input type="text" name="post_title" value="<?php echo esc_attr($post->post_title); ?>" placeholder="Например: Комфорт с балконом">
        </div>
        
        <!-- Фото номера -->
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
        
        <!-- Цена и вместимость -->
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
        
        <!-- Удобства -->
        <div class="rooms-form-group">
            <label>📋 Удобства</label>
            <textarea name="room_features" rows="5" placeholder="Двуспальная кровать&#10;TV Триколор&#10;Душ и туалет&#10;Балкон"><?php echo esc_textarea($features); ?></textarea>
            <div class="rooms-help">Каждое удобство с новой строки</div>
        </div>
        
        <!-- Описание -->
        <div class="rooms-form-group">
            <label>📝 Описание номера</label>
            <textarea name="room_description" rows="8" placeholder="Подробное описание номера..."><?php echo esc_textarea($description); ?></textarea>
            <div class="rooms-help">Подробное описание для страницы номера</div>
        </div>
        
        <!-- Кнопка сохранения -->
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

// ========== Сохранение данных ==========
function save_rooms_meta($post_id) {
    // Защита от автосохранения
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Проверяем права
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Проверяем тип записи
    if (get_post_type($post_id) != 'rooms') {
        return;
    }
    
    // Сохраняем название
    if (isset($_POST['post_title']) && !empty($_POST['post_title'])) {
        remove_action('save_post', 'save_rooms_meta');
        wp_update_post(array(
            'ID' => $post_id,
            'post_title' => sanitize_text_field($_POST['post_title'])
        ));
        add_action('save_post', 'save_rooms_meta');
    }
    
    // Сохраняем метаполя
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

// ========== Настройки темы (4 слота) ==========
function get_rooms_list() {
    $choices = array('' => '— Не выбрано —');
    $rooms = get_posts(array(
        'post_type' => 'rooms',
        'numberposts' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    foreach ($rooms as $room) {
        $choices[$room->ID] = $room->post_title;
    }
    return $choices;
}

function yurma_customize_register($wp_customize) {
    $wp_customize->add_section('rooms_slots', array(
        'title'    => 'Номера на главной (4 слота)',
        'priority' => 31,
    ));
    
    $wp_customize->add_setting('room_slot_1', array('default' => '', 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('room_slot_1', array(
        'label'    => 'Слот 1',
        'section'  => 'rooms_slots',
        'type'     => 'select',
        'choices'  => get_rooms_list(),
    ));
    
    $wp_customize->add_setting('room_slot_2', array('default' => '', 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('room_slot_2', array(
        'label'    => 'Слот 2',
        'section'  => 'rooms_slots',
        'type'     => 'select',
        'choices'  => get_rooms_list(),
    ));
    
    $wp_customize->add_setting('room_slot_3', array('default' => '', 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('room_slot_3', array(
        'label'    => 'Слот 3',
        'section'  => 'rooms_slots',
        'type'     => 'select',
        'choices'  => get_rooms_list(),
    ));
    
    $wp_customize->add_setting('room_slot_4', array('default' => '', 'sanitize_callback' => 'absint'));
    $wp_customize->add_control('room_slot_4', array(
        'label'    => 'Слот 4',
        'section'  => 'rooms_slots',
        'type'     => 'select',
        'choices'  => get_rooms_list(),
    ));
    
    // ========== Выбор экскурсий на главной (4 слота) ==========
$wp_customize->add_section('tours_slots', array(
    'title'    => 'Экскурсии на главной (4 слота)',
    'priority' => 32,
    'description' => 'Выберите экскурсии для отображения в 4 слотах на главной странице.',
));

// Функция для получения списка экскурсий
function get_tours_list() {
    $choices = array('' => '— Не выбрано —');
    $tours = get_posts(array(
        'post_type' => 'tours',
        'numberposts' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    foreach ($tours as $tour) {
        $choices[$tour->ID] = $tour->post_title;
    }
    return $choices;
}

// Слот 1
$wp_customize->add_setting('tour_slot_1', array('default' => '', 'sanitize_callback' => 'absint'));
$wp_customize->add_control('tour_slot_1', array(
    'label'    => 'Слот 1',
    'section'  => 'tours_slots',
    'type'     => 'select',
    'choices'  => get_tours_list(),
));

// Слот 2
$wp_customize->add_setting('tour_slot_2', array('default' => '', 'sanitize_callback' => 'absint'));
$wp_customize->add_control('tour_slot_2', array(
    'label'    => 'Слот 2',
    'section'  => 'tours_slots',
    'type'     => 'select',
    'choices'  => get_tours_list(),
));

// Слот 3
$wp_customize->add_setting('tour_slot_3', array('default' => '', 'sanitize_callback' => 'absint'));
$wp_customize->add_control('tour_slot_3', array(
    'label'    => 'Слот 3',
    'section'  => 'tours_slots',
    'type'     => 'select',
    'choices'  => get_tours_list(),
));

// Слот 4
$wp_customize->add_setting('tour_slot_4', array('default' => '', 'sanitize_callback' => 'absint'));
$wp_customize->add_control('tour_slot_4', array(
    'label'    => 'Слот 4',
    'section'  => 'tours_slots',
    'type'     => 'select',
    'choices'  => get_tours_list(),
));

// ========== Выбор услуг на главной (4 слота) ==========
$wp_customize->add_section('services_slots', array(
    'title'    => 'Услуги на главной (4 слота)',
    'priority' => 33,
));

function get_services_list() {
    $choices = array('' => '— Не выбрано —');
    $services = get_posts(array(
        'post_type' => 'services',
        'numberposts' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    foreach ($services as $service) {
        $choices[$service->ID] = $service->post_title;
    }
    return $choices;
}

$wp_customize->add_setting('service_slot_1', array('default' => '', 'sanitize_callback' => 'absint'));
$wp_customize->add_control('service_slot_1', array(
    'label'    => 'Слот 1',
    'section'  => 'services_slots',
    'type'     => 'select',
    'choices'  => get_services_list(),
));

$wp_customize->add_setting('service_slot_2', array('default' => '', 'sanitize_callback' => 'absint'));
$wp_customize->add_control('service_slot_2', array(
    'label'    => 'Слот 2',
    'section'  => 'services_slots',
    'type'     => 'select',
    'choices'  => get_services_list(),
));

$wp_customize->add_setting('service_slot_3', array('default' => '', 'sanitize_callback' => 'absint'));
$wp_customize->add_control('service_slot_3', array(
    'label'    => 'Слот 3',
    'section'  => 'services_slots',
    'type'     => 'select',
    'choices'  => get_services_list(),
));

$wp_customize->add_setting('service_slot_4', array('default' => '', 'sanitize_callback' => 'absint'));
$wp_customize->add_control('service_slot_4', array(
    'label'    => 'Слот 4',
    'section'  => 'services_slots',
    'type'     => 'select',
    'choices'  => get_services_list(),
));
// Слот 5
$wp_customize->add_setting('service_slot_5', array('default' => '', 'sanitize_callback' => 'absint'));
$wp_customize->add_control('service_slot_5', array(
    'label'    => 'Слот 5',
    'section'  => 'services_slots',
    'type'     => 'select',
    'choices'  => get_services_list(),
));
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



// ========== Тип записей "Галерея" (упрощенный) ==========
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



// Логируем все события Contact Form 7
add_action('wpcf7_before_send_mail', function($contact_form) {
    file_put_contents(__DIR__ . '/cf7_debug.txt', date('H:i:s') . " - before_send_mail вызван\n", FILE_APPEND);
});

add_action('wpcf7_mail_sent', function($contact_form) {
    file_put_contents(__DIR__ . '/cf7_debug.txt', date('H:i:s') . " - mail_sent (успех)\n", FILE_APPEND);
});

add_action('wpcf7_mail_failed', function($contact_form) {
    file_put_contents(__DIR__ . '/cf7_debug.txt', date('H:i:s') . " - mail_failed (ошибка)\n", FILE_APPEND);
});
add_action('wpcf7_mail_sent', function($contact_form) {
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        $data = $submission->get_posted_data();
        file_put_contents(__DIR__ . '/cf7_data.txt', date('H:i:s') . " - Имя: " . ($data['your-name'] ?? '') . " Телефон: " . ($data['your-phone'] ?? '') . "\n", FILE_APPEND);
    }
});