<?php

/*
 * Plugin Name: Watchdog
 * Description: Plugin for Watchdog page functionality and notification distribution.
 * Author: MAS group
 * Author URI: https://masgroup.agency/
 * Text Domain: watchdog
 * Domain Path: /languages
 */

const WATCHDOG_PLUGIN_DIR = __DIR__;

define("WPML_DEFAULT_LANGUAGE", apply_filters('wpml_default_language', NULL));

// Add Watchdog class
include_once WATCHDOG_PLUGIN_DIR . '/class.watchdog.php';

$watchdog = new Watchdog();


add_filter('cron_schedules', 'watchdog_intervals');

function watchdog_intervals($raspisanie)
{
    $raspisanie['watchdog_5_min'] = [
        'interval' => 300,
        'display'  => 'Watchdog 5 minute'
    ];
    return $raspisanie;
}

// Проверка существования расписания во время работы плагина на всякий случай
if (!wp_next_scheduled('watchdog_check_jobs')) {
    wp_schedule_event(time(), 'watchdog_5_min', 'watchdog_check_jobs');
}

function activation_watchdog()
{
    wp_clear_scheduled_hook('watchdog_check_jobs');
    wp_schedule_event(time(), 'watchdog_5_min', 'watchdog_check_jobs');
}

function deactivation_watchdog()
{
    wp_clear_scheduled_hook('watchdog_check_jobs');
}
