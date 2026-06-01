<div class="form-line__title">
    <?php _e("Contact Information", 'finanzia'); ?>
</div>
<div class="form-line__text">
    <?php _e("To confirm your registration, please enter your email address and we will send you an email with a confirmation code.", 'finanzia'); ?>
</div>
<div class="form-line__text js_info" style="display: none;"></div>
<form id="login_form" class="big-form">
    <?php wp_nonce_field('login-form-action', 'login-form-field'); ?>
    <input type="hidden" name="action" value="reg_or_login">
    <div class="big-form__row">
        <div class="contacts__label">
            <?php _e("Email", 'finanzia'); ?>
        </div>
        <input type="email" name="email" placeholder="<?php _e("Enter your email", 'finanzia'); ?>" required>
    </div>
    <div class="big-form__check-box">
        <label class="big-form__check">
            <input type="checkbox" required>
            <span class="big-form__check-ico"></span>
        </label>
        <?php _e("I agree to the", 'finanzia'); ?>&nbsp;
        <a target="_blank" href="<?= get_the_permalink(1792); ?>"><?php _e("terms and conditions", 'finanzia'); ?></a>
        &nbsp;<?php _e("and", 'finanzia'); ?>&nbsp;
        <a target="_blank" href="<?= get_privacy_policy_url(); ?>"><?php _e("privacy policy", 'finanzia'); ?></a>.
    </div>
    <div class="big-form__button">
        <button class="big-form__btn-log" type="submit"><?php _e("Send Code", 'finanzia'); ?></button>
    </div>
</form>