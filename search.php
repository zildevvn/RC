<?php
/**
 * The template for displaying search results pages
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 * @package rc
 */

get_header();
global $wp_query;
$search_query = trim(get_search_query());
$total_results = $wp_query->found_posts;
$bg_banner = get_field('bg_banner', 'option');
$bg_banner_url = '';
$bg_banner_alt = '';

if (!empty($bg_banner)) {
	if (is_array($bg_banner)) {
		$bg_banner_url = $bg_banner['url'] ?? '';
		$bg_banner_alt = !empty($bg_banner['alt']) ? $bg_banner['alt'] : '';
		if (empty($bg_banner_alt) && !empty($bg_banner['ID'])) {
			$bg_banner_alt = get_post_meta($bg_banner['ID'], '_wp_attachment_image_alt', true);
		}
	} else {
		$bg_banner_url = $bg_banner;
		if (function_exists('attachment_url_to_postid')) {
			$banner_id = attachment_url_to_postid($bg_banner_url);
			if ($banner_id) {
				$bg_banner_alt = get_post_meta($banner_id, '_wp_attachment_image_alt', true);
			}
		}
	}

	if (empty($bg_banner_alt)) {
		$bg_banner_alt = !empty($search_query) ? sprintf('Search results for "%s"', strip_tags($search_query)) : 'Search Our Site';
	}
}
?>
<section class="ui-section hero-section-shared search-hero-section<?= !empty($bg_banner_url) ? ' hero-section-shared--has-image' : ' hero-section-shared--no-image'; ?>">
	<div class="hero-section-shared__bg ui-section__bg">
		<?php if (!empty($bg_banner_url)): ?>
			<img src="<?= esc_url($bg_banner_url) ?>" alt="<?= esc_attr($bg_banner_alt) ?>" class="hero-section-shared__img" loading="eager" />
		<?php endif; ?>
		<div class="hero-section-shared__overlay"></div>
		<div class="hero-section-shared__glow"></div>
		<div class="hero-section-shared__motif" aria-hidden="true">
			<?= rc_svg_icon('hero_pattern'); ?>
		</div>
	</div>

	<div class="container hero-section-shared__container">
		<div class="hero-section-shared__box">
			<div class="hero-section-shared__meta-bar">
				<div class="hero-section-shared__breadcrumbs-pill">
					<?php rc_breadcrumbs('Search Results'); ?>
				</div>
			</div>

			<?php if (!empty($search_query)): ?>
				<h1 class="search-title hero-section-shared__title">
					Search: <span>"<?php echo esc_html($search_query); ?>"</span>
				</h1>
				<?php if ($total_results > 0): ?>
					<p class="search-subtitle hero-section-shared__desc">
						We found <strong><?php echo esc_html($total_results); ?></strong>
						result<?php echo $total_results !== 1 ? 's' : ''; ?> for your search.
					</p>
				<?php else: ?>
					<p class="search-subtitle hero-section-shared__desc">
						We couldn't find any results matching your search.
					</p>
				<?php endif; ?>
			<?php else: ?>
				<h1 class="ui-heading-animation search-title hero-section-shared__title">Search Our Site</h1>
				<p class="search-subtitle hero-section-shared__desc">Enter a keyword below to find tours, cars, and articles.</p>
			<?php endif; ?>

			<div class="search-form-wrapper">
				<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"
					class="modern-search-form">
					<div class="input-group">
						<span class="search-icon">
							<?= rc_svg_icon('search_outline'); ?>
						</span>
						<input type="search" name="s" placeholder="What are you looking for?"
							value="<?php echo esc_attr($search_query); ?>" required />
						<button type="submit" class="ui-button ui-button--primary search-submit">Search</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="hero-section-shared__bottom-accent" aria-hidden="true"></div>
</section>

<div class="search-results" id="search-results">
	<div class="ui-results-search-section">
		<div class="container">
			<?php if (have_posts() && !empty($search_query)): ?>
				<?php
				global $wp_query;

				$total_results = $wp_query->found_posts;
				$paged = max(1, get_query_var('paged'));
				$per_page = get_query_var('posts_per_page') ?: 10;

				$start = ($paged - 1) * $per_page + 1;
				$end = min($paged * $per_page, $total_results);

				$search_query = get_search_query();

				echo '<h4 class="ui-results-search-section__total">';

				if ($total_results > 10) {
					echo 'Showing ' . esc_html($start) . ' – ' . esc_html($end) . ' of ' . esc_html($total_results) . ' results for: “' . esc_html($search_query) . '”';
				} else {
					echo esc_html($total_results) . ' results for “' . esc_html($search_query) . '”';
				}

				echo '</h4>';
				?>

				<div class="ui-results-search-section__list">
					<?php while (have_posts()):
						the_post();
						$post_type = get_post_type();

						if ($post_type === 'tours' && function_exists('rc_item_tour')) {
							rc_item_tour();
						} elseif ($post_type === 'cars' && function_exists('rc_car_tour_item')) {
							rc_car_tour_item();
						} else {
							if (function_exists('rc_post_item')) {
								rc_post_item();
							}
						}
					endwhile; ?>
				</div>

				<?php
				rc_pagination();
				wp_reset_postdata();
				?>
			<?php else: ?>
				<div class="search-empty-state">
					<div class="search-empty-state__card">
						<div class="search-empty-state__content">
							<?php if (!empty($search_query)): ?>
								<h2 class="search-empty-state__title">No Results Found</h2>
								<p class="search-empty-state__desc">
									Sorry, we couldn't find any results for "<span
										class="search-empty-state__keyword"><?php echo esc_html($search_query); ?></span>".
								</p>
								<p class="search-empty-state__subdesc">It seems this keyword may be misspelled or too specific.
									Try searching again using the search bar above or explore our suggestions below.</p>
							<?php else: ?>
								<h2 class="search-empty-state__title">Search Our Experiences</h2>
								<p class="search-empty-state__desc">
									Enter a keyword in the search bar above to discover tours, cars, and travel guides.
								</p>
								<p class="search-empty-state__subdesc">Or explore some of our most popular tours and helpful
									search tips below.</p>
							<?php endif; ?>

							<div class="search-empty-state__actions">
								<a href="<?php echo esc_url(home_url('/hue-experience-all-tour/')); ?>" class="ui-button">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
										stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="12" cy="12" r="10"></circle>
										<polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
									</svg>
									Explore Tours
								</a>
								<a href="<?php echo esc_url(home_url('/')); ?>" class="ui-button ui-button--outline">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
										stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<line x1="19" y1="12" x2="5" y2="12"></line>
										<polyline points="12 19 5 12 12 5"></polyline>
									</svg>
									Back Home
								</a>
							</div>

							<div class="search-empty-state__tips">
								<div class="search-empty-state__tip-card">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<polyline points="20 6 9 17 4 12"></polyline>
									</svg>
									<span>Check your spelling</span>
								</div>
								<div class="search-empty-state__tip-card">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<polyline points="20 6 9 17 4 12"></polyline>
									</svg>
									<span>Try shorter keywords</span>
								</div>
								<div class="search-empty-state__tip-card">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<polyline points="20 6 9 17 4 12"></polyline>
									</svg>
									<span>Search destinations</span>
								</div>
								<div class="search-empty-state__tip-card">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
										stroke-linecap="round" stroke-linejoin="round">
										<polyline points="20 6 9 17 4 12"></polyline>
									</svg>
									<span>Search tour names</span>
								</div>
							</div>
						</div>
					</div>

					<?php
					$popular_tours = new WP_Query([
						'post_type' => 'tours',
						'posts_per_page' => 3,
						'post_status' => 'publish',
						'orderby' => 'date',
						'order' => 'DESC'
					]);
					if ($popular_tours->have_posts()):
						?>
						<div class="search-recommendations">
							<h3 class="ui-heading search-recommendations__title">You Might Also Like</h3>
							<div class="ui-results-search-section__list">
								<?php while ($popular_tours->have_posts()):
									$popular_tours->the_post();
									if (function_exists('rc_item_tour')) {
										rc_item_tour();
									}
								endwhile;
								wp_reset_postdata(); ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php
get_footer();
