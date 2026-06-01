<?php
function calculator_inbox_step_status_meta_box($post)
{
    $post->status = get_post_meta($post->ID, '_calc_status', true);
    ?>
    <div class="submitbox" id="submitinbox">
        <div id="minor-publishing">
            <div id="misc-publishing-actions">
                <!-- TODO disabled="disabled" -->
                <fieldset class="misc-pub-section" id="comment-status-radio">
                    <legend class="screen-reader-text"><?php echo esc_html(__('Request status', 'calculator')); ?></legend>
                    <label>
                        <input type="radio"<?php checked(-1, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="-1"/>
                        <?php echo esc_html(__('Denied', 'calculator')); ?>
                    </label>
                    <br/>
                    <label>
                        <input type="radio"<?php checked(0, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="0"/>
                        <?php echo esc_html(__('Unknown request', 'calculator')); ?>
                    </label>
                    <br/>
                    <label>
                        <input type="radio"<?php checked(1, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="1"/>
                        <?php echo esc_html(__('User registered', 'calculator')); ?>
                    </label>


                    <br/>
                    <label>
                        <input type="radio"<?php checked(2, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="2"/>
                        <?php echo esc_html(__('Finish step 1', 'calculator')); ?>
                    </label>
                    <br/>
                    <label>
                        <input type="radio"<?php checked(3, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="3"/>
                        <?php echo esc_html(__('Waiting start step 2', 'calculator')); ?>
                    </label>
                    <br/>
                    <label>
                        <input type="radio"<?php checked(4, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="4"/>
                        <?php echo esc_html(__('Finish step 2', 'calculator')); ?>
                    </label>
                    <br/>
                    <label>
                        <input type="radio"<?php checked(5, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="5"/>
                        <?php echo esc_html(__('Waiting start step 3', 'calculator')); ?>
                    </label>
                    <br/>
                    <label>
                        <input type="radio"<?php checked(6, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="6"/>
                        <?php echo esc_html(__('Finish step 3', 'calculator')); ?>
                    </label>

                    <!--                    <br/>-->
                    <!--                    <label>-->
                    <!--                        <input type="radio"--><?php //checked(8, $post->status);
                    ?><!-- name="inbox[status]"-->
                    <!--                               disabled="disabled" value="8"/>-->
                    <!--                        --><?php //echo esc_html(__('User registered in aio', 'calculator'));
                    ?>
                    <!--                    </label>-->
                    <br/>
                    <label>
                        <input type="radio"<?php checked(9, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="9"/>
                        <?php echo esc_html(__('Finish All-In-One', 'calculator')); ?>
                    </label>
                    <br/>
                    <label>
                        <input type="radio"<?php checked(10, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="10"/>
                        <?php echo esc_html(__('Waiting Documents', 'calculator')); ?>
                    </label>
                    <br/>
                    <label>
                        <input type="radio"<?php checked(11, $post->status); ?> name="inbox[status]"
                               disabled="disabled" value="11"/>
                        <?php echo esc_html(__('Finish Documents', 'calculator')); ?>
                    </label>
                </fieldset>

                <div class="misc-pub-section curtime misc-pub-curtime">
                    <span id="timestamp">
                        <?php
                        $submitted_timestamp = get_post_timestamp($post->ID);

                        $submitted_on = sprintf(
                        /* translators: Publish box date string. 1: Date, 2: Time. */
                                __('%1$s at %2$s', 'calculator'),
                                wp_date(
                                /* translators: Publish box date format, see https://www.php.net/date */
                                        _x('M j, Y', 'publish box date format', 'calculator'),
                                        $submitted_timestamp
                                ),
                                wp_date(
                                /* translators: Publish box time format, see https://www.php.net/date */
                                        _x('H:i', 'publish box time format', 'calculator'),
                                        $submitted_timestamp
                                )
                        );

                        echo sprintf(
                        /* translators: %s: message submission date */
                                esc_html(__('Submitted on: %s', 'calculator')),
                                '<b>' . esc_html($submitted_on) . '</b>'
                        );
                        ?>
                    </span>
                </div>
                <?php
                if (!empty($post->submission_status)) {
                    echo '<div class="misc-pub-section submission-status">', "\n";

                    $submission_status = sprintf(
                    /* translators: %s: Result of the submission. */
                            esc_html(__('Submission result: %s', 'calculator')),
                            sprintf('<b>%s</b>', esc_html($post->submission_status))
                    );

                    echo sprintf(
                            '<span class="dashicons-before %1$s"> %2$s</span>',
                            in_array($post->submission_status, array('mail_failed', 'spam'))
                                    ? 'dashicons-no' : 'dashicons-yes',
                            $submission_status
                    );

                    echo '</div>', "\n";
                }
                ?>
            </div><!-- #misc-publishing-actions -->

            <div class="clear"></div>
        </div><!-- #minor-publishing -->

        <div id="major-publishing-actions">
            <div id="delete-action">
                <?php
                if (current_user_can('calculator_edit_inbox', $post->ID)) {
                    if ($post->post_status === 'trash') {


                        $restore_text = __('Restore', 'calculator');

                        $restore_link = add_query_arg(
                                array(
                                        'post'   => $post->ID,
                                        'action' => 'untrash',
                                ),
                                menu_page_url('calculator-inbox', false)
                        );

                        $restore_link = wp_nonce_url(
                                $restore_link,
                                'calculator-untrash-inbox-message_' . $post->ID
                        );

                        echo sprintf('<a href="%1$s" class="submitdelete deletion">%2$s</a>',
                                esc_url($restore_link),
                                esc_html($restore_text)
                        );

                    } else {
                        if (!EMPTY_TRASH_DAYS) {
                            $delete_text = __('Delete permanently', 'calculator');
                        } else {
                            $delete_text = __('Move to trash', 'calculator');
                        }

                        $delete_link = add_query_arg(
                                array(
                                        'post'   => $post->ID,
                                        'action' => 'trash',
                                ),
                                menu_page_url('calculator-inbox', false)
                        );

                        $delete_link = wp_nonce_url(
                                $delete_link,
                                'calculator-trash-inbox-message_' . $post->ID
                        );

                        echo sprintf('<a href="%1$s" class="submitdelete deletion">%2$s</a>',
                                esc_url($delete_link),
                                esc_html($delete_text)
                        );
                    }
                }
                ?>
            </div>

            <div id="publishing-action">
                <?php
                submit_button(__('Update', 'calculator'), 'primary large', 'save', false);
                ?>
            </div>

            <div class="clear"></div>
        </div><!-- #major-publishing-actions -->
    </div>
    <?php
}

function calculator_inbox_verdict_meta_box($post)
{

    $post->step_one_user_email_time     = get_post_meta($post->ID, '_step_one_user_email_time', true);
    $post->step_one_user_email_template = get_post_meta($post->ID, '_step_one_user_email_template', true);

    ?>
    <div class="submitbox">
        <div>
            <div>
                <table style="width: 100%;">
                    <tr>
                        <th>
                            <h3>
                                Step 1 verdict
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <?php if ((int)$post->step_one_user_email_time > 0) : ?>
                                <fieldset class="misc-pub-section" data-nonce="<?= wp_create_nonce('calc-nonce') ?>">
                                    <div>
                                        <button type="button" class="button button-primary button-large"
                                                <?= ($post->step_one_user_email_template == 'step_one_approved' ? '' : ' disabled="disabled"'); ?>
                                                style="background-color:green; width: 100%">
                                            Approved
                                        </button>
                                    </div>
                                    <br>
                                    <div>
                                        <button type="button" class="button button-primary button-large"
                                                <?= ($post->step_one_user_email_template == 'step_one_denied' ? '' : ' disabled="disabled"'); ?>
                                                style="background-color:red; width: 100%">
                                            Denied
                                        </button>
                                    </div>
                                </fieldset>
                            <?php else: ?>
                                <fieldset class="misc-pub-section" data-nonce="<?= wp_create_nonce('calc-nonce') ?>">
                                    <div>
                                        <button type="button" data-verdict="approve"
                                                class="button button-primary button-large"
                                                style="background-color:green; width: 100%">
                                            Approve
                                        </button>
                                    </div>
                                    <br>
                                    <div>
                                        <button type="button" data-verdict="reject"
                                                class="button button-primary button-large"
                                                style="background-color:red; width: 100%">
                                            Reject
                                        </button>
                                    </div>
                                </fieldset>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id="verdict_wrap" style="text-align: center;"
                                 class="misc-pub-section curtime misc-pub-curtime">
                                <?php if ((int)$post->step_one_user_email_time > 0) : ?>
                                    <span>
                                    <?php
                                    $submitted_timestamp = (int)$post->step_one_user_email_time;

                                    $submitted_on = sprintf(
                                    /* translators: Publish box date string. 1: Date, 2: Time. */
                                            __('%1$s at %2$s', 'calculator'),
                                            wp_date(
                                            /* translators: Publish box date format, see https://www.php.net/date */
                                                    _x('M j, Y', 'publish box date format', 'calculator'),
                                                    $submitted_timestamp
                                            ),
                                            wp_date(
                                            /* translators: Publish box time format, see https://www.php.net/date */
                                                    _x('H:i', 'publish box time format', 'calculator'),
                                                    $submitted_timestamp
                                            )
                                    );

                                    echo sprintf(
                                    /* translators: %s: message submission date */
                                            esc_html(__('Submitted on: %s', 'calculator')),
                                            '<b>' . esc_html($submitted_on) . '</b>'
                                    );
                                    ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="clear"></div>
        </div><!-- #minor-publishing -->
    </div>
    <?php
}

function calculator_inbox_broker_meta_box($post)
{
    $post->brokers         = get_field('brokers', 'options');
    $post->selected_broker = get_post_meta($post->ID, '_selected_broker', true);

    $post->broker_email_time = get_post_meta($post->ID, '_broker_email_time', true);

    ?>
    <div class="submitbox">
        <div>
            <div>
                <table style="width: 100%; text-align: center;">
                    <?php if (!$post->selected_broker): ?>
                        <tr>
                            <th>
                                <h3>
                                    Choice broker for this request
                                </h3>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <?php if ($post->brokers) : ?>
                                    <select name="broker_email" style="width: 100%">
                                        <option value="">Choice broker</option>
                                        <?php foreach ($post->brokers as $broker) : ?>
                                            <option value="<?= $broker['broker_email'] ?>"><?= $broker['broker_email'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php if ($post->brokers) : ?>
                                    <button id="js_choice_broker" type="button" style="width: 100%; margin-top: 10px;"
                                            data-nonce="<?= wp_create_nonce('broker-nonce') ?>"
                                            class="button button-primary button-large">
                                        Send data from step 1
                                    </button>
                                    <div id="verdict_wrap" style="text-align: center;"></div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td>
                                Selected brocker is:
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= $post->selected_broker; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id="broker_wrap" style="text-align: center;"
                                     class="misc-pub-section curtime misc-pub-curtime">
                                    <?php if ((int)$post->broker_email_time > 0) : ?>
                                        <span>
                                    <?php
                                    $submitted_timestamp = (int)$post->broker_email_time;

                                    $submitted_on = sprintf(
                                    /* translators: Publish box date string. 1: Date, 2: Time. */
                                            __('%1$s at %2$s', 'calculator'),
                                            wp_date(
                                            /* translators: Publish box date format, see https://www.php.net/date */
                                                    _x('M j, Y', 'publish box date format', 'calculator'),
                                                    $submitted_timestamp
                                            ),
                                            wp_date(
                                            /* translators: Publish box time format, see https://www.php.net/date */
                                                    _x('H:i', 'publish box time format', 'calculator'),
                                                    $submitted_timestamp
                                            )
                                    );

                                    echo sprintf(
                                    /* translators: %s: message submission date */
                                            esc_html(__('Submitted on: %s', 'calculator')),
                                            '<b>' . esc_html($submitted_on) . '</b>'
                                    );
                                    ?>
                                </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
            <div class="clear"></div>
        </div><!-- #minor-publishing -->
    </div>
    <?php
}

function calculator_inbox_mini_calc_results_meta_box($post)
{
    $post->fields        = get_post_meta($post->ID);
    $post->currency      = strtoupper($post->fields['_mini_calc_currency'][0]) ?? '';
    $post->currency_rate = (float)calculator_htmlize($post->fields['_mini_calc_currency_rate']);
    $post->currency_rate = $post->currency_rate > 0 ? $post->currency_rate : 1;

    if ($_COOKIE['debug'] == 123) {
        dd($post);
    }
    ?>

    <table class="widefat message-fields striped">
        <tr>
            <td class="field-title"><?php _e("Credit type", 'finanzia'); ?></td>
            <td class="field-value"><?php echo Calculator::translate_credit_type(calculator_htmlize($post->fields['_mini_calc_credit_type'])); ?></td>
        </tr>
        <tr>
            <td class="field-title"><?php _e("Currency", 'finanzia'); ?></td>
            <td class="field-value"><?php echo strtoupper(calculator_htmlize($post->fields['_mini_calc_currency'])); ?></td>
        </tr>
        <tr>
            <td class="field-title"><?php _e("Currency rate", 'finanzia'); ?></td>
            <td class="field-value"><?php echo calculator_htmlize($post->fields['_mini_calc_currency_rate']); ?> CZK
            </td>
        </tr>
        <?php if (calculator_htmlize($post->fields['_mini_calc_duration']) != 0): ?>
            <tr>
                <td class="field-title"><?php _e("Duration", 'finanzia'); ?></td>
                <td class="field-value"><?php echo calculator_htmlize($post->fields['_mini_calc_duration']); ?> Years
                </td>
            </tr>
        <?php endif; ?>
        <?php if (calculator_htmlize($post->fields['_mini_calc_loan_amount']) != 0): ?>
            <tr>
                <td class="field-title"><?php _e("Loan amount", 'finanzia'); ?></td>
                <td class="field-value"><?php echo number_format((float)calculator_htmlize($post->fields['_mini_calc_loan_amount']) / $post->currency_rate); ?>
                    CZK
                </td>
            </tr>
        <?php endif; ?>
        <?php if (calculator_htmlize($post->fields['_mini_calc_total_payment_amount']) != 0): ?>
            <tr>
                <td class="field-title"><?php _e("Total payment", 'finanzia'); ?></td>
                <td class="field-value"><?php echo number_format((float)calculator_htmlize($post->fields['_mini_calc_total_payment_amount']) / $post->currency_rate); ?>
                    CZK
                </td>
            </tr>
        <?php endif; ?>
        <?php if (calculator_htmlize($post->fields['_mini_calc_interest_rate']) != 0): ?>
            <tr>
                <td class="field-title"><?php _e("Interest rate", 'finanzia'); ?></td>
                <td class="field-value"><?php echo calculator_htmlize($post->fields['_mini_calc_interest_rate']); ?>%
                </td>
            </tr>
        <?php endif; ?>
        <?php if (calculator_htmlize($post->fields['_mini_calc_rpsn']) != 0): ?>
            <tr>
                <td class="field-title"><?php _e("RPSN", 'finanzia'); ?></td>
                <td class="field-value"><?php echo calculator_htmlize($post->fields['_mini_calc_rpsn']); ?> %</td>
            </tr>
        <?php endif; ?>
        <?php if (calculator_htmlize($post->fields['_mini_calc_insurance_payment']) != 0): ?>
            <tr>
                <td class="field-title"><?php _e("Insurance payment", 'finanzia'); ?></td>
                <td class="field-value"><?php echo number_format((float)calculator_htmlize($post->fields['_mini_calc_insurance_payment']) / $post->currency_rate); ?>
                    CZK
                </td>
            </tr>
        <?php endif; ?>
        <?php if (calculator_htmlize($post->fields['_mini_calc_monthly_payment']) != 0): ?>
            <tr>
                <td class="field-title"><?php _e("Monthly payment", 'finanzia'); ?></td>
                <td class="field-value"><?php echo number_format((float)calculator_htmlize($post->fields['_mini_calc_monthly_payment']) / $post->currency_rate); ?>
                    CZK
                </td>
            </tr>
        <?php endif; ?>
        <?php
        $utm_labels = [
            '_calc_utm_source'   => __('UTM Source', 'calculator'),
            '_calc_utm_medium'   => __('UTM Medium', 'calculator'),
            '_calc_utm_campaign' => __('UTM Campaign', 'calculator'),
            '_calc_utm_term'     => __('UTM Term', 'calculator'),
            '_calc_utm_content'  => __('UTM Content', 'calculator'),
        ];
        foreach ($utm_labels as $meta_key => $label):
            $val = isset($post->fields[$meta_key][0]) ? $post->fields[$meta_key][0] : '';
            if (!empty($val)):
        ?>
            <tr>
                <td class="field-title"><?php echo esc_html($label); ?></td>
                <td class="field-value"><?php echo esc_html($val); ?></td>
            </tr>
        <?php
            endif;
        endforeach;
        ?>
    </table>
    <?php
}

function calculator_inbox_form_step_one_meta_box($post)
{

    $post->calc_user_lang = get_post_meta($post->ID, '_calc_user_lang', true);

    $post->relationship_type = get_post_meta($post->ID, '_relationship_type', true);
    $post->relationship_who  = get_post_meta($post->ID, '_relationship_who', true);

    $post->property         = get_post_meta($post->ID, '_step_one_property', true);
    $post->main_applicant   = get_post_meta($post->ID, '_step_one_main_applicant', true);
    $post->second_applicant = get_post_meta($post->ID, '_step_one_second_applicant', true);

    if ($calc_user_id = get_post_meta($post->ID, '_calc_user', true)) {
        $user = get_userdata($calc_user_id);
    }

    ?>
    <table class="widefat message-fields striped">
        <tr>
            <td class="field-title"><?php _e("Applying", 'finanzia'); ?></td>
            <td class="field-value"><?= ($post->relationship_type == 'alone' ?: 'with ' . $post->relationship_who); ?></td>
        </tr>
        <tr>
            <td class="field-title"><?php _e("Type of Property", 'finanzia'); ?></td>
            <td class="field-value">
                <?= esc_html($post->property['type'] ?? ''); ?>
                <?php if (isset($post->property['type']) && $post->property['type'] == 'Flat') : ?>
                    (<?= esc_html($post->property['variant']); ?>)
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td class="field-title"><?php _e("Additional Information", 'finanzia'); ?></td>
            <td class="field-value">
                <?= esc_html($post->property['additional_information'] ?? ''); ?>
            </td>
        </tr>
    </table>
    <h3>Main applicant</h3>
    <table class="widefat message-fields striped">
        <tr>
            <td class="field-title"><?php _e("First name", 'finanzia'); ?></td>
            <td class="field-value"><?= esc_html($post->main_applicant['first_name'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Last name</td>
            <td class="field-value"><?= esc_html($post->main_applicant['last_name'] ?? ''); ?></td>
        </tr>
        <?php if ($user->user_registered) : ?>
            <tr>
                <td class="field-title"><?php _e("Terms and Conditions and Privacy Policy Acceptance Date/Time", 'finanzia'); ?></td>
                <td class="field-value"><?= esc_html($user->user_registered); ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td class="field-title">Date of birth</td>
            <td class="field-value"><?= esc_html($post->main_applicant['dob'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Phone</td>
            <td class="field-value"><?= esc_html($post->main_applicant['phone'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Length of residency in Czech Republic</td>
            <td class="field-value"><?= esc_html($post->main_applicant['length_of_residency'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Nationality</td>
            <td class="field-value"><?= esc_html($post->main_applicant['main_nationality'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Residency type in Czech Republic</td>
            <td class="field-value"><?= esc_html($post->main_applicant['residency_type'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Birth registration number (Rodné číslo)</td>
            <td class="field-value"><?= esc_html($post->main_applicant['passport_registration_number'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Other passports</td>
            <td class="field-value"><?= esc_html($post->main_applicant['other_nationalities'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Bank you currently use in Czech Republic</td>
            <td class="field-value"><?= esc_html($post->main_applicant['current_bank'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">When did you start earning income in Czech Republic?</td>
            <td class="field-value"><?= esc_html($post->main_applicant['first_income'] ?? ''); ?></td>
        </tr>
    </table>
    <h3>Income type</h3>
    <table class="widefat message-fields striped">
        <?php if (isset($post->main_applicant['income_types']) && is_countable($post->main_applicant['income_types'])): ?>
            <?php foreach ($post->main_applicant['income_types'] as $key => $income_type) : ?>
                <?php foreach ($income_type as $item) : ?>
                    <?php switch ($key):
                        case 'full_time_employee': ?>
                            <tr>
                                <th class="" colspan="2">Full-time employee</th>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly net income — CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Income start date</td>
                                <td class="field-value"><?= get_months_name($item['income_start_date']['month']); ?> <?= (int)$item['income_start_date']['year']; ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Work experience duration</td>
                                <td class="field-value"><?= get_months_name($item['work_experience_duration']['month']); ?> <?= (int)$item['work_experience_duration']['year']; ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'self_employed': ?>
                            <tr>
                                <th class="" colspan="2">Self-employed</th>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly net income — CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Business identification number (IČO)</td>
                                <td class="field-value"><?= esc_html($item['business_identification_number']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Trade license start date</td>
                                <td class="field-value"><?= get_months_name($item['trade_license_start_date']['month']); ?> <?= (int)$item['trade_license_start_date']['year']; ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Annual gross income for the previous year</td>
                                <td class="field-value"><?= number_format((float)$item['annual_gross_income_previous_year']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Work experience duration</td>
                                <td class="field-value"><?= get_months_name($item['work_experience_duration']['month']); ?> <?= (int)$item['work_experience_duration']['year']; ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Field of specialization</td>
                                <td class="field-value"><?= esc_html($item['specialization']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'rental_income': ?>
                            <tr>
                                <th class="" colspan="2">Rental income</th>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly net income — CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Gross rent (including utilities) — CZK</td>
                                <td class="field-value"><?= number_format((float)$item['gross_rent']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Net rent (excluding utilities) — CZK</td>
                                <td class="field-value"><?= number_format((float)$item['net_rent']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Utility and service charges — CZK</td>
                                <td class="field-value"><?= number_format((float)$item['utility_service_charges']); ?></td>
                            </tr>
                            <!--                        <tr>-->
                            <!--                            <td class="field-title">When did you start earning income in Czech Republic?</td>-->
                            <!--                            <td class="field-value">--><?php //= get_months_name($item['income_start_date']['month']); ?><!-- --><?php //= (int)$item['income_start_date']['year']; ?><!--</td>-->
                            <!--                        </tr>-->
                            <tr>
                                <td class="field-title">Is this income already included in your tax return?</td>
                                <td class="field-value"><?= esc_html($item['income_included_in_tax']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Payment method</td>
                                <td class="field-value"><?= esc_html($item['payment_method']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'business': ?>
                            <tr>
                                <th class="" colspan="2">Business</th>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly net income — CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Please provide a brief description of what your company does
                                </td>
                                <td class="field-value"><?= esc_html($item['what_company_does']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Method of extracting funds from the company</td>
                                <td class="field-value"><?= esc_html($item['method_extracting_funds_from_company']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Trade license start date</td>
                                <td class="field-value"><?= get_months_name($item['trade_license_start_date']['month']); ?> <?= (int)$item['trade_license_start_date']['year']; ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">What percentage of the company do you own?</td>
                                <td class="field-value"><?= esc_html($item['percentage_of_company_own']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Income start date</td>
                                <td class="field-value"><?= get_months_name($item['income_start_date']['month']); ?> <?= (int)$item['income_start_date']['year']; ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'income_from_abroad': ?>
                            <tr>
                                <th class="" colspan="2">Income from abroad</th>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly net income — CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Type of income from abroad</td>
                                <td class="field-value"><?= esc_html($item['type_of_income_from_abroad']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Income start date</td>
                                <td class="field-value"><?= get_months_name($item['income_start_date']['month']); ?> <?= (int)$item['income_start_date']['year']; ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Experience duration</td>
                                <td class="field-value"><?= get_months_name($item['experience_duration']['month']); ?> <?= (int)$item['experience_duration']['year']; ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'other': ?>
                            <tr>
                                <th class="" colspan="2">Other</th>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly net income — CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Income start date</td>
                                <td class="field-value"><?= get_months_name($item['income_start_date']['month']); ?> <?= (int)$item['income_start_date']['year']; ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Experience duration</td>
                                <td class="field-value"><?= get_months_name($item['experience_duration']['month']); ?> <?= (int)$item['experience_duration']['year']; ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional information</td>
                                <td class="field-value"><?= esc_html($item['work_experience_duration']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php endswitch; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
    <h3>Existing liabilities</h3>
    <table class="widefat message-fields striped">
        <?php if (isset($post->main_applicant['existing_liabilities']) && is_countable($post->main_applicant['existing_liabilities'])): ?>
            <?php foreach ($post->main_applicant['existing_liabilities'] as $key => $existing_liability) : ?>
                <?php foreach ($existing_liability as $item) : ?>
                    <?php switch ($key):
                        case 'liability_type_loan': ?>
                            <tr>
                                <th class="" colspan="2">Loan</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">What is the product's interest rate (%)?</td>
                                <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly payment amount - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Remaining balance - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_mortgage': ?>
                            <tr>
                                <th class="" colspan="2">Mortgage</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">What is the product's interest rate (%)?</td>
                                <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly payment amount - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Remaining balance - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_credit_card': ?>
                            <tr>
                                <th class="" colspan="2">Credit card</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Maximum credit limit you can get on your credit card</td>
                                <td class="field-value"><?= esc_html($item['max_credit_limit']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_overdraft': ?>
                            <tr>
                                <th class="" colspan="2">Overdraft</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr><s></s>
                            <tr>
                                <td class="field-title">Maximum limit amount</td>
                                <td class="field-value"><?= esc_html($item['max_credit_limit']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_no_liabilities': ?>
                            <tr>
                                <th class="" colspan="2">No liabilities</th>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_other': ?>
                            <tr>
                                <th class="" colspan="2">Other</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">What is the product's interest rate (%)?</td>
                                <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly payment amount - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Remaining balance - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php endswitch; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <?php if ($post->relationship_type == 'someelse'): ?>
    <h3>Second applicant</h3>
    <table class="widefat message-fields striped">
        <tr>
            <td class="field-title">First name</td>
            <td class="field-value"><?= esc_html($post->second_applicant['first_name']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Last name</td>
            <td class="field-value"><?= esc_html($post->second_applicant['last_name']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Date of birth</td>
            <td class="field-value"><?= esc_html($post->second_applicant['dob']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Phone</td>
            <td class="field-value"><?= esc_html($post->second_applicant['phone']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Length of residency in Czech Republic</td>
            <td class="field-value"><?= esc_html($post->second_applicant['length_of_residency']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Nationality</td>
            <td class="field-value"><?= esc_html($post->second_applicant['main_nationality']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Residency type in Czech Republic</td>
            <td class="field-value"><?= esc_html($post->second_applicant['residency_type']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Birth registration number (Rodné číslo)</td>
            <td class="field-value"><?= esc_html($post->second_applicant['passport_registration_number']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Other passports</td>
            <td class="field-value"><?= esc_html($post->second_applicant['other_nationalities']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Bank you currently use in Czech Republic</td>
            <td class="field-value"><?= esc_html($post->second_applicant['current_bank']); ?></td>
        </tr>
        <tr>
            <td class="field-title">When did you start earning income in Czech Republic?</td>
            <td class="field-value"><?= esc_html($post->second_applicant['first_income']); ?></td>
        </tr>
    </table>
    <h3>Income types</h3>
    <table class="widefat message-fields striped">
        <?php foreach ($post->second_applicant['income_types'] as $key => $income_type) : ?>
            <?php foreach ($income_type as $item) : ?>
                <?php switch ($key):
                    case 'full_time_employee': ?>
                        <tr>
                            <th class="" colspan="2">Full-time employee</th>
                        </tr>
                        <tr>
                            <td class="field-title">Monthly net income — CZK</td>
                            <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Income start date</td>
                            <td class="field-value"><?= get_months_name($item['income_start_date']['month']); ?> <?= (int)$item['income_start_date']['year']; ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Work experience duration</td>
                            <td class="field-value"><?= get_months_name($item['work_experience_duration']['month']); ?> <?= (int)$item['work_experience_duration']['year']; ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional information</td>
                            <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php case 'self_employed': ?>
                        <tr>
                            <th class="" colspan="2">Self-employed</th>
                        </tr>
                        <tr>
                            <td class="field-title">Monthly net income — CZK</td>
                            <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Business identification number (IČO)</td>
                            <td class="field-value"><?= esc_html($item['business_identification_number']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Trade license start date</td>
                            <td class="field-value"><?= get_months_name($item['trade_license_start_date']['month']); ?> <?= (int)$item['trade_license_start_date']['year']; ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Annual gross income for the previous year</td>
                            <td class="field-value"><?= number_format((float)$item['annual_gross_income_previous_year']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Work experience duration</td>
                            <td class="field-value"><?= get_months_name($item['work_experience_duration']['month']); ?> <?= (int)$item['work_experience_duration']['year']; ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Field of specialization</td>
                            <td class="field-value"><?= esc_html($item['specialization']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional information</td>
                            <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php case 'rental_income': ?>
                        <tr>
                            <th class="" colspan="2">Rental income</th>
                        </tr>
                        <tr>
                            <td class="field-title">Monthly net income — CZK</td>
                            <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Gross rent (including utilities) — CZK</td>
                            <td class="field-value"><?= number_format((float)$item['gross_rent']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Net rent (excluding utilities) — CZK</td>
                            <td class="field-value"><?= number_format((float)$item['net_rent']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Utility and service charges — CZK</td>
                            <td class="field-value"><?= number_format((float)$item['utility_service_charges']); ?></td>
                        </tr>
                        <!--                        <tr>-->
                        <!--                            <td class="field-title">When did you start earning income in Czech Republic?</td>-->
                        <!--                            <td class="field-value">--><?php //= get_months_name($item['income_start_date']['month']); ?><!-- --><?php //= (int)$item['income_start_date']['year']; ?><!--</td>-->
                        <!--                        </tr>-->
                        <tr>
                            <td class="field-title">Is this income already included in your tax return?</td>
                            <td class="field-value"><?= esc_html($item['income_included_in_tax']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Payment method</td>
                            <td class="field-value"><?= esc_html($item['payment_method']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional information</td>
                            <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php case 'business': ?>
                        <tr>
                            <th class="" colspan="2">Business</th>
                        </tr>
                        <tr>
                            <td class="field-title">Monthly net income — CZK</td>
                            <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Please provide a brief description of what your company does</td>
                            <td class="field-value"><?= esc_html($item['what_company_does']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Method of extracting funds from the company</td>
                            <td class="field-value"><?= esc_html($item['method_extracting_funds_from_company']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Trade license start date</td>
                            <td class="field-value"><?= get_months_name($item['trade_license_start_date']['month']); ?> <?= (int)$item['trade_license_start_date']['year']; ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">What percentage of the company do you own?</td>
                            <td class="field-value"><?= esc_html($item['percentage_of_company_own']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Income start date</td>
                            <td class="field-value"><?= get_months_name($item['income_start_date']['month']); ?> <?= (int)$item['income_start_date']['year']; ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional information</td>
                            <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php case 'income_from_abroad': ?>
                        <tr>
                            <th class="" colspan="2">Income from abroad</th>
                        </tr>
                        <tr>
                            <td class="field-title">Monthly net income — CZK</td>
                            <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Type of income from abroad</td>
                            <td class="field-value"><?= esc_html($item['type_of_income_from_abroad']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Income start date</td>
                            <td class="field-value"><?= get_months_name($item['income_start_date']['month']); ?> <?= (int)$item['income_start_date']['year']; ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Experience duration</td>
                            <td class="field-value"><?= get_months_name($item['experience_duration']['month']); ?> <?= (int)$item['experience_duration']['year']; ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional information</td>
                            <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php case 'other': ?>
                        <tr>
                            <th class="" colspan="2">Other</th>
                        </tr>
                        <tr>
                            <td class="field-title">Monthly net income — CZK</td>
                            <td class="field-value"><?= number_format((float)$item['monthly_net_income']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Income start date</td>
                            <td class="field-value"><?= get_months_name($item['income_start_date']['month']); ?> <?= (int)$item['income_start_date']['year']; ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Experience duration</td>
                            <td class="field-value"><?= get_months_name($item['experience_duration']['month']); ?> <?= (int)$item['experience_duration']['year']; ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional information</td>
                            <td class="field-value"><?= esc_html($item['work_experience_duration']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php endswitch; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
    <h3>Existing liabilities</h3>
    <table class="widefat message-fields striped">
        <?php foreach ($post->second_applicant['existing_liabilities'] as $key => $existing_liability) : ?>
            <?php foreach ($existing_liability as $item) : ?>
                <?php switch ($key):
                    case 'liability_type_loan': ?>
                        <tr>
                            <th class="" colspan="2">Loan</th>
                        </tr>
                        <tr>
                            <td class="field-title">Which company provided the product?</td>
                            <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">What is the product's interest rate (%)?</td>
                            <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Monthly payment amount - CZK</td>
                            <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Remaining balance - CZK</td>
                            <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional Information</td>
                            <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php case 'liability_type_mortgage': ?>
                        <tr>
                            <th class="" colspan="2">Mortgage</th>
                        </tr>
                        <tr>
                            <td class="field-title">Which company provided the product?</td>
                            <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">What is the product's interest rate (%)?</td>
                            <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Monthly payment amount - CZK</td>
                            <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Remaining balance - CZK</td>
                            <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional Information</td>
                            <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php case 'liability_type_credit_card': ?>
                        <tr>
                            <th class="" colspan="2">Credit card</th>
                        </tr>
                        <tr>
                            <td class="field-title">Which company provided the product?</td>
                            <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Maximum credit limit you can get on your credit card</td>
                            <td class="field-value"><?= esc_html($item['max_credit_limit']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional Information</td>
                            <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php case 'liability_type_overdraft': ?>
                        <tr>
                            <th class="" colspan="2">Overdraft</th>
                        </tr>
                        <tr>
                            <td class="field-title">Which company provided the product?</td>
                            <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Maximum limit amount</td>
                            <td class="field-value"><?= esc_html($item['max_credit_limit']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional Information</td>
                            <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php case 'liability_type_no_liabilities': ?>
                        <tr>
                            <th class="" colspan="2">No liabilities</th>
                        </tr>
                        <?php break; ?>
                    <?php case 'liability_type_other': ?>
                        <tr>
                            <th class="" colspan="2">Other</th>
                        </tr>
                        <tr>
                            <td class="field-title">Which company provided the product?</td>
                            <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">What is the product's interest rate (%)?</td>
                            <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Monthly payment amount - CZK</td>
                            <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Remaining balance - CZK</td>
                            <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                        </tr>
                        <tr>
                            <td class="field-title">Additional Information</td>
                            <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                        </tr>
                        <?php break; ?>
                    <?php endswitch; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
    <?php
}

function calculator_inbox_form_step_two_meta_box($post)
{
    $post->main_applicant   = get_post_meta($post->ID, '_step_two_main_applicant', true);
    $post->second_applicant = get_post_meta($post->ID, '_step_two_second_applicant', true);
    ?>
    <h3>Main applicant</h3>
    <table class="widefat message-fields striped">
        <tr>
            <th class="" colspan="2">Personal Information</th>
        </tr>
        <tr>
            <td class="field-title">Gender</td>
            <td class="field-value"><?= esc_html($post->main_applicant['gender'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Marital status</td>
            <td class="field-value"><?= esc_html($post->main_applicant['marital_status'] ?? ''); ?></td>
        </tr>
        <?php if (isset($post->main_applicant['passport'])): ?>
            <tr>
                <td class="field-title">Passport number</td>
                <td class="field-value">
                    <?= sanitize_text_field($post->main_applicant['passport']['number'] ?? ''); ?>
                </td>
            </tr>
            <tr>
                <td class="field-title">Issue date</td>
                <td class="field-value">
                    <?= (int)$post->main_applicant['passport']['issue_date']['day'] . ' ' .
                    get_months_name((int)$post->main_applicant['passport']['issue_date']['month']) . ' ' .
                    (int)$post->main_applicant['passport']['issue_date']['year']; ?>
                </td>
            </tr>
            <tr>
                <td class="field-title">Expiration date</td>
                <td class="field-value">
                    <?= (int)$post->main_applicant['passport']['expiration_date']['day'] . ' ' .
                    get_months_name((int)$post->main_applicant['passport']['expiration_date']['month']) . ' ' .
                    (int)$post->main_applicant['passport']['expiration_date']['year']; ?>
                </td>
            </tr>
            <tr>
                <td class="field-title">Issuing authority</td>
                <td class="field-value">
                    <?= sanitize_text_field($post->main_applicant['passport']['issuing_authority']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if (isset($post->main_applicant['residency'])): ?>
            <tr>
                <td class="field-title">Residency number</td>
                <td class="field-value">
                    <?= sanitize_text_field($post->main_applicant['residency']['number']); ?>
                </td>
            </tr>
            <tr>
                <td class="field-title">Issue date</td>
                <td class="field-value">
                    <?= (int)$post->main_applicant['residency']['issue_date']['day'] . ' ' .
                    get_months_name((int)$post->main_applicant['residency']['issue_date']['month']) . ' ' .
                    (int)$post->main_applicant['residency']['issue_date']['year']; ?>
                </td>
            </tr>
            <tr>
                <td class="field-title">Expiration date</td>
                <td class="field-value">
                    <?= (int)$post->main_applicant['residency']['expiration_date']['day'] . ' ' .
                    get_months_name((int)$post->main_applicant['residency']['expiration_date']['month']) . ' ' .
                    (int)$post->main_applicant['residency']['expiration_date']['year']; ?>
                </td>
            </tr>
            <tr>
                <td class="field-title">Issuing authority</td>
                <td class="field-value">
                    <?= sanitize_text_field($post->main_applicant['residency']['issuing_authority']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <th class="" colspan="2">Additional Information</th>
        </tr>
        <tr>
            <td class="field-title">Country of birth</td>
            <td class="field-value"><?= esc_html($post->main_applicant['country_of_birth'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">City of birth</td>
            <td class="field-value"><?= esc_html($post->main_applicant['city_of_birth'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Education</td>
            <td class="field-value"><?= esc_html($post->main_applicant['education'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Type of housing you currently reside in</td>
            <td class="field-value"><?= esc_html($post->main_applicant['housing'] ?? ''); ?></td>
        </tr>
        <?php if (isset($post->main_applicant['housing']) && $post->main_applicant['housing'] == 'Other'): ?>
            <tr>
                <td class="field-title">Other comments</td>
                <td class="field-value"><?= esc_html($post->main_applicant['housing_comments']); ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td class="field-title">When did you start living there?</td>
            <td class="field-value">
                <?php if (isset($post->main_applicant['housing_start_living'])): ?>
                    <?= (int)$post->main_applicant['housing_start_living']['day'] . ' ' .
                    get_months_name((int)$post->main_applicant['housing_start_living']['month']) . ' ' .
                    (int)$post->main_applicant['housing_start_living']['year']; ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th class="" colspan="2">Other details</th>
        </tr>
        <tr>
            <td class="field-title">Monthly expenses — CZK</td>
            <td class="field-value"><?= number_format((float)($post->main_applicant['monthly_expenses'] ?? 0)); ?></td>
        </tr>
        <tr>
            <td class="field-title">Number of children</td>
            <td class="field-value"><?= esc_html($post->main_applicant['number_of_children'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Age(s) of child(ren)</td>
            <td class="field-value"><?= esc_html($post->main_applicant['ages_of_children'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Alimony monthly expense (if applicable) — CZK</td>
            <td class="field-value"><?= number_format((float)($post->main_applicant['alimony_monthly_expense'] ?? '')); ?></td>
        </tr>
        <tr>
            <td class="field-title">Additional information</td>
            <td class="field-value"><?= esc_html($post->main_applicant['other_additional_information'] ?? ''); ?></td>
        </tr>
    </table>
    <h3>Address</h3>
    <?php if (isset($post->main_applicant['permanent_address'])) : ?>
    <table class="widefat message-fields striped">
        <tr>
            <th class="" colspan="2">Permanent address</th>
        </tr>
        <tr>
            <td class="field-title">Country</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address']['country']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Municipality</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address']['municipality']); ?></td>
        </tr>
        <tr>
            <td class="field-title">City</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address']['city']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Street</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address']['street']); ?></td>
        </tr>
        <tr>
            <td class="field-title">House number</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address']['house_number']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Postal code</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address']['postal_code']); ?></td>
        </tr>
        <tr>
            <th class="" colspan="2">Current address</th>
        </tr>
        <tr>
            <td class="field-title">Country</td>
            <td class="field-value"><?= esc_html($post->main_applicant['current_address']['country']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Municipality</td>
            <td class="field-value"><?= esc_html($post->main_applicant['current_address']['municipality']); ?></td>
        </tr>
        <tr>
            <td class="field-title">City</td>
            <td class="field-value"><?= esc_html($post->main_applicant['current_address']['city']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Street</td>
            <td class="field-value"><?= esc_html($post->main_applicant['current_address']['street']); ?></td>
        </tr>
        <tr>
            <td class="field-title">House number</td>
            <td class="field-value"><?= esc_html($post->main_applicant['current_address']['house_number']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Postal code</td>
            <td class="field-value"><?= esc_html($post->main_applicant['current_address']['postal_code']); ?></td>
        </tr>
    </table>
<?php endif; ?>
    <h3>Income type</h3>
    <?php if (isset($post->main_applicant['income_type']['current_tab'])) : ?>
    <?php switch (key($post->main_applicant['income_type']['current_tab'])):
        case 'full_time_employee': ?>
            <table class="widefat message-fields striped">
                <tr>
                    <th class="" colspan="2">Full-time employee</th>
                </tr>
                <tr>
                    <td class="field-title">Employer's official name</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['full_time_employee']['employers_official_name']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Employer's identification number (IČO)</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['full_time_employee']['employers_identification_number']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Monthly net income (6-month average) — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->main_applicant['full_time_employee']['monthly_net_income']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Contract type</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['full_time_employee']['contract_type']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Contract validity from</td>
                    <td class="field-value">
                        <?= (int)$post->main_applicant['full_time_employee']['contract_validity_from']['day'] . ' ' .
                        get_months_name((int)$post->main_applicant['full_time_employee']['contract_validity_from']['month']) . ' ' .
                        (int)$post->main_applicant['full_time_employee']['contract_validity_from']['year']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="field-title">Contract validity until</td>
                    <td class="field-value">
                        <?= (int)$post->main_applicant['full_time_employee']['contract_validity_until']['day'] . ' ' .
                        get_months_name((int)$post->main_applicant['full_time_employee']['contract_validity_until']['month']) . ' ' .
                        (int)$post->main_applicant['full_time_employee']['contract_validity_until']['year']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="field-title">Employer's business field</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['full_time_employee']['employers_business_field']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Employer type</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['full_time_employee']['employer_type']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Job classification</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['full_time_employee']['job_classification']); ?></td>
                </tr>

                <tr>
                    <td class="field-title">Length of current employment (Year Month)</td>
                    <td class="field-value">
                        <?= esc_html($post->main_applicant['full_time_employee']['length_of_current_employment']['year']); ?>
                        <?= esc_html($post->main_applicant['full_time_employee']['length_of_current_employment']['month']); ?>
                    </td>
                </tr>
            </table>
            <?php break; ?>
        <?php case 'self_employed': ?>
            <table class="widefat message-fields striped">
                <tr>
                    <th class="" colspan="2">Self-employed</th>
                </tr>
                <tr>
                    <td class="field-title">Legal registered name</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['self_employed']['legal_registered_name']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Enter your tax ID (IČ)</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['self_employed']['tax_id']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Total revenue in the previous year — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->main_applicant['self_employed']['total_revenue_in_previous_year']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Total revenue in the year before — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->main_applicant['self_employed']['total_revenue_in_year_before']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Additional information</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['self_employed']['additional_information']); ?></td>
                </tr>
            </table>
            <?php break; ?>
        <?php case 'business': ?>
            <table class="widefat message-fields striped">
                <tr>
                    <th class="" colspan="2">Business</th>
                </tr>
                <tr>
                    <td class="field-title">Legal registered name</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['business']['legal_registered_name']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Enter your tax ID (IČ)</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['business']['tax_id']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Total revenue in the previous year — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->main_applicant['business']['total_revenue_i_previous_year']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Net profit in the previous year — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->main_applicant['business']['net_profit_in_previous_year']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Total revenue in the year before — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->main_applicant['business']['total_revenue_in_the_year_before']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Net profit in the year before — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->main_applicant['business']['net_profit_in_year_before']); ?></td>
                </tr>
                <!--            <tr>-->
                <!--                <td class="field-title">Field of business</td>-->
                <!--                <td class="field-value">-->
                <?php //= esc_html($post->main_applicant['business']['field_of_business']); ?><!--</td>-->
                <!--            </tr>-->
                <tr>
                    <td class="field-title">Additional information</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['business']['additional_information']); ?></td>
                </tr>
            </table>
            <?php break; ?>
        <?php case 'rental_income': ?>
            <table class="widefat message-fields striped">
                <tr>
                    <th class="" colspan="2">Rental income</th>
                </tr>
                <tr>
                    <td class="field-title">Property address</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['rental_income']['property_address']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Start of rental contract</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['rental_income']['start_rental_contract']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Additional information</td>
                    <td class="field-value"><?= esc_html($post->main_applicant['rental_income']['additional_information']); ?></td>
                </tr>
            </table>
            <?php break; ?>
        <?php endswitch; ?>
<?php endif; ?>
    <?php if ($post->relationship_type == 'someelse'): ?>
    <h3>Second applicant</h3>
    <table class="widefat message-fields striped">
        <tr>
            <th class="" colspan="2">Personal Information</th>
        </tr>
        <tr>
            <td class="field-title">Gender</td>
            <td class="field-value"><?= esc_html($post->second_applicant['gender']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Marital status</td>
            <td class="field-value"><?= esc_html($post->second_applicant['marital_status']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Passport number</td>
            <td class="field-value">
                <?= sanitize_text_field($post->second_applicant['passport']['number']); ?>
            </td>
        </tr>
        <tr>
            <td class="field-title">Issue date</td>
            <td class="field-value">
                <?= (int)$post->second_applicant['passport']['issue_date']['day'] . ' ' .
                get_months_name((int)$post->second_applicant['passport']['issue_date']['month']) . ' ' .
                (int)$post->second_applicant['passport']['issue_date']['year']; ?>
            </td>
        </tr>
        <tr>
            <td class="field-title">Expiration date</td>
            <td class="field-value">
                <?= (int)$post->second_applicant['passport']['expiration_date']['day'] . ' ' .
                get_months_name((int)$post->second_applicant['passport']['expiration_date']['month']) . ' ' .
                (int)$post->second_applicant['passport']['expiration_date']['year']; ?>
            </td>
        </tr>
        <tr>
            <td class="field-title">Residency number</td>
            <td class="field-value">
                <?= sanitize_text_field($post->second_applicant['residency']['number']); ?>
            </td>
        </tr>
        <tr>
            <td class="field-title">Issue date</td>
            <td class="field-value">
                <?= (int)$post->second_applicant['residency']['issue_date']['day'] . ' ' .
                get_months_name((int)$post->second_applicant['residency']['issue_date']['month']) . ' ' .
                (int)$post->second_applicant['residency']['issue_date']['year']; ?>
            </td>
        </tr>
        <tr>
            <td class="field-title">Expiration date</td>
            <td class="field-value">
                <?= (int)$post->second_applicant['residency']['expiration_date']['day'] . ' ' .
                get_months_name((int)$post->second_applicant['residency']['expiration_date']['month']) . ' ' .
                (int)$post->second_applicant['residency']['expiration_date']['year']; ?>
            </td>
        </tr>
        <tr>
            <th class="" colspan="2">Additional Information</th>
        </tr>
        <tr>
            <td class="field-title">Country of birth</td>
            <td class="field-value"><?= esc_html($post->second_applicant['country_of_birth']); ?></td>
        </tr>
        <tr>
            <td class="field-title">City of birth</td>
            <td class="field-value"><?= esc_html($post->second_applicant['city_of_birth']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Education</td>
            <td class="field-value"><?= esc_html($post->second_applicant['education']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Type of housing</td>
            <td class="field-value"><?= esc_html($post->second_applicant['housing']); ?></td>
        </tr>
        <?php if ($post->second_applicant['housing'] == 'Other'): ?>
            <tr>
                <td class="field-title">Other comments</td>
                <td class="field-value"><?= esc_html($post->second_applicant['housing_comments']); ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td class="field-title">When did you start living there?</td>
            <td class="field-value">
                <?= (int)$post->second_applicant['housing_start_living']['day'] . ' ' .
                get_months_name((int)$post->second_applicant['housing_start_living']['month']) . ' ' .
                (int)$post->second_applicant['housing_start_living']['year']; ?>
            </td>
        </tr>
        <tr>
            <th class="" colspan="2">Other details</th>
        </tr>
        <tr>
            <td class="field-title">Monthly expenses — CZK</td>
            <td class="field-value"><?= number_format((float)$post->second_applicant['monthly_expenses']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Number of children</td>
            <td class="field-value"><?= esc_html($post->second_applicant['number_of_children']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Age(s) of child(ren)</td>
            <td class="field-value"><?= esc_html($post->second_applicant['ages_of_children']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Alimony monthly expense (if applicable) — CZK</td>
            <td class="field-value"><?= number_format((float)$post->second_applicant['alimony_monthly_expense']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Additional information</td>
            <td class="field-value"><?= esc_html($post->second_applicant['other_additional_information']); ?></td>
        </tr>
    </table>
    <h3>Address</h3>
    <table class="widefat message-fields striped">
        <tr>
            <th class="" colspan="2">Permanent address</th>
        </tr>
        <tr>
            <td class="field-title">Country</td>
            <td class="field-value"><?= esc_html($post->second_applicant['permanent_address']['country']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Municipality</td>
            <td class="field-value"><?= esc_html($post->second_applicant['permanent_address']['municipality']); ?></td>
        </tr>
        <tr>
            <td class="field-title">City</td>
            <td class="field-value"><?= esc_html($post->second_applicant['permanent_address']['city']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Street</td>
            <td class="field-value"><?= esc_html($post->second_applicant['permanent_address']['street']); ?></td>
        </tr>
        <tr>
            <td class="field-title">House number</td>
            <td class="field-value"><?= esc_html($post->second_applicant['permanent_address']['house_number']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Postal code</td>
            <td class="field-value"><?= esc_html($post->second_applicant['permanent_address']['postal_code']); ?></td>
        </tr>
        <tr>
            <th class="" colspan="2">Current address</th>
        </tr>
        <tr>
            <td class="field-title">Country</td>
            <td class="field-value"><?= esc_html($post->second_applicant['current_address']['country']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Municipality</td>
            <td class="field-value"><?= esc_html($post->second_applicant['current_address']['municipality']); ?></td>
        </tr>
        <tr>
            <td class="field-title">City</td>
            <td class="field-value"><?= esc_html($post->second_applicant['current_address']['city']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Street</td>
            <td class="field-value"><?= esc_html($post->second_applicant['current_address']['street']); ?></td>
        </tr>
        <tr>
            <td class="field-title">House number</td>
            <td class="field-value"><?= esc_html($post->second_applicant['current_address']['house_number']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Postal code</td>
            <td class="field-value"><?= esc_html($post->second_applicant['current_address']['postal_code']); ?></td>
        </tr>
    </table>
    <h3>Income type</h3>

    <?php switch (key($post->second_applicant['income_type']['current_tab'])):
        case 'full_time_employee': ?>
            <table class="widefat message-fields striped">
                <tr>
                    <th class="" colspan="2">Full-time employee</th>
                </tr>
                <tr>
                    <td class="field-title">Employer's official name</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['full_time_employee']['employers_official_name']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Employer's identification number (IČO)</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['full_time_employee']['employers_identification_number']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Monthly net income (6-month average) — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->second_applicant['full_time_employee']['monthly_net_income']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Contract type</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['full_time_employee']['contract_type']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Contract validity from</td>
                    <td class="field-value">
                        <?= (int)$post->second_applicant['full_time_employee']['contract_validity_from']['day'] . ' ' .
                        get_months_name((int)$post->second_applicant['full_time_employee']['contract_validity_from']['month']) . ' ' .
                        (int)$post->second_applicant['full_time_employee']['contract_validity_from']['year']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="field-title">Contract validity until</td>
                    <td class="field-value">
                        <?= (int)$post->second_applicant['full_time_employee']['contract_validity_until']['day'] . ' ' .
                        get_months_name((int)$post->second_applicant['full_time_employee']['contract_validity_until']['month']) . ' ' .
                        (int)$post->second_applicant['full_time_employee']['contract_validity_until']['year']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="field-title">Employer's business field</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['full_time_employee']['employers_business_field']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Employer type</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['full_time_employee']['employer_type']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Job classification</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['full_time_employee']['job_classification']); ?></td>
                </tr>

                <tr>
                    <td class="field-title">Length of current employment (Year Month)</td>
                    <td class="field-value">
                        <?= esc_html($post->second_applicant['full_time_employee']['length_of_current_employment']['year']); ?>
                        <?= esc_html($post->second_applicant['full_time_employee']['length_of_current_employment']['month']); ?>
                    </td>
                </tr>
            </table>
            <?php break; ?>
        <?php case 'self_employed': ?>
            <table class="widefat message-fields striped">
                <tr>
                    <th class="" colspan="2">Self-employed</th>
                </tr>
                <tr>
                    <td class="field-title">Legal registered name</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['self_employed']['legal_registered_name']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Enter your tax ID (IČ)</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['self_employed']['tax_id']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Total revenue in the previous year — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->second_applicant['self_employed']['total_revenue_in_previous_year']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Total revenue in the year before — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->second_applicant['self_employed']['total_revenue_in_year_before']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Additional information</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['self_employed']['additional_information']); ?></td>
                </tr>
            </table>
            <?php break; ?>
        <?php case 'business': ?>
            <table class="widefat message-fields striped">
                <tr>
                    <th class="" colspan="2">Business</th>
                </tr>
                <tr>
                    <td class="field-title">Legal registered name</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['business']['legal_registered_name']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Enter your tax ID (IČ)</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['business']['tax_id']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Total revenue in the previous year — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->second_applicant['business']['total_revenue_i_previous_year']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Net profit in the previous year — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->second_applicant['business']['net_profit_in_previous_year']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Total revenue in the year before — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->second_applicant['business']['total_revenue_in_the_year_before']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Net profit in the year before — CZK</td>
                    <td class="field-value"><?= number_format((float)$post->second_applicant['business']['net_profit_in_year_before']); ?></td>
                </tr>
                <!--            <tr>-->
                <!--                <td class="field-title">Field of business</td>-->
                <!--                <td class="field-value">-->
                <?php //= esc_html($post->second_applicant['business']['field_of_business']); ?><!--</td>-->
                <!--            </tr>-->
                <tr>
                    <td class="field-title">Additional information</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['business']['additional_information']); ?></td>
                </tr>
            </table>
            <?php break; ?>
        <?php case 'rental_income': ?>
            <table class="widefat message-fields striped">
                <tr>
                    <th class="" colspan="2">Rental income</th>
                </tr>
                <tr>
                    <td class="field-title">Property address</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['rental_income']['property_address']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Start of rental contract</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['rental_income']['start_rental_contract']); ?></td>
                </tr>
                <tr>
                    <td class="field-title">Additional information</td>
                    <td class="field-value"><?= esc_html($post->second_applicant['rental_income']['additional_information']); ?></td>
                </tr>
            </table>
            <?php break; ?>
        <?php endswitch; ?>
<?php endif; ?>
    <?php
}

function calculator_inbox_form_step_three_meta_box($post)
{
    $post->error           = get_post_meta($post->ID, '_step_three_email_error', true);
    $post->time            = get_post_meta($post->ID, '_step_three_email_time', true);
    $post->message         = get_post_meta($post->ID, '_step_three_email_message', true);
    $post->user_email_time = get_post_meta($post->ID, '_step_three_user_email_time', true);

    $submitted_on = sprintf(
    /* translators: Publish box date string. 1: Date, 2: Time. */
            __('%1$s at %2$s', 'calculator'),
            wp_date(
            /* translators: Publish box date format, see https://www.php.net/date */
                    _x('M j, Y', 'publish box date format', 'calculator'),
                    $post->time
            ),
            wp_date(
            /* translators: Publish box time format, see https://www.php.net/date */
                    _x('H:i', 'publish box time format', 'calculator'),
                    $post->time
            )
    );

    $submitted_on = sprintf(
    /* translators: %s: message submission date */
            esc_html(__('Submitted on: %s', 'calculator')),
            '<b>' . esc_html($submitted_on) . '</b>'
    );
    if ($post->user_email_time) {
        $sent_on = sprintf(
        /* translators: Publish box date string. 1: Date, 2: Time. */
                __('%1$s at %2$s', 'calculator'),
                wp_date(
                /* translators: Publish box date format, see https://www.php.net/date */
                        _x('M j, Y', 'publish box date format', 'calculator'),
                        $post->user_email_time
                ),
                wp_date(
                /* translators: Publish box time format, see https://www.php.net/date */
                        _x('H:i', 'publish box time format', 'calculator'),
                        $post->user_email_time
                )
        );
    }

    $sent_on = sprintf(
    /* translators: %s: message submission date */
            esc_html(__('Sent on: %s', 'calculator')),
            '<b>' . esc_html($sent_on) . '</b>'
    );
    ?>
    <h3>Email status and message</h3>
    <table class="widefat message-fields striped">
        <tr>
            <th class=""
                colspan="2"><?= ($post->error ? "<b style='color: red'>Error</b>" : "<b style='color: green'>Success</b>"); ?></th>
        </tr>
        <tr>
            <td class="field-title"> Manager Email date</td>
            <td class="field-value"><?= $submitted_on; ?></td>
        </tr>
        <tr>
            <td class="field-title">Message</td>
            <td class="field-value"><?= nl2br(esc_textarea($post->message)); ?></td>
        </tr>
        <tr>
            <td class="field-title"> Client Email date</td>
            <td class="field-value"><?= ($post->user_email_time ? $sent_on : "<b style='color: red'>Error</b>"); ?></td>
        </tr>
    </table>
    <?php
}

function calculator_inbox_form_step_aio_meta_box($post)
{
    $post->relationship_type = get_post_meta($post->ID, '_relationship_type', true);
    $post->relationship_who  = get_post_meta($post->ID, '_relationship_who', true);

    $post->property         = get_post_meta($post->ID, '_aio_property', true);
    $post->main_applicant   = get_post_meta($post->ID, '_aio_main_applicant', true);
    $post->second_applicant = get_post_meta($post->ID, '_aio_second_applicant', true);

    if ($calc_user_id = get_post_meta($post->ID, '_calc_user', true)) {
        $user = get_userdata($calc_user_id);
    }

    ?>
    <h3>Main applicant</h3>
    <h4>Personal Info</h4>
    <table class="widefat message-fields striped">
        <tr>
            <td class="field-title"><?php _e("First name", 'finanzia'); ?></td>
            <td class="field-value"><?= esc_html($post->main_applicant['first_name'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Last name</td>
            <td class="field-value"><?= esc_html($post->main_applicant['last_name'] ?? ''); ?></td>
        </tr>
        <?php if ($user->user_registered) : ?>
            <tr>
                <td class="field-title"><?php _e("Terms and Conditions and Privacy Policy Acceptance Date/Time", 'finanzia'); ?></td>
                <td class="field-value"><?= esc_html($user->user_registered); ?></td>
            </tr>
        <?php endif; ?>
        <!--        <tr>-->
        <!--            <td class="field-title">Date of birth</td>-->
        <!--            <td class="field-value"> --><?php //= (int)$post->main_applicant['dob']['day'];
        ?><!-- --><?php //= get_months_name((int)$post->main_applicant['dob']['month']);
        ?><!-- --><?php //= (int)$post->main_applicant['dob']['year'];
        ?><!--</td>-->
        <!--        </tr>-->
        <tr>
            <td class="field-title">Marital status</td>
            <td class="field-value"><?= esc_html($post->main_applicant['marital_status'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Number of children</td>
            <td class="field-value"><?= esc_html($post->main_applicant['number_of_children'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Czech ID number (Rodné číslo) — if available</td>
            <td class="field-value"><?= esc_html($post->main_applicant['passport_registration_number'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Citizenship</td>
            <td class="field-value"><?= esc_html($post->main_applicant['main_nationality'] ?? ''); ?></td>
        </tr>
        <?php if ($post->main_applicant['multipass'] == 'yes') : ?>
            <tr>
                <td class="field-title">Other passports</td>
                <td class="field-value"><?= esc_html(implode(', ', $post->main_applicant["passports"]["nationality"]) ?? ''); ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td class="field-title">Education</td>
            <td class="field-value"><?= esc_html($post->main_applicant['education'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Type of visa in Czech Republic</td>
            <td class="field-value"><?= esc_html($post->main_applicant['residency_type'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Phone number</td>
            <td class="field-value"><?= esc_html($post->main_applicant['phone'] ?? ''); ?></td>
        </tr>
        <!--        <tr>-->
        <!--            <td class="field-title">Bank you currently use in Czech Republic</td>-->
        <!--            --><?php //if (is_countable($post->main_applicant["current_bank"])):
        ?>
        <!--                <td class="field-value">-->
        <?php //= esc_html(implode(', ', $post->main_applicant["current_bank"]) ?? '');
        ?><!--</td>-->
        <!--            --><?php //endif;
        ?>
        <!--        </tr>-->
        <tr>
            <td class="field-title">Applying alone or with someone else?</td>
            <td class="field-value"><?= esc_html($post->relationship_type == 'someelse' ? 'With someone else' : 'Alone'); ?></td>
        </tr>
        <tr>
            <td class="field-title">Relationship to the main applicant</td>
            <td class="field-value"><?= esc_html($post->relationship_who ?? ''); ?></td>
        </tr>
    </table>

    <h4>Address</h4>
    <?php if (isset($post->main_applicant['permanent_address'])) : ?>
    <table class="widefat message-fields striped">
        <tr>
            <th class="" colspan="2">Permanent address</th>
        </tr>
        <tr>
            <td class="field-title">City</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address']['city']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Street</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address']['street']); ?></td>
        </tr>
        <tr>
            <td class="field-title">House number</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address']['house_number']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Postal code</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address']['postal_code']); ?></td>
        </tr>
        <tr>
            <td class="field-title">When did you start living at this address?</td>
            <td class="field-value"> <?= (int)$post->main_applicant['permanent_address']['day']; ?> <?= get_months_name((int)$post->main_applicant['permanent_address']['month']); ?> <?= (int)$post->main_applicant['permanent_address']['year']; ?></td>
        </tr>
        <tr>
            <th class="" colspan="2">Permanent address abroad (if no permanent address in Czech Republic)</th>
        </tr>
        <tr>
            <td class="field-title">Country</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address_abroad']['country']); ?></td>
        </tr>
        <tr>
            <td class="field-title">City</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address_abroad']['city']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Street</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address_abroad']['street']); ?></td>
        </tr>
        <tr>
            <td class="field-title">House number</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address_abroad']['house_number']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Postal code</td>
            <td class="field-value"><?= esc_html($post->main_applicant['permanent_address_abroad']['postal_code']); ?></td>
        </tr>
    </table>
<?php endif; ?>

    <h4>Current Loans</h4>
    <table class="widefat message-fields striped">
        <?php if (isset($post->main_applicant['existing_liabilities']) && is_countable($post->main_applicant['existing_liabilities'])): ?>
            <?php foreach ($post->main_applicant['existing_liabilities'] as $key => $existing_liability) : ?>
                <?php foreach ($existing_liability as $item) : ?>
                    <?php switch ($key):
                        case 'liability_type_loan': ?>
                            <tr>
                                <th class="" colspan="2">Loan</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">What is the product's interest rate (%)?</td>
                                <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly payment amount - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Remaining balance - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_mortgage': ?>
                            <tr>
                                <th class="" colspan="2">Mortgage</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">What is the product's interest rate (%)?</td>
                                <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly payment amount - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Remaining balance - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_credit_card': ?>
                            <tr>
                                <th class="" colspan="2">Credit card</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Maximum credit limit you can get on your credit card</td>
                                <td class="field-value"><?= esc_html($item['max_credit_limit']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_overdraft': ?>
                            <tr>
                                <th class="" colspan="2">Overdraft</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr><s></s>
                            <tr>
                                <td class="field-title">Maximum limit amount</td>
                                <td class="field-value"><?= esc_html($item['max_credit_limit']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_no_liabilities': ?>
                            <tr>
                                <th class="" colspan="2">No liabilities</th>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_other': ?>
                            <tr>
                                <th class="" colspan="2">Other</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">What is the product's interest rate (%)?</td>
                                <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly payment amount - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Remaining balance - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php endswitch; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php if (!empty($post->property['own_property'])): ?>
            <tr>
                <td class="field-title">Do you already own any property in Czech Republic?</td>
                <td class="field-value"><?= esc_html($post->property['own_property']); ?></td>
            </tr>
        <?php endif; ?>
    </table>

    <h4>Income Info</h4>
    <table class="widefat message-fields striped">
        <tr>
            <td class="field-title">Company name</td>
            <td class="field-value"><?= esc_html($post->main_applicant['company_name']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Registration number (IČO)</td>
            <td class="field-value"><?= esc_html($post->main_applicant['company_registration_number']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Net income (monthly average – last 6 months)</td>
            <td class="field-value"><?= esc_html($post->main_applicant['average_monthly_net_income']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Job position</td>
            <td class="field-value"><?= esc_html($post->main_applicant['job_title']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Bank where your salary is paid</td>
            <td class="field-value"><?= esc_html($post->main_applicant['name_of_bank']); ?></td>
        </tr>
        <tr>
            <td class="field-title">When did you start working at this company?</td>
            <td class="field-value"> <?= (int)$post->main_applicant['start_working']['day']; ?> <?= get_months_name((int)$post->main_applicant['start_working']['month']); ?> <?= (int)$post->main_applicant['start_working']['year']; ?></td>
        </tr>
        <tr>
            <td class="field-title">When did you start working in this field?</td>
            <td class="field-value"> <?= (int)$post->main_applicant['start_working_field']['day']; ?> <?= get_months_name((int)$post->main_applicant['start_working_field']['month']); ?> <?= (int)$post->main_applicant['start_working_field']['year']; ?></td>
        </tr>
        <tr>
            <td class="field-title">When did you start working for your previous employer in Czechia?</td>
            <td class="field-value"> <?= (int)$post->main_applicant['start_working_previous_employer']['day']; ?> <?= get_months_name((int)$post->main_applicant['start_working_previous_employer']['month']); ?> <?= (int)$post->main_applicant['start_working_previous_employer']['year']; ?></td>
        </tr>
    </table>

    <h4>Other Info</h4>
    <table class="widefat message-fields striped">
        <?php if (!empty($post->property['already_selected_property'])): ?>
            <tr>
                <td class="field-title">Have you already selected a property?</td>
                <td class="field-value"><?= esc_html($post->property['already_selected_property']); ?></td>
            </tr>
        <?php endif; ?>
        <?php if (!empty($post->property['url_property_listing'])): ?>
            <tr>
                <td class="field-title">Link to the selected property</td>
                <td class="field-value"><?= esc_html($post->property['url_property_listing']); ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td class="field-title">Amount you want to borrow</td>
            <td class="field-value"><?= esc_html($post->property['borrow_amount']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Preferred monthly payment date</td>
            <td class="field-value"><?= esc_html($post->property['preferred_monthly_payment_date']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Additional Information (optional)</td>
            <td class="field-value"><?= esc_html($post->property['additional_information']); ?></td>
        </tr>
    </table>

    <?php if ($post->relationship_type == 'someelse'): ?>
    <h3>Second applicant</h3>
    <h4>Personal Info</h4>
    <table class="widefat message-fields striped">
        <tr>
            <td class="field-title"><?php _e("First name", 'finanzia'); ?></td>
            <td class="field-value"><?= esc_html($post->second_applicant['first_name'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Last name</td>
            <td class="field-value"><?= esc_html($post->second_applicant['last_name'] ?? ''); ?></td>
        </tr>
        <?php if ($user->user_registered) : ?>
            <tr>
                <td class="field-title"><?php _e("Terms and Conditions and Privacy Policy Acceptance Date/Time", 'finanzia'); ?></td>
                <td class="field-value"><?= esc_html($user->user_registered); ?></td>
            </tr>
        <?php endif; ?>
        <!--        <tr>-->
        <!--            <td class="field-title">Date of birth</td>-->
        <!--            <td class="field-value"> --><?php //= (int)$post->second_applicant['dob']['day']; ?><!-- -->
        <?php //= get_months_name((int)$post->second_applicant['dob']['month']); ?><!-- -->
        <?php //= (int)$post->second_applicant['dob']['year']; ?><!--</td>-->
        <!--        </tr>-->
        <tr>
            <td class="field-title">Marital status</td>
            <td class="field-value"><?= esc_html($post->second_applicant['marital_status'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Number of children</td>
            <td class="field-value"><?= esc_html($post->second_applicant['number_of_children'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Czech ID number (Rodné číslo) — if available</td>
            <td class="field-value"><?= esc_html($post->second_applicant['passport_registration_number'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Citizenship</td>
            <td class="field-value"><?= esc_html($post->second_applicant['main_nationality'] ?? ''); ?></td>
        </tr>
        <?php if ($post->second_applicant['multipass'] == 'yes') : ?>
            <tr>
                <td class="field-title">Other passports</td>
                <td class="field-value"><?= esc_html(implode(', ', $post->second_applicant["passports"]["nationality"]) ?? ''); ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td class="field-title">Education</td>
            <td class="field-value"><?= esc_html($post->second_applicant['education'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Type of visa in Czech Republic</td>
            <td class="field-value"><?= esc_html($post->second_applicant['residency_type'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="field-title">Phone number</td>
            <td class="field-value"><?= esc_html($post->second_applicant['phone'] ?? ''); ?></td>
        </tr>
        <!--        <tr>-->
        <!--            <td class="field-title">Bank you currently use in Czech Republic</td>-->
        <!--            --><?php //if (is_countable($post->second_applicant["current_bank"])): ?>
        <!--                <td class="field-value">-->
        <?php //= esc_html(implode(', ', $post->second_applicant["current_bank"]) ?? ''); ?><!--</td>-->
        <!--            --><?php //endif; ?>
        <!--        </tr>-->
    </table>

    <h4>Address</h4>
    <?php if (isset($post->second_applicant['permanent_address'])) : ?>
        <table class="widefat message-fields striped">
            <tr>
                <th class="" colspan="2">Permanent address</th>
            </tr>
            <tr>
                <td class="field-title">City</td>
                <td class="field-value"><?= esc_html($post->second_applicant['permanent_address']['city']); ?></td>
            </tr>
            <tr>
                <td class="field-title">Street</td>
                <td class="field-value"><?= esc_html($post->second_applicant['permanent_address']['street']); ?></td>
            </tr>
            <tr>
                <td class="field-title">House number</td>
                <td class="field-value"><?= esc_html($post->second_applicant['permanent_address']['house_number']); ?></td>
            </tr>
            <tr>
                <td class="field-title">Postal code</td>
                <td class="field-value"><?= esc_html($post->second_applicant['permanent_address']['postal_code']); ?></td>
            </tr>
            <tr>
                <td class="field-title">When did you start living at this address?</td>
                <td class="field-value"> <?= (int)$post->second_applicant['permanent_address']['day']; ?> <?= get_months_name((int)$post->second_applicant['permanent_address']['month']); ?> <?= (int)$post->second_applicant['permanent_address']['year']; ?></td>
            </tr>
            <tr>
                <th class="" colspan="2">Permanent address abroad (if no permanent address in Czech Republic)</th>
            </tr>
            <tr>
                <td class="field-title">Country</td>
                <td class="field-value"><?= esc_html($post->second_applicant['permanent_address_abroad']['country']); ?></td>
            </tr>
            <tr>
                <td class="field-title">City</td>
                <td class="field-value"><?= esc_html($post->second_applicant['permanent_address_abroad']['city']); ?></td>
            </tr>
            <tr>
                <td class="field-title">Street</td>
                <td class="field-value"><?= esc_html($post->second_applicant['permanent_address_abroad']['street']); ?></td>
            </tr>
            <tr>
                <td class="field-title">House number</td>
                <td class="field-value"><?= esc_html($post->second_applicant['permanent_address_abroad']['house_number']); ?></td>
            </tr>
            <tr>
                <td class="field-title">Postal code</td>
                <td class="field-value"><?= esc_html($post->second_applicant['permanent_address_abroad']['postal_code']); ?></td>
            </tr>
        </table>
    <?php endif; ?>

    <h4>Current Loans</h4>
    <table class="widefat message-fields striped">
        <?php if (isset($post->second_applicant['existing_liabilities']) && is_countable($post->second_applicant['existing_liabilities'])): ?>
            <?php foreach ($post->second_applicant['existing_liabilities'] as $key => $existing_liability) : ?>
                <?php foreach ($existing_liability as $item) : ?>
                    <?php switch ($key):
                        case 'liability_type_loan': ?>
                            <tr>
                                <th class="" colspan="2">Loan</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">What is the product's interest rate (%)?</td>
                                <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly payment amount - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Remaining balance - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_mortgage': ?>
                            <tr>
                                <th class="" colspan="2">Mortgage</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">What is the product's interest rate (%)?</td>
                                <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly payment amount - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Remaining balance - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_credit_card': ?>
                            <tr>
                                <th class="" colspan="2">Credit card</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Maximum credit limit you can get on your credit card</td>
                                <td class="field-value"><?= esc_html($item['max_credit_limit']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_overdraft': ?>
                            <tr>
                                <th class="" colspan="2">Overdraft</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr><s></s>
                            <tr>
                                <td class="field-title">Maximum limit amount</td>
                                <td class="field-value"><?= esc_html($item['max_credit_limit']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_no_liabilities': ?>
                            <tr>
                                <th class="" colspan="2">No liabilities</th>
                            </tr>
                            <?php break; ?>
                        <?php case 'liability_type_other': ?>
                            <tr>
                                <th class="" colspan="2">Other</th>
                            </tr>
                            <tr>
                                <td class="field-title">Which company provided the product?</td>
                                <td class="field-value"><?= esc_html($item['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">What is the product's interest rate (%)?</td>
                                <td class="field-value"><?= esc_html($item['products_interest_rate']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Monthly payment amount - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['monthly_payment_amount']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Remaining balance - CZK</td>
                                <td class="field-value"><?= number_format((float)$item['remaining_balance']); ?></td>
                            </tr>
                            <tr>
                                <td class="field-title">Additional Information</td>
                                <td class="field-value"><?= esc_html($item['additional_information']); ?></td>
                            </tr>
                            <?php break; ?>
                        <?php endswitch; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <h4>Income Info</h4>
    <table class="widefat message-fields striped">
        <tr>
            <td class="field-title">Company name</td>
            <td class="field-value"><?= esc_html($post->second_applicant['company_name']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Registration number (IČO)</td>
            <td class="field-value"><?= esc_html($post->second_applicant['company_registration_number']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Net income (monthly average – last 6 months)</td>
            <td class="field-value"><?= esc_html($post->second_applicant['average_monthly_net_income']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Job position</td>
            <td class="field-value"><?= esc_html($post->second_applicant['job_title']); ?></td>
        </tr>
        <tr>
            <td class="field-title">Bank where your salary is paid</td>
            <td class="field-value"><?= esc_html($post->second_applicant['name_of_bank']); ?></td>
        </tr>
        <tr>
            <td class="field-title">When did you start working at this company?</td>
            <td class="field-value"> <?= (int)$post->second_applicant['start_working']['day']; ?> <?= get_months_name((int)$post->second_applicant['start_working']['month']); ?> <?= (int)$post->second_applicant['start_working']['year']; ?></td>
        </tr>
        <tr>
            <td class="field-title">When did you start working in this field?</td>
            <td class="field-value"> <?= (int)$post->second_applicant['start_working_field']['day']; ?> <?= get_months_name((int)$post->second_applicant['start_working_field']['month']); ?> <?= (int)$post->second_applicant['start_working_field']['year']; ?></td>
        </tr>
        <tr>
            <td class="field-title">When did you start working for your previous employer in Czechia?</td>
            <td class="field-value"> <?= (int)$post->second_applicant['start_working_previous_employer']['day']; ?> <?= get_months_name((int)$post->second_applicant['start_working_previous_employer']['month']); ?> <?= (int)$post->second_applicant['start_working_previous_employer']['year']; ?></td>
        </tr>
    </table>
<?php endif; ?>
    <?php
}

function calculator_inbox_form_step_doc_meta_box($post)
{
    $post->error           = get_post_meta($post->ID, '_step_doc_email_error', true);
    $post->time            = get_post_meta($post->ID, '_step_doc_email_time', true);
    $post->message         = get_post_meta($post->ID, '_step_doc_email_message', true);
    $post->user_email_time = get_post_meta($post->ID, '_step_doc_user_email_time', true);

    $submitted_on = sprintf(
    /* translators: Publish box date string. 1: Date, 2: Time. */
            __('%1$s at %2$s', 'calculator'),
            wp_date(
            /* translators: Publish box date format, see https://www.php.net/date */
                    _x('M j, Y', 'publish box date format', 'calculator'),
                    $post->time
            ),
            wp_date(
            /* translators: Publish box time format, see https://www.php.net/date */
                    _x('H:i', 'publish box time format', 'calculator'),
                    $post->time
            )
    );

    $submitted_on = sprintf(
    /* translators: %s: message submission date */
            esc_html(__('Submitted on: %s', 'calculator')),
            '<b>' . esc_html($submitted_on) . '</b>'
    );
    if ($post->user_email_time) {
        $sent_on = sprintf(
        /* translators: Publish box date string. 1: Date, 2: Time. */
                __('%1$s at %2$s', 'calculator'),
                wp_date(
                /* translators: Publish box date format, see https://www.php.net/date */
                        _x('M j, Y', 'publish box date format', 'calculator'),
                        $post->user_email_time
                ),
                wp_date(
                /* translators: Publish box time format, see https://www.php.net/date */
                        _x('H:i', 'publish box time format', 'calculator'),
                        $post->user_email_time
                )
        );
    }

    $sent_on = sprintf(
    /* translators: %s: message submission date */
            esc_html(__('Sent on: %s', 'calculator')),
            '<b>' . esc_html($sent_on) . '</b>'
    );
    ?>
    <h3>Email status and message</h3>
    <table class="widefat message-fields striped">
        <tr>
            <th class=""
                colspan="2"><?= ($post->error ? "<b style='color: red'>Error</b>" : "<b style='color: green'>Success</b>"); ?></th>
        </tr>
        <tr>
            <td class="field-title"> Manager Email date</td>
            <td class="field-value"><?= $submitted_on; ?></td>
        </tr>
        <tr>
            <td class="field-title">Message</td>
            <td class="field-value"><?= nl2br(esc_textarea($post->message)); ?></td>
        </tr>
        <!--        <tr>-->
        <!--            <td class="field-title"> Client Email date</td>-->
        <!--            <td class="field-value">-->
        <?php //= ($post->user_email_time ? $sent_on : "<b style='color: red'>Error</b>");
        ?><!--</td>-->
        <!--        </tr>-->
    </table>
    <?php
}