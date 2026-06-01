<?php
/**
 * Template name: Mortgages form step two template
 */
$fields        = get_fields();
$option_fields = get_fields('option');

if (is_user_logged_in()) {
    $calc = get_actual_user_calc();

    $category = get_the_terms($calc->ID, Calculator::taxonomy);
//    if ($category) {
//        $url = get_form_page_url($calc->_calc_status, $category[0]->slug);
//    } else {
//        $url = home_url();
//    }
//    if (parse_url($url, PHP_URL_PATH) != $_SERVER['REQUEST_URI']) {
//        wp_redirect($url);
//    }
//} else {
//    wp_redirect(home_url());

    $calc->currency = mb_strtoupper(get_post_meta($calc->ID, '_mini_calc_currency', true));
    $calc->relationship_type = get_post_meta($calc->ID, '_relationship_type', true);
}
get_header('steps');
?>
    <div class="wrapper-form">
        <div class="form-page">
            <div class="form-aside">
                <div class="form-aside__box">
                    <a class="logo" href="<?= home_url(); ?>">
                        <img src="<?= theme()->getMainLogo(); ?>"
                             alt="<?php bloginfo('name'); ?> - <?php _e("photo", 'finanzia'); ?>"
                             title="<?php bloginfo('name'); ?>">
                    </a>
                    <a class="back" href="<?= home_url(); ?>"><?php _e("Return to Homepage", 'finanzia'); ?></a>
                    <div class="form-aside__button">
                        <button class="form-aside__btn-1" type="button"><?php _e("STEP 1", 'finanzia'); ?></button>
                        <button class="active form-aside__btn-2"
                                type="button"><?php _e("STEP 2", 'finanzia'); ?></button>
                    </div>
                    <div class="form-aside__progress">
                        <div class="form-aside__progress-text">
                            <span class="form-aside__progress-persent">0</span><?php _e("% filled", 'finanzia'); ?>
                        </div>
                        <div class="form-aside__progress-bar">
                            <div class="form-aside__progress-bar-line-2" style="width: 0%">
                            </div>
                        </div>
                    </div>
                    <?php get_template_part('template-parts/part', 'forms-calc-info', ['option_fields' => $option_fields, 'calc' => $calc]); ?>
                </div>
                <div class="form-page__bottom">
                    &copy; <?= get_bloginfo('name') . '&nbsp;' . date('Y'); ?>
                </div>
            </div>
            <div class="form-content">
                <ul class="form-line">
                    <li class="active">
                        <div class="form-line__number">
                            1
                        </div>
                        <div class="form-line__name">
                            <?php _e("Personal Info", 'finanzia'); ?>
                        </div>
                    </li>
                    <li>
                        <div class="form-line__number">
                            2
                        </div>
                        <div class="form-line__name">
                            <?php _e("Address", 'finanzia'); ?>
                        </div>
                    </li>
                    <li>
                        <div class="form-line__number">
                            3
                        </div>
                        <div class="form-line__name">
                            <?php _e("Income Info", 'finanzia'); ?>
                        </div>
                    </li>
                </ul>
                <form class="big-form js_one_send" method="post">
                    <?php wp_nonce_field('mortgages-form-step-two-action-' . $calc->ID, 'mortgages-form-step-two-field-' . $calc->ID); ?>
                    <input type="hidden" name="step_two[calc_id]" value="<?= $calc->ID ?>">
                    <div class="big-form__step-1 active">
                        <div class="form-line__title">
                            <?php _e("Personal Information", 'finanzia'); ?>
                        </div>
                        <div class="form-line__text">
                            <?php _e("Please fill out the form to confirm your application", 'finanzia'); ?>
                        </div>
                        <div class="form-opener">
                            <?php _e("Main Applicant", 'finanzia'); ?>
                        </div>
                        <div class="big-form__section">
                            <div class="big-form__row">
                                <div class="big-form__three-select">
                                    <div class="big-form__select-col select-big">
                                        <div class="contacts__label">
                                            <?php _e("Gender", 'finanzia'); ?>
                                        </div>
                                        <select required class="select" name="step_two[main_applicant][gender]">
                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                            <option value="Male"><?php _e("Male", 'finanzia'); ?></option>
                                            <option value="Female"><?php _e("Female", 'finanzia'); ?></option>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-big">
                                        <div class="contacts__label">
                                            <?php _e("Marital status", 'finanzia'); ?>
                                        </div>
                                        <select required class="select" name="step_two[main_applicant][marital_status]">
                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                            <option value="Single"><?php _e("Single", 'finanzia'); ?></option>
                                            <option value="Married"><?php _e("Married", 'finanzia'); ?></option>
                                            <option value="Divorced"><?php _e("Divorced", 'finanzia'); ?></option>
                                            <option value="Widow/er"><?php _e("Widow/er", 'finanzia'); ?></option>
                                            <option value="Other"><?php _e("Other", 'finanzia'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Passport number", 'finanzia'); ?>
                                </div>
                                <input required type="text"
                                       placeholder="<?php _e("Enter your passport number", 'finanzia'); ?>"
                                       name="step_two[main_applicant][passport][number]" value="">
                            </div>
                            <div class="new-step-select">
                                <div class="contacts__label">
                                    <?php _e("Issue date", 'finanzia'); ?>
                                </div>
                                <div class="new-step-select__list">
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][passport][issue_date][day]">
                                            <?php get_days_options(); ?>
                                        </select>
                                    </div>
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][passport][issue_date][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][passport][issue_date][year]">
                                            <?php get_years_options(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="new-step-select">
                                <div class="contacts__label">
                                    <?php _e("Expiration date", 'finanzia'); ?>
                                </div>
                                <div class="new-step-select__list">
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][passport][expiration_date][day]">
                                            <?php get_days_options(); ?>
                                        </select>
                                    </div>
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][passport][expiration_date][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][passport][expiration_date][year]">
                                            <?php get_years_options(2020, 2050); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Issuing authority", 'finanzia'); ?>
                                </div>
                                <input required type="text"
                                       placeholder="<?php _e("Enter the issuing authority", 'finanzia'); ?>"
                                       name="step_two[main_applicant][passport][issuing_authority]" value="">
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Residency number", 'finanzia'); ?>
                                </div>
                                <input required type="text"
                                       placeholder="<?php _e("Enter your residency number", 'finanzia'); ?>"
                                       name="step_two[main_applicant][residency][number]" value="">
                            </div>
                            <div class="new-step-select">
                                <div class="contacts__label">
                                    <?php _e("Issue date", 'finanzia'); ?>
                                </div>
                                <div class="new-step-select__list">
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][residency][issue_date][day]">
                                            <?php get_days_options(); ?>
                                        </select>
                                    </div>
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][residency][issue_date][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][residency][issue_date][year]">
                                            <?php get_years_options(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="new-step-select">
                                <div class="contacts__label">
                                    <?php _e("Expiration date", 'finanzia'); ?>
                                </div>
                                <div class="new-step-select__list">
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][residency][expiration_date][day]">
                                            <?php get_days_options(); ?>
                                        </select>
                                    </div>
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][residency][expiration_date][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][residency][expiration_date][year]">
                                            <?php get_years_options(2020, 2050); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Issuing authority", 'finanzia'); ?>
                                </div>
                                <input required type="text"
                                       placeholder="<?php _e("Enter the issuing authority", 'finanzia'); ?>"
                                       name="step_two[main_applicant][residency][issuing_authority]" value="">
                            </div>
                            <div class="new-step-title">
                                <?php _e("Additional Information", 'finanzia'); ?>
                            </div>
                            <div class="big-form__fields">
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Country of birth", 'finanzia'); ?>
                                    </div>
                                    <select required class="select" name="step_two[main_applicant][country_of_birth]">
                                        <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                        <?php get_nationality_options(); ?>
                                    </select>
                                </div>
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("City of birth", 'finanzia'); ?>
                                    </div>
                                    <input required
                                           placeholder="<?php _ex("Enter your city", "City of birth", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][city_of_birth]" value="">
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Education", 'finanzia'); ?>
                                </div>
                                <select required class="select" name="step_two[main_applicant][education]">
                                    <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                    <option value="Primary"><?php _e("Primary", 'finanzia'); ?></option>
                                    <option value="Secondary"><?php _e("Secondary", 'finanzia'); ?></option>
                                    <option value="University degree"><?php _e("University degree", 'finanzia'); ?></option>
                                    <option value="Other"><?php _e("Other (please describe below)", 'finanzia'); ?></option>
                                </select>
                            </div>
                            <div class="big-form__variant housing">
                                <div class="big-form__label">
                                    <?php _e("Type of housing you currently reside in", 'finanzia'); ?>
                                </div>
                                <div class="big-form__variant-list">
                                    <label class="big-form__var-1">
                                        <input checked="checked" type="radio" name="step_two[main_applicant][housing]"
                                               value="Rent">
                                        <span class="big-form__var-name"><?php _e("Rent", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input type="radio" name="step_two[main_applicant][housing]"
                                               value="Own property">
                                        <span class="big-form__var-name"><?php _e("Own property", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input type="radio" name="step_two[main_applicant][housing]"
                                               value="Cooperative flat">
                                        <span class="big-form__var-name"><?php _e("Cooperative flat", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input type="radio" name="step_two[main_applicant][housing]"
                                               value="Municipal flat">
                                        <span class="big-form__var-name"><?php _e("Municipal flat", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input type="radio" name="step_two[main_applicant][housing]"
                                               value="Staying with parent">
                                        <span class="big-form__var-name"><?php _e("Staying with parent", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input class="other" type="radio" name="step_two[main_applicant][housing]"
                                               value="Other">
                                        <span class="big-form__var-name"><?php _e("Other (please describe below)", 'finanzia'); ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="big-form__area">
                                <textarea class="optional"
                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                          name="step_two[main_applicant][housing_comments]" rows="8"
                                          cols="80"></textarea>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("When did you start living there?", 'finanzia'); ?>
                                </div>
                                <div class="new-step-select__list">
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][housing_start_living][day]">
                                            <?php get_days_options(); ?>
                                        </select>
                                    </div>
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][housing_start_living][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="new-step-select__box">
                                        <select required class="select"
                                                name="step_two[main_applicant][housing_start_living][year]">
                                            <?php get_years_options(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="new-step-title">
                                <?php _e("Other details", 'finanzia'); ?>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Monthly expenses — CZK", 'finanzia'); ?>
                                </div>
                                <input required type="text"
                                       placeholder="<?php _e("Household, Food, Utilities, Transport, Education, Healthcare, Other", 'finanzia'); ?>"
                                       name="step_two[main_applicant][monthly_expenses]" value="">
                            </div>
                            <div class="big-form__fields">
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Number of children", 'finanzia'); ?>
                                    </div>
                                    <input placeholder="<?php _e("Enter the number of children", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][number_of_children]" value="">
                                </div>
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Age(s) of child(ren)", 'finanzia'); ?>
                                    </div>
                                    <input placeholder="<?php _e("Enter the age(s) of your child(ren)", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][ages_of_children]" value="">
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Alimony monthly expense (if applicable) — CZK", 'finanzia'); ?>
                                </div>
                                <input type="text"
                                       placeholder="<?php _e("Enter the alimony expense amount (if applicable) — CZK", 'finanzia'); ?>"
                                       name="step_two[main_applicant][alimony_monthly_expense]" value="">
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Additional information (optional)", 'finanzia'); ?>
                                </div>
                                <textarea class="optional"
                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                          name="step_two[main_applicant][other_additional_information]" rows="8"
                                          cols="80"></textarea>
                            </div>
                        </div>
                        <?php if ($calc->relationship_type == 'someelse') : ?>
                            <div class="second-applicant">
                                <div class="form-opener">
                                    <?php _e("Second Applicant", 'finanzia'); ?>
                                </div>
                                <div class="big-form__section">
                                    <div class="big-form__row">
                                        <div class="big-form__three-select">
                                            <div class="big-form__select-col select-big">
                                                <div class="contacts__label">
                                                    <?php _e("Gender", 'finanzia'); ?>
                                                </div>
                                                <select required class="select"
                                                        name="step_two[second_applicant][gender]">
                                                    <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                    <option value="Male"><?php _e("Male", 'finanzia'); ?></option>
                                                    <option value="Female"><?php _e("Female", 'finanzia'); ?></option>
                                                </select>
                                            </div>
                                            <div class="big-form__select-col select-big">
                                                <div class="contacts__label">
                                                    <?php _e("Marital status", 'finanzia'); ?>
                                                </div>
                                                <select required class="select"
                                                        name="step_two[second_applicant][marital_status]">
                                                    <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                    <option value="Single"><?php _e("Single", 'finanzia'); ?></option>
                                                    <option value="Married"><?php _e("Married", 'finanzia'); ?></option>
                                                    <option value="Divorced"><?php _e("Divorced", 'finanzia'); ?></option>
                                                    <option value="Widow/er"><?php _e("Widow/er", 'finanzia'); ?></option>
                                                    <option value="Other"><?php _e("Other", 'finanzia'); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("Passport number", 'finanzia'); ?>
                                        </div>
                                        <input required type="text"
                                               placeholder="<?php _e("Enter your passport number", 'finanzia'); ?>"
                                               name="step_two[second_applicant][passport][number]" value="">
                                    </div>
                                    <div class="new-step-select">
                                        <div class="contacts__label">
                                            <?php _e("Issue date", 'finanzia'); ?>
                                        </div>
                                        <div class="new-step-select__list">
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][passport][issue_date][day]">
                                                    <?php get_days_options(); ?>
                                                </select>
                                            </div>
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][passport][issue_date][month]">
                                                    <?php get_months_options(); ?>
                                                </select>
                                            </div>
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][passport][issue_date][year]">
                                                    <?php get_years_options(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="new-step-select">
                                        <div class="contacts__label">
                                            <?php _e("Expiration date", 'finanzia'); ?>
                                        </div>
                                        <div class="new-step-select__list">
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][passport][expiration_date][day]">
                                                    <?php get_days_options(); ?>
                                                </select>
                                            </div>
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][passport][expiration_date][month]">
                                                    <?php get_months_options(); ?>
                                                </select>
                                            </div>
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][passport][expiration_date][year]">
                                                    <?php get_years_options(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("Issuing authority", 'finanzia'); ?>
                                        </div>
                                        <input required type="text"
                                               placeholder="<?php _e("Enter the issuing authority", 'finanzia'); ?>"
                                               name="step_two[second_applicant][passport][issuing_authority]" value="">
                                    </div>
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("Residency number", 'finanzia'); ?>
                                        </div>
                                        <input required type="text"
                                               placeholder="<?php _e("Enter your residency number", 'finanzia'); ?>"
                                               name="step_two[second_applicant][residency][number]" value="">
                                    </div>
                                    <div class="new-step-select">
                                        <div class="contacts__label">
                                            <?php _e("Issue date", 'finanzia'); ?>
                                        </div>
                                        <div class="new-step-select__list">
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][residency][issue_date][day]">
                                                    <?php get_days_options(); ?>
                                                </select>
                                            </div>
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][residency][issue_date][month]">
                                                    <?php get_months_options(); ?>
                                                </select>
                                            </div>
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][residency][issue_date][year]">
                                                    <?php get_years_options(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="new-step-select">
                                        <div class="contacts__label">
                                            <?php _e("Expiration date", 'finanzia'); ?>
                                        </div>
                                        <div class="new-step-select__list">
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][residency][expiration_date][day]">
                                                    <?php get_days_options(); ?>
                                                </select>
                                            </div>
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][residency][expiration_date][month]">
                                                    <?php get_months_options(); ?>
                                                </select>
                                            </div>
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][residency][expiration_date][year]">
                                                    <?php get_years_options(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("Issuing authority", 'finanzia'); ?>
                                        </div>
                                        <input required type="text"
                                               placeholder="<?php _e("Enter the issuing authority", 'finanzia'); ?>"
                                               name="step_two[second_applicant][residency][issuing_authority]" value="">
                                    </div>
                                    <div class="new-step-title">
                                        <?php _e("Additional Information", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__fields">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Country of birth", 'finanzia'); ?>
                                            </div>
                                            <select required class="select"
                                                    name="step_two[second_applicant][country_of_birth]">
                                                <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                <?php get_nationality_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("City of birth", 'finanzia'); ?>
                                            </div>
                                            <input required
                                                   placeholder="<?php _ex("Enter your city", "City of birth", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][city_of_birth]" value="">
                                        </div>
                                    </div>
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("Education", 'finanzia'); ?>
                                        </div>
                                        <select required class="select" name="step_two[second_applicant][education]">
                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                            <option value="Primary"><?php _e("Primary", 'finanzia'); ?></option>
                                            <option value="Secondary"><?php _e("Secondary", 'finanzia'); ?></option>
                                            <option value="University degree"><?php _e("University degree", 'finanzia'); ?></option>
                                            <option value="Other"><?php _e("Other (please describe below)", 'finanzia'); ?></option>
                                        </select>
                                    </div>
                                    <div class="big-form__variant housing">
                                        <div class="big-form__label">
                                            <?php _e("Type of housing you currently reside in", 'finanzia'); ?>
                                        </div>
                                        <div class="big-form__variant-list">
                                            <label class="big-form__var-1">
                                                <input checked="checked" type="radio"
                                                       name="step_two[second_applicant][housing]" value="Rent">
                                                <span class="big-form__var-name"><?php _e("Rent", 'finanzia'); ?></span>
                                            </label>
                                            <label class="big-form__var-1">
                                                <input type="radio" name="step_two[second_applicant][housing]"
                                                       value="Own property">
                                                <span class="big-form__var-name"><?php _e("Own property", 'finanzia'); ?></span>
                                            </label>
                                            <label class="big-form__var-1">
                                                <input type="radio" name="step_two[second_applicant][housing]"
                                                       value="Cooperative flat">
                                                <span class="big-form__var-name"><?php _e("Cooperative flat", 'finanzia'); ?></span>
                                            </label>
                                            <label class="big-form__var-1">
                                                <input type="radio" name="step_two[second_applicant][housing]"
                                                       value="Municipal flat">
                                                <span class="big-form__var-name"><?php _e("Municipal flat", 'finanzia'); ?></span>
                                            </label>
                                            <label class="big-form__var-1">
                                                <input type="radio" name="step_two[second_applicant][housing]"
                                                       value="Staying with parent">
                                                <span class="big-form__var-name"><?php _e("Staying with parent", 'finanzia'); ?></span>
                                            </label>
                                            <label class="big-form__var-1">
                                                <input class="other" type="radio"
                                                       name="step_two[second_applicant][housing]"
                                                       value="Other">
                                                <span class="big-form__var-name"><?php _e("Other (please describe below)", 'finanzia'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="big-form__area">
                                    <textarea class="optional"
                                              placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                              name="step_two[second_applicant][housing_comments]" rows="8"
                                              cols="80"></textarea>
                                    </div>
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("When did you start living there?", 'finanzia'); ?>
                                        </div>
                                        <div class="new-step-select__list">
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][housing_start_living][day]">
                                                    <?php get_days_options(); ?>
                                                </select>
                                            </div>
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][housing_start_living][month]">
                                                    <?php get_months_options(); ?>
                                                </select>
                                            </div>
                                            <div class="new-step-select__box">
                                                <select required class="select"
                                                        name="step_two[second_applicant][housing_start_living][year]">
                                                    <?php get_years_options(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="new-step-title">
                                        <?php _e("Other details", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("Monthly expenses — CZK", 'finanzia'); ?>
                                        </div>
                                        <input required type="text"
                                               placeholder="<?php _e("Household, Food, Utilities, Transport, Education, Healthcare, Other", 'finanzia'); ?>"
                                               name="step_two[second_applicant][monthly_expenses]" value="">
                                    </div>
                                    <div class="big-form__fields">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Number of children", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="<?php _e("Enter the number of children", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][number_of_children]" value="">
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Age(s) of child(ren)", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="<?php _e("Enter the age(s) of your child(ren)", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][ages_of_children]" value="">
                                        </div>
                                    </div>
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("Alimony monthly expense (if applicable) — CZK", 'finanzia'); ?>
                                        </div>
                                        <input type="text"
                                               placeholder="<?php _e("Enter the alimony expense amount (if applicable) — CZK", 'finanzia'); ?>"
                                               name="step_two[second_applicant][alimony_monthly_expense]" value="">
                                    </div>
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("Additional information (optional)", 'finanzia'); ?>
                                        </div>
                                        <textarea class="optional"
                                                  placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                  name="step_two[second_applicant][other_additional_information]"
                                                  rows="8"
                                                  cols="80"></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="big-form__button">
                            <button class="big-form__btn next-step"
                                    type="button"><?php _e("next step", 'finanzia'); ?></button>
                        </div>
                    </div>
                    <div class="big-form__step-2">
                        <div class="form-line__title">
                            <?php _e("Address", 'finanzia'); ?>
                        </div>
                        <div class="form-line__text">
                            <?php _e("Please fill out the form to confirm your application", 'finanzia'); ?>
                        </div>
                        <div class="form-opener">
                            <?php _e("Main Applicant", 'finanzia'); ?>
                        </div>
                        <div class="big-form__section">
                            <div class="new-step-title">
                                <?php _e("Permanent address", 'finanzia'); ?>
                            </div>
                            <div class="big-form__fields">
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Country", 'finanzia'); ?>
                                    </div>
                                    <select required class="select"
                                            name="step_two[main_applicant][permanent_address][country]">
                                        <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                        <?php get_nationality_options(); ?>
                                    </select>
                                </div>
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Municipality", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your municipality", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][permanent_address][municipality]" value="">
                                </div>
                            </div>
                            <div class="big-form__fields big-form__fields-difference">
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("City", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your city", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][permanent_address][city]" value="">
                                </div>
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Street", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your street", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][permanent_address][street]" value="">
                                </div>
                            </div>
                            <div class="big-form__fields">
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("House number", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your house number", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][permanent_address][house_number]" value="">
                                </div>
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Postal code", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your postal code", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][permanent_address][postal_code]" value="">
                                </div>
                            </div>
                            <div class="new-step-title">
                                <?php _e("Current address", 'finanzia'); ?>
                            </div>
                            <div class="big-form__fields">
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Country", 'finanzia'); ?>
                                    </div>
                                    <select required class="select"
                                            name="step_two[main_applicant][current_address][country]">
                                        <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                        <?php get_nationality_options(); ?>
                                    </select>
                                </div>
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Municipality", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your municipality", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][current_address][municipality]" value="">
                                </div>
                            </div>
                            <div class="big-form__fields big-form__fields-difference">
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("City", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your city", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][current_address][city]" value="">
                                </div>
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Street", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your street", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][current_address][street]" value="">
                                </div>
                            </div>
                            <div class="big-form__fields">
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("House number", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your house number", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][current_address][house_number]" value="">
                                </div>
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Postal code", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your postal code", 'finanzia'); ?>"
                                           type="text"
                                           name="step_two[main_applicant][current_address][postal_code]" value="">
                                </div>
                            </div>
                        </div>
                        <?php if ($calc->relationship_type == 'someelse') : ?>
                            <div class="second-applicant">
                                <div class="form-opener">
                                    <?php _e("Second Applicant", 'finanzia'); ?>
                                </div>
                                <div class="big-form__section">
                                    <div class="new-step-title">
                                        <?php _e("Permanent address", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__fields">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Country", 'finanzia'); ?>
                                            </div>
                                            <select required class="select"
                                                    name="step_two[second_applicant][permanent_address][country]">
                                                <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                <?php get_nationality_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Municipality", 'finanzia'); ?>
                                            </div>
                                            <input required
                                                   placeholder="<?php _e("Enter your municipality", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][permanent_address][municipality]"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="big-form__fields big-form__fields-difference">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("City", 'finanzia'); ?>
                                            </div>
                                            <input required placeholder="<?php _e("Enter your city", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][permanent_address][city]" value="">
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Street", 'finanzia'); ?>
                                            </div>
                                            <input required placeholder="<?php _e("Enter your street", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][permanent_address][street]"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="big-form__fields">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("House number", 'finanzia'); ?>
                                            </div>
                                            <input required
                                                   placeholder="<?php _e("Enter your house number", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][permanent_address][house_number]"
                                                   value="">
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Postal code", 'finanzia'); ?>
                                            </div>
                                            <input required
                                                   placeholder="<?php _e("Enter your postal code", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][permanent_address][postal_code]"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="new-step-title">
                                        <?php _e("Current address", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__fields">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Country", 'finanzia'); ?>
                                            </div>
                                            <select required class="select"
                                                    name="step_two[second_applicant][current_address][country]">
                                                <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                <?php get_nationality_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Municipality", 'finanzia'); ?>
                                            </div>
                                            <input required
                                                   placeholder="<?php _e("Enter your municipality", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][current_address][municipality]"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="big-form__fields big-form__fields-difference">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("City", 'finanzia'); ?>
                                            </div>
                                            <input required placeholder="<?php _e("Enter your city", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][current_address][city]" value="">
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Street", 'finanzia'); ?>
                                            </div>
                                            <input required placeholder="<?php _e("Enter your street", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][current_address][street]" value="">
                                        </div>
                                    </div>
                                    <div class="big-form__fields">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("House number", 'finanzia'); ?>
                                            </div>
                                            <input required
                                                   placeholder="<?php _e("Enter your house number", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][current_address][house_number]"
                                                   value="">
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Postal code", 'finanzia'); ?>
                                            </div>
                                            <input required
                                                   placeholder="<?php _e("Enter your postal code", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_two[second_applicant][current_address][postal_code]"
                                                   value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="big-form__button">
                            <button class="prev-step" type="button"><?php _e("Back", 'finanzia'); ?></button>
                            <button class="big-form__btn next-step"
                                    type="button"><?php _e("next step", 'finanzia'); ?></button>
                        </div>
                    </div>
                    <div class="big-form__step-3">
                        <div class="form-line__title">
                            <?php _e("Income Information", 'finanzia'); ?>
                        </div>
                        <div class="form-line__text">
                            <?php _e("Please fill out the form to confirm your application", 'finanzia'); ?>
                        </div>
                        <div class="form-opener">
                            <?php _e("Main Applicant", 'finanzia'); ?>
                        </div>
                        <div class="big-form__section">
                            <div class="big-form__holder">
                                <div class="big-form__variant">
                                    <div class="big-form__label">
                                        <?php _e("Income type", 'finanzia'); ?>
                                    </div>
                                    <ul class="big-form__tabs-list">
                                        <li>
                                            <a class="active" data-current_tab="full_time_employee"
                                               href="#big-form__income-1"><?php _e("Full-time employee", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="self_employed"
                                               href="#big-form__income-2"><?php _e("Self-employed", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="business"
                                               href="#big-form__income-3"><?php _e("Business", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="rental_income"
                                               href="#big-form__income-4"><?php _e("Rental income", 'finanzia'); ?></a>
                                        </li>
                                    </ul>
                                    <input type="hidden" class="current_tab"
                                           name="step_two[main_applicant][income_type][current_tab][full_time_employee]">
                                </div>
                                <div id="big-form__income-1" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Employer's official name", 'finanzia'); ?>
                                                </div>
                                                <input required
                                                       placeholder="<?php _e("Enter the official name of your employer", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_two[main_applicant][full_time_employee][employers_official_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Employer's identification number (IČO)", 'finanzia'); ?>
                                                </div>
                                                <input required
                                                       placeholder="<?php _e("Enter your employer's identification number (IČO)", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_two[main_applicant][full_time_employee][employers_identification_number]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Monthly net income (6-month average) — CZK", 'finanzia'); ?>
                                                </div>
                                                <input required
                                                       placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_two[main_applicant][full_time_employee][monthly_net_income]"
                                                       value="">
                                            </div>
                                            <div class="big-form__variant housing">
                                                <div class="big-form__label">
                                                    <?php _e("Contract type", 'finanzia'); ?>
                                                </div>
                                                <div class="big-form__variant-list">
                                                    <label class="big-form__var-1">
                                                        <input id="contract-1" checked="checked" type="radio"
                                                               name="step_two[main_applicant][full_time_employee][contract_type]"
                                                               value="Definite">
                                                        <span class="big-form__var-name"><?php _e("Definite", 'finanzia'); ?></span>
                                                    </label>
                                                    <label class="big-form__var-1">
                                                        <input id="contract-2" type="radio"
                                                               name="step_two[main_applicant][full_time_employee][contract_type]"
                                                               value="Indefinite">
                                                        <span class="big-form__var-name"><?php _e("Indefinite", 'finanzia'); ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="add-contract show">
                                                <div class="new-step-select">
                                                    <div class="contacts__label">
                                                        <?php _e("Contract validity from", 'finanzia'); ?>
                                                    </div>
                                                    <div class="new-step-select__list">
                                                        <div class="new-step-select__box">
                                                            <select required class="select"
                                                                    name="step_two[main_applicant][full_time_employee][contract_validity_from][day]">
                                                                <?php get_days_options(); ?>
                                                            </select>
                                                        </div>
                                                        <div class="new-step-select__box">
                                                            <select required class="select"
                                                                    name="step_two[main_applicant][full_time_employee][contract_validity_from][month]">
                                                                <?php get_months_options(); ?>
                                                            </select>
                                                        </div>
                                                        <div class="new-step-select__box">
                                                            <select required class="select"
                                                                    name="step_two[main_applicant][full_time_employee][contract_validity_from][year]">
                                                                <?php get_years_options(); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="new-step-select">
                                                    <div class="contacts__label">
                                                        <?php _e("Contract validity until", 'finanzia'); ?>
                                                    </div>
                                                    <div class="new-step-select__list">
                                                        <div class="new-step-select__box">
                                                            <select required class="select"
                                                                    name="step_two[main_applicant][full_time_employee][contract_validity_until][day]">
                                                                <?php get_days_options(); ?>
                                                            </select>
                                                        </div>
                                                        <div class="new-step-select__box">
                                                            <select required class="select"
                                                                    name="step_two[main_applicant][full_time_employee][contract_validity_until][month]">
                                                                <?php get_months_options(); ?>
                                                            </select>
                                                        </div>
                                                        <div class="new-step-select__box">
                                                            <select required class="select"
                                                                    name="step_two[main_applicant][full_time_employee][contract_validity_until][year]">
                                                                <?php get_years_options(2020, 2050); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Employer's business field", 'finanzia'); ?>
                                                </div>
                                                <select required class="select"
                                                        name="step_two[main_applicant][full_time_employee][employers_business_field]">
                                                    <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                    <option value="Transport"><?php _e("Transport", 'finanzia'); ?></option>
                                                    <option value="Utilities (electricity, water, gas)"><?php _e("Utilities (electricity, water, gas)", 'finanzia'); ?></option>
                                                    <option value="Retail"><?php _e("Retail", 'finanzia'); ?></option>
                                                    <option value="Finance"><?php _e("Finance", 'finanzia'); ?></option>
                                                    <option value="Tourism, hospitality"><?php _e("Tourism, hospitality", 'finanzia'); ?></option>
                                                    <option value="Telecommunication"><?php _e("Telecommunication", 'finanzia'); ?></option>
                                                    <option value="Real estate"><?php _e("Real estate", 'finanzia'); ?></option>
                                                    <option value="Healthcare"><?php _e("Healthcare", 'finanzia'); ?></option>
                                                    <option value="Agriculture"><?php _e("Agriculture", 'finanzia'); ?></option>
                                                    <option value="Other"><?php _e("Other (please describe below)", 'finanzia'); ?></option>
                                                </select>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Employer type", 'finanzia'); ?>
                                                </div>
                                                <select required class="select"
                                                        name="step_two[main_applicant][full_time_employee][employer_type]">
                                                    <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                    <option value="State"><?php _e("State", 'finanzia'); ?></option>
                                                    <option value="Private"><?php _e("Private", 'finanzia'); ?></option>
                                                    <option value="EU Government"><?php _e("EU Government", 'finanzia'); ?></option>
                                                </select>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Job classification", 'finanzia'); ?>
                                                </div>
                                                <select required class="select"
                                                        name="step_two[main_applicant][full_time_employee][job_classification]">
                                                    <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                    <option value="Administrative"><?php _e("Administrative", 'finanzia'); ?></option>
                                                    <option value="Physical staff"><?php _e("Physical staff", 'finanzia'); ?></option>
                                                    <option value="Middle and top management"><?php _e("Middle and top management", 'finanzia'); ?></option>
                                                    <option value="Director"><?php _e("Director", 'finanzia'); ?></option>
                                                    <option value="Other"><?php _e("Other (please describe below)", 'finanzia'); ?></option>
                                                </select>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Length of current employment", 'finanzia'); ?>
                                                </div>
                                                <div class="big-form__fields">
                                                    <div class="big-form__col">
                                                        <input required placeholder="<?php _e("Year", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[main_applicant][full_time_employee][length_of_current_employment][year]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__col">
                                                        <input required placeholder="<?php _e("Month", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[main_applicant][full_time_employee][length_of_current_employment][month]"
                                                               value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          name="step_two[main_applicant][full_time_employee][additional_information]"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="big-form__income-2" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _ex("Legal registered name", "Self-employed", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter your legal registered name", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_two[main_applicant][self_employed][legal_registered_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Tax identification number (DIČ)", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter your tax identification number (DIČ)", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_two[main_applicant][self_employed][tax_id]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Total revenue in the previous year — CZK", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_two[main_applicant][self_employed][total_revenue_in_previous_year]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Total revenue in the year before — CZK", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_two[main_applicant][self_employed][total_revenue_in_year_before]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_two[main_applicant][self_employed][additional_information]"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="big-form__income-3" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _ex("Legal registered name", "Business", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter your legal registered name", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_two[main_applicant][business][legal_registered_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Tax identification number (DIČ)", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter your tax identification number (DIČ)", 'finanzia'); ?>"
                                                       type="text" name="step_two[main_applicant][business][tax_id]"
                                                       value="">
                                            </div>
                                            <div class="big-form__fields">
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Total revenue in the previous year — CZK", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_two[main_applicant][business][total_revenue_i_previous_year]"
                                                           value="">
                                                </div>
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Net profit in the previous year — CZK", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_two[main_applicant][business][net_profit_in_previous_year]"
                                                           value="">
                                                </div>
                                            </div>
                                            <div class="big-form__fields">
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Total revenue in the year before — CZK", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_two[main_applicant][business][total_revenue_in_the_year_before]"
                                                           value="">
                                                </div>
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Net profit in the year before — CZK", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_two[main_applicant][business][net_profit_in_year_before]"
                                                           value="">
                                                </div>
                                            </div>
                                            <!--                                        <div class="big-form__row">-->
                                            <!--                                            <div class="contacts__label">-->
                                            <!--                                                --><?php //_e("Field of business", 'finanzia'); ?>
                                            <!--                                            </div>-->
                                            <!--                                            <input type="text"-->
                                            <!--                                                   name="step_two[main_applicant][business][field_of_business]"-->
                                            <!--                                                   value="">-->
                                            <!--                                        </div>-->
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_two[main_applicant][business][additional_information]"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="big-form__income-4" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__fields">
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Property address", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter property address", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_two[main_applicant][rental_income][property_address]"
                                                           value="">
                                                </div>
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Start of rental contract", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter start date of rental contract", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_two[main_applicant][rental_income][start_rental_contract]"
                                                           value="">
                                                </div>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          name="step_two[main_applicant][rental_income][additional_information]"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($calc->relationship_type == 'someelse') : ?>
                            <div class="second-applicant">
                                <div class="form-opener">
                                    <?php _e("Second Applicant", 'finanzia'); ?>
                                </div>
                                <div class="big-form__section">
                                    <div class="big-form__holder">
                                        <div class="big-form__variant">
                                            <div class="big-form__label">
                                                <?php _e("Income type", 'finanzia'); ?>
                                            </div>
                                            <ul class="big-form__tabs-list">
                                                <li>
                                                    <a class="active" data-current_tab="full_time_employee"
                                                       href="#big-form__second-income-1"><?php _e("Full-time employee", 'finanzia'); ?></a>
                                                </li>
                                                <li>
                                                    <a data-current_tab="self_employed"
                                                       href="#big-form__second-income-2"><?php _e("Self-employed", 'finanzia'); ?></a>
                                                </li>
                                                <li>
                                                    <a data-current_tab="business"
                                                       href="#big-form__second-income-3"><?php _e("Business", 'finanzia'); ?></a>
                                                </li>
                                                <li>
                                                    <a data-current_tab="rental_income"
                                                       href="#big-form__second-income-4"><?php _e("Rental income", 'finanzia'); ?></a>
                                                </li>
                                            </ul>
                                            <input type="hidden" class="current_tab"
                                                   name="step_two[second_applicant][income_type][current_tab][full_time_employee]">
                                        </div>
                                        <div id="big-form__second-income-1" class="tab">
                                            <div class="field-wrapper">
                                                <div class="field-section">
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Employer's official name", 'finanzia'); ?>
                                                        </div>
                                                        <input required
                                                               placeholder="<?php _e("Enter the official name of your employer", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[second_applicant][full_time_employee][employers_official_name]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Employer's identification number (IČO)", 'finanzia'); ?>
                                                        </div>
                                                        <input required
                                                               placeholder="<?php _e("Enter your employer's identification number (IČO)", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[second_applicant][full_time_employee][employers_identification_number]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Monthly net income (6-month average) — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input required
                                                               placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[second_applicant][full_time_employee][monthly_net_income]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__variant housing">
                                                        <div class="big-form__label">
                                                            <?php _e("Contract type", 'finanzia'); ?>
                                                        </div>
                                                        <div class="big-form__variant-list">
                                                            <label class="big-form__var-1">
                                                                <input id="second-contract-1" checked="checked"
                                                                       type="radio"
                                                                       name="step_two[second_applicant][full_time_employee][contract_type]"
                                                                       value="Definite">
                                                                <span class="big-form__var-name"><?php _e("Definite", 'finanzia'); ?></span>
                                                            </label>
                                                            <label class="big-form__var-1">
                                                                <input id="second-contract-2" type="radio"
                                                                       name="step_two[second_applicant][full_time_employee][contract_type]"
                                                                       value="Indefinite">
                                                                <span class="big-form__var-name"><?php _e("Indefinite", 'finanzia'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="second-add-contract show">
                                                        <div class="new-step-select">
                                                            <div class="contacts__label">
                                                                <?php _e("Contract validity from", 'finanzia'); ?>
                                                            </div>
                                                            <div class="new-step-select__list">
                                                                <div class="new-step-select__box">
                                                                    <select required class="select"
                                                                            name="step_two[second_applicant][full_time_employee][contract_validity_from][day]">
                                                                        <?php get_days_options(); ?>
                                                                    </select>
                                                                </div>
                                                                <div class="new-step-select__box">
                                                                    <select required class="select"
                                                                            name="step_two[second_applicant][full_time_employee][contract_validity_from][month]">
                                                                        <?php get_months_options(); ?>
                                                                    </select>
                                                                </div>
                                                                <div class="new-step-select__box">
                                                                    <select required class="select"
                                                                            name="step_two[second_applicant][full_time_employee][contract_validity_from][year]">
                                                                        <?php get_years_options(); ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="new-step-select">
                                                            <div class="contacts__label">
                                                                <?php _e("Contract validity until", 'finanzia'); ?>
                                                            </div>
                                                            <div class="new-step-select__list">
                                                                <div class="new-step-select__box">
                                                                    <select required class="select"
                                                                            name="step_two[second_applicant][full_time_employee][contract_validity_until][day]">
                                                                        <?php get_days_options(); ?>
                                                                    </select>
                                                                </div>
                                                                <div class="new-step-select__box">
                                                                    <select required class="select"
                                                                            name="step_two[second_applicant][full_time_employee][contract_validity_until][month]">
                                                                        <?php get_months_options(); ?>
                                                                    </select>
                                                                </div>
                                                                <div class="new-step-select__box">
                                                                    <select required class="select"
                                                                            name="step_two[second_applicant][full_time_employee][contract_validity_until][year]">
                                                                        <?php get_years_options(2020, 2050); ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Employer's business field", 'finanzia'); ?>
                                                        </div>
                                                        <select required class="select"
                                                                name="step_two[second_applicant][full_time_employee][employers_business_field]">
                                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                            <option value="Transport"><?php _e("Transport", 'finanzia'); ?></option>
                                                            <option value="Utilities (electricity, water, gas)"><?php _e("Utilities (electricity, water, gas)", 'finanzia'); ?></option>
                                                            <option value="Retail"><?php _e("Retail", 'finanzia'); ?></option>
                                                            <option value="Finance"><?php _e("Finance", 'finanzia'); ?></option>
                                                            <option value="Tourism, hospitality"><?php _e("Tourism, hospitality", 'finanzia'); ?></option>
                                                            <option value="Telecommunication"><?php _e("Telecommunication", 'finanzia'); ?></option>
                                                            <option value="Real estate"><?php _e("Real estate", 'finanzia'); ?></option>
                                                            <option value="Healthcare"><?php _e("Healthcare", 'finanzia'); ?></option>
                                                            <option value="Agriculture"><?php _e("Agriculture", 'finanzia'); ?></option>
                                                            <option value="Other"><?php _e("Other (please describe below)", 'finanzia'); ?></option>
                                                        </select>
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Employer type", 'finanzia'); ?>
                                                        </div>
                                                        <select required class="select"
                                                                name="step_two[second_applicant][full_time_employee][employer_type]">
                                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                            <option value="State"><?php _e("State", 'finanzia'); ?></option>
                                                            <option value="Private"><?php _e("Private", 'finanzia'); ?></option>
                                                            <option value="EU Government"><?php _e("EU Government", 'finanzia'); ?></option>
                                                        </select>
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Job classification", 'finanzia'); ?>
                                                        </div>
                                                        <select required class="select"
                                                                name="step_two[second_applicant][full_time_employee][job_classification]">
                                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                            <option value="Administrative"><?php _e("Administrative", 'finanzia'); ?></option>
                                                            <option value="Physical staff"><?php _e("Physical staff", 'finanzia'); ?></option>
                                                            <option value="Middle and top management"><?php _e("Middle and top management", 'finanzia'); ?></option>
                                                            <option value="Director"><?php _e("Director", 'finanzia'); ?></option>
                                                            <option value="Other"><?php _e("Other (please describe below)", 'finanzia'); ?></option>
                                                        </select>
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Length of current employment", 'finanzia'); ?>
                                                        </div>
                                                        <div class="big-form__fields">
                                                            <div class="big-form__col">
                                                                <input required
                                                                       placeholder="<?php _e("Year", 'finanzia'); ?>"
                                                                       type="text"
                                                                       name="step_two[second_applicant][full_time_employee][length_of_current_employment][year]"
                                                                       value="">
                                                            </div>
                                                            <div class="big-form__col">
                                                                <input required
                                                                       placeholder="<?php _e("Month", 'finanzia'); ?>"
                                                                       type="text"
                                                                       name="step_two[second_applicant][full_time_employee][length_of_current_employment][month]"
                                                                       value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Additional information (optional)", 'finanzia'); ?>
                                                        </div>
                                                        <textarea class="optional"
                                                                  placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                                  name="step_two[second_applicant][full_time_employee][additional_information]"
                                                                  rows="8"
                                                                  cols="80"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="big-form__second-income-2" class="tab">
                                            <div class="field-wrapper">
                                                <div class="field-section">
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _ex("Legal registered name", "Self-employed", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="<?php _e("Enter your legal registered name", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[second_applicant][self_employed][legal_registered_name]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Tax identification number (DIČ)", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="<?php _e("Enter your tax identification number (DIČ)", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[second_applicant][self_employed][tax_id]"
                                                               value="">
                                                    </div>

                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Total revenue in the previous year — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[second_applicant][self_employed][total_revenue_in_previous_year]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Total revenue in the year before — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[second_applicant][self_employed][total_revenue_in_year_before]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Additional information (optional)", 'finanzia'); ?>
                                                        </div>
                                                        <textarea class="optional"
                                                                  name="step_two[second_applicant][self_employed][additional_information]"
                                                                  placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                                  rows="8"
                                                                  cols="80"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="big-form__second-income-3" class="tab">
                                            <div class="field-wrapper">
                                                <div class="field-section">
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _ex("Legal registered name", "Business", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="<?php _e("Enter your legal registered name", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[second_applicant][business][legal_registered_name]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Tax identification number (DIČ)", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="<?php _e("Enter your tax identification number (DIČ)", 'finanzia'); ?>"
                                                               type="text"
                                                               name="step_two[second_applicant][business][tax_id]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__fields">
                                                        <div class="big-form__col">
                                                            <div class="contacts__label">
                                                                <?php _e("Total revenue in the previous year — CZK", 'finanzia'); ?>
                                                            </div>
                                                            <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                                   type="text"
                                                                   name="step_two[second_applicant][business][total_revenue_i_previous_year]"
                                                                   value="">
                                                        </div>
                                                        <div class="big-form__col">
                                                            <div class="contacts__label">
                                                                <?php _e("Net profit in the previous year — CZK", 'finanzia'); ?>
                                                            </div>
                                                            <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                                   type="text"
                                                                   name="step_two[second_applicant][business][net_profit_in_previous_year]"
                                                                   value="">
                                                        </div>
                                                    </div>
                                                    <div class="big-form__fields">
                                                        <div class="big-form__col">
                                                            <div class="contacts__label">
                                                                <?php _e("Total revenue in the year before — CZK", 'finanzia'); ?>
                                                            </div>
                                                            <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                                   type="text"
                                                                   name="step_two[second_applicant][business][total_revenue_in_the_year_before]"
                                                                   value="">
                                                        </div>
                                                        <div class="big-form__col">
                                                            <div class="contacts__label">
                                                                <?php _e("Net profit in the year before — CZK", 'finanzia'); ?>
                                                            </div>
                                                            <input placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                                                   type="text"
                                                                   name="step_two[second_applicant][business][net_profit_in_year_before]"
                                                                   value="">
                                                        </div>
                                                    </div>
                                                    <!--                                                <div class="big-form__row">-->
                                                    <!--                                                    <div class="contacts__label">-->
                                                    <!--                                                        --><?php //_e("Field of business", 'finanzia'); ?>
                                                    <!--                                                    </div>-->
                                                    <!--                                                    <input type="text"-->
                                                    <!--                                                           name="step_two[second_applicant][business][field_of_business]"-->
                                                    <!--                                                           value="">-->
                                                    <!--                                                </div>-->
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Additional information (optional)", 'finanzia'); ?>
                                                        </div>
                                                        <textarea class="optional"
                                                                  name="step_two[second_applicant][business][additional_information]"
                                                                  placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                                  rows="8"
                                                                  cols="80"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="big-form__second-income-4" class="tab">
                                            <div class="field-wrapper">
                                                <div class="field-section">
                                                    <div class="big-form__fields">
                                                        <div class="big-form__col">
                                                            <div class="contacts__label">
                                                                <?php _e("Property address", 'finanzia'); ?>
                                                            </div>
                                                            <input placeholder="<?php _e("Enter property address", 'finanzia'); ?>"
                                                                   type="text"
                                                                   name="step_two[second_applicant][rental_income][property_address]"
                                                                   value="">
                                                        </div>
                                                        <div class="big-form__col">
                                                            <div class="contacts__label">
                                                                <?php _e("Start of rental contract", 'finanzia'); ?>
                                                            </div>
                                                            <input placeholder="<?php _e("Enter start date of rental contract", 'finanzia'); ?>"
                                                                   type="text"
                                                                   name="step_two[second_applicant][rental_income][start_rental_contract]"
                                                                   value="">
                                                        </div>
                                                    </div>
                                                    <div class="big-form__row">
                                                        <div class="contacts__label">
                                                            <?php _e("Additional information (optional)", 'finanzia'); ?>
                                                        </div>
                                                        <textarea class="optional"
                                                                  placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                                  name="step_two[second_applicant][rental_income][additional_information]"
                                                                  rows="8"
                                                                  cols="80"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="big-form__button">
                            <button class="prev-step" type="button"><?php _e("Back", 'finanzia'); ?></button>
                            <button class="big-form__submit-btn"
                                    type="submit"><?php _e("Confirm application", 'finanzia'); ?></button>
                        </div>
                    </div
                </form>
            </div>
        </div>
    </div>
<?php
get_footer('steps');
