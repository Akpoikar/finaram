<?php

// don't load directly
if (!defined('ABSPATH')) {
    die('-1');
}

if (!empty($post->ID)) {
    $nonce_action = 'calculator-update-inbox_' . $post->ID;
} else {
    $nonce_action = 'calculator-add-inbox';
}
if ($calc_user_id = get_field('_calc_user', $post->ID)) {
    $user = get_userdata($calc_user_id);
}
$category = get_the_terms($post->ID, Calculator::taxonomy);

?>
<div class="wrap">

    <h1><?php echo esc_html(__('Inbox request', 'calculator')); ?></h1>

    <?php do_action('calculator_admin_updated_message', $post); ?>

    <form name="editinbox" id="editinbox" method="post"
          action="<?php echo esc_url(add_query_arg(array('post' => $post->ID), menu_page_url('calculator-inbox', false))); ?>">
        <?php
        wp_nonce_field($nonce_action);
        wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
        wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
        ?>

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">

                <div id="post-body-content">
                    <table class="message-main-fields">
                        <tbody>
                        <?php if (!empty($category)) : ?>
                            <tr class="message-subject">
                                <th><?php echo esc_html(__('Category', 'calculator')); ?>:</th>
                                <td><?php echo esc_html($category[0]->name); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if (!empty($user)) : ?>
                            <tr class="message-from">
                                <th><?php echo esc_html(__('From', 'calculator')); ?>:</th>
                                <td>
                                    <?php if (!empty($user->user_firstname && $user->user_lastname)) : ?>
                                        <?= esc_html($user->user_firstname) ?> <?= esc_html($user->user_lastname); ?> (<?= esc_html($user->user_email); ?>)
                                    <?php else : ?>
                                        <?= esc_html($user->user_email); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($calc_user_lang = get_post_meta($post->ID, '_calc_user_lang', true)) : ?>
                            <tr class="message-subject">
                                <th><?php echo esc_html(__('Language', 'calculator')); ?>:</th>
                                <td><?php echo esc_html(strtoupper($calc_user_lang)); ?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div><!-- #post-body-content -->

                <div id="postbox-container-1" class="postbox-container">
                    <?php
                    do_meta_boxes(null, 'side', $post);
                    ?>
                </div><!-- #postbox-container-1 -->

                <div id="postbox-container-2" class="postbox-container">
                    <?php
                    do_meta_boxes(null, 'normal', $post);
                    do_meta_boxes(null, 'advanced', $post);
                    ?>
                </div><!-- #postbox-container-2 -->

            </div><!-- #post-body -->
            <br class="clear"/>

        </div><!-- #poststuff -->

        <?php if ($post->ID) : ?>
            <input type="hidden" name="action" value="save"/>
            <input type="hidden" name="post" value="<?php echo (int)$post->ID; ?>"/>
            <!--        --><?php //else: ?>
            <!--            <input type="hidden" name="action" value="add"/>-->
        <?php endif; ?>
    </form>

</div><!-- .wrap -->
