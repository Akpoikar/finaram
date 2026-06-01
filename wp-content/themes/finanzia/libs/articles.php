<?php

// Articles page
add_action( 'init', function () {
	// Add post_type
	register_post_type( 'articles', array(
        'labels'        => [
            'name'               => __('Articles'),
            'singular_name'      => __('Article'),
            'add_new'            => __('Add article'),
            'add_new_item'       => __('Add new article'),
            'edit_item'          => __('Edit article'),
            'new_item'           => __('New article'),
            'all_items'          => __('All articles'),
            'view_item'          => __('Articles'),
            'search_items'       => __('Find article'),
            'not_found'          => __('Articles not found'),
            'not_found_in_trash' => __('Trash is empty'),
            'menu_name'          => __('Articles'),
        ],
		'public'        => true,
		'show_ui'       => true, // показывать интерфейс в админке
		'show_in_rest'  => true, // разрешить гутенберг
		'has_archive'   => true,
		'menu_icon'     => 'dashicons-admin-post', // иконка в меню
		'menu_position' => 4, // порядок в меню
		'supports'      => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'       => array('slug' => 'blog'), // Вот здесь изменяется URL
	) );

} );