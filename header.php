<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    
  <body>
    <!-- ШАПКА -->
    <header class="header">
        <div class="container header_container">
            <a href="index.html" class="logo">
                <img src="<?php echo get_template_directory_uri(); ?>/resource/img/logo.svg" alt="Логотип">
            </a>

            <!-- Навигация -->
            <nav>
                <ul>
                    <li><a href="<?php echo home_url('/проживание/'); ?>">Проживание</a></li>
                    <li><a href="<?php echo home_url('/маршруты/'); ?>">Экскурсии</a></li> 
                    <li><a href="<?php echo home_url('/услуги/'); ?>">Услуги</a></li>
                    <li><a href="<?php echo home_url('/галерея/'); ?>">Галерея</a></li>
                    <li><a href="<?php echo home_url('/отзывы/'); ?>">Отзывы</a></li>
                    <!-- <li><a href="#">О нас</a></li> -->
                </ul>
            </nav>
            <a href="#" class="btn btn-whatsapp">WhatsApp</a>
        </div>
    </header>