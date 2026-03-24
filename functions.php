<?php
// Поддержка стандартных функций WordPress
add_theme_support('title-tag');
add_theme_support('post-thumbnails');

// Подключаем CSS и JS
function yurma_enqueue_assets() {
    wp_enqueue_style('yurma-style', get_stylesheet_uri(), array(), '1.0');    
    wp_enqueue_script('yurma-script', get_template_directory_uri() . '/script.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'yurma_enqueue_assets');

// Добавляем настройки для hero-блока
function yurma_customize_register($wp_customize) {
    // Секция Hero
    $wp_customize->add_section('hero_section', array(
        'title'    => 'Hero блок',
        'priority' => 30,
    ));
    
    // Поле для заголовка
    $wp_customize->add_setting('hero_title', array(
        'default'           => 'ХРЕБЕТ ЮРМА',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_title', array(
        'label'    => 'Заголовок',
        'section'  => 'hero_section',
        'type'     => 'text',
    ));
    
    // Поле для подзаголовка
    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => 'активный отдых на южном урале',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_subtitle', array(
        'label'    => 'Подзаголовок',
        'section'  => 'hero_section',
        'type'     => 'text',
    ));
    
    // Поле для фоновой картинки
    $wp_customize->add_setting('hero_background', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_background', array(
        'label'    => 'Фоновая картинка',
        'section'  => 'hero_section',
        'settings' => 'hero_background',
    )));
}
add_action('customize_register', 'yurma_customize_register');
?>