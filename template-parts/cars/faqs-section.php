<?php
/**
 * Cars Template - FAQ Section
 * Reuses the Homepage FAQ component for 100% layout, styling, structure, and behavior consistency.
 *
 * @package rc
 */

get_template_part('template-parts/home/faqs-section', null, [
    'post_id' => get_the_ID(),
]);
