<?php

// Credit Cards page
add_action( 'init', function () {
	// Add post_type
	register_post_type( 'cards', array(
        'labels'        => [
            'name'               => __('Credit Cards'),
            'singular_name'      => __('Credit Card'),
            'add_new'            => __('Add page'),
            'add_new_item'       => __('Add new page'),
            'edit_item'          => __('Edit page'),
            'new_item'           => __('New page'),
            'all_items'          => __('All pages'),
            'view_item'          => __('Credit Cards'),
            'search_items'       => __('Find page'),
            'not_found'          => __('Pages not found'),
            'not_found_in_trash' => __('Trash is empty'),
            'menu_name'          => __('Credit Cards'),
        ],
		'public'        => true,
		'show_ui'       => true, // показывать интерфейс в админке
		'show_in_rest'  => true, // разрешить гутенберг
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-money', // иконка в меню
		'menu_position' => 25, // порядок в меню
		'supports'      => array( 'title', 'thumbnail' )
	) );

} );