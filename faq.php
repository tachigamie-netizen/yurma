<?php
/*
Template Name: FAQ
*/
get_header(); ?>

<main>
    <section class="section faq-page">
        <div class="container">
            <h2>Часто задаваемые вопросы</h2>
            
            <div class="faq-grid">
                <?php
                $faq_query = new WP_Query(array(
                    'post_type' => 'faq',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'meta_key' => 'faq_order',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC'
                ));
                
                if ($faq_query->have_posts()) :
                    while ($faq_query->have_posts()) : $faq_query->the_post();
                        ?>
                        <div class="faq-item">
                            <h3><?php the_title(); ?></h3>
                            <div class="faq-answer">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p>Вопросы пока не добавлены. Зайдите в админку → FAQ → Добавить вопрос</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>