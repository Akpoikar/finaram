<?php
require_once('wp-load.php'); // Load WordPress & WPML

$template_page_id_en = 22690; // English template
$template_page_id_cz = 22683; // Czech template

$csv_cz = __DIR__ . '/wp-content/uploads/cz_mortgage.csv';
$csv_en = __DIR__ . '/wp-content/uploads/en_mortgage.csv';

// Limit processing to a specific index (set to null or 0 to process all rows)
$max_index = 200; // Process rows from index 0 to this index (inclusive)

// Load template posts
$template_post_en = get_post($template_page_id_en);
$template_post_cz = get_post($template_page_id_cz);

if (!$template_post_en || !$template_post_cz) {
    die('❌ Template pages missing. Check IDs.');
}

$template_content_en = $template_post_en->post_content;
$template_content_cz = $template_post_cz->post_content;

// Helpers
function detect_delimiter($file) {
    $line = fgets(fopen($file, 'r'));
    return strpos($line, ';') !== false ? ';' : ',';
}

function read_csv_assoc($file) {
    $delimiter = detect_delimiter($file);
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (isset($lines[0])) {
        $lines[0] = preg_replace('/^\xEF\xBB\xBF/', '', $lines[0]);
    }
    $rows = array_map(fn($r) => str_getcsv($r, $delimiter), $lines);
    $header = array_map('strtolower', array_map('trim', array_shift($rows)));
    $result = [];
    foreach ($rows as $row) {
        if (count($row) !== count($header) || implode('', $row) === '') continue;
        $result[] = array_combine($header, $row);
    }
    return $result;
}

$rows_cz = read_csv_assoc($csv_cz);
$rows_en = read_csv_assoc($csv_en);

foreach ($rows_cz as $i => $cz) {
    // Stop processing if we've reached the max index
    if ($max_index !== null && $max_index > 0 && $i > $max_index) {
        break;
    }
    
    $en = $rows_en[$i] ?? [];

    $city_cz         = trim($cz['city'] ?? '');
    $service_name_cz = trim($cz['service name'] ?? '');
    $slug_cz         = trim($cz['slug'] ?? '');
    $title_cz        = trim($cz['headline'] ?? '');
    $description_cz  = trim($cz['description'] ?? '');

    $city_en         = trim($en['city'] ?? $city_cz);
    $service_name_en = trim($en['service name'] ?? $service_name_cz);
    $slug_en         = trim($en['slug'] ?? $slug_cz);
    $title_en        = trim($en['headline'] ?? $title_cz);
    $description_en  = trim($en['description'] ?? $description_cz);

    if (!$slug_cz || !$title_cz) continue;

    $slug_parts_cz = explode('/', trim($slug_cz, '/'));
    $city_slug_cz = $slug_parts_cz[0] ?? sanitize_title($city_cz);
    $service_slug_cz = $slug_parts_cz[1] ?? sanitize_title($service_name_cz);

    $slug_parts_en = explode('/', trim($slug_en, '/'));
    $city_slug_en = $slug_parts_en[0] ?? sanitize_title($city_en);
    $service_slug_en = $slug_parts_en[1] ?? sanitize_title($service_name_en);

    // --- Create Czech city page ---
    $city_page_cz = get_page_by_path($city_slug_cz);
    if (!$city_page_cz) {
        $city_page_cz_id = wp_insert_post([
            'post_title'  => ucfirst($city_cz),
            'post_name'   => $city_slug_cz,
            'post_status' => 'publish',
            'post_type'   => 'page',
        ]);
        do_action('wpml_set_element_language_details', [
            'element_id'    => $city_page_cz_id,
            'element_type'  => 'post_page',
            'trid'          => false,
            'language_code' => 'cs',
            'source_language_code' => null,
        ]);
        echo "🏙️ Created CZ city: /$city_slug_cz<br>";
    } else {
        $city_page_cz_id = $city_page_cz->ID;
    }

    // --- Create or find English city page ---
    $city_page_en = get_page_by_path("en/$city_slug_en");
    // Ensure there is an English root page with slug `en` to host English city pages.
    $en_root = get_page_by_path('en');
    if (!$en_root) {
        $en_root_id = wp_insert_post([
            'post_title'  => 'EN',
            'post_name'   => 'en',
            'post_status' => 'publish',
            'post_type'   => 'page',
        ]);
        if ($en_root_id) {
            do_action('wpml_set_element_language_details', [
                'element_id'    => $en_root_id,
                'element_type'  => 'post_page',
                'trid'          => false,
                'language_code' => 'en',
                'source_language_code' => null,
            ]);
            $en_root = get_post($en_root_id);
        }
    }
    $en_root_id = $en_root ? $en_root->ID : 0;

    if (!$city_page_en) {
        $city_page_en_id = wp_insert_post([
            'post_title'  => ucfirst($city_en),
            'post_name'   => $city_slug_en,
            'post_status' => 'publish',
            'post_type'   => 'page',
            'post_parent' => $en_root_id,
        ]);

        // Link city translations in WPML using the Czech city trid
        $trid_city = apply_filters('wpml_element_trid', null, $city_page_cz_id, 'post_page');
        do_action('wpml_set_element_language_details', [
            'element_id'    => $city_page_en_id,
            'element_type'  => 'post_page',
            'trid'          => $trid_city,
            'language_code' => 'en',
            'source_language_code' => 'cs',
        ]);

        echo "🌍 Created EN city: /en/$city_slug_en<br>";
    } else {
        $city_page_en_id = $city_page_en->ID;
    }

    // --- Prepare Czech content ---
    $replacements_cz = [
        '{{headline}}' => esc_html($title_cz),
        '{{city}}' => esc_html($city_cz),
        '{{service_name}}' => esc_html($service_name_cz),
        '{{service_name | lower}}' => esc_html(strtolower($service_name_cz)),
        '{{description}}' => wp_kses_post($description_cz),
    ];
    $content_cz = strtr($template_content_cz, $replacements_cz);

    // ✅ Replace contact form block completely
    $content_cz = preg_replace(
        '/<!-- wp:contact-form-7\/contact-form-selector[^>]*-->.*?<!-- \/wp:contact-form-7\/contact-form-selector -->/s',
        '<!-- wp:shortcode -->[contact-form-7 id="9934" title="Max mortgage CZ"]<!-- /wp:shortcode -->',
        $content_cz
    );

    $service_path_cz = "$city_slug_cz/$service_slug_cz";
    if (get_page_by_path($service_path_cz)) continue;

    // --- Create Czech service page ---
    $page_id_cz = wp_insert_post([
        'post_title'   => $title_cz,
        'post_name'    => $service_slug_cz,
        'post_content' => $content_cz,
        'post_status'  => 'publish',
        'post_author'  => 1,
        'post_type'    => 'page',
        'post_parent'  => $city_page_cz_id,
    ]);

    if (!$page_id_cz) continue;

    do_action('wpml_set_element_language_details', [
        'element_id'    => $page_id_cz,
        'element_type'  => 'post_page',
        'trid'          => false,
        'language_code' => 'cs',
        'source_language_code' => null,
    ]);

    // --- Prepare English content ---
    $replacements_en = [
        '{{headline}}' => esc_html($title_en),
        '{{city}}' => esc_html($city_en),
        '{{service_name}}' => esc_html($service_name_en),
        '{{service_name | lower}}' => esc_html(strtolower($service_name_en)),
        '{{description}}' => wp_kses_post($description_en),
    ];
    $content_en = strtr($template_content_en, $replacements_en);

    // ✅ Replace contact form block completely
    $content_en = preg_replace(
        '/<!-- wp:contact-form-7\/contact-form-selector[^>]*-->.*?<!-- \/wp:contact-form-7\/contact-form-selector -->/s',
        '<!-- wp:shortcode -->[contact-form-7 id="9934" title="Max mortgage EN"]<!-- /wp:shortcode -->',
        $content_en
    );

    // --- Create English service page under English city ---
    $page_id_en = wp_insert_post([
        'post_title'   => $title_en,
        'post_name'    => $service_slug_en,
        'post_content' => $content_en,
        'post_status'  => 'publish',
        'post_author'  => 1,
        'post_type'    => 'page',
        'post_parent'  => $city_page_en_id, // ✅ correct parent
    ]);

    if ($page_id_en) {
        // Link translation pair in WPML
        $trid_service = apply_filters('wpml_element_trid', null, $page_id_cz, 'post_page');
        do_action('wpml_set_element_language_details', [
            'element_id'    => $page_id_en,
            'element_type'  => 'post_page',
            'trid'          => $trid_service,
            'language_code' => 'en',
            'source_language_code' => 'cs',
        ]);

        // ✅ Add Rank Math SEO title and description
        update_post_meta($page_id_cz, '_rank_math_title', "$title_cz | Finaram.cz");
        update_post_meta($page_id_en, '_rank_math_title', "$title_en | Finaram.cz");

        update_post_meta($page_id_cz, '_rank_math_description', $description_cz);
        update_post_meta($page_id_en, '_rank_math_description', $description_en);

        echo "✅ Created pair: /$service_path_cz ↔ /en/$city_slug_en/$service_slug_en<br>";
    } else {
        echo "⚠️ Failed EN for /$service_path_cz<br>";
    }
}

echo "<hr>✅ Done — all pages created, linked, and SEO metadata set via Rank Math.";
