<?php
$faqs = get_field('faqs_list_tour');
?>

<?php if (!empty($faqs) && is_array($faqs)): ?>
    <div id="tour-faqs" class="tour-faqs">
        <h2 class="ui-heading">Frequently Asked Questions</h2>
        <div class="faqs-list" data-purpose="tour-faqs">
            <?php foreach ($faqs as $key => $item): ?>
                <?php
                $question = isset($item['question']) ? trim((string) $item['question']) : '';
                $answer = isset($item['answer']) ? trim((string) $item['answer']) : '';

                if ($question === '' || $answer === '') {
                    continue;
                }

                $is_active = ($key === 0);
                $active_class = $is_active ? ' is-active' : '';
                $answer_id = 'tour-faq-answer-' . $key;
                ?>
                <div class="faqs-list__item faq-item<?= $active_class ?>">
                    <button type="button" class="faqs-list__question faq-item__question"
                        aria-expanded="<?= $is_active ? 'true' : 'false' ?>"
                        aria-controls="<?= esc_attr($answer_id) ?>">
                        <span class="faqs-list__title faq-item__title">
                            <?= sprintf('%02d', $key + 1); ?> - <?= esc_html($question) ?>
                        </span>
                        <span class="faqs-list__icon faq-item__icon" aria-hidden="true">
                            <?= rc_svg_icon('chevron_down') ?>
                        </span>
                    </button>
                    <div id="<?= esc_attr($answer_id) ?>" class="faqs-list__answer faq-item__answer">
                        <div class="faqs-list__answer-inner faq-item__answer-inner">
                            <?= wp_kses_post(wpautop($answer)) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
