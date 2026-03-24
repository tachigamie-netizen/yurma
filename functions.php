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
}
add_action('customize_register', 'yurma_customize_register');


?>