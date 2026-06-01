<?php
/**
 * Run all WPML × RCB harness scripts in sequence (separate CLI processes so `exit()` is isolated).
 *
 *   wp eval-file wp-content/wordpress-packages/multilingual/.ai/scripts/wpml-rcb-run-all.php --allow-root
 */
defined('ABSPATH') or die();

$dir = __DIR__;
$scripts = [
    'wpml-rcb-phase-c-de-en-verify.php',
    'wpml-rcb-phase-c-verify.php',
    'wpml-rcb-blocker-gravatar-repro.php',
    'wpml-rcb-copycontent-blocker-services.php',
    'wpml-rcb-tm-duplicate-meta-taxonomy.php',
    'cg-tt-wpml-smoke.php',
    'wpml-rcb-template-consumer-id-check.php',
];

$results = [];
$failed = false;

foreach ($scripts as $script) {
    $rel = 'wp-content/wordpress-packages/multilingual/.ai/scripts/' . $script;
    $cmd = 'wp eval-file ' . escapeshellarg($rel) . ' --allow-root 2>&1';
    exec($cmd, $lines, $code);
    $pass = $code === 0;
    $results[] = [
        'script' => $script,
        'exitCode' => $code,
        'pass' => $pass,
        'tail' => array_slice($lines, -8),
    ];
    if (!$pass) {
        $failed = true;
    }
}

echo wp_json_encode(['results' => $results, 'pass' => ['all' => !$failed]], JSON_PRETTY_PRINT) . "\n";
exit($failed ? 1 : 0);
