<?php
/**
 * Tour Review Section wrapper
 * Calls the shared reusable review-section component
 */
?>

<?php
/**
 * Shared Review Section Component
 * Uses WordPress native comments system.
 * Rating (1-5) is stored in comment meta key: 'rating'
 */

$post_id = get_the_ID();
$post_type = get_post_type($post_id);
$reviews_per_page = 5;
$current_page = max(1, get_query_var('cpage', 1));

// Fetch approved comments
$approved_comments = get_comments([
    'post_id' => $post_id,
    'status' => 'approve',
    'type' => 'comment',
]);

$total_reviews = count($approved_comments);

// Compute average rating & breakdown
$rating_sum = 0;
$rating_counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

foreach ($approved_comments as $rc) {
    $r = intval(get_comment_meta($rc->comment_ID, 'rating', true));
    if ($r >= 1 && $r <= 5) {
        $rating_sum += $r;
        $rating_counts[$r]++;
    }
}

$avg_rating = $total_reviews > 0 ? round($rating_sum / $total_reviews, 1) : 0;

// Fetch unapproved comment if just submitted
$unapproved_comment = null;
$unapproved_id = isset($_GET['unapproved']) ? intval($_GET['unapproved']) : 0;
if ($unapproved_id > 0) {
    $temp_comment = get_comment($unapproved_id);
    if ($temp_comment && intval($temp_comment->comment_post_ID) === $post_id && $temp_comment->comment_approved === '0') {
        // Verify moderation hash
        $expected_hash = isset($_GET['moderation-hash']) ? sanitize_text_field($_GET['moderation-hash']) : '';
        if ($expected_hash === wp_hash($temp_comment->comment_date_gmt)) {
            $unapproved_comment = $temp_comment;
        }
    }
}

// Paginated comments
$paginated = get_comments([
    'post_id' => $post_id,
    'status' => 'approve',
    'type' => 'comment',
    'number' => $reviews_per_page,
    'offset' => ($current_page - 1) * $reviews_per_page,
    'order' => 'DESC',
    'orderby' => 'comment_date',
]);

if ($unapproved_comment) {
    array_unshift($paginated, $unapproved_comment);
}

$total_pages = $total_reviews > 0 ? ceil($total_reviews / $reviews_per_page) : 1;

if (!function_exists('rc_render_stars')) {
    function rc_render_stars(float $rating, string $size = '18'): string
    {
        $out = '<div class="ui-stars" style="--star-size: ' . esc_attr($size) . 'px;">';
        for ($i = 1; $i <= 5; $i++) {
            $is_active = ($rating >= $i || $rating >= $i - 0.5);
            $class = $is_active ? 'ui-star ui-star--filled' : 'ui-star ui-star--empty';
            $out .= '<span class="' . $class . '">' . rc_svg_icon('star') . '</span>';
        }
        $out .= '</div>';
        return $out;
    }
}

// Determine singular/plural labels and title icons
$post_type_label = ($post_type === 'cars') ? 'car' : 'tour';
$section_id = ($post_type === 'cars') ? 'car-review' : 'tour-review';
$empty_message = ($post_type === 'cars') ? 'Be the first to leave a review for this car!' : 'Be the first to leave a review for this tour!';
?>

<div id="<?php echo esc_attr($section_id); ?>" class="ui-reviews">
    <h2 class="ui-heading ui-reviews__title">
        <?php esc_html_e('Guest Reviews', 'rc'); ?>
    </h2>

    <?php if ($total_reviews > 0 || $unapproved_comment): ?>
        <div class="ui-reviews__container">
            <div class="ui-reviews__list-wrapper">
                <div class="ui-reviews__list">
                    <?php foreach ($paginated as $review):
                        $reviewer_rating = intval(get_comment_meta($review->comment_ID, 'rating', true));
                        $initials = strtoupper(mb_substr($review->comment_author, 0, 1));
                        ?>
                        <div class="ui-reviews__card" id="comment-<?php echo esc_attr($review->comment_ID); ?>">
                            <div class="ui-reviews__card-header">
                                <div class="ui-reviews__avatar">
                                    <?php echo esc_html($initials); ?>
                                </div>
                                <div class="ui-reviews__author-meta">
                                    <h4 class="ui-reviews__author-name">
                                        <?php echo esc_html($review->comment_author); ?>
                                    </h4>
                                    <span class="ui-reviews__date">
                                        <?php echo esc_html(date_i18n('F j, Y', strtotime($review->comment_date))); ?>
                                    </span>
                                </div>
                                <?php if ($reviewer_rating): ?>
                                    <div class="ui-reviews__card-rating">
                                        <?php echo rc_render_stars($reviewer_rating, '16'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="ui-reviews__card-content">
                                <?php echo wp_kses_post(wpautop($review->comment_content)); ?>
                            </div>
                            <?php if ($review->comment_approved == '0'): ?>
                                <div class="ui-reviews__card-pending">
                                    <?php echo rc_svg_icon('alert_circle'); ?>
                                    <em>
                                        <?php esc_html_e('Your review is awaiting moderation.', 'rc'); ?>
                                    </em>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="ui-reviews__pagination">
                        <?php
                        echo paginate_comments_links([
                            'base' => add_query_arg('cpage', '%#%'),
                            'format' => '',
                            'total' => $total_pages,
                            'current' => $current_page,
                            'prev_text' => '&laquo; Prev',
                            'next_text' => 'Next &raquo;',
                            'type' => 'list',
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="ui-reviews__empty">
            <?php echo rc_svg_icon('message_square'); ?>
            <p>
                <?php echo esc_html($empty_message); ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Review Form -->
    <?php if (comments_open($post_id)): ?>
        <div class="ui-reviews__form-container">
            <h3 class="ui-reviews__form-title">
                <?php esc_html_e('Write a Review', 'rc'); ?>
            </h3>

            <?php if (isset($_GET['unapproved'])): ?>
                <div class="ui-reviews__notice ui-reviews__notice--pending">
                    <?php echo rc_svg_icon('alert_circle'); ?>
                    <?php esc_html_e('Your review has been submitted and is awaiting moderation. Thank you!', 'rc'); ?>
                </div>
            <?php elseif (isset($_GET['approved'])): ?>
                <div class="ui-reviews__notice ui-reviews__notice--success">
                    <?php echo rc_svg_icon('check_circle'); ?>
                    <?php esc_html_e('Your review has been published. Thank you!', 'rc'); ?>
                </div>
            <?php endif; ?>

            <form id="rc-review-form" action="<?php echo esc_url(site_url('/wp-comments-post.php')); ?>" method="post"
                class="ui-reviews__form" novalidate>

                <!-- Row 1: Name and Email -->
                <div class="ui-reviews__form-row">
                    <!-- Name -->
                    <div class="ui-reviews__field">
                        <label class="ui-reviews__label" for="rc-author">
                            <?php esc_html_e('Name', 'rc'); ?> <span class="required">*</span>
                        </label>
                        <input type="text" id="rc-author" name="author" class="ui-reviews__input"
                            placeholder="<?php esc_attr_e('Your full name', 'rc'); ?>" required maxlength="245"
                            value="<?php echo esc_attr(isset($_POST['author']) ? $_POST['author'] : ''); ?>">
                    </div>
                    <!-- Email -->
                    <div class="ui-reviews__field">
                        <label class="ui-reviews__label" for="rc-email">
                            <?php esc_html_e('Email', 'rc'); ?> <span class="required">*</span>
                        </label>
                        <input type="email" id="rc-email" name="email" class="ui-reviews__input"
                            placeholder="<?php esc_attr_e('your@email.com', 'rc'); ?>" required maxlength="100"
                            value="<?php echo esc_attr(isset($_POST['email']) ? $_POST['email'] : ''); ?>">
                    </div>
                </div>

                <!-- Row 2: Title -->
                <div class="ui-reviews__field">
                    <label class="ui-reviews__label" for="rc-title">
                        <?php esc_html_e('Title', 'rc'); ?>
                    </label>
                    <input type="text" id="rc-title" name="title" class="ui-reviews__input"
                        placeholder="<?php esc_attr_e('Review Title', 'rc'); ?>" maxlength="245"
                        value="<?php echo esc_attr(isset($_POST['title']) ? $_POST['title'] : ''); ?>">
                </div>

                <!-- Row 3: Ratings and Content -->
                <div class="ui-reviews__form-row">
                    <!-- Left: Ratings -->
                    <div class="ui-reviews__ratings-col">

                        <?php if ($post_type === 'tours'): ?>
                            <!-- Category Ratings -->
                            <label class="ui-reviews__label">
                                <?php esc_html_e('Rating', 'rc'); ?> <span class="required">*</span>
                            </label>

                            <div class="ui-reviews__field ui-reviews__field--rating">

                                <?php
                                $rating_categories = [
                                    'services' => __('Services', 'rc'),
                                    'driver' => __('Driver', 'rc'),
                                    'experiences' => __('Experiences', 'rc'),
                                ];
                                foreach ($rating_categories as $key => $label):
                                    ?>
                                    <div class="ui-reviews__field--item">
                                        <label class="ui-reviews__label"><?php echo esc_html($label); ?>:</label>
                                        <div class="star-picker" id="star-picker-<?php echo esc_attr($key); ?>" role="radiogroup"
                                            aria-label="<?php echo esc_attr($label); ?> Rating">
                                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                                <label class="star-picker__label" for="star-<?php echo esc_attr($key . '-' . $s); ?>"
                                                    title="<?php printf(esc_attr__('%d star%s', 'rc'), $s, $s > 1 ? 's' : ''); ?>">
                                                    <input type="radio" class="star-picker__input"
                                                        name="rc_tour_rating_<?php echo esc_attr($key); ?>"
                                                        id="star-<?php echo esc_attr($key . '-' . $s); ?>"
                                                        value="<?php echo esc_attr($s); ?>" required>
                                                    <?php echo rc_svg_icon('star'); ?>
                                                </label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Right: Content -->
                    <div class="ui-reviews__field ui-reviews__field--comment">
                        <label class="ui-reviews__label" for="rc-comment">
                            <?php esc_html_e('Review', 'rc'); ?> <span class="required">*</span>
                        </label>
                        <textarea id="rc-comment" name="comment" class="ui-reviews__textarea"
                            placeholder="<?php printf(esc_attr__('Share your experience with this %s...', 'rc'), $post_type_label); ?>"
                            required maxlength="65525"></textarea>
                    </div>
                </div>

                <!-- WP hidden fields -->
                <?php wp_nonce_field('comment_nonce_action', 'comment_nonce_field'); ?>
                <input type="hidden" name="comment_post_ID" value="<?php echo esc_attr($post_id); ?>">
                <input type="hidden" name="comment_parent" value="0">

                <button type="submit" class="ui-button ui-reviews__submit">
                    <?php esc_html_e('Submit Review', 'rc'); ?>
                    <?php echo rc_svg_icon('button_arrow'); ?>
                </button>
            </form>
        </div>
    <?php endif; ?>

</div>