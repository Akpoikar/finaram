<?php

class Calculator
{

    const post_type = 'calculator';
    const taxonomy  = 'calculator_category';
    private static $found_items = 0;

    public function __construct()
    {
        // Create dashboard post type
        add_action('init', [$this, 'register_calculator_post_type']);
        add_action('init', [$this, 'register_calculator_category_taxonomy']);
        add_action('admin_menu', [$this, 'add_calculator_menu']);

        add_action('admin_enqueue_scripts', [$this, 'calculator_admin_enqueue_scripts'], 10, 1);

        add_action('calculator_admin_updated_message', [$this, 'calculator_admin_updated_message'], 10, 0);

        add_filter('set_screen_option_' . 'calculators_per_page', function ($status, $option, $value) {
            return (int)$value;
        }, 10, 3);

        add_action('init', [$this, 'save_calculator_form_data']);
        add_action('init', [$this, 'export_csv_calculators']);

        if (wp_doing_ajax()) {
            /*
             * deprecated
            add_action('wp_ajax_save_mini_calc_form_data', [$this, 'save_mini_calc_form_data']); //
            add_action('wp_ajax_nopriv_save_mini_calc_form_data', [$this, 'save_mini_calc_form_data']);
            */
            add_action('wp_ajax_go_aio_from_calc', [$this, 'go_aio_from_calc']); //
            add_action('wp_ajax_nopriv_go_aio_from_calc', [$this, 'go_aio_from_calc']);
            add_action('wp_ajax_step_one_verdict', [$this, 'step_one_verdict']);
            add_action('wp_ajax_choice_broker', [$this, 'choice_broker']);
        }
//        add_action('init', [$this, 'send_test_mail']);
        add_action('plugins_loaded', [$this, 'calculator_load_textdomain']);
    }

    public function step_one_verdict()
    {

        if (!wp_verify_nonce($_POST['nonce_code'], 'calc-nonce')) {
            wp_die('Stop! (1)');
        }

        if (!isset($_POST['verdict']) || !isset($_POST['calc_id'])) {
            wp_die('Stop! (2)');
        }

        $calc_id = (int)$_POST['calc_id'];

        if (self::post_type !== get_post_type($calc_id)) {
            wp_die('Stop! (3)');
        }

        $calc_user_id = get_post_meta($calc_id, '_calc_user', true);

        $calc_user = get_userdata($calc_user_id);

        if (!$calc_user) {
            wp_die('Stop! (4)');
        }

        $to            = $calc_user->user_email;
        $mail_data     = ['name' => $calc_user->user_firstname . ' ' . $calc_user->user_lastname];
        $mail_template = $_POST['verdict'] == 'approve' ? 'step_one_approved' : 'step_one_denied';
        $lang          = get_post_meta($calc_id, '_calc_user_lang', true);
        $lang          = substr($lang, 0, 2);

        $mail = sendMessage($to, $mail_data, $mail_template, $lang);

        if ($mail) {
            update_post_meta($calc_id, '_step_one_user_email_time', time());
            update_post_meta($calc_id, '_step_one_user_email_template', $mail_template);
            if ($_POST['verdict'] == 'approve') {
                update_post_meta($calc_id, '_calc_status', 3);
            } else {
                update_post_meta($calc_id, '_calc_status', -1);
            }
            echo json_encode([
                    'success' => true,
            ]);
            wp_die();
        } else {
            echo json_encode(['success' => false]);
            wp_die();
        }
    }

    public function choice_broker()
    {

        if (!wp_verify_nonce($_POST['nonce_code'], 'broker-nonce')) {
            wp_die('Stop! (5)');
        }

        if (!isset($_POST['broker']) || !isset($_POST['calc_id'])) {
            wp_die('Stop! (6)');
        }

        $calc_id = (int)$_POST['calc_id'];

        if (self::post_type !== get_post_type($calc_id)) {
            wp_die('Stop! (7)');
        }

        $broker_email = $_POST['broker'];

        if (!filter_var($broker_email, FILTER_VALIDATE_EMAIL)) {
            wp_die('Stop! (8)');
        }

        update_post_meta($calc_id, '_selected_broker', $broker_email);
        $post        = get_post($calc_id);
        $post_status = get_post_meta($calc_id, '_calc_status', true);

        require_once CALCULATOR_PLUGIN_DIR . '/meta-boxes.php';

        if ($post_status > 8) {
            ob_start();
            echo "<h3>Mini calculator results</h3>";
            calculator_inbox_mini_calc_results_meta_box($post);
            echo "<h3>Step All-In-One</h3>";
            calculator_inbox_form_step_aio_meta_box($post);
            $broker_message = ob_get_clean();
            $subject        = 'Finaram New Request - AIO';
        } else {
            ob_start();
            echo "<h3>Mini calculator results</h3>";
            calculator_inbox_mini_calc_results_meta_box($post);
            echo "<h3>STEP 1</h3>";
            calculator_inbox_form_step_one_meta_box($post);
            $broker_message = ob_get_clean();
            $subject        = 'Finaram New Request - Step 1';
        }

        $to = $broker_email;

        $mail = self::admin_wp_mail($to, $subject, $broker_message);

        if ($mail) {
            update_post_meta($calc_id, '_broker_email_time', time());

            echo json_encode([
                    'success' => true,
            ]);
            wp_die();
        } else {
            echo json_encode(['success' => false]);
            wp_die();
        }
    }

    public function save_calculator_form_data()
    {

        $manager_mail = get_field('manager_mail', 'options');

        if (isset($_POST['step_aio']) && is_array($_POST['step_aio']) && count($_POST['step_aio']) > 0) {
            $data    = $_POST['step_aio'];
            $calc_id = (int)$data['calc_id'];
            if (!wp_verify_nonce($_POST['mortgages-form-aio-field-' . $calc_id], 'mortgages-form-aio-action-' . $calc_id)) {
                return false;
            }

            if (self::post_type !== get_post_type($calc_id)) {
                return false;
            }

            $calc_user_id = get_post_meta($calc_id, '_calc_user', true);

            if ($calc_user_id != get_current_user_id()) {
                return false;
            }

            $calc     = get_post($calc_id);
            $category = get_the_terms($calc_id, Calculator::taxonomy);

            /* main_applicant */
            $main_applicant                         = validate_and_sanitize_text($data['main_applicant']);
            $main_applicant['existing_liabilities'] = $this->filter_breakpoints($main_applicant['existing_liabilities']);
            /*  main_applicant end */

            $relationship_type = sanitize_text_field($data['relationship_type']);
            $relationship_who  = sanitize_text_field($data['relationship_who']);

            /* second_applicant */
            if ($relationship_type === 'someelse') {
                $second_applicant                         = validate_and_sanitize_text($data['second_applicant']);
                $second_applicant['existing_liabilities'] = $this->filter_breakpoints($second_applicant['existing_liabilities']);
            }
            /* second_applicant end */

            $property['own_property']                   = sanitize_text_field($data['own_property']);
            $property['already_selected_property']      = sanitize_text_field($data['already_selected_property']);
            $property['url_property_listing']           = sanitize_text_field($data['url_property_listing']);
            $property['borrow_amount']                  = sanitize_text_field($data['borrow_amount']);
            $property['preferred_monthly_payment_date'] = sanitize_text_field($data['preferred_monthly_payment_date']);
            $property['additional_information']         = sanitize_text_field($data['property_additional_information']);

            // Обновляем пользователя
            $new_user_data = array(
                    'ID'         => $calc_user_id,
                    'first_name' => $main_applicant['first_name'],
                    'last_name'  => $main_applicant['last_name'],
            );
            wp_update_user($new_user_data);

            // Обновляем калькулятор
            $post_content = $calc->post_content . "\n" . implode("\n", [
                            $relationship_type,
                            $relationship_who,
                            var_export($main_applicant, true),
                            var_export($second_applicant, true),
                            var_export($property, true),
                    ]);


            $calc_data = array(
                    'ID'           => $calc_id,
                    'post_content' => $post_content,
                    'post_author'  => $calc_user_id,
            );

            $calc_id = wp_update_post($calc_data);

            if ($calc_id) {
                update_post_meta($calc_id, '_calc_user_lang', get_locale());
                update_post_meta($calc_id, '_calc_status', 9);

                if ($category[0]->slug == 'loans') {
                    update_post_meta($calc_id, '_calc_status', 10); // Waiting Documents

                    $loans_broker_email = get_field('loans_broker_email', 'options');
                    update_post_meta($calc_id, '_selected_broker', $loans_broker_email);
                }

                update_post_meta($calc_id, '_relationship_type', $relationship_type);
                update_post_meta($calc_id, '_relationship_who', $relationship_who);
                update_post_meta($calc_id, '_aio_property', $property);

                update_post_meta($calc_id, '_aio_main_applicant', $main_applicant);
                update_post_meta($calc_id, '_aio_second_applicant', $second_applicant);

//                $_SESSION['calc-ok'] = 1; // не уверен что тут нужно

                self::admin_wp_mail($manager_mail ?? get_option('admin_email'), "New data from the form aio", "New data from the form aio for ID: $calc_id");


                wp_redirect(get_permalink(1450)); //finish

            }

        } elseif (isset($_POST['step_doc']) && is_array($_POST['step_doc']) && count($_POST['step_doc']) > 0) {
            $data    = $_POST['step_doc'];
            $calc_id = (int)$data['calc_id'];
            if (!wp_verify_nonce($_POST['mortgages-form-step-doc-field-' . $calc_id], 'mortgages-form-step-doc-action-' . $calc_id)) {
                return false;
            }

            if (self::post_type !== get_post_type($calc_id)) {
                return false;
            }

            $calc_user_id = get_post_meta($calc_id, '_calc_user', true);

            if ($calc_user_id != get_current_user_id()) {
                return false;
            }

            $mail_sent = false;

            $files = $_FILES['step_doc']['tmp_name'];
            $names = $_FILES['step_doc']['name'];

            $to      = get_post_meta($calc_id, '_selected_broker', true);
            $subject = 'Files from documents form';

            $post = get_post($calc_id);
            require_once CALCULATOR_PLUGIN_DIR . '/meta-boxes.php';
            ob_start();

            echo "<h3>Mini calculator results</h3>";
            calculator_inbox_mini_calc_results_meta_box($post);
            echo "<h3>Step All-In-One</h3>";
            calculator_inbox_form_step_aio_meta_box($post);

            $broker_message = ob_get_clean();

            $message   = "\n";
            $message   .= 'Files from Documents form are attached.';
            $message   .= "\n" . 'If files from the list are missing from attachments, it means that they have an invalid format and have not been validated.';
            $message   .= "\n";
            $file_list = "\n" . 'Income type: ' . calculator_translate($current_tab);
            if (is_countable($names) && count($names) > 0) {
                foreach ($names as $field_name => $names_docs) {
                    if (is_countable($names_docs) && count($names_docs) > 0) {
                        $file_list .= "\n\n" . calculator_translate($field_name) . ' files:';
                        foreach ($names_docs as $key => $name) {
                            $file_list .= "\n" . $name;
                        }
                    }
                }
            }
            $message        .= $file_list;
            $broker_message .= nl2br(esc_textarea($message));
            $headers        = array();

            $attachments = array();
            if (is_countable($files) && count($files) > 0) {
                foreach ($files as $field_name => $field_docs) {
                    if (is_countable($files) && count($files) > 0) {
                        foreach ($field_docs as $key => $tmp_name) {
                            if (!empty($tmp_name) && is_uploaded_file($tmp_name)) {
                                $file_type     = mime_content_type($tmp_name);
                                $allowed_types = array('image/png', 'image/jpeg', 'application/pdf'); // Разрешенные типы файлов
                                if (in_array($file_type, $allowed_types)) {
                                    $attachments[$names[$field_name][$key]] = $tmp_name;
                                }
                            }
                        }
                    }
                }
            }
            $try = 0;
            do {
                $mail_sent = self::admin_wp_mail($to, $subject, $broker_message, $headers, $attachments);
                $try++;
            } while (!$mail_sent && $try < 3);

            if ($mail_sent) {
                update_post_meta($calc_id, '_calc_status', 11);

                update_post_meta($calc_id, '_step_doc_email_message', $message);
                update_post_meta($calc_id, '_step_doc_email_time', time());
                update_post_meta($calc_id, '_step_doc_email_error', 0);

                $_SESSION['calc-ok'] = 1;

//                if (get_post_meta($calc_id, "_mini_calc_credit_type", true) != 'housing_renovation') { // Это временно
//                    $user = get_userdata($calc_user_id);
//                    $mail = sendMessage($user->user_email, ['name' => $user->user_firstname . ' ' . $user->user_lastname], 'step_doc_completed');
//                    if ($mail) {
//                        update_post_meta($calc_id, '_step_doc_user_email_time', time());
//                    }
//                }
            } else {
//                update_post_meta($calc_id, '_calc_status', 6);

                update_post_meta($calc_id, '_step_doc_email_message', 'Warning! The files were NOT sent due to an unforeseen error and were lost without trace.' . "\n" . $file_list);
                update_post_meta($calc_id, '_step_doc_email_time', time());
                update_post_meta($calc_id, '_step_doc_email_error', 1);

            }

            wp_redirect(get_permalink(1450));

        } elseif (isset($_POST['step_one']) && is_array($_POST['step_one']) && count($_POST['step_one']) > 0) {

            $data    = $_POST['step_one'];
            $calc_id = (int)$data['calc_id'];
            if (!wp_verify_nonce($_POST['mortgages-form-step-one-field-' . $calc_id], 'mortgages-form-step-one-action-' . $calc_id)) {
                return false;
            }

            if (self::post_type !== get_post_type($calc_id)) {
                return false;
            }

            $calc_user_id = get_post_meta($calc_id, '_calc_user', true);

            if ($calc_user_id != get_current_user_id()) {
                return false;
            }

            $calc = get_post($calc_id);

            /* main_applicant */

            $main_applicant['first_name'] = sanitize_text_field($data['main_applicant']['first_name']);
            $main_applicant['last_name']  = sanitize_text_field($data['main_applicant']['last_name']);
            $main_applicant['phone']      = preg_replace('/[^0-9+]/', '', $data['main_applicant']['phone']);
            $main_applicant['dob']        = (int)$data['main_applicant']['dob']['day'] . ' ' .
                    get_months_name((int)$data['main_applicant']['dob']['month']) . ' ' .
                    (int)$data['main_applicant']['dob']['year'];

            $main_applicant['length_of_residency']
                    = sanitize_text_field($data['main_applicant']['length_of_residency']['duration']) . ', From ' .
                    get_months_name((int)$data['main_applicant']['length_of_residency']['from_month']) . ' ' .
                    (int)$data['main_applicant']['length_of_residency']['from_year'];

            $main_applicant['main_nationality']             = sanitize_text_field($data['main_applicant']['main_nationality']);
            $main_applicant['residency_type']               = sanitize_text_field($data['main_applicant']['residency_type']);
            $main_applicant['passport_registration_number'] = preg_replace('/[^0-9\/]/', '', $data['main_applicant']['passport_registration_number']);;

            $main_applicant['multipass'] = sanitize_text_field($data['main_applicant']["passports"]["multipass"]);

            if ($main_applicant['multipass'] !== 'yes' || !is_array($data['main_applicant']["passports"]["nationality"])) {
                $main_applicant['other_nationalities'] = false;
            } else {
                $main_applicant['other_nationalities'] = implode(', ', $data['main_applicant']["passports"]["nationality"]);
            }

            $main_applicant['current_bank'] = sanitize_text_field(implode(', ', $data['main_applicant']['current_bank']));

            $main_applicant['first_income']
                    = get_months_name((int)$data['main_applicant']['first_income']['month']) . ' ' .
                    (int)$data['main_applicant']['first_income']['year'];

            $main_applicant['income_types']         = $this->filter_breakpoints($data['main_applicant']['income_type']);
            $main_applicant['existing_liabilities'] = $this->filter_breakpoints($data['main_applicant']['existing_liabilities']);

            /*  main_applicant end */

            $relationship_type = sanitize_text_field($data['relationship_type']);
            $relationship_who  = sanitize_text_field($data['relationship_who']);

            $property['type']                   = sanitize_text_field($data['type_of_property']);
            $property['variant']                = ($property['type'] === 'Flat' ? sanitize_text_field($data['property_variant']) : '');
            $property['additional_information'] = sanitize_text_field($data['property_additional_information']);

            /* second_applicant */
            if ($relationship_type === 'someelse') {

                $second_applicant['first_name'] = sanitize_text_field($data['second_applicant']['first_name']);
                $second_applicant['last_name']  = sanitize_text_field($data['second_applicant']['last_name']);
                $second_applicant['dob']
                                                = (int)$data['second_applicant']['dob']['day'] . ' ' .
                        get_months_name((int)$data['second_applicant']['dob']['month']) . ' ' .
                        (int)$data['second_applicant']['dob']['year'];
                $second_applicant['phone']      = preg_replace('/[^0-9+]/', '', $data['second_applicant']['phone']);

                $second_applicant['length_of_residency']
                        = sanitize_text_field($data['second_applicant']['length_of_residency']['duration']) . ', From ' .
                        get_months_name((int)$data['second_applicant']['length_of_residency']['from_month']) . ' ' .
                        (int)$data['second_applicant']['length_of_residency']['from_year'];

                $second_applicant['main_nationality']             = sanitize_text_field($data['second_applicant']['main_nationality']);
                $second_applicant['residency_type']               = sanitize_text_field($data['second_applicant']['residency_type']);
                $second_applicant['passport_registration_number'] = preg_replace('/[^0-9\/]/', '', $data['second_applicant']['passport_registration_number']);;

                $second_applicant['multipass'] = sanitize_text_field($data['second_applicant']["passports"]["multipass"]);

                $second_applicant['other_nationalities']
                        = ($second_applicant['multipass'] !== 'yes' || !is_array($data['second_applicant']["passports"]["nationality"])
                        ?: implode(', ', $data['second_applicant']["passports"]["nationality"]));

                $second_applicant['current_bank'] = sanitize_text_field(implode(', ', $data['second_applicant']['current_bank']));

                $second_applicant['first_income']
                        = get_months_name((int)$data['second_applicant']['first_income']['month']) . ' ' .
                        (int)$data['second_applicant']['first_income']['year'];

                $second_applicant['income_types']         = $this->filter_breakpoints($data['second_applicant']['income_type']);
                $second_applicant['existing_liabilities'] = $this->filter_breakpoints($data['second_applicant']['existing_liabilities']);

            }

            /* second_applicant end */


            $new_user_data = array(
                    'ID'         => $calc_user_id,
                    'first_name' => $main_applicant['first_name'],
                    'last_name'  => $main_applicant['last_name'],
            );
            // Обновляем пользователя
            wp_update_user($new_user_data);

            $post_content = $calc->post_content . "\n" . implode("\n", [
                            $relationship_type,
                            $relationship_who,
                            $property['type'],
                            $property['variant'],
                            $property['additional_information'],

                            var_export($main_applicant, true),
                            var_export($second_applicant, true),
                    ]);


            $calc_data = array(
                    'ID'           => $calc_id,
                    'post_content' => $post_content,
                    'post_author'  => $calc_user_id,
            );

            $calc_id = wp_update_post($calc_data);

            if ($calc_id) {
                update_post_meta($calc_id, '_calc_user_lang', get_locale());
                update_post_meta($calc_id, '_calc_status', 2);

                update_post_meta($calc_id, '_relationship_type', $relationship_type);
                update_post_meta($calc_id, '_relationship_who', $relationship_who);
                update_post_meta($calc_id, '_step_one_property', $property);

                update_post_meta($calc_id, '_step_one_main_applicant', $main_applicant);
                update_post_meta($calc_id, '_step_one_second_applicant', $second_applicant);

                $_SESSION['calc-ok'] = 1;

                self::admin_wp_mail($manager_mail ?? get_option('admin_email'), "New data from the form step 1", "New data from the form step 1 for ID: $calc_id");

                wp_redirect(get_permalink(1450));

            }
        } elseif (isset($_POST['step_two']) && is_array($_POST['step_two']) && count($_POST['step_two']) > 0) {
            $data    = $_POST['step_two'];
            $calc_id = (int)$data['calc_id'];
            if (!wp_verify_nonce($_POST['mortgages-form-step-two-field-' . $calc_id], 'mortgages-form-step-two-action-' . $calc_id)) {
                return false;
            }

            if (self::post_type !== get_post_type($calc_id)) {
                return false;
            }

            $calc_user_id = get_post_meta($calc_id, '_calc_user', true);

            if ($calc_user_id != get_current_user_id()) {
                return false;
            }

            $calc = get_post($calc_id);

            $main_applicant = validate_and_sanitize_text($data['main_applicant']);

            $relationship_type = get_post_meta($calc->ID, '_relationship_type', true);

            if ($relationship_type == 'someelse') {
                $second_applicant = validate_and_sanitize_text($data['second_applicant']);
            }

            $post_content = $calc->post_content . "\n" . implode("\n", [
                            var_export($main_applicant, true),
                            var_export($second_applicant ?? '', true),
                    ]);


            $calc_data = array(
                    'ID'           => $calc_id,
                    'post_content' => $post_content,
            );

            $calc_id = wp_update_post($calc_data);

            if ($calc_id) {
                $without_step_three = 0;
                update_post_meta($calc_id, '_calc_status', 4);

                if (key($main_applicant['income_type']['current_tab']) == 'business') {
                    update_post_meta($calc_id, '_without_step_three', 1);
                    $without_step_three = 1;
                }

                update_post_meta($calc_id, '_step_two_main_applicant', $main_applicant);
                update_post_meta($calc_id, '_step_two_second_applicant', $second_applicant ?? null);

                $_SESSION['calc-ok'] = 1;

                self::admin_wp_mail($manager_mail ?? get_option('admin_email'), "New data from the form step 2", "New data from the form step 2 for ID: $calc_id");

                require_once CALCULATOR_PLUGIN_DIR . '/meta-boxes.php';
                ob_start();

                echo "<h3>Mini calculator results</h3>";
                calculator_inbox_mini_calc_results_meta_box($calc);
                echo "<h3>STEP 1</h3>";
                calculator_inbox_form_step_one_meta_box($calc);
                echo "<h3>STEP 2</h3>";
                calculator_inbox_form_step_two_meta_box($calc);

                $broker_message = ob_get_clean();

                $to = get_post_meta($calc_id, '_selected_broker', true);

                self::admin_wp_mail($to, 'Finaram New Request - Step 2', $broker_message);

                $category = get_the_terms($calc_id, Calculator::taxonomy);

                if (
                        get_post_meta($calc_id, "_mini_calc_credit_type", true) != 'housing_renovation' &&
                        $category[0]->slug != 'loans' &&
                        !$without_step_three
                ) { // Это временно или нет
                    $user = get_userdata($calc_user_id);
                    $mail = sendMessage($user->user_email, ['name' => $user->user_firstname . ' ' . $user->user_lastname], 'step_two_approved');
                    if ($mail) {
                        update_post_meta($calc_id, '_step_three_user_email_time', time());
                    }
                } else {
                    $user = get_userdata($calc_user_id);
                    $mail = sendMessage($user->user_email, ['name' => $user->user_firstname . ' ' . $user->user_lastname], 'step_two_completed');
                    if ($mail) {
                        update_post_meta($calc_id, '_step_three_user_email_time', time());
                    }
                }
                wp_redirect(get_permalink(1450));
            }
        } elseif (isset($_POST['step_three']) && is_array($_POST['step_three']) && count($_POST['step_three']) > 0) {
            $data    = $_POST['step_three'];
            $calc_id = (int)$data['calc_id'];
            if (!wp_verify_nonce($_POST['mortgages-form-step-three-field-' . $calc_id], 'mortgages-form-step-three-action-' . $calc_id)) {
                return false;
            }

            if (self::post_type !== get_post_type($calc_id)) {
                return false;
            }

            $calc_user_id = get_post_meta($calc_id, '_calc_user', true);

            if ($calc_user_id != get_current_user_id()) {
                return false;
            }

            $current_tab = key($data['income_type']['current_tab']);
            $mail_sent   = false;
            if (isset($_FILES['step_three']['tmp_name'][$current_tab])) {
                $files = $_FILES['step_three']['tmp_name'][$current_tab];
                $names = $_FILES['step_three']['name'][$current_tab];

                $to      = get_post_meta($calc_id, '_selected_broker', true);
                $subject = 'Files from form step 3';

                $post = get_post($calc_id);
                require_once CALCULATOR_PLUGIN_DIR . '/meta-boxes.php';
                ob_start();

                echo "<h3>Mini calculator results</h3>";
                calculator_inbox_mini_calc_results_meta_box($post);
                echo "<h3>STEP 1</h3>";
                calculator_inbox_form_step_one_meta_box($post);
                echo "<h3>STEP 2</h3>";
                calculator_inbox_form_step_two_meta_box($post);

                $broker_message = ob_get_clean();

                $message   = "\n";
                $message   .= 'Files from form step 3 are attached.';
                $message   .= "\n" . 'If files from the list are missing from attachments, it means that they have an invalid format and have not been validated.';
                $message   .= "\n";
                $file_list = "\n" . 'Income type: ' . calculator_translate($current_tab);
                if (is_countable($names) && count($names) > 0) {
                    foreach ($names as $field_name => $names_docs) {
                        if (is_countable($names_docs) && count($names_docs) > 0) {
                            $file_list .= "\n\n" . calculator_translate($field_name) . ' files:';
                            foreach ($names_docs as $key => $name) {
                                $file_list .= "\n" . $name;
                            }
                        }
                    }
                }
                $message        .= $file_list;
                $broker_message .= nl2br(esc_textarea($message));
                $headers        = array();

                $attachments = array();
                if (is_countable($files) && count($files) > 0) {
                    foreach ($files as $field_name => $field_docs) {
                        if (is_countable($files) && count($files) > 0) {
                            foreach ($field_docs as $key => $tmp_name) {
                                if (!empty($tmp_name) && is_uploaded_file($tmp_name)) {
                                    $file_type     = mime_content_type($tmp_name);
                                    $allowed_types = array('image/png', 'image/jpeg', 'application/pdf'); // Разрешенные типы файлов
                                    if (in_array($file_type, $allowed_types)) {
                                        $attachments[$names[$field_name][$key]] = $tmp_name;
                                    }
                                }
                            }
                        }
                    }
                }
                $try = 0;
                do {
                    $mail_sent = self::admin_wp_mail($to, $subject, $broker_message, $headers, $attachments);
                    $try++;
                } while (!$mail_sent && $try < 3);
            }
            if ($mail_sent) {
                update_post_meta($calc_id, '_calc_status', 6);

                update_post_meta($calc_id, '_step_three_email_message', $message);
                update_post_meta($calc_id, '_step_three_email_time', time());
                update_post_meta($calc_id, '_step_three_email_error', 0);

                $_SESSION['calc-ok'] = 1;

                if (get_post_meta($calc_id, "_mini_calc_credit_type", true) != 'housing_renovation') { // Это временно
                    $user = get_userdata($calc_user_id);
                    $mail = sendMessage($user->user_email, ['name' => $user->user_firstname . ' ' . $user->user_lastname], 'step_three_completed');
                    if ($mail) {
                        update_post_meta($calc_id, '_step_three_user_email_time', time());
                    }
                }
            } else {
//                update_post_meta($calc_id, '_calc_status', 6);

                update_post_meta($calc_id, '_step_three_email_message', 'Warning! The files were NOT sent due to an unforeseen error and were lost without trace.' . "\n" . $file_list);
                update_post_meta($calc_id, '_step_three_email_time', time());
                update_post_meta($calc_id, '_step_three_email_error', 1);

            }

            wp_redirect(get_permalink(1450));

        }
        return false;
    }

    private function filter_breakpoints($data): array
    {
        $i          = 0;
        $first_sort = [];
        $result     = [];
        foreach ($data as $item) {
            if (key($item) === 'breakpoint') {
                $i++;
            } else {
                $first_sort[$i][] = $item;
            }
        }

        foreach ($first_sort as $i => $values) {
            $second_sort = [];
            foreach ($values as $value) {
                if (key($value) === 'current_tab') {
                    $current_tab = key(end($value));
                }
                switch ($this->array_depth($value)) {
                    case 3:
                        $second_sort[key($value)][key(end($value))][key(end(end($value)))] = sanitize_text_field(end(end(end($value))));
                        break;
                    case 2:
                        $second_sort[key($value)][key(end($value))] = sanitize_text_field(end(end($value)));
                        break;
                    default:
                        $second_sort[key($value)] = sanitize_text_field(end($value));
                }
            }
            if ($second_sort[$current_tab]) {
                $result[$current_tab][] = $second_sort[$current_tab];
            }
        }
        return $result;
    }

    private function array_depth($array)
    {
        if (!is_array($array)) {
            return 0;
        }

        $max_depth = 1;
        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = $this->array_depth($value) + 1;
                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }

        return $max_depth;
    }

    function calculator_admin_enqueue_scripts($hook_suffix)
    {
        if (false === strpos($hook_suffix, 'calculator')) {
            return;
        }

        wp_enqueue_style('calculator-admin',
                CALCULATOR_PLUGIN_URL . '/css/style.css',
                array(), CALCULATOR_VERSION, 'all'
        );

        if (is_rtl()) {
            wp_enqueue_style('calculator-admin-rtl',
                    CALCULATOR_PLUGIN_URL . '/css/style-rtl.css',
                    array(), CALCULATOR_VERSION, 'all'
            );
        }

        wp_enqueue_script('calculator-admin',
                CALCULATOR_PLUGIN_URL . '/js/script.js',
                array('postbox'), CALCULATOR_VERSION, true
        );

        $current_screen = get_current_screen();

        wp_localize_script('calculator-admin', 'calculator', array(
                'screenId' => $current_screen->id,
        ));
    }

    function calculator_admin_updated_message()
    {
        if (empty($_REQUEST['message'])) {
            return;
        }

        if ('inboxupdated' == $_REQUEST['message']) {
            $message = __('Request updated.', 'calculator');
        } elseif ('inboxtrashed' == $_REQUEST['message']) {
            $message = __('Request trashed.', 'calculator');
        } elseif ('inboxuntrashed' == $_REQUEST['message']) {
            $message = __('Request restored.', 'calculator');
        } elseif ('inboxdeleted' == $_REQUEST['message']) {
            $message = __('Request deleted.', 'calculator');
        }

        if (isset($message) and '' !== $message) {
            echo sprintf(
                    '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>',
                    esc_html($message)
            );
        }
    }

    public function calculator_load_textdomain()
    {
        load_plugin_textdomain('calculator', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function export_csv_calculators()
    {
        if (empty($_GET['export-calculators'])) {
            return;
        }

        $args = array(
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
        );

        if (!empty($_REQUEST['s'])) {
            $args['s'] = $_REQUEST['s'];
        }
        if (!empty($_REQUEST['m'])) {
            $args['m'] = $_REQUEST['m'];
        }

        if (!empty($_REQUEST['orderby'])) {
            switch ($_REQUEST['orderby']) {
                case 'user':
                    $args['meta_key'] = '_calc_user';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'credit_type':
                    $args['meta_key'] = '_mini_calc_credit_type';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'currency':
                    $args['meta_key'] = '_mini_calc_currency';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'currency_rate':
                    $args['meta_key'] = '_mini_calc_currency_rate';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'loan_amount':
                    $args['meta_key'] = '_mini_calc_loan_amount';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'total_payment_amount':
                    $args['meta_key'] = '_mini_calc_total_payment_amount';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'interest_rate':
                    $args['meta_key'] = '_mini_calc_interest_rate';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'rpsn':
                    $args['meta_key'] = '_mini_calc_rpsn';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'insurance_payment':
                    $args['meta_key'] = '_mini_calc_insurance_payment';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'monthly_payment':
                    $args['meta_key'] = '_mini_calc_monthly_payment';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'status':
                    $args['meta_key'] = '_calc_status';
                    $args['orderby']  = 'meta_value';
                    break;
            }
        }

        if (!empty($_REQUEST['order'])
                and 'asc' == strtolower($_REQUEST['order'])) {
            $args['order'] = 'ASC';
        }


        if (!empty($_REQUEST['credit_type'])) {
            $args['meta_query'] = [[
                    'key'   => '_mini_calc_credit_type',
                    'value' => $_REQUEST['credit_type'],
            ]];
        }

        if (!empty($_REQUEST['currency'])) {
            $args['meta_query'] = [[
                    'key'   => '_mini_calc_currency',
                    'value' => $_REQUEST['currency'],
            ]];
        }

        if (!empty($_REQUEST['category'])) {
            $args['tax_query'] = [[
                    'taxonomy' => Calculator::taxonomy,
                    'field'    => 'slug',
                    'terms'    => $_REQUEST['category'],
            ]];
        }

        if (!empty($_REQUEST['post_status'])) {
            if ('trash' == $_REQUEST['post_status']) {
                $args['post_status'] = 'trash';
                $this->is_trash      = true;
            }
        }

        $data_array  = self::find($args);
        $csv_headers = array("ID", "User", "Credit type", "Loan amount", "Total payment", "Interest rate", "Monthly payment", "Date", "Status", "UTM Source", "UTM Medium", "UTM Campaign", "UTM Term", "UTM Content");
        $temp_file   = fopen('php://temp', 'w');
        fputcsv($temp_file, $csv_headers);
        foreach ($data_array as $item) {
            $columns = [];
            $columns = [];

            // ID
            $columns['id'] = $item->ID;

            // User
            $columns['user'] = (is_array($item->meta['_calc_user']) && $user_id = array_shift($item->meta['_calc_user']))
                    ? get_userdata($user_id)->user_email
                    : '';

            // Credit Type
            $columns['credit_type'] = (is_array($item->meta['_mini_calc_credit_type']))
                    ? Calculator::translate_credit_type(array_shift($item->meta['_mini_calc_credit_type']))
                    : '';

            // Loan Amount
            $columns['loan_amount'] = (is_array($item->meta['_mini_calc_loan_amount']) && $item->meta['_mini_calc_loan_amount'][0] != 0)
                    ? array_shift($item->meta['_mini_calc_loan_amount']) . ' ' . strtoupper($item->meta['_mini_calc_currency'][0])
                    : '';

            // Total Payment Amount
            $columns['total_payment_amount'] = (is_array($item->meta['_mini_calc_total_payment_amount']) && $item->meta['_mini_calc_total_payment_amount'][0] != 0)
                    ? array_shift($item->meta['_mini_calc_total_payment_amount']) . ' ' . strtoupper($item->meta['_mini_calc_currency'][0])
                    : '';

            // Interest Rate
            $columns['interest_rate'] = (is_array($item->meta['_mini_calc_interest_rate']) && $item->meta['_mini_calc_interest_rate'][0] != 0)
                    ? array_shift($item->meta['_mini_calc_interest_rate']) . ' %'
                    : '';

            // RPSN
            $columns['rpsn'] = (is_array($item->meta['_mini_calc_rpsn']))
                    ? array_shift($item->meta['_mini_calc_rpsn']) . ' %'
                    : '';

            // Insurance Payment
            $columns['insurance_payment'] = (is_array($item->meta['_mini_calc_insurance_payment']))
                    ? array_shift($item->meta['_mini_calc_insurance_payment']) . ' ' . strtoupper($item->meta['_mini_calc_currency'][0])
                    : '';

            // Monthly Payment
            $columns['monthly_payment'] = (is_array($item->meta['_mini_calc_monthly_payment']) && $item->meta['_mini_calc_monthly_payment'][0] != 0)
                    ? array_shift($item->meta['_mini_calc_monthly_payment']) . ' ' . strtoupper($item->meta['_mini_calc_currency'][0])
                    : '';

            // Date
            $datetime        = get_post_datetime($item->ID);
            $columns['date'] = ($datetime !== false)
                    ? sprintf(
                            __('%1$s at %2$s', 'calculator'),
                            $datetime->format(__('d.m.Y', 'calculator')),
                            $datetime->format(__('G:i', 'calculator'))
                    )
                    : '';

            // Status
            if (is_array($item->meta['_calc_status'])) {
                switch (array_shift($item->meta['_calc_status'])) {
                    case '-1':
                        $status = __('Denied', 'calculator');
                        break;
                    case '0':
                        $status = __('Unknown request', 'calculator');
                        break;
                    case '1':
                        $status = __('User registered', 'calculator');
                        break;
                    case '2':
                        $status = __('Finish step 1', 'calculator');
                        break;
                    case '3':
                        $status = __('Waiting start step 2', 'calculator');
                        break;
                    case '4':
                        $status = __('Finish step 2', 'calculator');
                        break;
                    case '5':
                        $status = __('Waiting start step 3', 'calculator');
                        break;
                    case '6':
                        $status = __('Finish step 3', 'calculator');
                        break;
                    case '9':
                        $status = __('Finish All-In-One', 'calculator');
                        break;
                    case '10':
                        $status = __('Waiting Documents', 'calculator');
                        break;
                    case '11':
                        $status = __('Finish Documents', 'calculator');
                        break;
                    default:
                        $status = 'Invalid data';
                        break;
                }
            } else {
                $status = 'Invalid data';
            }
            $columns['status'] = $status;

            foreach (['_calc_utm_source', '_calc_utm_medium', '_calc_utm_campaign', '_calc_utm_term', '_calc_utm_content'] as $utm_key) {
                $columns[$utm_key] = (is_array($item->meta[$utm_key]) && !empty($item->meta[$utm_key][0]))
                    ? $item->meta[$utm_key][0]
                    : '';
            }

            fputcsv($temp_file, array_values($columns));
        }
        rewind($temp_file);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv"');
        fpassthru($temp_file);
        fclose($temp_file);
        exit;
    }

    /*
                 * deprecated
        public function save_mini_calc_form_data()
        {
            if (!wp_verify_nonce($_POST['nonce_code'], 'front-nonce')) {
                wp_die('Stop! (9)');
            }

            if (is_array($_POST) && count($_POST) > 0) {
                $data = $_POST;

                self::console_log($data);

    //            if (is_user_logged_in() && get_actual_user_calc()) { // Если авторизован и есть обращение в работе, то не создаем новые.
    //                echo json_encode([
    //                    'success' => true,
    //                    'url'     => get_permalink(1370)
    //                ]);
    //                wp_die();
    //            }

                $category             = sanitize_text_field($data['calc_category']);
                $credit_type          = sanitize_text_field($data['calc_tab']);
                $currency_rate        = sanitize_text_field($data['calc_currency']);
                $currency             = (float)str_replace(',', '', $data['calc_currency_rate']);
                $loan_amount          = (float)str_replace(',', '', $data['calc_loan_amount']);
                $total_payment_amount = (float)str_replace(',', '', $data['calc_total_payment_amount']);
                $interest_rate        = (float)str_replace(',', '', $data['calc_interest_rate']);
                $rpsn                 = (float)str_replace(',', '', $data['calc_rpsn']);
                $insurance_payment    = (float)str_replace(',', '', $data['calc_insurance_payment']);
                $monthly_payment      = (float)str_replace(',', '', $data['calc_monthly_payment']);
                $duration             = (float)str_replace(',', '', $data['calc_duration']);

                $datetime = date_create();
                $datetime->setTimezone(wp_timezone());

                $post_content = implode("\n", [
                    $currency_rate,
                    $currency,
                    $loan_amount,
                    $total_payment_amount,
                    $interest_rate,
                    $rpsn,
                    $insurance_payment,
                    $monthly_payment,
                    $duration,
                    self::translate_credit_type($credit_type),
                ]);

                $post_data = array(
                    'post_title'   => self::translate_credit_type($credit_type),
                    'post_content' => $post_content,
                    'post_type'    => self::post_type,
                    'post_status'  => 'publish',
                    'post_date'    => $datetime->format('Y-m-d H:i:s'),
                );
                $post_id   = wp_insert_post($post_data);

                if ($post_id) {
                    wp_set_object_terms($post_id, $category, self::taxonomy);

                    update_post_meta($post_id, '_mini_calc_credit_type', $credit_type);
                    update_post_meta($post_id, '_mini_calc_currency', $currency_rate);
                    update_post_meta($post_id, '_mini_calc_currency_rate', $currency);
                    update_post_meta($post_id, '_mini_calc_loan_amount', $loan_amount);
                    update_post_meta($post_id, '_mini_calc_total_payment_amount', $total_payment_amount);
                    update_post_meta($post_id, '_mini_calc_interest_rate', $interest_rate);
                    update_post_meta($post_id, '_mini_calc_rpsn', $rpsn);
                    update_post_meta($post_id, '_mini_calc_insurance_payment', $insurance_payment);
                    update_post_meta($post_id, '_mini_calc_monthly_payment', $monthly_payment);
                    update_post_meta($post_id, '_mini_calc_duration', $duration);
                    update_post_meta($post_id, '_calc_status', 0);
                    update_post_meta($post_id, '_calc_user_lang', ICL_LANGUAGE_CODE);

                    if (is_user_logged_in()) { // Этот сценарий должен быть не возможен на начальных стадиях проекта
                        update_post_meta($post_id, '_calc_user', get_current_user_id());
                        update_post_meta($post_id, '_calc_status', 1);
                    }
                    $_SESSION['calc_ids'][] = $post_id; // храним все, но пока не будет возможности управлять своими заявками, так что если их будет много, то привяжем последнюю

                    echo json_encode([
                        'success' => true,
    //                    'url'     => (is_user_logged_in() ? get_permalink(1374) : get_permalink(1370)) //первый шаг или авторизация
                        'url'     => get_permalink(1370) // авторизация, а там разберутся
                    ]);
                    wp_die();

                }
                echo json_encode(['success' => false]);
            }
            wp_die();
        }
        */

    public function go_aio_from_calc()
    {
        if (!wp_verify_nonce($_POST['nonce_code'], 'front-nonce')) {
            wp_die('Stop! (9)');
        }

        if (is_array($_POST) && count($_POST) > 0) {
            $data = $_POST;

            if (is_user_logged_in() && get_actual_user_calc()) { // Если авторизован и есть обращение в работе, то не создаем новые.
                echo json_encode([
                        'success' => true,
                        'url'     => get_permalink(1370) // Autorizace
                ]);
                wp_die();
            }

            $category             = sanitize_text_field($data['calc_category']);
            $credit_type          = sanitize_text_field($data['calc_tab']);
            $currency_rate        = sanitize_text_field($data['calc_currency']);
            $currency             = (float)str_replace(',', '', $data['calc_currency_rate']);
            $loan_amount          = (float)str_replace(',', '', $data['calc_loan_amount']);
            $total_payment_amount = (float)str_replace(',', '', $data['calc_total_payment_amount']);
            $interest_rate        = (float)str_replace(',', '', $data['calc_interest_rate']);
            $rpsn                 = (float)str_replace(',', '', $data['calc_rpsn']);
            $insurance_payment    = (float)str_replace(',', '', $data['calc_insurance_payment']);
            $monthly_payment      = (float)str_replace(',', '', $data['calc_monthly_payment']);
            $duration             = (float)str_replace(',', '', $data['calc_duration']);

            $datetime = date_create();
            $datetime->setTimezone(wp_timezone());

            $post_content = implode("\n", [
                    $currency_rate,
                    $currency,
                    $loan_amount,
                    $total_payment_amount,
                    $interest_rate,
                    $rpsn,
                    $insurance_payment,
                    $monthly_payment,
                    $duration,
                    self::translate_credit_type($credit_type),
            ]);

            $post_data = array(
                    'post_title'   => self::translate_credit_type($credit_type),
                    'post_content' => $post_content,
                    'post_type'    => self::post_type,
                    'post_status'  => 'publish',
                    'post_date'    => $datetime->format('Y-m-d H:i:s'),
            );
            $post_id   = wp_insert_post($post_data);

            if ($post_id) {
                wp_set_object_terms($post_id, $category, self::taxonomy);

                update_post_meta($post_id, '_mini_calc_credit_type', $credit_type);
                update_post_meta($post_id, '_mini_calc_currency', $currency_rate);
                update_post_meta($post_id, '_mini_calc_currency_rate', $currency);
                update_post_meta($post_id, '_mini_calc_loan_amount', $loan_amount);
                update_post_meta($post_id, '_mini_calc_total_payment_amount', $total_payment_amount);
                update_post_meta($post_id, '_mini_calc_interest_rate', $interest_rate);
                update_post_meta($post_id, '_mini_calc_rpsn', $rpsn);
                update_post_meta($post_id, '_mini_calc_insurance_payment', $insurance_payment);
                update_post_meta($post_id, '_mini_calc_monthly_payment', $monthly_payment);
                update_post_meta($post_id, '_mini_calc_duration', $duration);
                update_post_meta($post_id, '_calc_status', 0);
                update_post_meta($post_id, '_calc_user_lang', ICL_LANGUAGE_CODE);

                foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'] as $utm_key) {
                    $data_key = 'calc_' . $utm_key;
                    if (!empty($data[$data_key])) {
                        update_post_meta($post_id, '_calc_' . $utm_key, sanitize_text_field($data[$data_key]));
                    }
                }

                if (is_user_logged_in()) { // Этот сценарий должен быть не возможен на начальных стадиях проекта
                    update_post_meta($post_id, '_calc_user', get_current_user_id());
                    update_post_meta($post_id, '_calc_status', 1); // User registered
                }
//                $_SESSION['calc_ids'][] = $post_id; // Чет не работает
                add_guest_calc_id($post_id);// храним все, но пока не будет возможности управлять своими заявками, так что если их будет много, то привяжем последнюю

                echo json_encode([
                        'success' => true,
                        'url'     => get_permalink(1370) // авторизация, а там разберутся
                ]);
                wp_die();

            }
            echo json_encode(['success' => false]);
        }
        wp_die();
    }

    public function register_calculator_category_taxonomy()
    {
        register_taxonomy(self::taxonomy, array(self::post_type), array(
                'label'             => __('Calculator category'),
                'hierarchical'      => true,      // true - по типу рубрик, false - по типу меток, по умолчанию - false
                'public'            => false,     // каждый может использовать таксономию, либо только администраторы, по умолчанию - true
                'show_in_nav_menus' => false,     // добавить на страницу создания меню
                'show_ui'           => false,
                'show_in_menu'      => false,
                'show_admin_column' => false,
                'show_in_rest'      => false,    // добавить интерфейс создания и редактирования
                'show_tagcloud'     => false,    // нужно ли разрешить облако тегов для этой таксономии
                'query_var'         => true,
        ));
    }

    public function register_calculator_post_type()
    {
        register_post_type(self::post_type, [
                'label'       => null,
                'labels'      => [
                        'name'               => __('Calculator', 'calculator'),
                        'singular_name'      => __('Calculator', 'calculator'),
                        'add_new'            => __('Add Calculator', 'calculator'),
                        'add_new_item'       => __('Add new Calculator', 'calculator'),
                        'edit_item'          => __('Edit Calculator', 'calculator'),
                        'new_item'           => __('New Calculator', 'calculator'),
                        'all_items'          => __('All Calculators', 'calculator'),
                        'view_item'          => __('Calculator', 'calculator'),
                        'search_items'       => __('Find Calculator', 'calculator'),
                        'not_found'          => __('Calculators not found', 'calculator'),
                        'not_found_in_trash' => __('Trash is empty', 'calculator'),
                        'menu_name'          => __('Calculator', 'calculator'),
                ],
                'public'      => false,
                'show_ui'     => false,
                'has_archive' => false,
            //            'menu_icon' => 'dashicons-grid-view',
            //            'menu_position' => 20,
                'supports'    => array('title'), // , 'editor', 'custom-fields'
                'taxonomies'  => array(self::taxonomy),
        ]);
    }

    public function add_calculator_menu()
    {
        add_menu_page(
                __('Calculator forms', 'calculator'),
                __('Calculator forms', 'calculator'),
                'manage_options',
                'calculator-inbox',
                [$this, 'get_calculator_inbox'],
                'dashicons-welcome-widgets-menus'
        );

        $hook = add_submenu_page(
                'calculator-inbox',
                __('Calculator inbox', 'calculator'),
                __('Calculator inbox', 'calculator'),
                'manage_options',
                'calculator-inbox',
                [$this, 'get_calculator_inbox']
        );
        add_action("load-$hook", [$this, 'get_calculator_inbox_load']);

        //        add_submenu_page(
        //            'calculator-inbox',
        //            __('Calculator settings', 'calculator'),
        //            __('Calculator settings', 'calculator'),
        //            'manage_options',
        //            'calculator-settings',
        //            [$this, 'get_calculator_template']
        //        );
    }

    public static function find($args = '')
    {
        $defaults = array(
                'posts_per_page' => 10,
                'offset'         => 0,
                'orderby'        => 'ID',
                'order'          => 'ASC',
                'meta_key'       => '',
                'meta_value'     => '',
                'post_status'    => 'any',
                'tax_query'      => array(),
        );

        $args = wp_parse_args($args, $defaults);

        $args['post_type'] = self::post_type;

        $q     = new WP_Query();
        $posts = $q->query($args);
        //dd($q->request);
        self::$found_items = $q->found_posts;

        foreach ((array)$posts as &$post) {
            $post->meta = get_post_meta($post->ID);
        }
        return $posts;
    }

    public
    static function count($args = '')
    {
        if ($args) {
            $args = wp_parse_args($args, array(
                    'offset'      => 0,
                    'post_status' => 'publish',
            ));

            self::find($args);
        }

        return absint(self::$found_items);
    }

    public function get_calculator_inbox_load()
    {
        $action = calculator_current_action();

        $redirect_to = menu_page_url('calculator-inbox', false);

        if (isset($_GET['post_status'])) {
            $redirect_to = add_query_arg(
                    array(
                            'post_status' => $_GET['post_status'],
                    ),
                    $redirect_to
            );
        }

        if ('save' === $action and !empty($_REQUEST['post'])) {
            $post = get_post($_REQUEST['post']);

            if (!empty($post)) {
                if (!current_user_can('calculator_edit_inbox', $post->ID)
                        || Calculator::post_type !== get_post_type($post)) {
                    wp_die(__('You are not allowed to edit this item.', 'calculator'));
                }

                check_admin_referer('calculator-update-inbox_' . $post->ID);

                $status = $_POST['inbox']['status'] ?? '';
                update_post_meta($post->ID, '_calc_status', (int)$status);

                $redirect_to = add_query_arg(
                        array(
                                'action'  => 'edit',
                                'post'    => $post->ID,
                                'message' => 'inboxupdated',
                        ), $redirect_to
                );
            }

            wp_safe_redirect($redirect_to);
            exit();
        }

        if ('trash' === $action and !empty($_REQUEST['post'])) {
            if (!is_array($_REQUEST['post'])) {
                check_admin_referer(
                        'calculator-trash-inbox-message_' . $_REQUEST['post']
                );
            } else {
                check_admin_referer('bulk-posts');
            }

            $trashed = 0;

            foreach ((array)$_REQUEST['post'] as $post_id) {
                $post = get_post($post_id);

                if (empty($post) || Calculator::post_type !== get_post_type($post)) {
                    continue;
                }

                if (!current_user_can(
                        'calculator_edit_inbox', $post->ID)) {
                    wp_die(__('You are not allowed to move this item to the Trash.', 'calculator'));
                }

                if (!EMPTY_TRASH_DAYS) {
                    $post_status = wp_delete_post($post->ID, true);
                } else {
                    $post_status = wp_trash_post($post->ID);
                }

                if (!$post_status) {
                    wp_die(__('Error in moving to Trash.', 'calculator'));
                }

                $trashed += 1;
            }

            if (!empty($trashed)) {
                $redirect_to = add_query_arg(
                        array(
                                'message' => 'inboxtrashed',
                        ),
                        $redirect_to
                );
            }

            wp_safe_redirect($redirect_to);
            exit();
        }

        if ('untrash' === $action and !empty($_REQUEST['post'])) {
            if (!is_array($_REQUEST['post'])) {
                check_admin_referer(
                        'calculator-untrash-inbox-message_' . $_REQUEST['post']
                );
            } else {
                check_admin_referer('bulk-posts');
            }

            $untrashed = 0;

            foreach ((array)$_REQUEST['post'] as $post_id) {
                $post = get_post($post_id);

                if (empty($post) || Calculator::post_type !== get_post_type($post)) {
                    continue;
                }

                if (!current_user_can(
                        'calculator_edit_inbox', $post->ID)) {
                    wp_die(__('You are not allowed to restore this item from the Trash.', 'calculator'));
                }

                if (!wp_untrash_post($post->ID)) {
                    wp_die(__('Error in restoring from Trash.', 'calculator'));
                }
                // Обновляем статус поста на publish
                wp_update_post(array(
                        'ID'          => $post->ID,
                        'post_status' => 'publish',
                ));

                $untrashed += 1;
            }

            if (!empty($untrashed)) {
                $redirect_to = add_query_arg(
                        array(
                                'message' => 'inboxuntrashed',
                        ), $redirect_to
                );
            }

            wp_safe_redirect($redirect_to);
            exit();
        }

        if ('delete_all' === $action) {
            //            dd(123);
            check_admin_referer('bulk-posts');

            $_REQUEST['post'] = $this->calculator_get_all_ids_in_trash();

            $action = 'delete';
        }

        if ('delete' === $action and !empty($_REQUEST['post'])) {
            if (!is_array($_REQUEST['post'])) {
                check_admin_referer(
                        'calculator-delete-inbox-message_' . $_REQUEST['post']
                );
            } else {
                check_admin_referer('bulk-posts');
            }

            $deleted = 0;

            foreach ((array)$_REQUEST['post'] as $post_id) {
                $post = get_post($post_id);

                if (empty($post) || Calculator::post_type !== get_post_type($post)) {
                    continue;
                }

                if (!current_user_can(
                        'calculator_edit_inbox', $post->ID)) {
                    wp_die(__('You are not allowed to delete this item.', 'calculator'));
                }

                if (!wp_delete_post($post->ID, true)) {
                    wp_die(__('Error in deleting.', 'calculator'));
                }

                $deleted += 1;
            }

            if (!empty($deleted)) {
                $redirect_to = add_query_arg(
                        array(
                                'message' => 'inboxdeleted',
                        ),
                        $redirect_to
                );
            }

            wp_safe_redirect($redirect_to);
            exit();
        }

        if ('edit' === $action) {
            $post_id = isset($_REQUEST['post']) ? (int)$_REQUEST['post'] : 0;

            if (!$post_id) {
                wp_safe_redirect($redirect_to);
                exit();
            }

            if (!current_user_can('calculator_edit_inbox', $post_id)
                    || Calculator::post_type !== get_post_type($post_id)) {
                wp_die(__("You are not allowed to edit this item.", 'calculator'));
            }

            $post         = get_post($post_id);
            $post->status = get_post_meta($post_id, '_calc_status', true);

            if (!$post) {
                return;
            }

            add_meta_box('step_status_div', __('Step status', 'calculator'),
                    'calculator_inbox_step_status_meta_box', null, 'side', 'core'
            );


            if (isset($post->status) && ($post->status >= 0 || $post->status == -1)) {
                add_meta_box('mini_calc_results_div', __('Mini calculator results', 'calculator'),
                        'calculator_inbox_mini_calc_results_meta_box', null, 'normal', 'core'
                );
            }

            //status = 1 just register
            if (isset($post->status) && ($post->status >= 2 || $post->status == -1)) {
                add_meta_box('broker_div', __('Broker selection', 'calculator'),
                        'calculator_inbox_broker_meta_box', null, 'side', 'core'
                );
            }

            if (($post->status) && ($post->status >= 2 && $post->status < 9)) {
                if (isset($post->status) && ($post->status >= 2 || $post->status == -1)) {
                    add_meta_box('form_step_one_div', __('Step 1', 'calculator'),
                            'calculator_inbox_form_step_one_meta_box', null, 'normal', 'core'
                    );
                    add_meta_box('verdict_div', __('Verdict', 'calculator'),
                            'calculator_inbox_verdict_meta_box', null, 'side', 'core'
                    );
                }

                if (isset($post->status) && $post->status >= 4) {
                    add_meta_box('inbox_form_step_two_div', __('Step 2', 'calculator'),
                            'calculator_inbox_form_step_two_meta_box', null, 'normal', 'core'
                    );
                }
                if (isset($post->status) && $post->status >= 6) {
                    add_meta_box('form_step_three_div', __('Step 3', 'calculator'),
                            'calculator_inbox_form_step_three_meta_box', null, 'normal', 'core'
                    );
                }
            }
            if (isset($post->status) && $post->status >= 7) {
                add_meta_box('form_step_aio_div', __('Step All-In-One', 'calculator'),
                        'calculator_inbox_form_step_aio_meta_box', null, 'normal', 'core'
                );
                if (isset($post->status) && $post->status >= 11) {
                    add_meta_box('form_step_doc_div', __('Documents', 'calculator'),
                            'calculator_inbox_form_step_doc_meta_box', null, 'normal', 'core'
                    );
                }
            }


        } else {
            if (!class_exists('Calculator_List_Table')) {
                require_once CALCULATOR_PLUGIN_DIR . '/class.calculator-list.php';
            }

            $current_screen = get_current_screen();

            add_filter('manage_' . $current_screen->id . '_columns',
                    array('Calculator_List_Table', 'define_columns'),
                    10, 0
            );

            add_screen_option('per_page', array(
                    'default' => 20,
                    'option'  => 'calculator_inbox_messages_per_page',
            ));
        }
    }

    public function calculator_get_all_ids_in_trash()
    {
        global $wpdb;

        $q = "SELECT ID FROM $wpdb->posts WHERE post_status = 'trash'"
                . $wpdb->prepare(" AND post_type = %s", self::post_type);

        return $wpdb->get_col($q);
    }

    public function get_calculator_inbox()
    {
        if ('edit' === calculator_current_action()) {
            $this->calculator_inbox_edit_page();
            return;
        }

        $list_table = new Calculator_List_Table();
        $list_table->prepare_items();

        ?>
        <div class="wrap">

            <h1 class="wp-heading-inline"><?php echo get_admin_page_title() ?></h1>

            <?php
            if (!empty($_REQUEST['s'])) {
                echo sprintf('<span class="subtitle">'
                        . __('Search results for &#8220;%s&#8221;', 'calculator')
                        . '</span>', esc_html($_REQUEST['s']));
            }
            ?>

            <hr class="wp-header-end">

            <?php do_action('calculator_admin_updated_message'); ?>

            <?php $list_table->views(); ?>

            <form method="get" action="">
                <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>"/>
                <input type="hidden" name="post_status"
                       value="<?php echo isset($_REQUEST['post_status']) ? esc_attr($_REQUEST['post_status']) : ''; ?>"/>
                <?php $list_table->search_box(__('Search', 'calculator'), 'calculator-list'); ?>
                <?php $list_table->display(); ?>
            </form>

        </div>
        <?php
    }

    public function calculator_inbox_edit_page()
    {
        $post = get_post($_REQUEST['post']);


        if (empty($post) || Calculator::post_type !== get_post_type($post)) {
            return;
        }

        require_once CALCULATOR_PLUGIN_DIR . '/meta-boxes.php';

        include CALCULATOR_PLUGIN_DIR . '/edit-inbox-form.php';
    }

    public static function translate_credit_type($type)
    {
        $credit_arr = self::get_translate_credit_type_arr();
        return $credit_arr[$type];
    }

    public static function get_translate_credit_type_arr(): array
    {
        return [
                'new_mortgage'       => __("New mortgage", 'finanzia'),
                'refinancing'        => __("Refinancing", 'finanzia'),
                'american_mortgage'  => __("American mortgage", 'finanzia'),
                'housing_renovation' => __("Housing & Renovation", 'finanzia'),

                'simple_mortgage' => __("Simple Mortgage", 'finanzia'),

                'auto_loan'                 => __("Auto Loan", 'finanzia'),
                'personal_loan'             => __("Personal Loan", 'finanzia'),
                'refinancing_consolidation' => __("Refinancing & Consolidation", 'finanzia'),


                'maximum_mortgage'    => __("Maximum mortgage", 'finanzia'),          // maximum_mortgage_calculator
                'rent_mortgage'       => __("Mortgage vs. Rent", 'finanzia'),         // mortgage_vs_rent_calculator
                'investment_mortgage' => __("Mortgage as an Investment", 'finanzia'), // mortgage_vs_investment_calculator
        ];
    }

    public function get_calculator_template()
    {
        return;
        // Сохранение настроек
        /*
        if ($_POST) {
            update_option('manager_mail', stripslashes($_POST['manager_mail']));

            echo '<div id="message" class="updated fade"><p><strong>Успешно сохраненно</strong></p></div>';
        }
        ?>
        <div class="wrap">
            <h3><?= get_admin_page_title(); ?></h3>
            <?php if (isset($_GET['send_test_mail'])) : ?>
                <form method="get" novalidate="novalidate">
                    <input type="hidden" name="page" value="calculator-settings">
                    <input type="text" name="send_test_mail" style="width: 500px;"
                           value="<?= $_GET['send_test_mail'] ?>">
                    <button type="submit"><?php _e("Send", 'finanzia'); ?></button>
                </form>
            <?php endif; ?>

            <form method="post" novalidate="novalidate">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label><?php _e("Manager  Email", 'calculator'); ?></label></th>
                        <td>
                            <label style="qwe">
                                <input type="text" name="manager_mail" size="30"
                                       value="<?php echo get_option('manager_mail'); ?>">
                                <small>The email address where the documents from step three will be sent.</small>
                            </label>
                        </td>
                    </tr>

                </table>

                <p class="submit">
                    <input type="submit" class="button button-primary button-large"
                           value="<?php _e("Save", 'calculator'); ?>"/>
                </p>

            </form>

        </div>
        <?php
        */
    }

    public static function admin_wp_mail($to, $subject, $message, $headers = '', $attachments = array())
    {
        add_filter('wp_mail_content_type', function () {
            return 'text/html';
        });

        add_filter('wp_mail_from', function ($email) {
            return get_field('contact_email', 'option');
        });

        add_filter('wp_mail_from_name', function ($name) {
            return get_bloginfo('name');
        });

        return wp_mail($to, $subject, $message, $headers, $attachments);

    }

    private static function console_log()
    {
        if (!func_num_args()) {
            return; # Аргументы не переданы
        }

        $folder = dirname(__FILE__) . '/log/' . date("Y") . '/' . date("m") . '/';

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $log_name = $folder . date("Y-m-d") . '.log'; // Раскидываем по дате
        $f        = fopen($log_name, "a");
        fwrite($f, '[' . date("Y-m-d H:i:s") . '] ');
        foreach (func_get_args() as $arg) {
            if (is_bool($arg)) {
                $s = $arg ? 'TRUE' : 'FALSE';
            } elseif (is_array($arg) || is_object($arg)) {
                $s = print_r($arg, true);
            } else {
                $s = $arg;
            }
            fwrite($f, $s . ' '); # вывод аргументов разделяется пробелом
        }
        fwrite($f, "\n");
        fclose($f);
    }

}

