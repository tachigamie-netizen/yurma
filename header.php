<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="header">
    <div class="container header-container">
        <a href="<?php echo home_url('/'); ?>" class="logo">
            <img src="<?php echo get_template_directory_uri(); ?>/resource/img/logo.svg" alt="Логотип">
        </a>

        <!-- Бургер-кнопка (только на мобильных) -->
        <button class="burger-menu" aria-label="Меню">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Навигация (обернута в overlay для мобильной версии) -->
        <div class="nav-wrapper">
            <nav>
                <ul>
                    <li><a href="<?php echo home_url('/проживание/'); ?>">Проживание</a></li>
                    <li><a href="<?php echo home_url('/маршруты/'); ?>">Экскурсии</a></li>
                    <li><a href="<?php echo home_url('/услуги/'); ?>">Услуги</a></li>
                    <li><a href="<?php echo home_url('/галерея/'); ?>">Галерея</a></li>
                    <li><a href="<?php echo home_url('/отзывы/'); ?>">Отзывы</a></li>
                    <li><a href="<?php echo home_url('/о-нас/'); ?>">О Нас</a></li>
                </ul>
            </nav>
            <!-- Кнопка WhatsApp внутри мобильного меню -->
            <a href="#" class="btn btn-whatsapp mobile-wa">WhatsApp</a>
        </div>

        <!-- Кнопка WhatsApp для десктопа -->
        <a href="#" class="btn btn-whatsapp desktop-wa">WhatsApp</a>
    </div>
</header>

<main>