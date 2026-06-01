<?php

// Mortgages page
add_action( 'init', function () {
	// Add post_type
	register_post_type( 'mortgages', array(
        'labels'        => [
            'name'               => __('Mortgages'),
            'singular_name'      => __('Mortgage'),
            'add_new'            => __('Add mortgage'),
            'add_new_item'       => __('Add new mortgage'),
            'edit_item'          => __('Edit mortgage'),
            'new_item'           => __('New mortgage'),
            'all_items'          => __('All mortgages'),
            'view_item'          => __('Mortgages'),
            'search_items'       => __('Find mortgage'),
            'not_found'          => __('Mortgages not found'),
            'not_found_in_trash' => __('Trash is empty'),
            'menu_name'          => __('Mortgages'),
        ],
		'public'        => true,
		'show_ui'       => true, // показывать интерфейс в админке
		'show_in_rest'  => true, // разрешить гутенберг
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-admin-home', // иконка в меню
		'menu_position' => 20, // порядок в меню
		'supports'      => array( 'title', 'thumbnail' )
	) );

} );