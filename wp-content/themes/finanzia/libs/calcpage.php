<?php

// Mortgages page
add_action('init', function () {
    // Add post_type
    register_post_type('calcpages', array(
        'labels'        => [
            'name'               => __('Calculator pages'),
            'singular_name'      => __('Calculator page'),
            'add_new'            => __('Add page'),
            'add_new_item'       => __('Add new page'),
            'edit_item'          => __('Edit page'),
            'new_item'           => __('New page'),
            'all_items'          => __('All pages'),
            'view_item'          => __('Calculator page'),
            'search_items'       => __('Find pages'),
            'not_found'          => __('Pages not found'),
            'not_found_in_trash' => __('Trash is empty'),
            'menu_name'          => __('Calculator pages'),
        ],
        'public'        => true,
        'show_ui'       => true, // показывать интерфейс в админке
        'show_in_rest'  => true, // разрешить гутенберг
        'has_archive'   => false,
        'menu_icon'     => 'dashicons-media-interactive', // иконка в меню
        'menu_position' => 20,                            // порядок в меню
        'supports'      => array('title', 'excerpt', 'thumbnail'),
        'rewrite'       => array('slug' => 'calculators'), // Вот здесь изменяется URL
    ));
});