<?php

// Reviews page
add_action( 'init', function () {
	// Add post_type
	register_post_type( 'reviews', array(
        'labels'        => [
            'name'               => __('Reviews'),
            'singular_name'      => __('Review'),
            'add_new'            => __('Add review'),
            'add_new_item'       => __('Add new review'),
            'edit_item'          => __('Edit review'),
            'new_item'           => __('New review'),
            'all_items'          => __('All reviews'),
            'view_item'          => __('Reviews'),
            'search_items'       => __('Find review'),
            'not_found'          => __('Reviews not found'),
            'not_found_in_trash' => __('Trash is empty'),
            'menu_name'          => __('Reviews'),
        ],
		'public'        => false,
		'show_ui'       => true, // показывать интерфейс в админке
		'show_in_rest'  => true, // разрешить гутенберг
		'has_archive'   => true,
		'menu_icon'     => 'dashicons-format-status', // иконка в меню
		'menu_position' => 4, // порядок в меню
		'supports'      => array( 'title', 'editor', 'thumbnail' )
	) );

    register_taxonomy( 'reviews_category', array( 'reviews' ), array(
        'label'                 => __( 'Category' ),
        'hierarchical'          => true,	// true - по типу рубрик, false - по типу меток, по умолчанию - false
        'public'                => false,	// каждый может использовать таксономию, либо только администраторы, по умолчанию - true
        'show_in_nav_menus'     => false,	// добавить на страницу создания меню
        'show_ui'               => true,
        'show_in_rest'          => true,	// добавить интерфейс создания и редактирования
        'show_tagcloud'         => false,	// нужно ли разрешить облако тегов для этой таксономии
        'update_count_callback' => '_update_post_term_count',	// callback-функция для обновления счетчика $object_type
        'query_var'             => true,
    ) );

} );