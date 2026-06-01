<?php

// Loans page
add_action( 'init', function () {
	// Add post_type
	register_post_type( 'loans', array(
        'labels'        => [
            'name'               => __('Loans'),
            'singular_name'      => __('Loan'),
            'add_new'            => __('Add loan'),
            'add_new_item'       => __('Add new loan'),
            'edit_item'          => __('Edit loan'),
            'new_item'           => __('New loan'),
            'all_items'          => __('All loans'),
            'view_item'          => __('Loans'),
            'search_items'       => __('Find loan'),
            'not_found'          => __('Loans not found'),
            'not_found_in_trash' => __('Trash is empty'),
            'menu_name'          => __('Loans'),
        ],
		'public'        => true,
		'show_ui'       => true, // показывать интерфейс в админке
		'show_in_rest'  => true, // разрешить гутенберг
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-bank', // иконка в меню
		'menu_position' => 23, // порядок в меню
		'supports'      => array( 'title', 'thumbnail' )
	) );

} );