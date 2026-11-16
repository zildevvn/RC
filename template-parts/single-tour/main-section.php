<?php
global $currency;
if (empty($currency)) {
    $currency = get_field('currency', 'option');
}

$booking_form = get_field('form_booking', 'option');
$included_tour = get_field('included_tour');
$excluded_tour = get_field('excluded_tour');
$itinerary_tour = get_field('itinerary_tour');
$highlights = get_field('highlights_tour');
$price_group = get_field('price_for_group_tour');
$price_group_child = get_field('child_price_group');
$price_private = get_field('price_for_private_tour');
$gallery_tour = get_field('gallery_tour');
$price_tour = get_field('price_tour');
$formatted_price = rc_format_price($price_tour);
$faqs_tour = get_field('faqs_list_tour');
?>
<section class="wp-block-section main-section">
    <div class="container">
        <div class="main-section-wrap">
            <div class="main-section-content">
                <?php
                // Build array of menu items to display dynamically
                $nav_items = [];
                $nav_items['tour-overview'] = ['label' => __('Overview', 'rc'), 'show' => true];
                $nav_items['tour-highlights'] = ['label' => __('Highlights', 'rc'), 'show' => !empty($highlights) || !empty($gallery_tour)];
                $nav_items['tour-inclusions'] = ['label' => __('Inclusions', 'rc'), 'show' => (!empty($included_tour) || !empty($excluded_tour))];
                $nav_items['tour-prices'] = ['label' => __('Prices', 'rc'), 'show' => (!empty($price_group) || !empty($price_private))];
                $nav_items['tour-itinerary'] = ['label' => __('Itinerary', 'rc'), 'show' => !empty($itinerary_tour)];
                $nav_items['tour-review'] = ['label' => __('Reviews', 'rc'), 'show' => (comments_open() || get_comments_number() > 0)];
                $nav_items['tour-faqs'] = ['label' => __('FAQs', 'rc'), 'show' => !empty($faqs_tour)];
                ?>

                <!-- Anchor Navigation Menu -->
                <nav class="anchor-nav" id="anchor-nav" aria-label="<?php esc_attr_e('Tour navigation', 'rc'); ?>">
                    <div class="anchor-nav__inner">
                        <ul class="anchor-nav__list">
                            <?php foreach ($nav_items as $id => $item):
                                if (!$item['show'])
                                    continue;
                                ?>
                                <li class="anchor-nav__item">
                                    <a href="#<?php echo esc_attr($id); ?>" class="anchor-nav__link">
                                        <?php echo esc_html($item['label']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </nav>

                <div class="main-section-content-wrapper">
                    <div id="tour-overview" class="tour-overview">
                        <div class="tour-section-header">
                            <span class="tour-section-header__eyebrow">
                                <?php echo rc_svg_icon('compass'); ?>
                                <span><?php esc_html_e('The Experience', 'rc'); ?></span>
                            </span>
                            <h2 class="ui-heading"><?php esc_html_e('Overview Tour', 'rc'); ?></h2>
                        </div>
                        <div class="tour-overview__content">
                            <?php the_content() ?>
                        </div>
                    </div>

                    <?php if (!empty($highlights)):
                        $highlight_items = [];
                        $highlights_intro = '';
                        $highlights_outro = '';

                        if (is_array($highlights)) {
                            foreach ($highlights as $h_entry) {
                                $h_text = is_array($h_entry) ? current($h_entry) : $h_entry;
                                if (is_string($h_text) && trim(strip_tags($h_text)) !== '') {
                                    $highlight_items[] = trim($h_text);
                                }
                            }
                        } elseif (is_string($highlights) && trim(strip_tags($highlights)) !== '') {
                            if (preg_match_all('/<li\b[^>]*>(.*?)<\/li>/is', $highlights, $li_matches) && !empty($li_matches[1])) {
                                if (preg_match('/^(.*?)<(?:ul|ol)\b/is', $highlights, $before_m)) {
                                    if (trim(strip_tags($before_m[1])) !== '') {
                                        $highlights_intro = trim($before_m[1]);
                                    }
                                }
                                if (preg_match('/^.*<\/(?:ul|ol)>(.*)$/is', $highlights, $after_m)) {
                                    if (trim(strip_tags($after_m[1])) !== '') {
                                        $highlights_outro = trim($after_m[1]);
                                    }
                                }
                                foreach ($li_matches[1] as $raw_li) {
                                    $clean_li = trim($raw_li);
                                    if (trim(strip_tags($clean_li)) !== '') {
                                        $highlight_items[] = $clean_li;
                                    }
                                }
                            } else {
                                $autop_highlights = wpautop($highlights);
                                if (preg_match_all('/<p\b[^>]*>(.*?)<\/p>/is', $autop_highlights, $p_matches) && !empty($p_matches[1])) {
                                    $valid_p = [];
                                    foreach ($p_matches[1] as $raw_p) {
                                        $clean_p = trim($raw_p);
                                        if (trim(strip_tags($clean_p)) !== '') {
                                            $valid_p[] = $clean_p;
                                        }
                                    }
                                    if (count($valid_p) >= 2) {
                                        $highlight_items = $valid_p;
                                    }
                                }
                            }
                        }

                        $parsed_highlights = [];
                        foreach ($highlight_items as $raw_item) {
                            $lead = '';
                            $body = $raw_item;
                            if (preg_match('/^\s*<(strong|b)\b[^>]*>(.*?)<\/\1>\s*(?:[:\-–—•|]\s*)?(.*)$/is', $raw_item, $m)) {
                                $candidate_lead = trim(preg_replace('/\s*[:\-–—•|]+$/u', '', trim($m[2])));
                                $candidate_body = trim($m[3]);
                                if ($candidate_lead !== '' && trim(strip_tags($candidate_body)) !== '') {
                                    $lead = $candidate_lead;
                                    $body = $candidate_body;
                                }
                            }
                            $parsed_highlights[] = [
                                'lead' => $lead,
                                'body' => $body,
                                'raw' => $raw_item,
                            ];
                        }

                        $highlights_count = count($parsed_highlights);
                        if ($highlights_count === 0) {
                            $highlights_modifier = 'tour-highlights--prose';
                        } elseif ($highlights_count >= 5) {
                            $highlights_modifier = 'tour-highlights--count-5-plus';
                        } else {
                            $highlights_modifier = 'tour-highlights--count-' . $highlights_count;
                        }

                        $primary_highlight = $highlights_count > 0 ? $parsed_highlights[0] : null;
                        $upper_ledger_limit = ($highlights_count >= 5) ? 2 : max(0, $highlights_count - 1);
                        $upper_ledger_items = $highlights_count > 1
                            ? array_slice($parsed_highlights, 1, $upper_ledger_limit, true)
                            : [];
                        $lower_ledger_items = $highlights_count >= 5
                            ? array_slice($parsed_highlights, 3, null, true)
                            : [];
                        ?>
                        <div id="tour-highlights" class="tour-highlights <?php echo esc_attr($highlights_modifier); ?>">
                            <div class="tour-highlights__masthead">
                                <div class="tour-highlights__heading-group">
                                    <div class="tour-highlights__kicker">
                                        <span class="tour-highlights__kicker-icon" aria-hidden="true">
                                            <?php echo rc_svg_icon('sparkle'); ?>
                                        </span>
                                        <span
                                            class="tour-highlights__kicker-text"><?php esc_html_e('Curated Moments', 'rc'); ?></span>
                                        <span class="tour-highlights__kicker-sep" aria-hidden="true"></span>
                                        <span
                                            class="tour-highlights__kicker-sub"><?php esc_html_e('Signature Route', 'rc'); ?></span>
                                    </div>
                                    <h2 class="ui-heading tour-highlights__title">
                                        <?php esc_html_e('Tour Highlights', 'rc'); ?>
                                    </h2>
                                </div>

                                <?php if ($highlights_count > 0): ?>
                                    <div class="tour-highlights__stamp"
                                        aria-label="<?php echo esc_attr(sprintf(_n('%d key experience', '%d key experiences', $highlights_count, 'rc'), $highlights_count)); ?>">
                                        <span
                                            class="tour-highlights__stamp-num"><?php echo esc_html(sprintf('%02d', $highlights_count)); ?></span>
                                        <span class="tour-highlights__stamp-label">
                                            <?php echo esc_html(_n('Key Experience', 'Key Experiences', $highlights_count, 'rc')); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($highlights_intro)): ?>
                                <div class="tour-highlights__deck">
                                    <?php echo wp_kses_post(wpautop($highlights_intro)); ?>
                                </div>
                            <?php endif; ?>

                            <div class="tour-highlights__content">
                                <?php if ($highlights_count > 0 && $primary_highlight): ?>
                                    <div class="tour-highlights__editorial">
                                        <div class="tour-highlights__primary-spread">
                                            <!-- Primary Focal Highlight (#01) -->
                                            <article class="tour-highlights__spotlight">
                                                <div class="tour-highlights__spotlight-motif" aria-hidden="true">
                                                    <?php echo rc_svg_icon('hero_pattern'); ?>
                                                </div>
                                                <div class="tour-highlights__spotlight-top">
                                                    <div class="tour-highlights__spotlight-badge">
                                                        <span class="tour-highlights__spotlight-index">01</span>
                                                        <span
                                                            class="tour-highlights__spotlight-tag"><?php esc_html_e('Primary Highlight', 'rc'); ?></span>
                                                    </div>
                                                    <span class="tour-highlights__spotlight-compass" aria-hidden="true">
                                                        <?php echo rc_svg_icon('compass'); ?>
                                                    </span>
                                                </div>

                                                <div class="tour-highlights__spotlight-body">
                                                    <?php if (!empty($primary_highlight['lead'])): ?>
                                                        <h3 class="tour-highlights__spotlight-lead">
                                                            <?php echo wp_kses_post($primary_highlight['lead']); ?>
                                                        </h3>
                                                        <div class="tour-highlights__spotlight-desc">
                                                            <?php echo wp_kses_post($primary_highlight['body']); ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="tour-highlights__spotlight-statement">
                                                            <?php echo wp_kses_post($primary_highlight['raw']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="tour-highlights__spotlight-footer" aria-hidden="true">
                                                    <span class="tour-highlights__spotlight-rule"></span>
                                                    <span
                                                        class="tour-highlights__spotlight-caption"><?php esc_html_e('Essential Stop', 'rc'); ?></span>
                                                </div>
                                            </article>

                                            <!-- Supporting Highlights Ledger (#02..#04 or #02..#03) -->
                                            <?php if (!empty($upper_ledger_items)): ?>
                                                <div class="tour-highlights__ledger" role="list">
                                                    <?php foreach ($upper_ledger_items as $idx => $h_item):
                                                        $display_num = sprintf('%02d', $idx + 1);
                                                        ?>
                                                        <div class="tour-highlights__item" role="listitem">
                                                            <div class="tour-highlights__item-num-col" aria-hidden="true">
                                                                <span
                                                                    class="tour-highlights__item-num"><?php echo esc_html($display_num); ?></span>
                                                                <span class="tour-highlights__item-tick"></span>
                                                            </div>
                                                            <div class="tour-highlights__item-body">
                                                                <?php if (!empty($h_item['lead'])): ?>
                                                                    <h3 class="tour-highlights__item-title">
                                                                        <?php echo wp_kses_post($h_item['lead']); ?>
                                                                    </h3>
                                                                    <div class="tour-highlights__item-desc">
                                                                        <?php echo wp_kses_post($h_item['body']); ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="tour-highlights__item-text">
                                                                        <?php echo wp_kses_post($h_item['raw']); ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Secondary Architectural Ledger Grid (#04+ when 5 or more highlights exist) -->
                                        <?php if (!empty($lower_ledger_items)): ?>
                                            <div class="tour-highlights__secondary-grid" role="list">
                                                <?php foreach ($lower_ledger_items as $idx => $h_item):
                                                    $display_num = sprintf('%02d', $idx + 1);
                                                    ?>
                                                    <div class="tour-highlights__item tour-highlights__item--secondary" role="listitem">
                                                        <div class="tour-highlights__item-num-col" aria-hidden="true">
                                                            <span
                                                                class="tour-highlights__item-num"><?php echo esc_html($display_num); ?></span>
                                                            <span class="tour-highlights__item-tick"></span>
                                                        </div>
                                                        <div class="tour-highlights__item-body">
                                                            <?php if (!empty($h_item['lead'])): ?>
                                                                <h3 class="tour-highlights__item-title">
                                                                    <?php echo wp_kses_post($h_item['lead']); ?>
                                                                </h3>
                                                                <div class="tour-highlights__item-desc">
                                                                    <?php echo wp_kses_post($h_item['body']); ?>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="tour-highlights__item-text">
                                                                    <?php echo wp_kses_post($h_item['raw']); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="tour-highlights__prose">
                                        <?= wp_kses_post($highlights); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($highlights_outro)): ?>
                                    <div class="tour-highlights__outro">
                                        <?php echo wp_kses_post(wpautop($highlights_outro)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php
                    $included_items = [];
                    if (!empty($included_tour) && is_array($included_tour)) {
                        foreach ($included_tour as $item) {
                            $text = is_array($item) ? current($item) : $item;
                            if (!is_scalar($text)) {
                                continue;
                            }
                            $clean_text = trim((string) $text);
                            if ($clean_text === '') {
                                continue;
                            }
                            $lead = '';
                            $detail = $clean_text;
                            if (preg_match('/^([^:–—\-]{3,42}?)\s*(?::\s*|\s+[–—\-]\s+)(.+)$/u', $clean_text, $inc_m)) {
                                $lead = trim($inc_m[1]);
                                $detail = trim($inc_m[2]);
                            }
                            $included_items[] = [
                                'lead' => $lead,
                                'detail' => $detail,
                                'raw' => $clean_text,
                            ];
                        }
                    }

                    $excluded_items = [];
                    if (!empty($excluded_tour) && is_array($excluded_tour)) {
                        foreach ($excluded_tour as $item) {
                            $text = is_array($item) ? current($item) : $item;
                            if (!is_scalar($text)) {
                                continue;
                            }
                            $clean_text = trim((string) $text);
                            if ($clean_text === '') {
                                continue;
                            }
                            $lead = '';
                            $detail = $clean_text;
                            if (preg_match('/^([^:–—\-]{3,42}?)\s*(?::\s*|\s+[–—\-]\s+)(.+)$/u', $clean_text, $exc_m)) {
                                $lead = trim($exc_m[1]);
                                $detail = trim($exc_m[2]);
                            }
                            $excluded_items[] = [
                                'lead' => $lead,
                                'detail' => $detail,
                                'raw' => $clean_text,
                            ];
                        }
                    }

                    $included_count = count($included_items);
                    $excluded_count = count($excluded_items);

                    if ($included_count > 0 || $excluded_count > 0):
                        $inclusions_modifiers = [];
                        $inclusions_modifiers[] = ($included_count > 0 && $excluded_count > 0)
                            ? 'tour-inclusions--split'
                            : 'tour-inclusions--single';
                        $inclusions_modifiers[] = ($included_count >= 4)
                            ? 'tour-inclusions--inc-grid'
                            : 'tour-inclusions--inc-stack';
                        if ($included_count >= 4 && ($included_count % 2 === 1)) {
                            $inclusions_modifiers[] = 'tour-inclusions--inc-odd';
                        }
                        ?>
                        <div id="tour-inclusions"
                            class="tour-inclusions <?php echo esc_attr(implode(' ', $inclusions_modifiers)); ?>">
                            <div class="tour-inclusions__masthead">
                                <div class="tour-inclusions__heading-group">
                                    <div class="tour-inclusions__kicker">
                                        <span class="tour-inclusions__kicker-icon" aria-hidden="true">
                                            <?php echo rc_svg_icon('shield_check'); ?>
                                        </span>
                                        <span
                                            class="tour-inclusions__kicker-text"><?php esc_html_e('Package Details', 'rc'); ?></span>
                                        <span class="tour-inclusions__kicker-sep" aria-hidden="true"></span>
                                        <span
                                            class="tour-inclusions__kicker-sub"><?php esc_html_e('Fare Specification', 'rc'); ?></span>
                                    </div>
                                    <h2 class="ui-heading tour-inclusions__title">
                                        <?php esc_html_e('Included & Excluded', 'rc'); ?>
                                    </h2>
                                </div>

                                <div class="tour-inclusions__ledger-summary"
                                    aria-label="<?php esc_attr_e('Package coverage summary', 'rc'); ?>">
                                    <?php if ($included_count > 0): ?>
                                        <div class="tour-inclusions__summary-stat tour-inclusions__summary-stat--included">
                                            <span
                                                class="tour-inclusions__summary-num"><?php echo esc_html(sprintf('%02d', $included_count)); ?></span>
                                            <span
                                                class="tour-inclusions__summary-label"><?php esc_html_e('Included', 'rc'); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($included_count > 0 && $excluded_count > 0): ?>
                                        <span class="tour-inclusions__summary-divider" aria-hidden="true"></span>
                                    <?php endif; ?>
                                    <?php if ($excluded_count > 0): ?>
                                        <div class="tour-inclusions__summary-stat tour-inclusions__summary-stat--excluded">
                                            <span
                                                class="tour-inclusions__summary-num"><?php echo esc_html(sprintf('%02d', $excluded_count)); ?></span>
                                            <span
                                                class="tour-inclusions__summary-label"><?php esc_html_e('Not Included', 'rc'); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="tour-inclusions__wrapper">
                                <?php if ($included_count > 0): ?>
                                    <div
                                        class="inclusion-card card-included tour-inclusions__zone tour-inclusions__zone--included">
                                        <div class="tour-inclusions__zone-motif" aria-hidden="true">
                                            <?php echo rc_svg_icon('wc_bg'); ?>
                                        </div>

                                        <div class="tour-inclusions__zone-header">
                                            <div class="tour-inclusions__zone-meta">
                                                <span class="tour-inclusions__zone-index">01</span>
                                                <span
                                                    class="tour-inclusions__zone-tag"><?php esc_html_e('Complimentary Coverage', 'rc'); ?></span>
                                            </div>
                                            <div class="tour-inclusions__zone-title-row">
                                                <h3>
                                                    <span class="inclusion-card__icon" aria-hidden="true">
                                                        <?php echo rc_svg_icon('check_circle'); ?>
                                                    </span>
                                                    <span><?php esc_html_e("What's Included", 'rc'); ?></span>
                                                </h3>
                                                <span class="tour-inclusions__zone-count">
                                                    <?php
                                                    echo esc_html(
                                                        sprintf(
                                                            _n('%02d Entitlement', '%02d Entitlements', $included_count, 'rc'),
                                                            $included_count
                                                        )
                                                    );
                                                    ?>
                                                </span>
                                            </div>
                                            <p class="tour-inclusions__zone-desc">
                                                <?php esc_html_e('Covered in your confirmed fare with full arrangement by our local operations team.', 'rc'); ?>
                                            </p>
                                        </div>

                                        <ul class="tour-inclusions__list tour-inclusions__list--included">
                                            <?php foreach ($included_items as $idx => $item):
                                                $item_num = sprintf('%02d', $idx + 1);
                                                $is_featured_first = ($idx === 0 && $included_count >= 5 && ($included_count % 2 === 1));
                                                ?>
                                                <li
                                                    class="tour-inclusions__item tour-inclusions__item--included<?php echo $is_featured_first ? ' tour-inclusions__item--featured' : ''; ?>">
                                                    <span class="tour-inclusions__marker tour-inclusions__marker--included"
                                                        aria-hidden="true">
                                                        <?php echo rc_svg_icon('check_check'); ?>
                                                    </span>
                                                    <div class="tour-inclusions__item-body">
                                                        <?php if (!empty($item['lead'])): ?>
                                                            <strong
                                                                class="tour-inclusions__item-lead"><?php echo esc_html($item['lead']); ?></strong>
                                                            <span
                                                                class="tour-inclusions__item-detail"><?php echo esc_html($item['detail']); ?></span>
                                                        <?php else: ?>
                                                            <span
                                                                class="tour-inclusions__item-text"><?php echo esc_html($item['raw']); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="tour-inclusions__item-index"
                                                        aria-hidden="true"><?php echo esc_html($item_num); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if ($excluded_count > 0): ?>
                                    <div
                                        class="inclusion-card card-excluded tour-inclusions__zone tour-inclusions__zone--excluded">
                                        <div class="tour-inclusions__zone-header">
                                            <div class="tour-inclusions__zone-meta">
                                                <span
                                                    class="tour-inclusions__zone-index"><?php echo $included_count > 0 ? '02' : '01'; ?></span>
                                                <span
                                                    class="tour-inclusions__zone-tag"><?php esc_html_e('Outside Base Fare', 'rc'); ?></span>
                                            </div>
                                            <div class="tour-inclusions__zone-title-row">
                                                <h3>
                                                    <span class="inclusion-card__icon" aria-hidden="true">
                                                        <?php echo rc_svg_icon('x_circle'); ?>
                                                    </span>
                                                    <span><?php esc_html_e('Not Included', 'rc'); ?></span>
                                                </h3>
                                                <span class="tour-inclusions__zone-count">
                                                    <?php
                                                    echo esc_html(
                                                        sprintf(
                                                            _n('%02d Exception', '%02d Exceptions', $excluded_count, 'rc'),
                                                            $excluded_count
                                                        )
                                                    );
                                                    ?>
                                                </span>
                                            </div>
                                            <p class="tour-inclusions__zone-desc">
                                                <?php esc_html_e('Personal expenses and optional add-ons arranged separately at your discretion.', 'rc'); ?>
                                            </p>
                                        </div>

                                        <ul class="tour-inclusions__list tour-inclusions__list--excluded">
                                            <?php foreach ($excluded_items as $idx => $item):
                                                $item_num = sprintf('%02d', $idx + 1);
                                                ?>
                                                <li class="tour-inclusions__item tour-inclusions__item--excluded">
                                                    <span class="tour-inclusions__marker tour-inclusions__marker--excluded"
                                                        aria-hidden="true">
                                                        <?php echo rc_svg_icon('x_mark'); ?>
                                                    </span>
                                                    <div class="tour-inclusions__item-body">
                                                        <?php if (!empty($item['lead'])): ?>
                                                            <strong
                                                                class="tour-inclusions__item-lead"><?php echo esc_html($item['lead']); ?></strong>
                                                            <span
                                                                class="tour-inclusions__item-detail"><?php echo esc_html($item['detail']); ?></span>
                                                        <?php else: ?>
                                                            <span
                                                                class="tour-inclusions__item-text"><?php echo esc_html($item['raw']); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="tour-inclusions__item-index"
                                                        aria-hidden="true"><?php echo esc_html($item_num); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <div class="tour-inclusions__zone-note">
                                            <span class="tour-inclusions__zone-note-dot" aria-hidden="true"></span>
                                            <span><?php esc_html_e('Need custom arrangements? Let our team know when booking.', 'rc'); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="tour-inclusions__charter-bar">
                                <div class="tour-inclusions__charter-assurance">
                                    <span class="tour-inclusions__charter-icon" aria-hidden="true">
                                        <?php echo rc_svg_icon('shield_check'); ?>
                                    </span>
                                    <span><?php esc_html_e('Transparent Fare Charter — All listed inclusions are guaranteed upon booking confirmation.', 'rc'); ?></span>
                                </div>
                                <span class="tour-inclusions__charter-stamp">
                                    <?php esc_html_e('Verified Local Operator', 'rc'); ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php
                    get_template_part('template-parts/single-tour/itinerary-section');
                    get_template_part('template-parts/single-tour/review-section');
                    get_template_part('template-parts/single-tour/faqs-section');
                    ?>
                </div>
            </div>

            <div class="main-section__sidebar">
                <?php get_template_part('template-parts/single-tour/form-booking'); ?>
            </div>

            <div class="main-section__sidebar-mobile">
                <div class="main-section__sidebar-mobile-inner">

                    <div class="price">
                        <?php if ($formatted_price !== ''): ?>
                            <span class="label">From:</span>
                            <span class="value">
                                <?php if (!empty($currency)): ?>
                                    <span class="mb-0"><?= esc_html($currency) ?></span>
                                <?php endif; ?>
                                <?= esc_html($formatted_price) ?>
                            </span>
                            <?php
                            $is_car_tour = has_term('Car Tours', 'tour_cats', get_the_ID());
                            if (!$is_car_tour):
                                ?>
                                <span class="unit">/ person</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <a href="#form-booking-tour" class="ui-button d-flex align-items-center justify-content-center"
                        aria-label="button Find Car">
                        Find Car
                        <?php echo rc_svg_icon('button_arrow'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>