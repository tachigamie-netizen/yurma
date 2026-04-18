<?php
/*
Template Name: Все услуги
*/
get_header(); ?>

<main>
    <section class="section services">
        <div class="container">
            <h2>Все услуги</h2>
            
            <div class="card-grid">
                <?php
                $services_query = new WP_Query(array(
                    'post_type' => 'services',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'title',
                    'order' => 'ASC'
                ));
                
                if ($services_query->have_posts()) :
                    while ($services_query->have_posts()) : $services_query->the_post();
                        $service_id = get_the_ID();
                        $price = get_post_meta($service_id, 'service_price', true);
                        $price_unit = get_post_meta($service_id, 'service_price_unit', true);
                        $image_id = get_post_meta($service_id, 'service_image_id', true);
                        ?>
                        <div class="card service-card">
                            <?php if ($image_id) : ?>
                                <div class="service-card-image">
                                    <img src="<?php echo wp_get_attachment_url($image_id); ?>" alt="<?php the_title(); ?>">
                                </div>
                            <?php else : ?>
                                <div class="service-card-image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/resource/img/placeholder.jpg" alt="Услуга">
                                </div>
                            <?php endif; ?>
                            <div class="card-content">
                                <h3><?php the_title(); ?></h3>
                                <span class="price"><?php echo esc_html($price); ?> <?php echo esc_html($price_unit); ?></span>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p>Услуги пока не добавлены.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>