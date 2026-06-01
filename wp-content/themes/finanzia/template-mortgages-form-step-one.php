<?php
/**
 * Template name: Mortgages form step one template
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
                        <button class="active form-aside__btn-1"
                                type="button"><?php _e("STEP 1", 'finanzia'); ?></button>
                        <button class="form-aside__btn-2" type="button"><?php _e("STEP 2", 'finanzia'); ?></button>
                    </div>
                    <div class="form-aside__progress">
                        <div class="form-aside__progress-text">
                            <span class="form-aside__progress-persent">0</span><?php _e("% filled", 'finanzia'); ?>
                        </div>
                        <div class="form-aside__progress-bar">
                            <div class="form-aside__progress-bar-line" style="width: 0%">
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
                            <?php _e("Income Type", 'finanzia'); ?>
                        </div>
                    </li>
                    <li>
                        <div class="form-line__number">
                            3
                        </div>
                        <div class="form-line__name">
                            <?php _e("Existing Liabilities", 'finanzia'); ?>
                        </div>
                    </li>
                    <li>
                        <div class="form-line__number">
                            4
                        </div>
                        <div class="form-line__name">
                            <?php _e("Type of property", 'finanzia'); ?>
                        </div>
                    </li>
                </ul>
                <form class="big-form js_one_send" method="post">
                    <?php wp_nonce_field('mortgages-form-step-one-action-' . $calc->ID, 'mortgages-form-step-one-field-' . $calc->ID); ?>
                    <input type="hidden" name="step_one[calc_id]" value="<?= $calc->ID ?>">
                    <div class="big-form__step-1 active">
                        <div class="form-line__title">
                            <?php _e("Personal Information", 'finanzia'); ?>
                        </div>
                        <div class="form-line__text">
                            <?php _e("Please fill out the form to confirm your application", 'finanzia'); ?>
                        </div>
                        <div class="big-form__fields">
                            <div class="big-form__col">
                                <div class="contacts__label">
                                    <?php _e("First name", 'finanzia'); ?>
                                </div>
                                <input required placeholder="<?php _e("Enter your first name", 'finanzia'); ?>"
                                       type="text" name="step_one[main_applicant][first_name]" value="">
                            </div>
                            <div class="big-form__col">
                                <div class="contacts__label">
                                    <?php _e("Last name", 'finanzia'); ?>
                                </div>
                                <input required placeholder="<?php _e("Enter your last name", 'finanzia'); ?>"
                                       type="text" name="step_one[main_applicant][last_name]" value="">
                            </div>
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Date of birth", 'finanzia'); ?>
                            </div>
                            <div class="big-form__three-select">
                                <div class="big-form__select-col select-big">
                                    <select required class="select select-big"
                                            name="step_one[main_applicant][dob][month]">
                                        <?php get_months_options(); ?>
                                    </select>
                                </div>
                                <div class="big-form__select-col select-small">
                                    <select required class="select" name="step_one[main_applicant][dob][day]">
                                        <?php get_days_options(); ?>
                                    </select>
                                </div>
                                <div class="big-form__select-col select-small">
                                    <select required class="select select-small"
                                            name="step_one[main_applicant][dob][year]">
                                        <?php get_years_options(1952, 2005); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Phone", 'finanzia'); ?>
                            </div>
                            <input class="tel-input" required type="tel" name="step_one[main_applicant][phone]" value="">
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Length of residency in Czech Republic", 'finanzia'); ?>
                            </div>
                            <div class="big-form__three-select">
                                <div class="big-form__select-col select-big">
                                    <select required class="select"
                                            name="step_one[main_applicant][length_of_residency][duration]">
                                        <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                        <option value="Less than 6 months"><?php _e("Less than 6 months", 'finanzia'); ?></option>
                                        <option value="Up to 1 year"><?php _e("Up to 1 year", 'finanzia'); ?></option>
                                        <option value="More than 1 year"><?php _e("More than 1 year", 'finanzia'); ?></option>
                                        <option value="Over 2 years"><?php _e("Over 2 years", 'finanzia'); ?></option>
                                        <option value="Over 5 years"><?php _e("Over 5 years", 'finanzia'); ?></option>
                                    </select>
                                </div>
                                <div class="big-form__select-col select-small">
                                    <select required class="select"
                                            name="step_one[main_applicant][length_of_residency][from_month]">
                                        <?php get_months_options(); ?>
                                    </select>
                                </div>
                                <div class="big-form__select-col select-small">
                                    <select required class="select"
                                            name="step_one[main_applicant][length_of_residency][from_year]">
                                        <?php get_years_options(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="big-form__row">
                            <div class="big-form__three-select">
                                <div class="big-form__select-col select-big">
                                    <div class="contacts__label">
                                        <?php _e("Nationality", 'finanzia'); ?>
                                    </div>
                                    <select required class="select" name="step_one[main_applicant][main_nationality]">
                                        <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                        <?php get_nationality_options(); ?>
                                    </select>
                                </div>
                                <div class="big-form__select-col select-big">
                                    <div class="contacts__label">
                                        <?php _e("Residency type in Czech Republic", 'finanzia'); ?>
                                    </div>
                                    <select required class="select" name="step_one[main_applicant][residency_type]">
                                        <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                        <option value="No residency"><?php _e("No residency", 'finanzia'); ?></option>
                                        <option value="Temporary"><?php _e("Temporary", 'finanzia'); ?></option>
                                        <option value="Long-term"><?php _e("Long-term", 'finanzia'); ?></option>
                                        <option value="Working card"><?php _e("Working card", 'finanzia'); ?></option>
                                        <option value="Blue card"><?php _e("Blue card", 'finanzia'); ?></option>
                                        <option value="Permanent"><?php _e("Permanent", 'finanzia'); ?></option>
                                        <option value="Diplomatic"><?php _e("Diplomatic", 'finanzia'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="big-form__check-box">
                            <label class="big-form__check-multi">
                                <input type="hidden" name="step_one[main_applicant][passports][multipass]" value="no">
                                <input class="check-multi" type="checkbox"
                                       name="step_one[main_applicant][passports][multipass]" value="yes">
                                <span class="big-form__check-text"><?php _e("I have multiple passports", 'finanzia'); ?></span>
                            </label>
                        </div>
                        <div class="big-form__add">
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Nationality", 'finanzia'); ?>
                                </div>
                                <select class="select" name="step_one[main_applicant][passports][nationality][]">
                                    <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                    <?php get_nationality_options(); ?>
                                </select>
                            </div>
                            <div class="big-form__check-box">
                                <label class="big-form__check-multi">
                                    <input class="add-nationality" type="checkbox">
                                    <span class="big-form__check-text"><?php _e("I have one more passport", 'finanzia'); ?></span>
                                </label>
                            </div>
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Birth registration number (Rodné číslo) — optional", 'finanzia'); ?>
                            </div>
                            <input class="optional" placeholder="XXXXXX/XXXX" type="text"
                                   name="step_one[main_applicant][passport_registration_number]" value="">
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Banks you currently use in Czech Republic (select all that apply)", 'finanzia'); ?>
                            </div>
                            <select required multiple class="select" name="step_one[main_applicant][current_bank][]">
                                <option disabled value=""><?php _e("Select", 'finanzia'); ?></option>
                                <option value="Česká spořitelna">Česká spořitelna</option>
                                <option value="Komerční banka">Komerční banka</option>
                                <option value="Československá obchodní banka(ČSOB)">Československá obchodní
                                    banka(ČSOB)
                                </option>
                                <option value="Raiffeisenbank">Raiffeisenbank</option>
                                <option value="UniCredit Bank">UniCredit Bank</option>
                                <option value="MONETA Money Bank">MONETA Money Bank</option>
                                <option value="Fio banka">Fio banka</option>
                                <option value="Air Bank">Air Bank</option>
                                <option value="Banka CREDITAS">Banka CREDITAS</option>
                                <option value="mBank">mBank</option>
                                <option value="Oberbank">Oberbank</option>
                                <option value="Other"><?php _e("Other (please describe below)", 'finanzia'); ?></option>
                            </select>
                        </div>


                        <div class="big-form__variant">
                            <div class="big-form__label">
                                <?php _e("Applying alone or with someone else?", 'finanzia'); ?>
                            </div>
                            <div class="big-form__variant-list">
                                <label class="big-form__var-1">
                                    <input id="alone" checked="checked" type="radio"
                                           name="step_one[relationship_type]" value="alone">
                                    <span class="big-form__var-name"><?php _e("Alone", 'finanzia'); ?></span>
                                </label>
                                <label class="big-form__var-2">
                                    <input id="someelse" type="radio" name="step_one[relationship_type]"
                                           value="someelse">
                                    <span class="big-form__var-name"><?php _e("With someone else", 'finanzia'); ?></span>
                                </label>
                            </div>
                        </div>
                        <div class="big-form__add-section">
                            <div class="big-form__variant">
                                <div class="big-form__label">
                                    <?php _e("Relationship to the main applicant", 'finanzia'); ?>
                                </div>
                                <div class="big-form__variant-list">
                                    <label class="big-form__var-1">
                                        <input checked="checked" type="radio" name="step_one[relationship_who]"
                                               value="Spouse">
                                        <span class="big-form__var-name"><?php _e("Spouse", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input type="radio" name="step_one[relationship_who]" value="Partner">
                                        <span class="big-form__var-name"><?php _e("Partner", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input type="radio" name="step_one[relationship_who]"
                                               value="Close person">
                                        <span class="big-form__var-name"><?php _e("Close person", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input type="radio" name="step_one[relationship_who]" value="Other">
                                        <span class="big-form__var-name"><?php _e("Other", 'finanzia'); ?></span>
                                    </label>
                                </div>
                            </div>


                            <div class="big-form__fields">
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("First name", 'finanzia'); ?>
                                    </div>
                                    <input placeholder="<?php _e("Enter your first name", 'finanzia'); ?>"
                                           type="text" name="step_one[second_applicant][first_name]" value="">
                                </div>
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Last name", 'finanzia'); ?>
                                    </div>
                                    <input placeholder="<?php _e("Enter your last name", 'finanzia'); ?>"
                                           type="text" name="step_one[second_applicant][last_name]" value="">
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Date of birth", 'finanzia'); ?>
                                </div>
                                <div class="big-form__three-select">
                                    <div class="big-form__select-col select-big">
                                        <select class="select select-big"
                                                name="step_one[second_applicant][dob][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select class="select" name="step_one[second_applicant][dob][day]">
                                            <?php get_days_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select class="select select-small"
                                                name="step_one[second_applicant][dob][year]">
                                            <?php get_years_options(1952, 2005); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Phone (optional)", 'finanzia'); ?>
                                </div>
                                <input class="tel-input" type="tel" name="step_one[second_applicant][phone]" value="">
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Length of residency in Czech Republic", 'finanzia'); ?>
                                </div>
                                <div class="big-form__three-select">
                                    <div class="big-form__select-col select-big">
                                        <select class="select"
                                                name="step_one[second_applicant][length_of_residency][duration]">
                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                            <option value="Less than 6 months"><?php _e("Less than 6 months", 'finanzia'); ?></option>
                                            <option value="Up to 1 year"><?php _e("Up to 1 year", 'finanzia'); ?></option>
                                            <option value="More than 1 year"><?php _e("More than 1 year", 'finanzia'); ?></option>
                                            <option value="Over 2 years"><?php _e("Over 2 years", 'finanzia'); ?></option>
                                            <option value="Over 5 years"><?php _e("Over 5 years", 'finanzia'); ?></option>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select class="select"
                                                name="step_one[second_applicant][length_of_residency][from_month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select class="select"
                                                name="step_one[second_applicant][length_of_residency][from_year]">
                                            <?php get_years_options(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="big-form__three-select">
                                    <div class="big-form__select-col select-big">
                                        <div class="contacts__label">
                                            <?php _e("Nationality", 'finanzia'); ?>
                                        </div>
                                        <select class="select" name="step_one[second_applicant][main_nationality]">
                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                            <?php get_nationality_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-big">
                                        <div class="contacts__label">
                                            <?php _e("Residency type in Czech Republic", 'finanzia'); ?>
                                        </div>
                                        <select class="select"
                                                name="step_one[second_applicant][residency_type]">
                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                            <option value="No residency"><?php _e("No residency", 'finanzia'); ?></option>
                                            <option value="Temporary"><?php _e("Temporary", 'finanzia'); ?></option>
                                            <option value="Long-term"><?php _e("Long-term", 'finanzia'); ?></option>
                                            <option value="Working card"><?php _e("Working card", 'finanzia'); ?></option>
                                            <option value="Blue card"><?php _e("Blue card", 'finanzia'); ?></option>
                                            <option value="Permanent"><?php _e("Permanent", 'finanzia'); ?></option>
                                            <option value="Diplomatic"><?php _e("Diplomatic", 'finanzia'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__check-box">
                                <label class="big-form__check-multi">
                                    <input type="hidden"
                                           name="step_one[second_applicant][passports][multipass]" value="no">
                                    <input class="check-multi" type="checkbox"
                                           name="step_one[second_applicant][passports][multipass]" value="yes">
                                    <span class="big-form__check-text"><?php _e("I have multiple passports", 'finanzia'); ?></span>
                                </label>
                            </div>
                            <div class="big-form__add">
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("Nationality", 'finanzia'); ?>
                                    </div>
                                    <select class="select"
                                            name="step_one[second_applicant][passports][nationality][]">
                                        <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                        <?php get_nationality_options(); ?>
                                    </select>
                                </div>
                                <div class="big-form__check-box">
                                    <label class="big-form__check-multi">
                                        <input class="add-nationality" type="checkbox">
                                        <span class="big-form__check-text"><?php _e("I have one more passport", 'finanzia'); ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Birth registration number (Rodné číslo) — optional", 'finanzia'); ?>
                                </div>
                                <input class="optional" placeholder="XXXXXX/XXXX" type="text"
                                       name="step_one[second_applicant][passport_registration_number]"
                                       value="">
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Bank you currently use in Czech Republic", 'finanzia'); ?>
                                </div>
                                <select multiple class="select" name="step_one[second_applicant][current_bank][]">
                                    <option disabled value=""><?php _e("Select", 'finanzia'); ?></option>
                                    <option value="Česká spořitelna">Česká spořitelna</option>
                                    <option value="Komerční banka">Komerční banka</option>
                                    <option value="Československá obchodní banka(ČSOB)">Československá obchodní
                                        banka(ČSOB)
                                    </option>
                                    <option value="Raiffeisenbank">Raiffeisenbank</option>
                                    <option value="UniCredit Bank">UniCredit Bank</option>
                                    <option value="MONETA Money Bank">MONETA Money Bank</option>
                                    <option value="Fio banka">Fio banka</option>
                                    <option value="Air Bank">Air Bank</option>
                                    <option value="Banka CREDITAS">Banka CREDITAS</option>
                                    <option value="mBank">mBank</option>
                                    <option value="Oberbank">Oberbank</option>
                                    <option value="Other"><?php _e("Other (please describe below)", 'finanzia'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="big-form__button">
                            <button class="big-form__btn next-step"
                                    type="button"><?php _e("next step", 'finanzia'); ?></button>
                        </div>
                    </div>
                    <div class="big-form__step-2">
                        <div class="form-line__title">
                            <?php _e("Income Type", 'finanzia'); ?>
                        </div>
                        <div class="form-line__text">
                            <?php _e("Please fill out the form to confirm your application", 'finanzia'); ?>
                        </div>
                        <div class="form-opener">
                            <?php _e("Main Applicant", 'finanzia'); ?>
                        </div>
                        <div class="big-form__section">
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("When did you start earning income in Czech Republic?", 'finanzia'); ?>
                                </div>
                                <div class="big-form__three-select">
                                    <div class="big-form__select-col select-big">
                                        <select required class="select"
                                                name="step_one[main_applicant][first_income][year]">
                                            <?php get_years_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-big">
                                        <select required class="select"
                                                name="step_one[main_applicant][first_income][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__section-income-1">
                                <div class="big-form__holder">
                                    <div class="big-form__variant">
                                        <div class="big-form__label">
                                            <?php _e("Income type", 'finanzia'); ?>
                                        </div>
                                        <ul class="big-form__tabs-list">
                                            <li>
                                                <a class="active" data-current_tab="full_time_employee"
                                                   href="#big-form__tab-1"><?php _e("Full-time employee", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="self_employed"
                                                   href="#big-form__tab-2"><?php _e("Self-employed", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="rental_income"
                                                   href="#big-form__tab-3"><?php _e("Rental income", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="business"
                                                   href="#big-form__tab-4"><?php _e("Business", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="income_from_abroad"
                                                   href="#big-form__tab-5"><?php _e("Income from abroad", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="other"
                                                   href="#big-form__tab-6"><?php _e("Other", 'finanzia'); ?></a>
                                            </li>
                                        </ul>
                                        <input type="hidden" class="current_tab"
                                               name="step_one[main_applicant][income_type][][current_tab][full_time_employee]">
                                    </div>
                                    <div id="big-form__tab-1" class="tab">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                            </div>
                                            <input required placeholder="50 000" type="number"
                                                   name="step_one[main_applicant][income_type][][full_time_employee][monthly_net_income]"
                                                   class="js_monthly_net_income"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _ex("Income start date", "Full-time employee", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select required class="select"
                                                            name="step_one[main_applicant][income_type][][full_time_employee][income_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select required class="select"
                                                            name="step_one[main_applicant][income_type][][full_time_employee][income_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Work experience duration", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select required class="select"
                                                            name="step_one[main_applicant][income_type][][full_time_employee][work_experience_duration][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select required class="select"
                                                            name="step_one[main_applicant][income_type][][full_time_employee][work_experience_duration][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[main_applicant][income_type][][full_time_employee][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <div id="big-form__tab-2" class="tab">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="50 000" type="number"
                                                   name="step_one[main_applicant][income_type][][self_employed][monthly_net_income]"
                                                   class="js_monthly_net_income"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Business identification number (IČO)", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="<?php _e("Enter 8 to 10 digit code", 'finanzia'); ?>" type="text"
                                                   name="step_one[main_applicant][income_type][][self_employed][business_identification_number]"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Trade license start date", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][self_employed][trade_license_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][self_employed][trade_license_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Annual gross income for the previous year", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="1 000 000" type="text"
                                                   name="step_one[main_applicant][income_type][][self_employed][annual_gross_income_previous_year]"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Work experience duration", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][self_employed][work_experience_duration][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][self_employed][work_experience_duration][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Field of specialization", 'finanzia'); ?>
                                            </div>
                                            <input type="text"
                                                   name="step_one[main_applicant][income_type][][self_employed][specialization]"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[main_applicant][income_type][][self_employed][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <div id="big-form__tab-3" class="tab">
                                        <div class="big-form__fields">
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="20 000" type="number"
                                                       name="step_one[main_applicant][income_type][][rental_income][monthly_net_income]"
                                                       class="js_monthly_net_income"
                                                       value="">
                                            </div>
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("Gross rent (including utilities) — CZK", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="15 000" type="number"
                                                       name="step_one[main_applicant][income_type][][rental_income][gross_rent]"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="big-form__fields">
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("Net rent (excluding utilities) — CZK", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="13 000" type="number"
                                                       name="step_one[main_applicant][income_type][][rental_income][net_rent]"
                                                       value="">
                                            </div>

                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("Utility and service charges — CZK", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="2 000" type="number"
                                                       name="step_one[main_applicant][income_type][][rental_income][utility_service_charges]"
                                                       value="">
                                            </div>
                                        </div>
<!--                                        <div class="big-form__row">-->
<!--                                            <div class="contacts__label">-->
<!--                                                --><?php //_e("When did you start earning income in Czech Republic?", 'finanzia'); ?>
<!--                                            </div>-->
<!--                                            <div class="big-form__three-select">-->
<!--                                                <div class="big-form__select-col select-big">-->
<!--                                                    <select class="select"-->
<!--                                                            name="step_one[main_applicant][income_type][][rental_income][income_start_date][year]">-->
<!--                                                        --><?php //get_years_options(); ?>
<!--                                                    </select>-->
<!--                                                </div>-->
<!--                                                <div class="big-form__select-col select-big">-->
<!--                                                    <select class="select"-->
<!--                                                            name="step_one[main_applicant][income_type][][rental_income][income_start_date][month]">-->
<!--                                                        --><?php //get_months_options(); ?>
<!--                                                    </select>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <div class="big-form__variant">
                                            <div class="big-form__label">
                                                <?php _e("Is this income already included in your tax return?", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__variant-list">
                                                <label class="big-form__var-1">
                                                    <input type="radio"
                                                           name="step_one[main_applicant][income_type][][rental_income][income_included_in_tax]"
                                                           value="Yes">
                                                    <span class="big-form__var-name"><?php _e("Yes", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-2">
                                                    <input type="radio"
                                                           name="step_one[main_applicant][income_type][][rental_income][income_included_in_tax]"
                                                           value="No">
                                                    <span class="big-form__var-name"><?php _e("No", 'finanzia'); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="big-form__variant">
                                            <div class="big-form__label">
                                                <?php _e("Payment method", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__variant-list">
                                                <label class="big-form__var-1">
                                                    <input type="radio"
                                                           name="step_one[main_applicant][income_type][][rental_income][payment_method]"
                                                           value="<?php _e("Bank transfer", 'finanzia'); ?>">
                                                    <span class="big-form__var-name"><?php _e("Bank transfer", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-2">
                                                    <input type="radio"
                                                           name="step_one[main_applicant][income_type][][rental_income][payment_method]"
                                                           value="<?php _e("Cash", 'finanzia'); ?>">
                                                    <span class="big-form__var-name"><?php _e("Cash", 'finanzia'); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[main_applicant][income_type][][rental_income][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <div id="big-form__tab-4" class="tab">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="50 000" type="number"
                                                   name="step_one[main_applicant][income_type][][business][monthly_net_income]"
                                                   class="js_monthly_net_income"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Please provide a brief description of what your company does", 'finanzia'); ?>
                                            </div>
                                            <textarea
                                                      name="step_one[main_applicant][income_type][][business][what_company_does]"
                                                      placeholder="<?php _e("Summarize your company's services and operations", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Method of extracting funds from the company", 'finanzia'); ?>
                                            </div>
                                            <select class="select"
                                                    name="step_one[main_applicant][income_type][][business][method_extracting_funds_from_company]">
                                                <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                <option value="Full-time employment"><?php _e("Full-time employment", 'finanzia'); ?></option>
                                                <option value="Contractual work (DPP, DPČ)"><?php _e("Contractual work (DPP, DPČ)", 'finanzia'); ?></option>
                                                <option value="Profit sharing "><?php _e("Profit sharing ", 'finanzia'); ?></option>
                                                <option value="Dividends"><?php _e("Dividends", 'finanzia'); ?></option>
                                                <option value="Reinvestment in the company"><?php _e("Reinvestment in the company", 'finanzia'); ?></option>
                                                <option value="Other"><?php _e("Other (please describe below)", 'finanzia'); ?></option>
                                            </select>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Trade license start date", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][business][trade_license_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][business][trade_license_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("What percentage of the company do you own?", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="" type="text"
                                                   name="step_one[main_applicant][income_type][][business][percentage_of_company_own]"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _ex("Income start date", "Business", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][business][income_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][business][income_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[main_applicant][income_type][][business][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <div id="big-form__tab-5" class="tab">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="50 000" type="number"
                                                   name="step_one[main_applicant][income_type][][income_from_abroad][monthly_net_income]"
                                                   class="js_monthly_net_income"
                                                   value="">
                                        </div>
                                        <div class="big-form__variant">
                                            <div class="big-form__label">
                                                <?php _e("Type of income from abroad", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__variant-list">
                                                <label class="big-form__var-1">
                                                    <input checked="checked" type="radio"
                                                           name="step_one[main_applicant][income_type][][income_from_abroad][type_of_income_from_abroad]"
                                                           value="Employee">
                                                    <span class="big-form__var-name"><?php _e("Employee", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-2">
                                                    <input type="radio"
                                                           name="step_one[main_applicant][income_type][][income_from_abroad][type_of_income_from_abroad]"
                                                           value="Self-employed">
                                                    <span class="big-form__var-name"><?php _e("Self-employed", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-1">
                                                    <input checked="checked" type="radio"
                                                           name="step_one[main_applicant][income_type][][income_from_abroad][type_of_income_from_abroad]"
                                                           value="Business">
                                                    <span class="big-form__var-name"><?php _e("Business", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-1">
                                                    <input checked="checked" type="radio"
                                                           name="step_one[main_applicant][income_type][][income_from_abroad][type_of_income_from_abroad]"
                                                           value="Rental">
                                                    <span class="big-form__var-name"><?php _e("Rental", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-1">
                                                    <input checked="checked" type="radio"
                                                           name="step_one[main_applicant][income_type][][income_from_abroad][type_of_income_from_abroad]"
                                                           value="Other">
                                                    <span class="big-form__var-name"><?php _e("Other (please describe below)", 'finanzia'); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _ex("Income start date", "Income from abroad", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][income_from_abroad][income_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][income_from_abroad][income_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Experience duration (optional)", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select optional"
                                                            name="step_one[main_applicant][income_type][][income_from_abroad][experience_duration][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select optional"
                                                            name="step_one[main_applicant][income_type][][income_from_abroad][experience_duration][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[main_applicant][income_type][][income_from_abroad][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <div id="big-form__tab-6" class="tab">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="50 000" type="number"
                                                   name="step_one[main_applicant][income_type][][other][monthly_net_income]"
                                                   class="js_monthly_net_income"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _ex("Income start date", "Other", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][other][income_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[main_applicant][income_type][][other][income_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Experience duration (optional)", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select optional"
                                                            name="step_one[main_applicant][income_type][][other][work_experience_duration][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select optional"
                                                            name="step_one[main_applicant][income_type][][other][work_experience_duration][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[main_applicant][income_type][][other][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="step_one[main_applicant][income_type][][breakpoint]">
                                </div>
                                <div class="plus" data-text="<?php _e("Income type", 'finanzia'); ?>">
                                    <?php _e("I have another source of income", 'finanzia'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="second-applicant">
                            <div class="form-opener">
                                <?php _e("Second Applicant", 'finanzia'); ?>
                            </div>
                            <div class="big-form__section-income-2">
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("When did you start earning income in Czech Republic?", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__three-select">
                                        <div class="big-form__select-col select-big">
                                            <select class="select"
                                                    name="step_one[second_applicant][first_income][year]">
                                                <?php get_years_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__select-col select-big">
                                            <select class="select"
                                                    name="step_one[second_applicant][first_income][month]">
                                                <?php get_months_options(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="big-form__holder">
                                    <div class="big-form__variant">
                                        <div class="big-form__label">
                                            <?php _e("Income type", 'finanzia'); ?>
                                        </div>
                                        <ul class="big-form__tabs-list">
                                            <li>
                                                <a class="active" data-current_tab="full_time_employee"
                                                   href="#big-form__second-tab-1"><?php _e("Full-time employee", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="self_employed"
                                                   href="#big-form__second-tab-2"><?php _e("Self-employed", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="rental_income"
                                                   href="#big-form__second-tab-3"><?php _e("Rental income", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="business"
                                                   href="#big-form__second-tab-4"><?php _e("Business", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="income_from_abroad"
                                                   href="#big-form__second-tab-5"><?php _e("Income from abroad", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="other"
                                                   href="#big-form__second-tab-6"><?php _e("Other", 'finanzia'); ?></a>
                                            </li>
                                        </ul>
                                        <input type="hidden" class="current_tab"
                                               name="step_one[second_applicant][income_type][][current_tab][full_time_employee]">
                                    </div>
                                    <div id="big-form__second-tab-1" class="tab">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="50 000" type="number"
                                                   name="step_one[second_applicant][income_type][][full_time_employee][monthly_net_income]"
                                                   class="js_monthly_net_income"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Income start date", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][full_time_employee][income_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][full_time_employee][income_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Work experience duration", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][full_time_employee][work_experience_duration][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][full_time_employee][work_experience_duration][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[second_applicant][income_type][][full_time_employee][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <div id="big-form__second-tab-2" class="tab">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="50 000" type="number"
                                                   name="step_one[second_applicant][income_type][][self_employed][monthly_net_income]"
                                                   class="js_monthly_net_income"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Business identification number (IČO)", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="<?php _e("Enter 8 to 10 digit code", 'finanzia'); ?>" type="text"
                                                   name="step_one[second_applicant][income_type][][self_employed][business_identification_number]"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Trade license start date", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][self_employed][trade_license_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][self_employed][trade_license_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Annual gross income for the previous year", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="2 000 000" type="text"
                                                   name="step_one[second_applicant][income_type][][self_employed][annual_gross_income_previous_year]"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Work experience duration", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][self_employed][work_experience_duration][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][self_employed][work_experience_duration][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Field of specialization", 'finanzia'); ?>
                                            </div>
                                            <input type="text"
                                                   name="step_one[second_applicant][income_type][][self_employed][specialization]"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[second_applicant][income_type][][self_employed][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <div id="big-form__second-tab-3" class="tab">
                                        <div class="big-form__fields">
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="20 000" type="number"
                                                       name="step_one[second_applicant][income_type][][rental_income][monthly_net_income]"
                                                       class="js_monthly_net_income"
                                                       value="">
                                            </div>
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("Gross rent (including utilities) — CZK", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="15 000" type="number"
                                                       name="step_one[main_applicant][income_type][][rental_income][gross_rent]"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="big-form__fields">
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("Net rent (excluding utilities) — CZK", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="13 000" type="number"
                                                       name="step_one[second_applicant][income_type][][rental_income][net_rent]"
                                                       value="">
                                            </div>
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("Utility and service charges — CZK", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="2 000" type="number"
                                                       name="step_one[second_applicant][income_type][][rental_income][utility_service_charges]"
                                                       value="">
                                            </div>
                                        </div>
<!--                                        <div class="big-form__row">-->
<!--                                            <div class="contacts__label">-->
<!--                                                --><?php //_e("When did you start earning income in Czech Republic?", 'finanzia'); ?>
<!--                                            </div>-->
<!--                                            <div class="big-form__three-select">-->
<!--                                                <div class="big-form__select-col select-big">-->
<!--                                                    <select class="select"-->
<!--                                                            name="step_one[second_applicant][income_type][][rental_income][income_start_date][year]">-->
<!--                                                        --><?php //get_years_options(); ?>
<!--                                                    </select>-->
<!--                                                </div>-->
<!--                                                <div class="big-form__select-col select-big">-->
<!--                                                    <select class="select"-->
<!--                                                            name="step_one[second_applicant][income_type][][rental_income][income_start_date][month]">-->
<!--                                                        --><?php //get_months_options(); ?>
<!--                                                    </select>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <div class="big-form__variant">
                                            <div class="big-form__label">
                                                <?php _e("Is this income already included in your tax return?", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__variant-list">
                                                <label class="big-form__var-1">
                                                    <input type="radio"
                                                           name="step_one[second_applicant][income_type][][rental_income][income_included_in_tax]"
                                                           value="Yes">
                                                    <span class="big-form__var-name"><?php _e("Yes", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-2">
                                                    <input type="radio"
                                                           name="step_one[second_applicant][income_type][][rental_income][income_included_in_tax]"
                                                           value="No">
                                                    <span class="big-form__var-name"><?php _e("No", 'finanzia'); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="big-form__variant">
                                            <div class="big-form__label">
                                                <?php _e("Payment method", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__variant-list">
                                                <label class="big-form__var-1">
                                                    <input type="radio"
                                                           name="step_one[second_applicant][income_type][][rental_income][payment_method]"
                                                           value="<?php _e("Bank transfer", 'finanzia'); ?>">
                                                    <span class="big-form__var-name"><?php _e("Bank transfer", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-2">
                                                    <input type="radio"
                                                           name="step_one[second_applicant][income_type][][rental_income][payment_method]"
                                                           value="<?php _e("Cash", 'finanzia'); ?>">
                                                    <span class="big-form__var-name"><?php _e("Cash", 'finanzia'); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[second_applicant][income_type][][rental_income][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <div id="big-form__second-tab-4" class="tab">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="50 000" type="number"
                                                   name="step_one[second_applicant][income_type][][business][monthly_net_income]"
                                                   class="js_monthly_net_income"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Please provide a brief description of what your company does", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[second_applicant][income_type][][business][what_company_does]"
                                                      placeholder="<?php _e("Summarize your company's services and operations", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Method of extracting funds from the company", 'finanzia'); ?>
                                            </div>
                                            <select class="select"
                                                    name="step_one[second_applicant][income_type][][business][method_extracting_funds_from_company]">
                                                <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                <option value="Full-time employment"><?php _e("Full-time employment", 'finanzia'); ?></option>
                                                <option value="Contractual work (DPP, DPČ)"><?php _e("Contractual work (DPP, DPČ)", 'finanzia'); ?></option>
                                                <option value="Profit sharing "><?php _e("Profit sharing ", 'finanzia'); ?></option>
                                                <option value="Dividends"><?php _e("Dividends", 'finanzia'); ?></option>
                                                <option value="Reinvestment in the company"><?php _e("Reinvestment in the company", 'finanzia'); ?></option>
                                                <option value="Other"><?php _e("Other (please describe below)", 'finanzia'); ?></option>
                                            </select>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Trade license start date", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][business][trade_license_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][business][trade_license_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("What percentage of the company do you own?", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="" type="text"
                                                   name="step_one[second_applicant][income_type][][business][percentage_of_company_own]"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Income start date", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][business][income_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][business][income_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[second_applicant][income_type][][business][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <div id="big-form__second-tab-5" class="tab">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="50 000" type="number"
                                                   name="step_one[second_applicant][income_type][][income_from_abroad][monthly_net_income]"
                                                   class="js_monthly_net_income"
                                                   value="">
                                        </div>
                                        <div class="big-form__variant">
                                            <div class="big-form__label">
                                                <?php _e("Type of income from abroad", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__variant-list">
                                                <label class="big-form__var-1">
                                                    <input checked="checked" type="radio"
                                                           name="step_one[second_applicant][income_type][][income_from_abroad][type_of_income_from_abroad]"
                                                           value="Employee">
                                                    <span class="big-form__var-name"><?php _e("Employee", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-2">
                                                    <input type="radio"
                                                           name="step_one[second_applicant][income_type][][income_from_abroad][type_of_income_from_abroad]"
                                                           value="Self-employed">
                                                    <span class="big-form__var-name"><?php _e("Self-employed", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-1">
                                                    <input checked="checked" type="radio"
                                                           name="step_one[second_applicant][income_type][][income_from_abroad][type_of_income_from_abroad]"
                                                           value="Business">
                                                    <span class="big-form__var-name"><?php _e("Business", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-1">
                                                    <input checked="checked" type="radio"
                                                           name="step_one[second_applicant][income_type][][income_from_abroad][type_of_income_from_abroad]"
                                                           value="Rental">
                                                    <span class="big-form__var-name"><?php _e("Rental", 'finanzia'); ?></span>
                                                </label>
                                                <label class="big-form__var-1">
                                                    <input checked="checked" type="radio"
                                                           name="step_one[second_applicant][income_type][][income_from_abroad][type_of_income_from_abroad]"
                                                           value="Other">
                                                    <span class="big-form__var-name"><?php _e("Other (please describe below)", 'finanzia'); ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Income start date", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][income_from_abroad][income_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][income_from_abroad][income_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Experience duration (optional)", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select optional"
                                                            name="step_one[second_applicant][income_type][][income_from_abroad][experience_duration][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select optional"
                                                            name="step_one[second_applicant][income_type][][income_from_abroad][experience_duration][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[second_applicant][income_type][][income_from_abroad][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <div id="big-form__second-tab-6" class="tab">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Monthly net income — CZK", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="50 000" type="number"
                                                   name="step_one[second_applicant][income_type][][other][monthly_net_income]"
                                                   class="js_monthly_net_income"
                                                   value="">
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Income start date", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][other][income_start_date][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select"
                                                            name="step_one[second_applicant][income_type][][other][income_start_date][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Experience duration (optional)", 'finanzia'); ?>
                                            </div>
                                            <div class="big-form__three-select">
                                                <div class="big-form__select-col select-big">
                                                    <select class="select optional"
                                                            name="step_one[second_applicant][income_type][][other][work_experience_duration][year]">
                                                        <?php get_years_options(); ?>
                                                    </select>
                                                </div>
                                                <div class="big-form__select-col select-big">
                                                    <select class="select optional"
                                                            name="step_one[second_applicant][income_type][][other][work_experience_duration][month]">
                                                        <?php get_months_options(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Additional information (optional)", 'finanzia'); ?>
                                            </div>
                                            <textarea class="optional"
                                                      name="step_one[second_applicant][income_type][][other][additional_information]"
                                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                                      cols="80"></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="step_one[second_applicant][income_type][][breakpoint]">
                                </div>
                                <div class="plus" data-text="<?php _e("Income type", 'finanzia'); ?>">
                                    <?php _e("I have another source of income", 'finanzia'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="big-form__button">
                            <button class="prev-step" type="button"><?php _e("Back", 'finanzia'); ?></button>
                            <button class="big-form__btn next-step"
                                    type="button"><?php _e("next step", 'finanzia'); ?></button>
                        </div>
                    </div>
                    <div class="big-form__step-3">
                        <div class="form-line__title">
                            <?php _e("Existing Liabilities", 'finanzia'); ?>
                        </div>
                        <div class="form-line__text">
                            <?php _e("Please fill out the form to confirm your application", 'finanzia'); ?>
                        </div>
                        <div class="form-opener">
                            <?php _e("Main Applicant", 'finanzia'); ?>
                        </div>
                        <div class="big-form__section-1">
                            <div class="big-form__holder">
                                <div class="big-form__variant">
                                    <div class="big-form__label">
                                        <?php _e("Liability type", 'finanzia'); ?>
                                    </div>
                                    <ul class="big-form__tabs-list">
                                        <li>
                                            <a class="active" data-current_tab="liability_type_loan"
                                               href="#big-form__type-1"><?php _e("Loan", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="liability_type_mortgage"
                                               href="#big-form__type-2"><?php _e("Mortgage", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="liability_type_credit_card"
                                               href="#big-form__type-3"><?php _e("Credit card", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="liability_type_overdraft"
                                               href="#big-form__type-4"><?php _e("Overdraft", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="liability_type_no_liabilities"
                                               href="#big-form__type-5"><?php _e("No liabilities", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="liability_type_other"
                                               href="#big-form__type-6"><?php _e("Other (please describe below)", 'finanzia'); ?></a>
                                        </li>
                                    </ul>
                                    <input type="hidden" class="current_tab"
                                           name="step_one[main_applicant][existing_liabilities][][current_tab][liability_type_loan]">
                                </div>
                                <div id="big-form__type-1" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                </div>
                                                <input required
                                                       placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_one[main_applicant][existing_liabilities][][liability_type_loan][company_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                </div>
                                                <input required placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>" type="text"
                                                       name="step_one[main_applicant][existing_liabilities][][liability_type_loan][products_interest_rate]"
                                                       value="">
                                            </div>
                                            <div class="big-form__fields">
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                    </div>
                                                    <input required placeholder="10 000" type="number"
                                                           name="step_one[main_applicant][existing_liabilities][][liability_type_loan][monthly_payment_amount]"
                                                           value="">
                                                </div>
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _ex("Remaining balance — CZK", "Liability type loan", 'finanzia'); ?>
                                                    </div>
                                                    <input required placeholder="500 000" type="number"
                                                           name="step_one[main_applicant][existing_liabilities][][liability_type_loan][remaining_balance]"
                                                           value="">
                                                </div>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_one[main_applicant][existing_liabilities][][liability_type_loan][additional_information]"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="big-form__type-2" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_one[main_applicant][existing_liabilities][][liability_type_mortgage][company_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>" type="text"
                                                       name="step_one[main_applicant][existing_liabilities][][liability_type_mortgage][products_interest_rate]"
                                                       value="">
                                            </div>
                                            <div class="big-form__fields">
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="25 000" type="number"
                                                           name="step_one[main_applicant][existing_liabilities][][liability_type_mortgage][monthly_payment_amount]"
                                                           value="">
                                                </div>
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _ex("Remaining balance — CZK", "Liability type mortgage", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="2 000 000" type="number"
                                                           name="step_one[main_applicant][existing_liabilities][][liability_type_mortgage][remaining_balance]"
                                                           value="">
                                                </div>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_one[main_applicant][existing_liabilities][][liability_type_mortgage][additional_information]"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="big-form__type-3" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_one[main_applicant][existing_liabilities][][liability_type_credit_card][company_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Maximum credit limit you can get on your credit card", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="100 000" type="text"
                                                       name="step_one[main_applicant][existing_liabilities][][liability_type_credit_card][max_credit_limit]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_one[main_applicant][existing_liabilities][][liability_type_credit_card][additional_information]"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="big-form__type-4" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_one[main_applicant][existing_liabilities][][liability_type_overdraft][company_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Maximum limit amount", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="100 000" type="text"
                                                       name="step_one[main_applicant][existing_liabilities][][liability_type_overdraft][max_credit_limit]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_one[main_applicant][existing_liabilities][][liability_type_overdraft][additional_information]"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="big-form__type-5" class="tab">
                                    <input type="hidden"
                                           name="step_one[main_applicant][existing_liabilities][][liability_type_no_liabilities][additional_information]"
                                           value="No liabilities">
                                </div>
                                <div id="big-form__type-6" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_one[main_applicant][existing_liabilities][][liability_type_other][company_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>" type="text"
                                                       name="step_one[main_applicant][existing_liabilities][][liability_type_other][products_interest_rate]"
                                                       value="">
                                            </div>
                                            <div class="big-form__fields">
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="10 000" type="number"
                                                           name="step_one[main_applicant][existing_liabilities][][liability_type_other][monthly_payment_amount]"
                                                           value="">
                                                </div>
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _ex("Remaining balance — CZK", "Liability type other", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="500 000" type="number"
                                                           name="step_one[main_applicant][existing_liabilities][][liability_type_other][remaining_balance]"
                                                           value="">
                                                </div>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_one[main_applicant][existing_liabilities][][liability_type_other][additional_information]"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden"
                                       name="step_one[main_applicant][existing_liabilities][][breakpoint]">
                            </div>
                            <div class="plus" data-text="<?php _e("Liability type", 'finanzia'); ?>">
                                <?php _e("I have one more liability type", 'finanzia'); ?>
                            </div>
                        </div>
                        <div class="second-applicant">
                            <div class="form-opener">
                                <?php _e("Second Applicant", 'finanzia'); ?>
                            </div>
                            <div class="big-form__section-2">
                                <div class="big-form__holder">
                                    <div class="big-form__variant">
                                        <div class="big-form__label">
                                            <?php _e("Liability type", 'finanzia'); ?>
                                        </div>
                                        <ul class="big-form__tabs-list">
                                            <li>
                                                <a class="active" data-current_tab="liability_type_loan"
                                                   href="#big-form__second-type-1"><?php _e("Loan", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="liability_type_mortgage"
                                                   href="#big-form__second-type-2"><?php _e("Mortgage", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="liability_type_credit_card"
                                                   href="#big-form__second-type-3"><?php _e("Credit card", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="liability_type_overdraft"
                                                   href="#big-form__second-type-4"><?php _e("Overdraft", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="liability_type_no_liabilities"
                                                   href="#big-form__second-type-5"><?php _e("No liabilities", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="liability_type_other"
                                                   href="#big-form__second-type-6"><?php _e("Other (please describe below)", 'finanzia'); ?></a>
                                            </li>
                                        </ul>
                                        <input type="hidden" class="current_tab"
                                               name="step_one[second_applicant][existing_liabilities][][current_tab][liability_type_loan]">
                                    </div>
                                    <div id="big-form__second-type-1" class="tab">
                                        <div class="field-wrapper">
                                            <div class="field-section">
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_one[second_applicant][existing_liabilities][][liability_type_loan][company_name]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>" type="text"
                                                           name="step_one[second_applicant][existing_liabilities][][liability_type_loan][products_interest_rate]"
                                                           value="">
                                                </div>
                                                <div class="big-form__fields">
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="10 000" type="number"
                                                               name="step_one[second_applicant][existing_liabilities][][liability_type_loan][monthly_payment_amount]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Remaining balance — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="500 000" type="number"
                                                               name="step_one[second_applicant][existing_liabilities][][liability_type_loan][remaining_balance]"
                                                               value="">
                                                    </div>
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                    </div>
                                                    <textarea class="optional"
                                                              name="step_one[second_applicant][existing_liabilities][][liability_type_loan][additional_information]"
                                                              placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                              rows="8"
                                                              cols="80"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="big-form__second-type-2" class="tab">
                                        <div class="field-wrapper">
                                            <div class="field-section">
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_one[second_applicant][existing_liabilities][][liability_type_mortgage][company_name]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>" type="text"
                                                           name="step_one[second_applicant][existing_liabilities][][liability_type_mortgage][products_interest_rate]"
                                                           value="">
                                                </div>
                                                <div class="big-form__fields">
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="25 000" type="number"
                                                               name="step_one[second_applicant][existing_liabilities][][liability_type_mortgage][monthly_payment_amount]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Remaining balance — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="2 000 000" type="number"
                                                               name="step_one[second_applicant][existing_liabilities][][liability_type_mortgage][remaining_balance]"
                                                               value="">
                                                    </div>
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                    </div>
                                                    <textarea class="optional"
                                                              name="step_one[second_applicant][existing_liabilities][][liability_type_mortgage][additional_information]"
                                                              placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                              rows="8"
                                                              cols="80"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="big-form__second-type-3" class="tab">
                                        <div class="field-wrapper">
                                            <div class="field-section">
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_one[second_applicant][existing_liabilities][][liability_type_credit_card][company_name]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Maximum credit limit you can get on your credit card", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="100 000" type="text"
                                                           name="step_one[second_applicant][existing_liabilities][][liability_type_credit_card][max_credit_limit]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                    </div>
                                                    <textarea class="optional"
                                                              name="step_one[second_applicant][existing_liabilities][][liability_type_credit_card][additional_information]"
                                                              placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                              rows="8"
                                                              cols="80"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="big-form__second-type-4" class="tab">
                                        <div class="field-wrapper">
                                            <div class="field-section">
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_one[second_applicant][existing_liabilities][][liability_type_overdraft][company_name]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Maximum limit amount", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="100 000" type="text"
                                                           name="step_one[second_applicant][existing_liabilities][][liability_type_overdraft][max_credit_limit]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                    </div>
                                                    <textarea class="optional"
                                                              name="step_one[second_applicant][existing_liabilities][][liability_type_overdraft][additional_information]"
                                                              placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                              rows="8"
                                                              cols="80"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="big-form__second-type-5" class="tab">
                                        <input type="hidden"
                                               name="step_one[second_applicant][existing_liabilities][][liability_type_no_liabilities][additional_information]"
                                               value="No liabilities">
                                    </div>
                                    <div id="big-form__second-type-6" class="tab">
                                        <div class="field-wrapper">
                                            <div class="field-section">
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_one[second_applicant][existing_liabilities][][liability_type_other][company_name]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>" type="text"
                                                           name="step_one[second_applicant][existing_liabilities][][liability_type_other][products_interest_rate]"
                                                           value="">
                                                </div>
                                                <div class="big-form__fields">
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="10 000" type="number"
                                                               name="step_one[second_applicant][existing_liabilities][][liability_type_other][monthly_payment_amount]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Remaining balance — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="500 000" type="number"
                                                               name="step_one[second_applicant][existing_liabilities][][liability_type_other][remaining_balance]"
                                                               value="">
                                                    </div>
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                    </div>
                                                    <textarea class="optional"
                                                              name="step_one[second_applicant][existing_liabilities][][liability_type_other][additional_information]"
                                                              placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                              rows="8"
                                                              cols="80"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden"
                                           name="step_one[second_applicant][existing_liabilities][][breakpoint]">
                                </div>
                                <div class="plus" data-text="<?php _e("Liability type", 'finanzia'); ?>">
                                    <?php _e("I have one more liability type", 'finanzia'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="big-form__button">
                            <button class="prev-step" type="button"><?php _e("Back", 'finanzia'); ?></button>
                            <button class="big-form__btn next-step"
                                    type="button"><?php _e("next step", 'finanzia'); ?></button>
                        </div>
                    </div>
                    <div class="big-form__step-4">
                        <div class="form-line__title">
                            <?php _e("Type of Property", 'finanzia'); ?>
                        </div>
                        <div class="form-line__text">
                            <?php _e("Please fill out the form to confirm your application", 'finanzia'); ?>
                        </div>
                        <div class="big-form__row property">
                            <div class="contacts__label">
                                <?php _e("Type of the property", 'finanzia'); ?>
                            </div>
                            <select required class="select" name="step_one[type_of_property]">
                                <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                <option selected="selected" class="custom-option-1"
                                        value="Flat"><?php _e("Flat", 'finanzia'); ?></option>
                                <option value="House"><?php _e("House", 'finanzia'); ?></option>
                                <option value="Cottage"><?php _e("Cottage", 'finanzia'); ?></option>
                                <option value="Land"><?php _e("Land", 'finanzia'); ?></option>
                                <option value="Other — please specify"><?php _e("Other — please specify", 'finanzia'); ?></option>
                            </select>
                        </div>
                        <div class="big-form__variant property-variant">
                            <div class="big-form__variant-list">
                                <label class="big-form__var-1">
                                    <input checked="checked" type="radio" name="step_one[property_variant]"
                                           value="Personal ownership">
                                    <span class="big-form__var-name"><?php _e("Personal ownership", 'finanzia'); ?></span>
                                </label>
                                <label class="big-form__var-2">
                                    <input type="radio" name="step_one[property_variant]" value="Cooperative ownership">
                                    <span class="big-form__var-name"><?php _e("Cooperative ownership", 'finanzia'); ?></span>
                                </label>
                            </div>
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Additional Information (optional)", 'finanzia'); ?>
                            </div>
                            <textarea class="optional" name="step_one[property_additional_information]"
                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>" rows="8"
                                      cols="80"></textarea>
                        </div>
<!--                        <div class="big-form__check-box">-->
<!--                            <label class="big-form__check-multi">-->
<!--                                <input type="checkbox" name="step_one[processing_personal_data]" value="yes" required>-->
<!--                                <span class="big-form__check-text">--><?php //_e("By proceeding, you consent to the processing of your personal data.", 'finanzia'); ?><!--</span>-->
<!--                            </label>-->
<!--                        </div>-->
                        <div class="big-form__button">
                            <button class="prev-step" type="button"><?php _e("Back", 'finanzia'); ?></button>
                            <button class="big-form__submit-btn" type="submit"><?php _e("apply", 'finanzia'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
get_footer('steps');
