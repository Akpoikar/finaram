<?php

add_action('after_setup_theme',
    function () {
        add_theme_support('html5', array(
            'comment-list',
            'comment-form',
            'search-form',
            'gallery',
            'caption',
            'script',
            'style',
        ));
        add_theme_support('menus');
        add_theme_support('post-thumbnails', ['page', 'post', 'articles', 'cards', 'loans', 'mortgages', 'reviews', 'calcpages']);
        add_theme_support('title-tag');

        add_theme_support('custom-logo');

        register_nav_menus(
            array(
                'header-menu' => 'Header menu',
                'footer-menu' => 'Footer menu',
                'social-menu' => 'Social menu',
                'footer-copy' => 'Footer copyright menu',
            )
        );
    }
);

add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_setting('second_logo');

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'second_logo', array(
        'label'    => __('Second logo'),
        'section'  => 'title_tagline',
        'settings' => 'second_logo',
        'priority' => 8 // show it just below the custom-logo
    )));

});

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => __('Finaram Settings'),
        'menu_title' => __('Finaram Settings'),
        'menu_slug'  => 'finanzia-general-settings',
        'capability' => 'edit_posts',
        'redirect'   => false
    ));
};

add_action('wp_enqueue_scripts', 'finanzia_scripts');
function finanzia_scripts()
{
    wp_enqueue_style('style', get_stylesheet_uri(), _S_VERSION);
    wp_enqueue_style('main-css', get_template_directory_uri() . '/assets/css/index.css', _S_VERSION);
    // wp_enqueue_style( 'animate-css', '//cdn.someurl.example/libs/test.css');

    wp_enqueue_script('app-js', get_template_directory_uri() . '/assets/js/app.js', array(), _S_VERSION, true);
    wp_enqueue_script('function', get_template_directory_uri() . '/assets/js/function.js', array(), _S_VERSION, true);
    // wp_enqueue_script( 'tween', '//cdn.someurl.example/libs/test.js', array(), false, true);

    wp_localize_script('function', 'front',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('front-nonce')
        )
    );
}


add_action('admin_menu', 'post_remove');   //adding action for triggering function call
function post_remove()      //creating functions post_remove for removing menu item
{
    remove_menu_page('edit.php');
}

add_action('admin_menu', 'remove_menus');
function remove_menus()
{
    remove_menu_page('edit-comments.php');
}

add_action('after_setup_theme', 'finanzia_load_theme_textdomain');
function finanzia_load_theme_textdomain()
{
    load_theme_textdomain('finanzia', get_template_directory() . '/languages');
}

add_filter('wpcf7_autop_or_not', '__return_false');