<div class="form-line__title">
    <?php _e("Contact Information", 'finanzia'); ?>
</div>
<div class="form-line__text">
    <?php _e("Enter the code that you received in your email.", 'finanzia'); ?>
</div>
<div class="form-line__text js_info" style="display: none;"></div>
<form id="login_code_form" class="big-form">
    <?php wp_nonce_field('login-code-form-action', 'login-code-form-field'); ?>
    <input type="hidden" name="action" value="check_code_form">
    <input type="hidden" name="email" value="<?= $args['email'] ?>">
    <div class="big-form__cols">
        <input type="text" name="code[]" maxlength="1">
        <input type="text" name="code[]" maxlength="1">
        <input type="text" name="code[]" maxlength="1">
        <input type="text" name="code[]" maxlength="1">
    </div>
    <div class="big-form__button">
        <button class="big-form__btn" type="submit">
            <?php _e("confirm registration", 'finanzia'); ?>
        </button>
    </div>
</form>