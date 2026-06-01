<?php
/**
 * Template name: Mortgages form step three template
 */
$fields        = get_fields();
$option_fields = get_fields("option");

if (is_user_logged_in()) {
    $calc = get_actual_user_calc();

    $category = get_the_terms($calc->ID, Calculator::taxonomy);
//    if ($category) {
//        $url = get_form_page_url($calc->_calc_status, $category[0]->slug);
//    } else {
//        $url = home_url();
//    }
//    if (parse_url($url, PHP_URL_PATH) != $_SERVER["REQUEST_URI"]) {
//        wp_redirect($url);
//    }
//} else {
//    wp_redirect(home_url());


    $calc->currency    = mb_strtoupper(get_post_meta($calc->ID, "_mini_calc_currency", true));
    $calc->credit_type = get_post_meta($calc->ID, "_mini_calc_credit_type", true);
}
get_header("steps");
?>
    <div class="wrapper-form">
        <div class="form-page">
            <div class="form-aside">
                <div class="form-aside__box">
                    <a class="logo" href="<?= home_url(); ?>">
                        <img src="<?= theme()->getMainLogo(); ?>"
                             alt="<?php bloginfo("name"); ?> - <?php _e("photo", 'finanzia'); ?>"
                             title="<?php bloginfo("name"); ?>">
                    </a>
                    <a class="back" href="<?= home_url(); ?>"><?php _e("Return to Homepage", 'finanzia'); ?></a>
                    <div class="form-aside__step-text">
                        <?php _e("STEP 3", 'finanzia'); ?>
                    </div>
                    <div class="form-aside__step-title">
                        <?php _e("You are in the third stage of the application process.", 'finanzia'); ?>
                    </div>
                    <!--                    <div class="form-aside__step-notes">-->
                    <!--                        --><?php //_e("It is preferable to have each month's statement in separate PDF files.", 'finanzia'); ?>
                    <!--                        <br>-->
                    <!--                        --><?php //_e("Download these statements from your internet banking for convenience.", 'finanzia'); ?>
                    <!--                    </div>-->
                </div>
            </div>
            <div class="form-content">
                <form class="big-form js_one_send" method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('mortgages-form-step-three-action-' . $calc->ID, 'mortgages-form-step-three-field-' . $calc->ID); ?>
                    <input data-text="<?php _e("file", 'finanzia'); ?>" data-texts="<?php _e("files", 'finanzia'); ?>"
                           type="hidden" name="step_three[calc_id]"
                           value="<?= $calc->ID ?>">
                    <div class="form-line__title">
                        <?php _e("Documents Upload", 'finanzia'); ?>
                    </div>
                    <div class="form-line__text">
                        <?php _e("Please upload the necessary documents in the form below", 'finanzia'); ?>
                    </div>
                    <div class="big-form__holder">
                        <div class="big-form__variant">
                            <div class="big-form__label">
                                <?php _e("Income type", 'finanzia'); ?>
                            </div>
                            <ul class="big-form__tabs-list">
                                <li>
                                    <a data-current_tab="full_time_employee" class="active"
                                       href="#big-form__tab-1"><?php _ex("Full-time employee", 'STEP 3 Income type tab', 'finanzia'); ?></a>
                                </li>
                                <li>
                                    <a data-current_tab="self_employed"
                                       href="#big-form__tab-2"><?php _ex("Self-employed", 'STEP 3 Income type tab', 'finanzia'); ?></a>
                                </li>
                                <li>
                                    <a data-current_tab="rental_income"
                                       href="#big-form__tab-3"><?php _ex("Rental income", 'STEP 3 Income type tab', 'finanzia'); ?></a>
                                </li>
                                <li>
                                    <a data-current_tab="income_from_abroad"
                                       href="#big-form__tab-4"><?php _ex("Income from abroad", 'STEP 3 Income type tab', 'finanzia'); ?></a>
                                </li>
                            </ul>
                            <input type="hidden" class="current_tab"
                                   name="step_three[income_type][current_tab][full_time_employee]">


                            <div id="big-form__tab-1" class="tab">
                                <?php if ($calc->credit_type == "refinancing"): ?>
                                    <div class="documents">
                                        <div class="documents__title">
                                            <?php _e("Original Mortgage Contract", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__text">
                                            <?php _e("Scanned copy of original signed mortgage contract", 'finanzia'); ?>
                                        </div>
                                        <div class="file-upload">
                                            <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            </svg>
                                          </span>
                                                <span class="file-upload__title"
                                                      data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                      data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                                <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                            </label>
                                            <input data-text="<?php _e("file", 'finanzia'); ?>"
                                                   data-texts="<?php _e("files", 'finanzia'); ?>" required
                                                   name="step_three[full_time_employee][original_mortgage_contract][]"
                                                   type="file" multiple class="file-upload__input file-upload__all">
                                            <button type="button"
                                                    class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                            <div class="file-upload-error">
                                                <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Identification Documents", 'finanzia'); ?>
                                    </div>
                                    <ul class="documents__list">
                                        <li>
                                            <?php _e("Scanned copy of your passport(s) and residency card, showing both sides.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("For Czech citizens, scanned personal ID from both sides, along with a secondary ID such as a driver's license or passport.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("Non-EU citizens with a Czech residency in the form of a book should also include pages 4-5 displaying the validity of the ID.", 'finanzia'); ?>
                                        </li>
                                        <!--                                        <li>-->
                                        <!--                                            --><?php //_e("Upload your Identification Documents", 'finanzia'); ?>
                                        <!--                                        </li>-->
                                    </ul>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>" required
                                               name="step_three[full_time_employee][identification_documents][]"
                                               type="file"
                                               multiple class="file-upload__input file-upload__all">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($calc->credit_type == "refinancing"): ?>
                                    <div class="documents">
                                        <div class="documents__title">
                                            <?php _e("Mortgage Bank Statement", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__notes">
                                            <?php _e("The statement should display the remaining balance on your mortgage.", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__text">
                                            <?php _e("The most recent bank statement of your mortgage account", 'finanzia'); ?>
                                        </div>
                                        <div class="file-upload">
                                            <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            </svg>
                                          </span>
                                                <span class="file-upload__title"
                                                      data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                      data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                                <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                            </label>
                                            <input data-text="<?php _e("file", 'finanzia'); ?>"
                                                   data-texts="<?php _e("files", 'finanzia'); ?>" required
                                                   name="step_three[full_time_employee][mortgage_bank_statement][]"
                                                   type="file" multiple class="file-upload__input file-upload__all">
                                            <button type="button"
                                                    class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                            <div class="file-upload-error">
                                                <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Bank Statements", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__notes">
                                        <?php _e("It is preferable to have each month's statement in separate PDF files.", 'finanzia'); ?>
                                        <br>
                                        <?php _e("Download these statements from your internet banking for convenience.", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Last 6 months of your bank statements in PDF format.", 'finanzia'); ?>
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                      <span class="file-upload__ico">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 20 20"
                                             fill="none">
                                        <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                              stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                        </svg>
                                      </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>" required
                                               name="step_three[full_time_employee][bank_statement][]" type="file"
                                               multiple class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Income Certificate", 'finanzia'); ?>
                                    </div>
                                    <ol class="documents__list-num">
                                        <li>
                                            <?php _e("Obtain the income certificate specific to the bank where you intend to secure the mortgage from our website.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("Send the certificate to your employer (HR or accounting department) for completion.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("Once filled, collect the forms and add your signature if required.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("While scanned copies are acceptable at this stage, please note that originals will be required later, including both your and your employer's signatures (preferably in blue pen).", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("Some banks may require you to co-sign, in which case, please use the same date as your employer.", 'finanzia'); ?>
                                        </li>
                                    </ol>
                                    <!--                                    <div class="documents__text">-->
                                    <!--                                        --><?php //_e("Upload your Income Certificate", 'finanzia'); ?>
                                    <!--                                    </div>-->
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>" required
                                               name="step_three[full_time_employee][income_certificate][]" type="file"
                                               multiple class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Optional Documents", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Scanned copy of your signed work contract", 'finanzia'); ?>
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[full_time_employee][optional_documents][]" type="file"
                                               multiple class="file-upload__input file-upload__pdf optional">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Any recent amendments related to your work contract (if available)", 'finanzia'); ?>
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                      <span class="file-upload__ico">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 20 20"
                                             fill="none">
                                        <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                              stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                        </svg>
                                      </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[full_time_employee][recent_contract_changes][]"
                                               type="file"
                                               multiple class="file-upload__input file-upload__pdf optional">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div id="big-form__tab-2" class="tab">
                                <?php if ($calc->credit_type == "refinancing"): ?>
                                    <div class="documents">
                                        <div class="documents__title">
                                            <?php _e("Original Mortgage Contract", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__text">
                                            <?php _e("Scanned copy of original signed mortgage contract", 'finanzia'); ?>
                                        </div>
                                        <div class="file-upload">
                                            <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            </svg>
                                          </span>
                                                <span class="file-upload__title"
                                                      data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                      data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                                <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                            </label>
                                            <input data-text="<?php _e("file", 'finanzia'); ?>"
                                                   data-texts="<?php _e("files", 'finanzia'); ?>"
                                                   name="step_three[self_employed][original_mortgage_contract][]"
                                                   type="file" multiple class="file-upload__input file-upload__all">
                                            <button type="button"
                                                    class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                            <div class="file-upload-error">
                                                <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="documents">
                                        <div class="documents__title">
                                            <?php _e("Mortgage Bank Statement", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__notes">
                                            <?php _e("The statement should display the remaining balance on your mortgage.", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__text">
                                            <?php _e("The most recent bank statement of your mortgage account", 'finanzia'); ?>
                                        </div>
                                        <div class="file-upload">
                                            <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            </svg>
                                          </span>
                                                <span class="file-upload__title"
                                                      data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                      data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                                <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                            </label>
                                            <input data-text="<?php _e("file", 'finanzia'); ?>"
                                                   data-texts="<?php _e("files", 'finanzia'); ?>"
                                                   name="step_three[self_employed][mortgage_bank_statement][]"
                                                   type="file"
                                                   multiple class="file-upload__input file-upload__all">
                                            <button type="button"
                                                    class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                            <div class="file-upload-error">
                                                <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Identification Documents", 'finanzia'); ?>
                                    </div>
                                    <ul class="documents__list">
                                        <li>
                                            <?php _e("Scanned copy of your passport(s) and residency card, showing both sides.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("For Czech citizens, scanned personal ID from both sides, along with a secondary ID such as a driver's license or passport.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("Non-EU citizens with a Czech residency in the form of a book should also include pages 4-5 displaying the validity of the ID.", 'finanzia'); ?>
                                        </li>
                                    </ul>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"></path>
                                            </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[self_employed][identification_documents][]" type="file"
                                               multiple class="file-upload__input file-upload__all">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?>
                                        </button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Personal Tax Returns", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Scanned copy of your complete tax returns for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-2 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[self_employed][personal_tax_returns_year_before_last][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?>
                                        </button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Scanned copy of your complete tax returns for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-1 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                                              </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[self_employed][personal_tax_returns_last_year][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?>
                                        </button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Confirmation of Tax Return Submission", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Confirmation of submitting your tax returns for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-2 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[self_employed][confirmation_tax_return_submission_year_before_last][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Confirmation of submitting your tax returns for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-1 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                                              </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[self_employed][confirmation_tax_return_submission_last_year][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Confirmation of Tax Payment", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Confirmation of the payment of taxes for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-2 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                                              </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[self_employed][tax_payment_confirmation_for_year_before_last][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Confirmation of the payment of taxes for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-1 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                      <span class="file-upload__ico">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 20 20"
                                             fill="none">
                                        <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                              stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                              stroke-linejoin="round"/>
                                        </svg>
                                      </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[self_employed][tax_payment_confirmation_for_last_year][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Bank Statements", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__notes">
                                        <?php _e("It is preferable to have each month's statement in separate PDF files.", 'finanzia'); ?>
                                        <br>
                                        <?php _e("Download these statements from your internet banking for convenience.", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Last 6 months of your bank statements in PDF format.", 'finanzia'); ?>
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                   viewBox="0 0 20 20" fill="none">
                                                  <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                        stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"/>
                                              </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[self_employed][bank_statements][]" type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="big-form__tab-3" class="tab">
                                <?php if ($calc->credit_type == "refinancing"): ?>
                                    <div class="documents">
                                        <div class="documents__title">
                                            <?php _e("Original Mortgage Contract", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__text">
                                            <?php _e("Scanned copy of original signed mortgage contract", 'finanzia'); ?>
                                        </div>
                                        <div class="file-upload">
                                            <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                                <span class="file-upload__title"
                                                      data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                      data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                                <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                            </label>
                                            <input data-text="<?php _e("file", 'finanzia'); ?>"
                                                   data-texts="<?php _e("files", 'finanzia'); ?>"
                                                   name="step_three[rental_income][original_mortgage_contract][]"
                                                   type="file" multiple
                                                   class="file-upload__input file-upload__all">
                                            <button type="button"
                                                    class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                            <div class="file-upload-error">
                                                <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="documents">
                                        <div class="documents__title">
                                            <?php _e("Mortgage Bank Statement", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__notes">
                                            <?php _e("The statement should display the remaining balance on your mortgage.", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__text">
                                            <?php _e("The most recent bank statement of your mortgage account", 'finanzia'); ?>
                                        </div>
                                        <div class="file-upload">
                                            <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                                <span class="file-upload__title"
                                                      data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                      data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                                <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                            </label>
                                            <input data-text="<?php _e("file", 'finanzia'); ?>"
                                                   data-texts="<?php _e("files", 'finanzia'); ?>"
                                                   name="step_three[rental_income][mortgage_bank_statement][]"
                                                   type="file" multiple
                                                   class="file-upload__input file-upload__all">
                                            <button type="button"
                                                    class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                            <div class="file-upload-error">
                                                <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Identification Documents", 'finanzia'); ?>
                                    </div>
                                    <ul class="documents__list">
                                        <li>
                                            <?php _e("Scanned copy of your passport(s) and residency card, showing both sides.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("For Czech citizens, scanned personal ID from both sides, along with a secondary ID such as a driver's license or passport.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("Non-EU citizens with a Czech residency in the form of a book should also include pages 4-5 displaying the validity of the ID.", 'finanzia'); ?>
                                        </li>
                                    </ul>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                      <span class="file-upload__ico">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                           fill="none">
                                      <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                            stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                      </svg>
                                      </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[rental_income][identification_documents][]" type="file"
                                               multiple
                                               class="file-upload__input file-upload__all">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Rental Contract", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Scanned copy of the rental contract between you and the tenant who is renting your property, resulting in rental income", 'finanzia'); ?>
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[rental_income][rental_contract][]" type="file" multiple
                                               class="file-upload__input file-upload__all">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Personal Tax Returns", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Scanned copy of your complete tax returns for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-2 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 20 20" fill="none">
                                            <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                  stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                  stroke-linejoin="round"/>
                                            </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[rental_income][personal_tax_returns_year_before_last][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Scanned copy of your complete tax returns for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-1 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[rental_income][personal_tax_returns_last_year][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Confirmation of Tax Return Submission", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Confirmation of submitting your tax returns for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-2 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[rental_income][confirmation_tax_return_submission_year_before_last][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Confirmation of submitting your tax returns for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-1 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[rental_income][confirmation_tax_return_submission_last_year][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Confirmation of Tax Payment", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Confirmation of the payment of taxes for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-2 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[rental_income][tax_payment_confirmation_for_year_before_last][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Confirmation of the payment of taxes for the year", 'finanzia'); ?>
                                        &nbsp;<?= date("Y", strtotime("-1 year")); ?>.
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[rental_income][tax_payment_confirmation_for_last_year][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Bank Statements", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__notes">
                                        <?php _e("It is preferable to have each month's statement in separate PDF files.", 'finanzia'); ?>
                                        <br>
                                        <?php _e("Download these statements from your internet banking for convenience.", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Last 6 months of your bank statements in PDF format.", 'finanzia'); ?>
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[rental_income][bank_statements][]" type="file" multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="big-form__tab-4" class="tab">
                                <?php if ($calc->credit_type == "refinancing"): ?>
                                    <div class="documents">
                                        <div class="documents__title">
                                            <?php _e("Original Mortgage Contract", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__text">
                                            <?php _e("Scanned copy of original signed mortgage contract", 'finanzia'); ?>
                                        </div>
                                        <div class="file-upload">
                                            <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                                <span class="file-upload__title"
                                                      data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                      data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                                <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                            </label>
                                            <input data-text="<?php _e("file", 'finanzia'); ?>"
                                                   data-texts="<?php _e("files", 'finanzia'); ?>"
                                                   name="step_three[income_from_abroad][original_mortgage_contract][]"
                                                   type="file" multiple
                                                   class="file-upload__input file-upload__all">
                                            <button type="button"
                                                    class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                            <div class="file-upload-error">
                                                <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="documents">
                                        <div class="documents__title">
                                            <?php _e("Mortgage Bank Statement", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__notes">
                                            <?php _e("The statement should display the remaining balance on your mortgage.", 'finanzia'); ?>
                                        </div>
                                        <div class="documents__text">
                                            <?php _e("The most recent bank statement of your mortgage account", 'finanzia'); ?>
                                        </div>
                                        <div class="file-upload">
                                            <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                                <span class="file-upload__title"
                                                      data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                      data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                                <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                            </label>
                                            <input data-text="<?php _e("file", 'finanzia'); ?>"
                                                   data-texts="<?php _e("files", 'finanzia'); ?>"
                                                   name="step_three[income_from_abroad][mortgage_bank_statement][]"
                                                   type="file" multiple
                                                   class="file-upload__input file-upload__all">
                                            <button type="button"
                                                    class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                            <div class="file-upload-error">
                                                <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Identification Documents", 'finanzia'); ?>
                                    </div>
                                    <ul class="documents__list">
                                        <li>
                                            <?php _e("Scanned copy of your passport(s) and residency card, showing both sides.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("For Czech citizens, scanned personal ID from both sides, along with a secondary ID such as a driver's license or passport.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("Non-EU citizens with a Czech residency in the form of a book should also include pages 4-5 displaying the validity of the ID.", 'finanzia'); ?>
                                        </li>
                                    </ul>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                      <span class="file-upload__ico">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                           fill="none">
                                      <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                            stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                      </svg>
                                      </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[income_from_abroad][identification_documents][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__all">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Bank Statements", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__notes">
                                        <?php _e("It is preferable to have each month's statement in separate PDF files.", 'finanzia'); ?>
                                        <br>
                                        <?php _e("Download these statements from your internet banking for convenience.", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Last 6 months of your bank statements in PDF format.", 'finanzia'); ?>
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[income_from_abroad][bank_statements][]" type="file"
                                               multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Income Certificate", 'finanzia'); ?>
                                    </div>
                                    <ol class="documents__list-num">
                                        <li>
                                            <?php _e("Obtain the income certificate specific to the bank where you intend to secure the mortgage from our website.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("Send the certificate to your employer (HR or accounting department) for completion.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("Once filled, collect the forms and add your signature if required.", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("While scanned copies are acceptable at this stage, please note that originals will be required later, including both your and your employer's signatures (preferably in blue pen).", 'finanzia'); ?>
                                        </li>
                                        <li>
                                            <?php _e("Some banks may require you to co-sign, in which case, please use the same date as your employer.", 'finanzia'); ?>
                                        </li>
                                    </ol>
                                    <!--                                    <div class="documents__text">-->
                                    <!--                                        --><?php //_e("Upload your Income Certificate", 'finanzia'); ?>
                                    <!--                                    </div>-->
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                      <span class="file-upload__ico">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                           fill="none">
                                      <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                            stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                      </svg>
                                      </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("Only PDF", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[income_from_abroad][income_certificate][]" type="file"
                                               multiple
                                               class="file-upload__input file-upload__pdf">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="documents">
                                    <div class="documents__title">
                                        <?php _e("Work Contract", 'finanzia'); ?>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Scanned copy of your signed work contract", 'finanzia'); ?>
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[income_from_abroad][work_contract][]" type="file"
                                               multiple
                                               class="file-upload__input file-upload__all">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                    <div class="documents__text">
                                        <?php _e("Include any recent amendments if applicable", 'finanzia'); ?>
                                    </div>
                                    <div class="file-upload">
                                        <label class="file-upload__label">
                                          <span class="file-upload__ico">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                               viewBox="0 0 20 20" fill="none">
                                          <path d="M6.66797 13.3333L10.0013 10M10.0013 10L13.3346 13.3333M10.0013 10V17.5M16.668 13.9524C17.6859 13.1117 18.3346 11.8399 18.3346 10.4167C18.3346 7.88536 16.2826 5.83333 13.7513 5.83333C13.5692 5.83333 13.3989 5.73833 13.3064 5.58145C12.2197 3.73736 10.2133 2.5 7.91797 2.5C4.46619 2.5 1.66797 5.29822 1.66797 8.75C1.66797 10.4718 2.36417 12.0309 3.49043 13.1613"
                                                stroke="#DD6B3A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"/>
                                          </svg>
                                          </span>
                                            <span class="file-upload__title"
                                                  data-first="<?php _e("Click to upload", 'finanzia'); ?>"
                                                  data-second="<?php _e("or drag and drop", 'finanzia'); ?>"><span><?php _e("Click to upload", 'finanzia'); ?></span>&nbsp;<?php _e("or drag and drop", 'finanzia'); ?></span>
                                            <span class="file-upload__format"><?php _e("PDF, PNG or JPG", 'finanzia'); ?></span>
                                        </label>
                                        <input data-text="<?php _e("file", 'finanzia'); ?>"
                                               data-texts="<?php _e("files", 'finanzia'); ?>"
                                               name="step_three[income_from_abroad][any_recent_amendments][]"
                                               type="file" multiple
                                               class="file-upload__input file-upload__all">
                                        <button type="button"
                                                class="clear-files-btn"><?php _e("Clear Files", 'finanzia'); ?></button>
                                        <div class="file-upload-error">
                                            <?php _e("Please upload PDF, PNG or JPG!", 'finanzia'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="big-form__btn big-form__btn--confirm" type="submit">
                        <?php _e("SUBMIT DOCUMENTS", 'finanzia'); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        var filesText = "<?php _e("Files to upload", 'finanzia'); ?>";
    </script>
<?php
get_footer("steps");
