<?php
get_header();
$theme_dir = get_template_directory_uri() . '/';
global $wp_query;

?>
    <div class="error-section">
        <div class="error-section__image">
            <img src="<?=theme()->getThemeUrl()?>assets/data/error-image.png" width="537" height="260" alt="error">
        </div>
        <div class="error-section__title">
            <?php _e("Page not found", 'finanzia'); ?>
        </div>
        <div class="error-section__text">
            <?php _e("You've reached a page that doesn't exist.", 'finanzia'); ?>
        </div>
        <div class="error-section__buttons">
            <a class="error-section__btn" href="<?=home_url();?>"><?php _e("Back to homepage", 'finanzia'); ?></a>
        </div>
    </div>
<?php

get_footer();


