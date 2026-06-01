<?php
/**
 * Template name: Page builder template
 */

$fields = get_fields();

$sections      = $fields['page-builder'];
get_header();

if ($sections) {
    $sections_keys = array_column($sections, 'acf_fc_layout');
    foreach ($sections as $section) {
        $template = str_replace('_', '-', $section['acf_fc_layout']);
        if ($template === 'links') {
            $section['sections_keys'] = $sections_keys;
        }
        get_template_part('template-parts/part', $template, $section);
    }
}

get_footer();
