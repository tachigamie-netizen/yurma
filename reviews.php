<?php
/*
Template Name: Отзывы
*/
get_header(); ?>

<main>
    <section class="section reviews-page">
        <div class="container">
            <h2>Отзывы</h2>
            
            <!-- Показываем сообщение об успехе -->
            <?php if (isset($_GET['review_sent']) && $_GET['review_sent'] == 1) : ?>
                <div class="review-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    Спасибо за ваш отзыв!
                </div>
            <?php endif; ?>
            
            <!-- Блок среднего рейтинга -->
            <div class="rating-wrapper">
                <?php 
                $stats = get_reviews_statistics();
                $average_rating = $stats['avg_rating'];
                $total_reviews = $stats['total_reviews'];
                ?>
                
                <?php if ($total_reviews > 0) : ?>
                <div class="rating-summary">
                    <span class="rating-summary-number"><?php echo number_format($average_rating, 1); ?></span>
                    <span class="rating-summary-total"><?php echo $total_reviews; ?> <?php 
                        $last_digit = $total_reviews % 10;
                        $last_two = $total_reviews % 100;
                        if ($last_two >= 11 && $last_two <= 14) echo 'отзывов';
                        elseif ($last_digit == 1) echo 'отзыв';
                        elseif ($last_digit >= 2 && $last_digit <= 4) echo 'отзыва';
                        else echo 'отзывов';
                    ?></span>
                </div>
                <?php endif; ?>
                
                <!-- Кнопка открытия формы -->
                <div class="reviews-button-wrapper">
                    <button class="btn btn-primary" onclick="openReviewModal()">Оставить отзыв</button>
                </div>
            </div>

            <!-- Список отзывов -->
            <div class="reviews-list">
                <?php
                $reviews_query = new WP_Query(array(
                    'post_type' => 'reviews',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($reviews_query->have_posts()) :
                    while ($reviews_query->have_posts()) : $reviews_query->the_post();
                        $author = get_post_meta(get_the_ID(), 'review_author', true);
                        $rating = get_post_meta(get_the_ID(), 'review_rating', true);
                        if (!$author) $author = 'Аноним';
                        if (!$rating) $rating = 5;
                        
                        $stars = '';
                        for ($i = 1; $i <= 5; $i++) {
                            $stars .= ($i <= $rating) ? '★' : '☆';
                        }
                        ?>
                        <div class="review-card-horizontal">
                            <div class="review-header">
                                <h3><?php echo esc_html($author); ?></h3>
                                <div class="review-rating"><?php echo $stars; ?></div>
                            </div>
                            <div class="review-content">
                                <?php the_content(); ?>
                            </div>
                            <div class="review-date">
                                <?php echo get_the_date('d.m.Y'); ?>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                else : ?>
                    <p class="no-reviews">Пока нет отзывов. Будьте первым!</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<!-- Модальное окно для отзыва -->
<div id="reviewModal" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <h3>Оставить отзыв</h3>
        <form method="post" action="">
            <input type="hidden" name="submit_review" value="1">
            <div class="form-row">
                <label>Ваше имя <span class="required">*</span></label>
                <input type="text" name="review_author" required>
            </div>
            <div class="form-row">
                <label>Оценка <span class="required">*</span></label>
                <select name="review_rating" required>
                    <option value="5">★★★★★ (5) - Отлично</option>
                    <option value="4">★★★★☆ (4) - Хорошо</option>
                    <option value="3">★★★☆☆ (3) - Нормально</option>
                    <option value="2">★★☆☆☆ (2) - Плохо</option>
                    <option value="1">★☆☆☆☆ (1) - Ужасно</option>
                </select>
            </div>
            <div class="form-row">
                <label>Ваш отзыв <span class="required">*</span></label>
                <textarea name="review_content" rows="5" required placeholder="Расскажите о вашем опыте..."></textarea>
            </div>
            <div class="form-row">
                <button type="submit" class="btn btn-primary">Отправить отзыв</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReviewModal() {
    document.getElementById('reviewModal').style.display = 'block';
}
function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

// Закрытие по клику вне окна
window.onclick = function(event) {
    var modal = document.getElementById('reviewModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<?php get_footer(); ?>