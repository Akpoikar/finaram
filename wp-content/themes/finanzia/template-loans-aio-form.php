<?php
/**
 * Template name: Loans all-in-one form
 */
$fields        = get_fields();
$option_fields = get_fields('option');

if (is_user_logged_in()) {
    $calc = get_actual_user_calc();

    $category = get_the_terms($calc->ID, Calculator::taxonomy);

    $calc->currency = mb_strtoupper(get_post_meta($calc->ID, '_mini_calc_currency', true));
}


get_header('steps');
?>
    <div class="wrapper-form">
        <div class="header-form">
            <a class="back" href="<?= home_url(); ?>">
                <?php _e("Return to Homepage", 'finanzia'); ?>
            </a>
            <a class="header-form__logo" href="<?= home_url(); ?>">
                <svg width="145" height="29" viewBox="0 0 145 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M41.3 8.5V13.3H48.6V14.8H41.3V21H38.4V7H51V8.5H41.3ZM55.6945 21H52.7945V7H55.6945V21ZM72.8977 7V21H69.0977L61.5977 9.6H61.3977V21H58.4977V7H62.2977L69.7977 18.4H69.9977V7H72.8977ZM79.4047 16.1H85.6047L82.6047 8.7H82.4047L79.4047 16.1ZM84.8047 7L90.5047 21H87.6247L86.3047 17.6H78.7047L77.3847 21H74.5047L80.2047 7H84.8047ZM99.3109 7C100.311 7 101.204 7.11333 101.991 7.34C102.791 7.55333 103.464 7.87333 104.011 8.3C104.558 8.71333 104.978 9.22667 105.271 9.84C105.564 10.44 105.711 11.1267 105.711 11.9C105.711 12.9667 105.444 13.8667 104.911 14.6C104.378 15.3333 103.611 15.8933 102.611 16.28L105.911 21H102.611L99.6709 16.8H99.3109H95.0109V21H92.1109V7H99.3109ZM95.0109 8.5V15.3H99.3109C100.364 15.3 101.178 15.0267 101.751 14.48C102.324 13.92 102.611 13.06 102.611 11.9C102.611 10.74 102.324 9.88667 101.751 9.34C101.178 8.78 100.364 8.5 99.3109 8.5H95.0109ZM111.807 16.1H118.007L115.007 8.7H114.807L111.807 16.1ZM117.207 7L122.907 21H120.027L118.707 17.6H111.107L109.787 21H106.907L112.607 7H117.207ZM134.013 19H134.213L139.413 7H143.713V21H140.813V9.8H140.613L135.813 21H132.413L127.613 9.8H127.413V21H124.513V7H128.813L134.013 19Z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M14.0321 0.00931473C12.4818 0.121387 11.3188 0.337571 10.0647 0.746879C7.25088 1.66513 4.71221 3.4783 2.88626 5.87394C1.65216 7.49303 0.71589 9.54797 0.30451 11.5403C0.105433 12.5044 0.0367075 13.0911 0.00690761 14.0803C-0.0847844 17.1271 0.736873 19.9709 2.44159 22.5065C4.92199 26.1961 8.91302 28.5451 13.4099 28.9624C14.2338 29.0388 16.1169 28.9929 16.8693 28.878C19.1711 28.5264 21.1896 27.7592 23.025 26.5379C24.5458 25.5261 26.0407 24.0478 27.0437 22.564C27.4387 21.9796 27.9243 21.1718 27.8807 21.1718C27.8705 21.1718 27.726 21.3887 27.5595 21.6538C27.3931 21.9188 27.0786 22.3642 26.8606 22.6435C26.383 23.2555 25.2135 24.4291 24.6279 24.8839C22.5621 26.4887 20.1181 27.4637 17.5684 27.7003C16.75 27.7762 15.0797 27.7316 14.3007 27.613C11.6453 27.2087 9.13877 25.9996 7.15857 24.1679C4.77551 21.9634 3.30575 18.9863 2.98544 15.7148C2.91099 14.954 2.95683 13.2974 3.07281 12.5575C3.57395 9.36168 5.1611 6.53654 7.64234 4.42387C11.2706 1.33452 16.4302 0.421364 20.9168 2.07452C23.7177 3.10658 26.1243 5.04362 27.6302 7.47815C27.7597 7.68755 27.8742 7.85883 27.8847 7.85883C27.949 7.85883 27.1138 6.52384 26.7152 5.98953C25.8059 4.77044 24.6043 3.59433 23.3952 2.73969C21.3171 1.27074 19.003 0.390344 16.4346 0.0914987C15.8873 0.0278053 14.4542 -0.0212268 14.0321 0.00931473ZM15.4648 2.09819C15.3799 2.10811 15.1121 2.13678 14.8697 2.16184C13.7513 2.27752 12.253 2.68266 11.1888 3.15718C8.95314 4.15409 7.13353 5.67355 5.79408 7.6621C5.4878 8.11678 5.19085 8.60253 5.24856 8.55454C5.26319 8.54236 5.40461 8.34132 5.56278 8.10782C7.1062 5.82956 9.55041 4.15 12.3155 3.46773C13.2536 3.23623 13.744 3.18507 15.024 3.18507C16.3626 3.18507 16.8219 3.23758 17.9114 3.51515C21.7853 4.50205 24.9144 7.51487 26.0377 11.3393C26.3775 12.4961 26.5051 13.5126 26.4722 14.7981C26.4455 15.8355 26.378 16.338 26.1361 17.2997C25.9196 18.1605 25.727 18.6736 25.2949 19.5403C24.1572 21.8224 22.3724 23.5848 20.0714 24.6978C19.2335 25.1032 18.6908 25.3055 17.8982 25.5079C14.772 26.3062 11.3983 25.7519 8.73603 24.0027C7.99861 23.5181 7.55073 23.1528 6.88372 22.4916C6.27304 21.8864 5.9571 21.5042 5.50464 20.8238C5.17587 20.3293 5.12835 20.277 5.32663 20.628C5.82922 21.5175 6.53577 22.4151 7.41699 23.2836C8.47568 24.3272 9.32039 24.9346 10.6157 25.584C12.8987 26.7285 15.3767 27.1554 17.9334 26.8447C23.122 26.214 27.4047 22.46 28.6489 17.452C28.9492 16.2432 29.0736 14.6032 28.9564 13.3977C28.636 10.1032 27.0497 7.10482 24.5281 5.02743C24.2105 4.76583 23.9249 4.56144 23.8933 4.57331C23.8618 4.58515 23.8473 4.57675 23.8611 4.55465C23.9174 4.46477 22.8083 3.77645 21.9009 3.33817C20.6826 2.74974 19.4129 2.36993 18.0216 2.17785C17.5775 2.11655 15.7851 2.06069 15.4648 2.09819ZM22.8046 6.03156C22.8484 6.07942 22.8744 6.11858 22.8623 6.11858C22.8502 6.11858 22.8044 6.07942 22.7605 6.03156C22.7166 5.98371 22.6906 5.94455 22.7027 5.94455C22.7149 5.94455 22.7607 5.98371 22.8046 6.03156Z"/>
                </svg>
            </a>
        </div>
        <div class="form-page">
            <div class="form-content" style="margin-left: 0px;">
                <ul class="form-line">
                    <li class="active">
                        <div class="form-line__number">
                            1
                        </div>
                        <div class="form-line__name">
                            <?php _e("Contact Info", 'finanzia'); ?>
                        </div>
                    </li>
                    <li class="active">
                        <div class="form-line__number">
                            2
                        </div>
                        <div class="form-line__name">
                            <?php _e("Personal Info", 'finanzia'); ?>
                        </div>
                    </li>
                    <li>
                        <div class="form-line__number">
                            3
                        </div>
                        <div class="form-line__name">
                            <?php _e("Address", 'finanzia'); ?>
                        </div>
                    </li>
                    <li>
                        <div class="form-line__number">
                            4
                        </div>
                        <div class="form-line__name">
                            <?php _e("Current Loans", 'finanzia'); ?>
                        </div>
                    </li>
                    <li>
                        <div class="form-line__number">
                            5
                        </div>
                        <div class="form-line__name">
                            <?php _e("Income Info", 'finanzia'); ?>
                        </div>
                    </li>
                    <li>
                        <div class="form-line__number">
                            6
                        </div>
                        <div class="form-line__name">
                            <?php _e("Other Info", 'finanzia'); ?>
                        </div>
                    </li>
                    <li>
                        <div class="form-line__number">
                            7
                        </div>
                        <div class="form-line__name">
                            <?php _e("Documents", 'finanzia'); ?>
                        </div>
                    </li>
                </ul>
                <form class="big-form js_one_send" method="post">
                    <?php wp_nonce_field('mortgages-form-aio-action-' . $calc->ID, 'mortgages-form-aio-field-' . $calc->ID); ?>
                    <input type="hidden" name="step_aio[calc_id]" value="<?= $calc->ID ?>">
                    <div class="big-form__step-1 active">
                        <div class="form-line__title">
                            <?php _e("Personal Information", 'finanzia'); ?>
                        </div>
                        <div class="form-line__text">
                            <?php _e("Please fill out the form to confirm your application", 'finanzia'); ?>
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("First name (as in passport)", 'finanzia'); ?>
                            </div>
                            <input required placeholder="<?php _e("Enter your first name", 'finanzia'); ?>"
                                   type="text" name="step_aio[main_applicant][first_name]" value="">
                        </div>
                        <div class="big-form__fields">
                            <div class="big-form__col">
                                <div class="contacts__label">
                                    <?php _e("Last name (as in passport)", 'finanzia'); ?>
                                </div>
                                <input required placeholder="<?php _e("Enter your last name", 'finanzia'); ?>"
                                       type="text" name="step_aio[main_applicant][last_name]" value="">
                            </div>
                            <div class="big-form__col">
                                <div class="contacts__label">
                                    <?php _e("Maiden name (as in passport)", 'finanzia'); ?>
                                </div>
                                <input placeholder="<?php _e("Enter your maiden name", 'finanzia'); ?>"
                                       type="text" name="step_aio[main_applicant][maiden_name]" value="">
                            </div>
                        </div>
                        <?php /*
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Date of birth", 'finanzia'); ?>
                            </div>
                            <div class="big-form__three-select">
                                <div class="big-form__select-col select-big">
                                    <select required class="select select-big"
                                            name="step_aio[main_applicant][dob][month]">
                                        <?php get_months_options(); ?>
                                    </select>
                                </div>
                                <div class="big-form__select-col select-small">
                                    <select required class="select" name="step_aio[main_applicant][dob][day]">
                                        <?php get_days_options(); ?>
                                    </select>
                                </div>
                                <div class="big-form__select-col select-small">
                                    <select required class="select select-small"
                                            name="step_aio[main_applicant][dob][year]">
                                        <?php get_years_options(1952); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
 */ ?>
                        <div class="big-form__row">
                            <div class="big-form__three-select">
                                <div class="big-form__select-col select-big">
                                    <div class="contacts__label">
                                        <?php _e("Marital status", 'finanzia'); ?>
                                    </div>
                                    <select required class="select" name="step_aio[main_applicant][marital_status]">
                                        <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                        <option value="Single"><?php _e("Single", 'finanzia'); ?></option>
                                        <option value="Married"><?php _e("Married", 'finanzia'); ?></option>
                                        <option value="Divorced"><?php _e("Divorced", 'finanzia'); ?></option>
                                        <option value="Widow/er"><?php _e("Widow/er", 'finanzia'); ?></option>
                                    </select>
                                </div>
                                <div class="big-form__select-col select-big">
                                    <div class="contacts__label">
                                        <?php _e("Number of children", 'finanzia'); ?>
                                    </div>
                                    <input placeholder="<?php _e("Enter the number of children", 'finanzia'); ?>"
                                           type="text"
                                           name="step_aio[main_applicant][number_of_children]" value="">
                                </div>
                            </div>
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Czech ID number (Rodné číslo) — if available", 'finanzia'); ?>
                            </div>
                            <input class="optional" placeholder="XXXXXX/XXXX" type="text"
                                   name="step_aio[main_applicant][passport_registration_number]" value="">
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Citizenship", 'finanzia'); ?>
                            </div>
                            <select required class="select" name="step_aio[main_applicant][main_nationality]">
                                <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                <?php get_nationality_options(); ?>
                            </select>
                        </div>
                        <div class="big-form__check-box">
                            <label class="big-form__check-multi">
                                <input type="hidden" name="step_aio[main_applicant][passports][multipass]"
                                       value="no">
                                <input class="check-multi" type="checkbox"
                                       name="step_aio[main_applicant][passports][multipass]" value="yes">
                                <span class="big-form__check-text"><?php _e("I have multiple passports", 'finanzia'); ?></span>
                            </label>
                        </div>
                        <div class="big-form__add">
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Citizenship", 'finanzia'); ?>
                                </div>
                                <select class="select" name="step_aio[main_applicant][passports][nationality][]">
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
                                <?php _e("Education", 'finanzia'); ?>
                            </div>
                            <select required class="select" name="step_aio[main_applicant][education]">
                                <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                <option value="Primary"><?php _e("Primary", 'finanzia'); ?></option>
                                <option value="Secondary"><?php _e("Secondary", 'finanzia'); ?></option>
                                <option value="University degree"><?php _e("University degree", 'finanzia'); ?></option>
                            </select>
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Type of visa in Czech Republic", 'finanzia'); ?>
                            </div>
                            <select required class="select" name="step_aio[main_applicant][residency_type]">
                                <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                <option value="No residency"><?php _e("No residency", 'finanzia'); ?></option>
                                <option value="Czech citizen"><?php _e("Czech citizen", 'finanzia'); ?></option>
                                <option value="Temporary"><?php _e("Temporary", 'finanzia'); ?></option>
                                <option value="Long-term"><?php _e("Long-term", 'finanzia'); ?></option>
                                <option value="Permanent"><?php _e("Permanent", 'finanzia'); ?></option>
                                <option value="Diplomatic"><?php _e("Diplomatic", 'finanzia'); ?></option>
                            </select>
                        </div>
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Phone number", 'finanzia'); ?>
                            </div>
                            <input placeholder="+420-999-999-999" class="form-tel" required type="tel" name="step_aio[main_applicant][phone]"
                                   value="">
                        </div>
                        <?php /*
                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Bank you currently use in Czech Republic", 'finanzia'); ?>
                            </div>
                            <select multiple class="select" name="step_aio[main_applicant][current_bank][]">
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
  */ ?>
                        <div class="big-form__variant">
                            <div class="big-form__label">
                                <?php _e("Applying alone or with someone else?", 'finanzia'); ?>
                            </div>
                            <div class="big-form__variant-list">
                                <label class="big-form__var-1">
                                    <input id="alone" checked="checked" type="radio"
                                           name="step_aio[relationship_type]" value="alone">
                                    <span class="big-form__var-name"><?php _e("Alone", 'finanzia'); ?></span>
                                </label>
                                <label class="big-form__var-2">
                                    <input id="someelse" type="radio" name="step_aio[relationship_type]"
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
                                        <input checked="checked" type="radio" name="step_aio[relationship_who]"
                                               value="Spouse">
                                        <span class="big-form__var-name"><?php _e("Spouse", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input type="radio" name="step_aio[relationship_who]" value="Partner">
                                        <span class="big-form__var-name"><?php _e("Partner", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input type="radio" name="step_aio[relationship_who]"
                                               value="Close person">
                                        <span class="big-form__var-name"><?php _e("Close person", 'finanzia'); ?></span>
                                    </label>
                                    <label class="big-form__var-1">
                                        <input type="radio" name="step_aio[relationship_who]" value="Other">
                                        <span class="big-form__var-name"><?php _e("Other", 'finanzia'); ?></span>
                                    </label>
                                </div>
                            </div>


                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("First name (as in passport)", 'finanzia'); ?>
                                </div>
                                <input required placeholder="<?php _e("Enter your first name", 'finanzia'); ?>"
                                       type="text" name="step_aio[second_applicant][first_name]" value="">
                            </div>
                            <div class="big-form__fields">
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Last name (as in passport)", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your last name", 'finanzia'); ?>"
                                           type="text" name="step_aio[second_applicant][last_name]" value="">
                                </div>
                                <div class="big-form__col">
                                    <div class="contacts__label">
                                        <?php _e("Maiden name (as in passport)", 'finanzia'); ?>
                                    </div>
                                    <input class="optional"
                                           placeholder="<?php _e("Enter your maiden name", 'finanzia'); ?>"
                                           type="text" name="step_aio[second_applicant][maiden_name]" value="">
                                </div>
                            </div>
                            <?php /*
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Date of birth", 'finanzia'); ?>
                                </div>
                                <div class="big-form__three-select">
                                    <div class="big-form__select-col select-big">
                                        <select required class="select select-big"
                                                name="step_aio[second_applicant][dob][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select required class="select" name="step_aio[second_applicant][dob][day]">
                                            <?php get_days_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select required class="select select-small"
                                                name="step_aio[second_applicant][dob][year]">
                                            <?php get_years_options(1952); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
 */ ?>
                            <div class="big-form__row">
                                <div class="big-form__three-select">
                                    <div class="big-form__select-col select-big">
                                        <div class="contacts__label">
                                            <?php _e("Marital status", 'finanzia'); ?>
                                        </div>
                                        <select required class="select"
                                                name="step_aio[second_applicant][marital_status]">
                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                            <option value="Single"><?php _e("Single", 'finanzia'); ?></option>
                                            <option value="Married"><?php _e("Married", 'finanzia'); ?></option>
                                            <option value="Divorced"><?php _e("Divorced", 'finanzia'); ?></option>
                                            <option value="Widow/er"><?php _e("Widow/er", 'finanzia'); ?></option>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-big">
                                        <div class="contacts__label">
                                            <?php _e("Number of children", 'finanzia'); ?>
                                        </div>
                                        <input class="optional"
                                               placeholder="<?php _e("Enter the number of children", 'finanzia'); ?>"
                                               type="text"
                                               name="step_aio[second_applicant][number_of_children]" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Czech ID number (Rodné číslo) — if available", 'finanzia'); ?>
                                </div>
                                <input class="optional" placeholder="XXXXXX/XXXX" type="text"
                                       name="step_aio[second_applicant][passport_registration_number]" value="">
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Citizenship", 'finanzia'); ?>
                                </div>
                                <select required class="select" name="step_aio[second_applicant][main_nationality]">
                                    <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                    <?php get_nationality_options(); ?>
                                </select>
                            </div>
                            <div class="big-form__check-box">
                                <label class="big-form__check-multi">
                                    <input type="hidden" name="step_aio[second_applicant][passports][multipass]"
                                           value="no">
                                    <input class="check-multi" type="checkbox"
                                           name="step_aio[second_applicant][passports][multipass]" value="yes">
                                    <span class="big-form__check-text"><?php _e("I have multiple passports", 'finanzia'); ?></span>
                                </label>
                            </div>
                            <div class="big-form__add">
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("Citizenship", 'finanzia'); ?>
                                    </div>
                                    <select class="select" name="step_aio[second_applicant][passports][nationality][]">
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
                                    <?php _e("Education", 'finanzia'); ?>
                                </div>
                                <select required class="select" name="step_aio[second_applicant][education]">
                                    <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                    <option value="Primary"><?php _e("Primary", 'finanzia'); ?></option>
                                    <option value="Secondary"><?php _e("Secondary", 'finanzia'); ?></option>
                                    <option value="University degree"><?php _e("University degree", 'finanzia'); ?></option>
                                </select>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Type of visa in Czech Republic", 'finanzia'); ?>
                                </div>
                                <select required class="select" name="step_aio[second_applicant][residency_type]">
                                    <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                    <option value="No residency"><?php _e("No residency", 'finanzia'); ?></option>
                                    <option value="Czech citizen"><?php _e("Czech citizen", 'finanzia'); ?></option>
                                    <option value="Temporary"><?php _e("Temporary", 'finanzia'); ?></option>
                                    <option value="Long-term"><?php _e("Long-term", 'finanzia'); ?></option>
                                    <option value="Permanent"><?php _e("Permanent", 'finanzia'); ?></option>
                                    <option value="Diplomatic"><?php _e("Diplomatic", 'finanzia'); ?></option>
                                </select>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Phone number", 'finanzia'); ?>
                                </div>
                                <input placeholder="+420-999-999-999" class="form-tel" required type="tel" name="step_aio[second_applicant][phone]"
                                       value="">
                            </div>
                            <?php /*
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Bank you currently use in Czech Republic", 'finanzia'); ?>
                                </div>
                                <select multiple class="select" name="step_aio[second_applicant][current_bank][]">
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
                            */ ?>
                        </div>

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
                            <div class="big-form__address-top">
                                <div class="new-step-title">
                                    <?php _e("Permanent address in Czech Republic", 'finanzia'); ?>
                                </div>
                                <div class="big-form__fields big-form__fields-difference">
                                    <div class="big-form__col">
                                        <div class="contacts__label">
                                            <?php _e("City", 'finanzia'); ?>
                                        </div>
                                        <input required placeholder="<?php _e("Enter your city", 'finanzia'); ?>"
                                               type="text"
                                               name="step_aio[main_applicant][permanent_address][city]" value="">
                                    </div>
                                    <div class="big-form__col">
                                        <div class="contacts__label">
                                            <?php _e("Street", 'finanzia'); ?>
                                        </div>
                                        <input required placeholder="<?php _e("Enter your street", 'finanzia'); ?>"
                                               type="text"
                                               name="step_aio[main_applicant][permanent_address][street]" value="">
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
                                               name="step_aio[main_applicant][permanent_address][house_number]"
                                               value="">
                                    </div>
                                    <div class="big-form__col">
                                        <div class="contacts__label">
                                            <?php _e("Postal code", 'finanzia'); ?>
                                        </div>
                                        <input required placeholder="<?php _e("Enter your postal code", 'finanzia'); ?>"
                                               type="text"
                                               name="step_aio[main_applicant][permanent_address][postal_code]" value="">
                                    </div>
                                </div>
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("When did you start living at this address?", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__three-select">
                                        <div class="big-form__select-col select-big">
                                            <select required class="select select-big"
                                                    name="step_aio[main_applicant][start_living_address][month]">
                                                <?php get_months_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__select-col select-small">
                                            <select required class="select"
                                                    name="step_aio[main_applicant][start_living_address][day]">
                                                <?php get_days_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__select-col select-small">
                                            <select required class="select select-small"
                                                    name="step_aio[main_applicant][start_living_address][year]">
                                                <?php get_years_options(1952); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__sep"><span>or</span></div>
                            <div class="big-form__address-bottom">
                                <div class="new-step-title">
                                    <?php _e("Permanent address abroad (if no permanent address in Czech Republic)", 'finanzia'); ?>
                                </div>
                                <div class="big-form__address-add">
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("Country", 'finanzia'); ?>
                                        </div>
                                        <select class="select"
                                                name="step_aio[main_applicant][permanent_address_abroad][country]">
                                            <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                            <?php get_nationality_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__fields big-form__fields-difference">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("City", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="<?php _e("Enter your city", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_aio[main_applicant][permanent_address_abroad][city]"
                                                   value="">
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Street", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="<?php _e("Enter your street", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_aio[main_applicant][permanent_address_abroad][street]"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="big-form__fields">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("House number", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="<?php _e("Enter your house number", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_aio[main_applicant][permanent_address_abroad][house_number]"
                                                   value="">
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Postal code", 'finanzia'); ?>
                                            </div>
                                            <input placeholder="<?php _e("Enter your postal code", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_aio[main_applicant][permanent_address_abroad][postal_code]"
                                                   value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="second-applicant">
                            <div class="form-opener">
                                <?php _e("Second Applicant", 'finanzia'); ?>
                            </div>
                            <div class="big-form__section">
                                <div class="big-form__address-top">
                                    <div class="new-step-title">
                                        <?php _e("Permanent address in Czech Republic", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__fields big-form__fields-difference">
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("City", 'finanzia'); ?>
                                            </div>
                                            <input required placeholder="<?php _e("Enter your city", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_aio[second_applicant][permanent_address][city]" value="">
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Street", 'finanzia'); ?>
                                            </div>
                                            <input required placeholder="<?php _e("Enter your street", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_aio[second_applicant][permanent_address][street]"
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
                                                   name="step_aio[second_applicant][permanent_address][house_number]"
                                                   value="">
                                        </div>
                                        <div class="big-form__col">
                                            <div class="contacts__label">
                                                <?php _e("Postal code", 'finanzia'); ?>
                                            </div>
                                            <input required
                                                   placeholder="<?php _e("Enter your postal code", 'finanzia'); ?>"
                                                   type="text"
                                                   name="step_aio[second_applicant][permanent_address][postal_code]"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="big-form__row">
                                        <div class="contacts__label">
                                            <?php _e("When did you start living at this address?", 'finanzia'); ?>
                                        </div>
                                        <div class="big-form__three-select">
                                            <div class="big-form__select-col select-big">
                                                <select required class="select select-big"
                                                        name="step_aio[second_applicant][start_living_address][month]">
                                                    <?php get_months_options(); ?>
                                                </select>
                                            </div>
                                            <div class="big-form__select-col select-small">
                                                <select required class="select"
                                                        name="step_aio[second_applicant][start_living_address][day]">
                                                    <?php get_days_options(); ?>
                                                </select>
                                            </div>
                                            <div class="big-form__select-col select-small">
                                                <select required class="select select-small"
                                                        name="step_aio[second_applicant][start_living_address][year]">
                                                    <?php get_years_options(1952); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="big-form__sep"><span>or</span></div>
                                <div class="big-form__address-bottom">
                                    <div class="new-step-title">
                                        <?php _e("Permanent address abroad (if no permanent address in Czech Republic)", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__address-add">
                                        <div class="big-form__row">
                                            <div class="contacts__label">
                                                <?php _e("Country", 'finanzia'); ?>
                                            </div>
                                            <select class="select"
                                                    name="step_aio[second_applicant][permanent_address_abroad][country]">
                                                <option value=""><?php _e("Select", 'finanzia'); ?></option>
                                                <?php get_nationality_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__fields big-form__fields-difference">
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("City", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter your city", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_aio[second_applicant][permanent_address_abroad][city]"
                                                       value="">
                                            </div>
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("Street", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter your street", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_aio[second_applicant][permanent_address_abroad][street]"
                                                       value="">
                                            </div>
                                        </div>
                                        <div class="big-form__fields">
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("House number", 'finanzia'); ?>
                                                </div>
                                                <input
                                                        placeholder="<?php _e("Enter your house number", 'finanzia'); ?>"
                                                        type="text"
                                                        name="step_aio[second_applicant][permanent_address_abroad][house_number]"
                                                        value="">
                                            </div>
                                            <div class="big-form__col">
                                                <div class="contacts__label">
                                                    <?php _e("Postal code", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter your postal code", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_aio[second_applicant][permanent_address_abroad][postal_code]"
                                                       value="">
                                            </div>
                                        </div>
                                    </div>
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
                            <?php _e("Current Loans", 'finanzia'); ?>
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
                                        <?php _e("Do you have any existing loans or debts?", 'finanzia'); ?>
                                    </div>
                                    <ul class="big-form__tabs-list">
                                        <li>
                                            <a class="active" data-current_tab="liability_type_no_liabilities"
                                               href="#big-form__type-1"><?php _e("I don’t have any loans", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="liability_type_loan"
                                               href="#big-form__type-2"><?php _e("Loan", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="liability_type_mortgage"
                                               href="#big-form__type-3"><?php _e("Mortgage", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="liability_type_credit_card"
                                               href="#big-form__type-4"><?php _e("Credit card", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="liability_type_overdraft"
                                               href="#big-form__type-5"><?php _e("Overdraft", 'finanzia'); ?></a>
                                        </li>
                                        <li>
                                            <a data-current_tab="liability_type_other"
                                               href="#big-form__type-6"><?php _e("Other (please describe below)", 'finanzia'); ?></a>
                                        </li>
                                    </ul>
                                    <input type="hidden" class="current_tab"
                                           name="step_aio[main_applicant][existing_liabilities][][current_tab][liability_type_loan]">
                                </div>
                                <div id="big-form__type-1" class="tab">
                                    <input type="hidden"
                                           name="step_aio[main_applicant][existing_liabilities][][liability_type_no_liabilities][additional_information]"
                                           value="No liabilities">
                                </div>
                                <div id="big-form__type-2" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                </div>
                                                <input
                                                        placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                        type="text"
                                                        name="step_aio[main_applicant][existing_liabilities][][liability_type_loan][company_name]"
                                                        value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                </div>
                                                <input
                                                        placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>"
                                                        type="text"
                                                        name="step_aio[main_applicant][existing_liabilities][][liability_type_loan][products_interest_rate]"
                                                        value="">
                                            </div>
                                            <div class="big-form__fields">
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="10 000" type="number"
                                                           name="step_aio[main_applicant][existing_liabilities][][liability_type_loan][monthly_payment_amount]"
                                                           value="">
                                                </div>
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _ex("Remaining balance — CZK", "Liability type loan", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="500 000" type="number"
                                                           name="step_aio[main_applicant][existing_liabilities][][liability_type_loan][remaining_balance]"
                                                           value="">
                                                </div>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_aio[main_applicant][existing_liabilities][][liability_type_loan][additional_information]"
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
                                                       name="step_aio[main_applicant][existing_liabilities][][liability_type_mortgage][company_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_aio[main_applicant][existing_liabilities][][liability_type_mortgage][products_interest_rate]"
                                                       value="">
                                            </div>
                                            <div class="big-form__fields">
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="25 000" type="number"
                                                           name="step_aio[main_applicant][existing_liabilities][][liability_type_mortgage][monthly_payment_amount]"
                                                           value="">
                                                </div>
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _ex("Remaining balance — CZK", "Liability type mortgage", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="2 000 000" type="number"
                                                           name="step_aio[main_applicant][existing_liabilities][][liability_type_mortgage][remaining_balance]"
                                                           value="">
                                                </div>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_aio[main_applicant][existing_liabilities][][liability_type_mortgage][additional_information]"
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
                                                       name="step_aio[main_applicant][existing_liabilities][][liability_type_credit_card][company_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Maximum credit limit you can get on your credit card", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="100 000" type="text"
                                                       name="step_aio[main_applicant][existing_liabilities][][liability_type_credit_card][max_credit_limit]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_aio[main_applicant][existing_liabilities][][liability_type_credit_card][additional_information]"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="big-form__type-5" class="tab">
                                    <div class="field-wrapper">
                                        <div class="field-section">
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Which company provided the product?", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the company name", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_aio[main_applicant][existing_liabilities][][liability_type_overdraft][company_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Maximum limit amount", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="100 000" type="text"
                                                       name="step_aio[main_applicant][existing_liabilities][][liability_type_overdraft][max_credit_limit]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_aio[main_applicant][existing_liabilities][][liability_type_overdraft][additional_information]"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
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
                                                       name="step_aio[main_applicant][existing_liabilities][][liability_type_other][company_name]"
                                                       value="">
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                </div>
                                                <input placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>"
                                                       type="text"
                                                       name="step_aio[main_applicant][existing_liabilities][][liability_type_other][products_interest_rate]"
                                                       value="">
                                            </div>
                                            <div class="big-form__fields">
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="10 000" type="number"
                                                           name="step_aio[main_applicant][existing_liabilities][][liability_type_other][monthly_payment_amount]"
                                                           value="">
                                                </div>
                                                <div class="big-form__col">
                                                    <div class="contacts__label">
                                                        <?php _ex("Remaining balance — CZK", "Liability type other", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="500 000" type="number"
                                                           name="step_aio[main_applicant][existing_liabilities][][liability_type_other][remaining_balance]"
                                                           value="">
                                                </div>
                                            </div>
                                            <div class="big-form__row">
                                                <div class="contacts__label">
                                                    <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                </div>
                                                <textarea class="optional"
                                                          name="step_aio[main_applicant][existing_liabilities][][liability_type_other][additional_information]"
                                                          placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                          rows="8"
                                                          cols="80"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden"
                                       name="step_aio[main_applicant][existing_liabilities][][breakpoint]">
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
                                            <?php _e("Do you have any existing loans or debts?", 'finanzia'); ?>
                                        </div>
                                        <ul class="big-form__tabs-list">
                                            <li>
                                                <a class="active" data-current_tab="liability_type_no_liabilities"
                                                   href="#big-form__second-type-5"><?php _e("I don’t have any loans", 'finanzia'); ?></a>
                                            </li>
                                            <li>
                                                <a data-current_tab="liability_type_loan"
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
                                                <a data-current_tab="liability_type_other"
                                                   href="#big-form__second-type-6"><?php _e("Other (please describe below)", 'finanzia'); ?></a>
                                            </li>
                                        </ul>
                                        <input type="hidden" class="current_tab"
                                               name="step_aio[second_applicant][existing_liabilities][][current_tab][liability_type_loan]">
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
                                                           name="step_aio[second_applicant][existing_liabilities][][liability_type_loan][company_name]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_aio[second_applicant][existing_liabilities][][liability_type_loan][products_interest_rate]"
                                                           value="">
                                                </div>
                                                <div class="big-form__fields">
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="10 000" type="number"
                                                               name="step_aio[second_applicant][existing_liabilities][][liability_type_loan][monthly_payment_amount]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Remaining balance — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="500 000" type="number"
                                                               name="step_aio[second_applicant][existing_liabilities][][liability_type_loan][remaining_balance]"
                                                               value="">
                                                    </div>
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                    </div>
                                                    <textarea class="optional"
                                                              name="step_aio[second_applicant][existing_liabilities][][liability_type_loan][additional_information]"
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
                                                           name="step_aio[second_applicant][existing_liabilities][][liability_type_mortgage][company_name]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_aio[second_applicant][existing_liabilities][][liability_type_mortgage][products_interest_rate]"
                                                           value="">
                                                </div>
                                                <div class="big-form__fields">
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="25 000" type="number"
                                                               name="step_aio[second_applicant][existing_liabilities][][liability_type_mortgage][monthly_payment_amount]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Remaining balance — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="2 000 000" type="number"
                                                               name="step_aio[second_applicant][existing_liabilities][][liability_type_mortgage][remaining_balance]"
                                                               value="">
                                                    </div>
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                    </div>
                                                    <textarea class="optional"
                                                              name="step_aio[second_applicant][existing_liabilities][][liability_type_mortgage][additional_information]"
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
                                                           name="step_aio[second_applicant][existing_liabilities][][liability_type_credit_card][company_name]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Maximum credit limit you can get on your credit card", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="100 000" type="text"
                                                           name="step_aio[second_applicant][existing_liabilities][][liability_type_credit_card][max_credit_limit]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                    </div>
                                                    <textarea class="optional"
                                                              name="step_aio[second_applicant][existing_liabilities][][liability_type_credit_card][additional_information]"
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
                                                           name="step_aio[second_applicant][existing_liabilities][][liability_type_overdraft][company_name]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Maximum limit amount", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="100 000" type="text"
                                                           name="step_aio[second_applicant][existing_liabilities][][liability_type_overdraft][max_credit_limit]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                    </div>
                                                    <textarea class="optional"
                                                              name="step_aio[second_applicant][existing_liabilities][][liability_type_overdraft][additional_information]"
                                                              placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                              rows="8"
                                                              cols="80"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="big-form__second-type-5" class="tab">
                                        <input type="hidden"
                                               name="step_aio[second_applicant][existing_liabilities][][liability_type_no_liabilities][additional_information]"
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
                                                           name="step_aio[second_applicant][existing_liabilities][][liability_type_other][company_name]"
                                                           value="">
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("What is the product's interest rate (%)?", 'finanzia'); ?>
                                                    </div>
                                                    <input placeholder="<?php _e("Enter the interest rate percentage", 'finanzia'); ?>"
                                                           type="text"
                                                           name="step_aio[second_applicant][existing_liabilities][][liability_type_other][products_interest_rate]"
                                                           value="">
                                                </div>
                                                <div class="big-form__fields">
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Monthly payment amount — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="10 000" type="number"
                                                               name="step_aio[second_applicant][existing_liabilities][][liability_type_other][monthly_payment_amount]"
                                                               value="">
                                                    </div>
                                                    <div class="big-form__col">
                                                        <div class="contacts__label">
                                                            <?php _e("Remaining balance — CZK", 'finanzia'); ?>
                                                        </div>
                                                        <input placeholder="500 000" type="number"
                                                               name="step_aio[second_applicant][existing_liabilities][][liability_type_other][remaining_balance]"
                                                               value="">
                                                    </div>
                                                </div>
                                                <div class="big-form__row">
                                                    <div class="contacts__label">
                                                        <?php _e("Additional Information (optional)", 'finanzia'); ?>
                                                    </div>
                                                    <textarea class="optional"
                                                              name="step_aio[second_applicant][existing_liabilities][][liability_type_other][additional_information]"
                                                              placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                                              rows="8"
                                                              cols="80"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden"
                                           name="step_aio[second_applicant][existing_liabilities][][breakpoint]">
                                </div>
                                <div class="plus" data-text="<?php _e("Liability type", 'finanzia'); ?>">
                                    <?php _e("I have one more liability type", 'finanzia'); ?>
                                </div>
<!--                                <div class="big-form__variant custom-var">-->
<!--                                    <div class="big-form__label">-->
<!--                                        --><?php //_e("Do you already own any property in Czech Republic?", 'finanzia'); ?>
<!--                                    </div>-->
<!--                                    <div class="big-form__variant-list">-->
<!--                                        <label class="big-form__var-1">-->
<!--                                            <input id="yes" checked="checked" type="radio"-->
<!--                                                   name="step_aio[second_applicant][own_property]" value="yes">-->
<!--                                            <span class="big-form__var-name">--><?php //_e("Yes", 'finanzia'); ?><!--</span>-->
<!--                                        </label>-->
<!--                                        <label class="big-form__var-2">-->
<!--                                            <input id="no" type="radio" name="step_aio[second_applicant][own_property]"-->
<!--                                                   value="no">-->
<!--                                            <span class="big-form__var-name">--><?php //_e("No", 'finanzia'); ?><!--</span>-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
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
                            <?php _e("Income Information", 'finanzia'); ?>
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
                                    <?php _e("Company name", 'finanzia'); ?>
                                </div>
                                <input required placeholder="<?php _e("Enter company name", 'finanzia'); ?>"
                                       type="text" name="step_aio[main_applicant][company_name]" value="">
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Registration number (IČO)", 'finanzia'); ?>
                                </div>
                                <input required
                                       placeholder="<?php _e("Enter company registration number", 'finanzia'); ?>"
                                       type="text" name="step_aio[main_applicant][company_registration_number]"
                                       value="">
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Net income (monthly average – last 6 months)", 'finanzia'); ?>
                                </div>
                                <input required
                                       placeholder="<?php _e("Enter average monthly net income", 'finanzia'); ?>"
                                       type="text" name="step_aio[main_applicant][average_monthly_net_income]" value="">
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Job position", 'finanzia'); ?>
                                </div>
                                <input required placeholder="<?php _e("Enter your job title", 'finanzia'); ?>"
                                       type="text" name="step_aio[main_applicant][job_title]" value="">
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("Bank where your salary is paid", 'finanzia'); ?>
                                </div>
                                <input required placeholder="<?php _e("Enter the name of the bank", 'finanzia'); ?>"
                                       type="text" name="step_aio[main_applicant][name_of_bank]" value="">
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("When did you start working at this company?", 'finanzia'); ?>
                                </div>
                                <div class="big-form__three-select">
                                    <div class="big-form__select-col select-big">
                                        <select required class="select select-big"
                                                name="step_aio[main_applicant][start_working][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select required class="select"
                                                name="step_aio[main_applicant][start_working][day]">
                                            <?php get_days_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select required class="select select-small"
                                                name="step_aio[main_applicant][start_working_company][year]">
                                            <?php get_years_options(1952); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("When did you start working in this field?", 'finanzia'); ?>
                                </div>
                                <div class="big-form__three-select">
                                    <div class="big-form__select-col select-big">
                                        <select required class="select select-big"
                                                name="step_aio[main_applicant][start_working_field][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select required class="select"
                                                name="step_aio[main_applicant][start_working_field][day]">
                                            <?php get_days_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select required class="select select-small"
                                                name="step_aio[main_applicant][start_working_field][year]">
                                            <?php get_years_options(1952); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="big-form__row">
                                <div class="contacts__label">
                                    <?php _e("When did you start working for your previous employer in Czechia?", 'finanzia'); ?>
                                </div>
                                <div class="big-form__three-select">
                                    <div class="big-form__select-col select-big">
                                        <select required class="select select-big"
                                                name="step_aio[main_applicant][start_working_previous_employer][month]">
                                            <?php get_months_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select required class="select"
                                                name="step_aio[main_applicant][start_working_previous_employer][day]">
                                            <?php get_days_options(); ?>
                                        </select>
                                    </div>
                                    <div class="big-form__select-col select-small">
                                        <select required class="select select-small"
                                                name="step_aio[main_applicant][start_working_previous_employer][year]">
                                            <?php get_years_options(1952); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="second-applicant">
                            <div class="form-opener">
                                <?php _e("Second Applicant", 'finanzia'); ?>
                            </div>
                            <div class="big-form__section">
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("Company name", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter company name", 'finanzia'); ?>"
                                           type="text" name="step_aio[second_applicant][company_name]" value="">
                                </div>
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("Registration number (IČO)", 'finanzia'); ?>
                                    </div>
                                    <input required
                                           placeholder="<?php _e("Enter company registration number", 'finanzia'); ?>"
                                           type="text" name="step_aio[second_applicant][company_registration_number]"
                                           value="">
                                </div>
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("Net income (monthly average – last 6 months)", 'finanzia'); ?>
                                    </div>
                                    <input required
                                           placeholder="<?php _e("Enter average monthly net income", 'finanzia'); ?>"
                                           type="text" name="step_aio[second_applicant][average_monthly_net_income]"
                                           value="">
                                </div>
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("Job position", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter your job title", 'finanzia'); ?>"
                                           type="text" name="step_aio[second_applicant][job_title]" value="">
                                </div>
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("Bank where your salary is paid", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter the name of the bank", 'finanzia'); ?>"
                                           type="text" name="step_aio[second_applicant][name_of_bank]" value="">
                                </div>
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("When did you start working at this company?", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__three-select">
                                        <div class="big-form__select-col select-big">
                                            <select required class="select select-big"
                                                    name="step_aio[second_applicant][start_working][month]">
                                                <?php get_months_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__select-col select-small">
                                            <select required class="select"
                                                    name="step_aio[second_applicant][start_working][day]">
                                                <?php get_days_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__select-col select-small">
                                            <select required class="select select-small"
                                                    name="step_aio[second_applicant][start_working_company][year]">
                                                <?php get_years_options(1952); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("When did you start working in this field?", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__three-select">
                                        <div class="big-form__select-col select-big">
                                            <select required class="select select-big"
                                                    name="step_aio[second_applicant][start_working_field][month]">
                                                <?php get_months_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__select-col select-small">
                                            <select required class="select"
                                                    name="step_aio[second_applicant][start_working_field][day]">
                                                <?php get_days_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__select-col select-small">
                                            <select required class="select select-small"
                                                    name="step_aio[second_applicant][start_working_field][year]">
                                                <?php get_years_options(1952); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="big-form__row">
                                    <div class="contacts__label">
                                        <?php _e("When did you start working for your previous employer in Czechia?", 'finanzia'); ?>
                                    </div>
                                    <div class="big-form__three-select">
                                        <div class="big-form__select-col select-big">
                                            <select required class="select select-big"
                                                    name="step_aio[second_applicant][start_working_previous_employer][month]">
                                                <?php get_months_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__select-col select-small">
                                            <select required class="select"
                                                    name="step_aio[second_applicant][start_working_previous_employer][day]">
                                                <?php get_days_options(); ?>
                                            </select>
                                        </div>
                                        <div class="big-form__select-col select-small">
                                            <select required class="select select-small"
                                                    name="step_aio[second_applicant][start_working_previous_employer][year]">
                                                <?php get_years_options(1952); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="big-form__button">
                            <button class="prev-step" type="button"><?php _e("Back", 'finanzia'); ?></button>
                            <button class="big-form__btn next-step"
                                    type="button"><?php _e("next step", 'finanzia'); ?></button>
                        </div>
                    </div>

                    <div class="big-form__step-5">
                        <div class="form-line__title">
                            <?php _e("Other Information", 'finanzia'); ?>
                        </div>
                        <div class="form-line__text">
                            <?php _e("Please fill out the form to confirm your application", 'finanzia'); ?>
                        </div>
                        <div class="big-form__row">
                            <div class="big-form__three-select">

                                <div class="big-form__select-col select-big">
                                    <div class="contacts__label">
                                        <?php _e("Amount you want to borrow", 'finanzia'); ?>
                                    </div>
                                    <input required placeholder="<?php _e("Enter the amount", 'finanzia'); ?>"
                                           type="text"
                                           name="step_aio[borrow_amount]" value="">
                                </div>
                                <div class="big-form__select-col select-big">
                                    <div class="contacts__label">
                                        <?php _e("Preferred monthly payment date", 'finanzia'); ?>
                                    </div>
                                    <select required class="select" name="step_aio[preferred_monthly_payment_date]">
                                        <?php get_days_options(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="big-form__row">
                            <div class="contacts__label">
                                <?php _e("Additional Information (optional)", 'finanzia'); ?>
                            </div>
                            <textarea class="optional" name="step_aio[property_additional_information]"
                                      placeholder="<?php _e("Please provide any additional comments or information", 'finanzia'); ?>"
                                      rows="8"
                                      cols="80"></textarea>
                        </div>
                        <div class="big-form__button">
                            <button class="prev-step" type="button"><?php _e("Back", 'finanzia'); ?></button>
                            <button class="big-form__submit-btn"
                                    type="submit"><?php _e("next step", 'finanzia'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
get_footer('steps');
