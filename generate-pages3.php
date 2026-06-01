<?php
require_once('wp-load.php'); // Load WordPress

$template_page_id = 20772; // Gutenberg template page ID
$csvFile = __DIR__ . '/wp-content/uploads/pages.csv';

if (!file_exists($csvFile)) {
    die('❌ CSV file not found.');
}

$template_post = get_post($template_page_id);
if (!$template_post) {
    die('❌ Template page not found. Make sure ID is correct.');
}

$template_content = $template_post->post_content;

// Detect delimiter automatically
$csvLines = file($csvFile);
$delimiter = strpos($csvLines[0], ';') !== false ? ';' : ',';
$rows = array_map(fn($r) => str_getcsv($r, $delimiter), $csvLines);
$header = array_shift($rows);

// Normalize headers
$header = array_map('trim', $header);
$header = array_map('strtolower', $header);

foreach ($rows as $row) {
    if (count($row) !== count($header)) {
        echo "⚠️ Skipping malformed row: " . implode(' | ', $row) . "<br>";
        continue;
    }

    $data = array_combine($header, $row);

    $city         = trim($data['city'] ?? '');
    $service_name = trim($data['service name'] ?? '');
    $slug         = trim($data['slug'] ?? '');
    $title        = trim($data['headline'] ?? '');
    $description  = trim($data['description'] ?? '');

    if (!$slug || !$title) {
        echo "⚠️ Missing slug or title for row: " . implode(' | ', $row) . "<br>";
        continue;
    }

    // Normalize slugs
    $slug_parts   = explode('/', trim($slug, '/'));
    $city_slug    = $slug_parts[0] ?? sanitize_title($city);
    $service_slug = $slug_parts[1] ?? sanitize_title($service_name);

    // Ensure parent (city) page exists
    $city_page = get_page_by_path($city_slug);
    if (!$city_page) {
        $city_page_id = wp_insert_post([
            'post_title'  => ucfirst($city),
            'post_name'   => $city_slug,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type'   => 'page',
        ]);
        echo "🏙️ Created city page: $city_slug<br>";

        // 🏳️ Set language to English (EN)
        if (function_exists('pll_set_post_language')) {
            pll_set_post_language($city_page_id, 'en');
        }
    } else {
        $city_page_id = $city_page->ID;
    }

    // Check if the service page already exists
    $service_path = $city_slug . '/' . $service_slug;
    $existing     = get_page_by_path($service_path);
    if ($existing) {
        echo "⚠️ Page already exists: $service_path<br>";
        continue;
    }

    // 🧩 Replace placeholders safely
    $replacements = [
        '{{headline}}'             => esc_html($title),
        '{{city}}'                 => esc_html($city),
        '{{service_name}}'         => esc_html($service_name),
        '{{service_name | lower}}' => esc_html(strtolower($service_name)),
        '{{description}}'          => wp_kses_post($description),
    ];

    $content = strtr($template_content, $replacements);

    // ✅ Replace any Gutenberg contact-form-7 block with shortcode version
    $content = preg_replace(
        '/<!-- wp:contact-form-7\/contact-form-selector.*?<!-- \/wp:contact-form-7\/contact-form-selector -->/s',
        '<!-- wp:shortcode -->[contact-form-7 id="9934" title="Max mortgage EN"]<!-- /wp:shortcode -->',
        $content
    );

    echo "➡️ Creating: $service_path ($title)<br>";

    // Insert Gutenberg-safe content
    $page_id = wp_insert_post([
        'post_title'   => $title,
        'post_name'    => $service_slug,
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_author'  => 1,
        'post_type'    => 'page',
        'post_parent'  => $city_page_id,
    ]);

    if ($page_id) {
        update_post_meta($page_id, '_yoast_wpseo_title', "$title | Finaram.cz");
        update_post_meta($page_id, '_yoast_wpseo_metadesc', $description);

        // 🏳️ Set language to English (EN)
        if (function_exists('pll_set_post_language')) {
            pll_set_post_language($page_id, 'en');
        }

        echo "✅ Created: <a href='/$service_path' target='_blank'>$service_path</a><br>";
    } else {
        echo "❌ Failed to create page: $service_path<br>";
    }
}

echo "<hr>✅ Done.";
