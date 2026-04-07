<?php
/*
Template Name: Все фото
*/
get_header(); ?>

<main>
    <section class="section gallery">
        <div class="container">
            <h2>Все фотографии</h2>
            
            <div class="gallery-all-grid">
                <?php
                $all_gallery = new WP_Query(array(
                    'post_type' => 'gallery',
                    'posts_per_page' => -1,
                    'post_status' => 'publish'
                ));
                
                if ($all_gallery->have_posts()) :
                    while ($all_gallery->have_posts()) : $all_gallery->the_post();
                        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        if ($image_url) : ?>
                            <div class="gallery-all-item">
                                <a href="<?php echo esc_url($image_url); ?>" data-lightbox="gallery-all" data-title="<?php the_title(); ?>">
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>">
                                </a>
                            </div>
                        <?php endif;
                    endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p>Фотографии пока не добавлены. Зайдите в админку и добавьте фото.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>