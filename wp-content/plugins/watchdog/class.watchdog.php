<?php

class Watchdog
{
    const post_type = 'watchdog';

    const types
        = [
            'new_mortgage',
            'refinancing',
            'american_mortgage',
            'housing_renovation',
            'refinancing_consolidation',
            'personal_loan',
            'auto_loan',
        ];
    private static $found_items = 0;

    public function __construct()
    {
        // Create dashboard post type
        add_action('init', [$this, 'register_watchdog_post_type']);
        add_action('admin_menu', [$this, 'add_watchdog_menu']);

        add_filter('set_screen_option_' . 'watchdogs_per_page', function ($status, $option, $value) {
            return (int)$value;
        }, 10, 3);

        add_action('init', [$this, 'save_watchdog_form_data']);
        add_action('init', [$this, 'export_csv_watchdogs']);

//        add_action('init', [$this, 'seed_watchdog_form_data']);
//        add_action('init', [$this, 'send_test_mail']);

        add_action('plugins_loaded', [$this, 'watchdog_load_textdomain']);

        add_action('watchdog_check_jobs', [$this, 'checkJobs']);

    }

    public function checkJobs()
    {
        foreach (self::types as $type) {
            self::check_watchdogs($type, get_option($type));
        }
    }

    private static function check_watchdog($post_id)
    {
        $watchdog = get_post($post_id);

        if (self::post_type !== get_post_type($watchdog->ID)) {
            return false;
        }

        $watchdog->status = get_post_meta($watchdog->ID, '_watchdog_status', true);

        if ($watchdog->status > 0) {
            return false;
        }

        $watchdog->credit_type = get_post_meta($watchdog->ID, '_watchdog_credit_type', true);
        $watchdog->rate        = get_post_meta($watchdog->ID, '_watchdog_rate', true);

        $rate = get_option($watchdog->credit_type);

        if ($rate && $rate < $watchdog->rate) {
            return false;
        }

        $watchdog->email     = get_post_meta($watchdog->ID, '_watchdog_email', true);
        $watchdog->phone     = get_post_meta($watchdog->ID, '_watchdog_phone', true);
        $watchdog->user_lang = get_post_meta($watchdog->ID, '_watchdog_user_lang', true);
        if (filter_var($watchdog->email, FILTER_VALIDATE_EMAIL)) {
            $data_item = [
                'name'         => $watchdog->post_title,
                'phone'        => $watchdog->phone,
                'email'        => $watchdog->email,
                'credit_type'  => self::translate_credit_type($watchdog->credit_type, $watchdog->user_lang),
                'client_rate'  => $watchdog->rate . ' %',
                'date'         => get_post_datetime($watchdog->ID)->format('d.m.Y G:i'),
                'current_rate' => $rate . ' %',
            ];
            $mail      = sendMessage($watchdog->email, $data_item, 'watchdog', $watchdog->user_lang);
            if ($mail) {
                update_post_meta($watchdog->ID, '_watchdog_status', 1);
            }
        } else {
            update_post_meta($watchdog->ID, '_watchdog_status', 2);
        }
        return true;
    }

    /*
     * В админке стоит текущий процент, например $rate = 5
     * клиент оставляет заявку, что ему это много и он хочет по 3
     * когда $rate изменяется, запускается этот скрипт и мы
     * забираем из базы удовлетворительные заявки
     * те, которые равны или больше текущего процента $rate
     * */

    private static function check_watchdogs($type, $rate)
    {
        $args       = array(
            'posts_per_page' => -1,
            'meta_query'     => [
                'relation' => 'AND',
                [
                    'key'   => '_watchdog_credit_type',
                    'value' => $type,
                ],
                [
                    'key'     => '_watchdog_rate',
                    'value'   => (float)$rate,
                    'type'    => 'DECIMAL(10,2)',
                    'compare' => '>='
                ],
                [
                    'key'   => '_watchdog_status',
                    'value' => 0,
                    'compare' => '=='
                ]
            ]
        );
        $data_array = self::find($args);
        if (is_array($data_array) && count($data_array) > 0) {
            foreach ($data_array as $item) {
                $email = array_shift($item->meta['_watchdog_email']);
                $lang  = array_shift($item->meta['_watchdog_user_lang']);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $data_item = [
                        'name'         => $item->post_title,
                        'phone'        => @array_shift($item->meta['_watchdog_phone']),
                        'email'        => $email,
                        'credit_type'  => self::translate_credit_type(@array_shift($item->meta['_watchdog_credit_type']), $lang),
                        'client_rate'  => @array_shift($item->meta['_watchdog_rate']) . ' %',
                        'date'         => get_post_datetime($item->ID)->format('d.m.Y G:i'),
                        'current_rate' => $rate . ' %',
                    ];
                    $mail      = sendMessage($email, $data_item, 'watchdog', $lang);
                    if ($mail) {
                        update_post_meta($item->ID, '_watchdog_status', 1);
                    }
                } else {
                    update_post_meta($item->ID, '_watchdog_status', 2);
                }
            }
        }
    }

    public function watchdog_load_textdomain()
    {
        load_plugin_textdomain('watchdog', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function export_csv_watchdogs()
    {
        if (empty($_GET['export'])) {
            return;
        }

        $args = array(
            'posts_per_page' => -1,
            'orderby'        => 'date', // забрать текущий ордер
            'order'          => 'DESC',
        );

        if (!empty($_REQUEST['s'])) {
            $args['s'] = $_REQUEST['s'];
        }

        if (!empty($_REQUEST['orderby'])) {
            switch ($_REQUEST['orderby']) {
                case '_watchdog_credit_type':
                    $args['meta_key'] = '_watchdog_credit_type';
                    $args['orderby']  = 'meta_value';
                    break;
                case '_watchdog_rate':
                    $args['meta_key'] = '_watchdog_rate';
                    $args['orderby']  = 'meta_value';
                    break;
                case '_watchdog_email':
                    $args['meta_key'] = '_watchdog_email';
                    $args['orderby']  = 'meta_value';
                    break;
                case '_watchdog_phone':
                    $args['meta_key'] = '_watchdog_phone';
                    $args['orderby']  = 'meta_value';
                    break;
                case '_watchdog_status':
                    $args['meta_key'] = '_watchdog_status';
                    $args['orderby']  = 'meta_value';
                    break;
                case '_watchdog_user_lang':
                    $args['meta_key'] = '_watchdog_user_lang';
                    $args['orderby']  = 'meta_value';
                    break;
                case '_watchdog_agreement':
                    $args['meta_key'] = '_watchdog_agreement';
                    $args['orderby']  = 'meta_value';
                    break;
            }
        }

        if (!empty($_REQUEST['order'])
            and 'asc' == strtolower($_REQUEST['order'])) {
            $args['order'] = 'ASC';
        }

        if (!empty($_REQUEST['m'])) {
            $args['m'] = $_REQUEST['m'];
        }

        if (!empty($_REQUEST['credit_type'])) {
            $args['meta_query'] = [[
                'key'   => '_watchdog_credit_type',
                'value' => $_REQUEST['credit_type'],
            ]];
        }

        if (!empty($_REQUEST['post_status'])) {
            if ('trash' == $_REQUEST['post_status']) {
                $args['post_status'] = 'trash';
            }
        }

        $data_array  = self::find($args);
        $csv_headers = array("Name", "Phone", "Email", "Type", "Rate", "Date", "Status", "Language", "Agreement");
        $temp_file   = fopen('php://temp', 'w');
        fputcsv($temp_file, $csv_headers);
        foreach ($data_array as $item) {
            fputcsv($temp_file, [
                $item->post_title,
                @array_shift($item->meta['_watchdog_phone']),
                @array_shift($item->meta['_watchdog_email']),
                self::translate_credit_type(@array_shift($item->meta['_watchdog_credit_type'])),
                @array_shift($item->meta['_watchdog_rate']) . ' %',
                get_post_datetime($item->ID)->format('d.m.Y G:i'),
                (@array_shift($item->meta['_watchdog_status']) == 1 ? __("Sent", 'watchdog') : __("Pending", 'watchdog')),
                @array_shift($item->meta['_watchdog_user_lang']),
                @array_shift($item->meta['_watchdog_agreement']),
            ]);
        }
        rewind($temp_file);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv"');
        fpassthru($temp_file);
        fclose($temp_file);
        exit;
    }

    public function save_watchdog_form_data()
    {
        if (isset($_POST['watchdog']) && is_array($_POST['watchdog']) && count($_POST['watchdog']) > 0) {
            if (!wp_verify_nonce($_POST['watchdog-form-field'], 'watchdog-form-action')) {
                return false;
            }

            // Загружаем текстовый домен вручную
            load_textdomain('watchdog', WATCHDOG_PLUGIN_DIR . 'languages');

            $data = $_POST['watchdog'];

            $credit_type = sanitize_text_field($data['credit_type']);
            $rate        = sanitize_text_field($data['rate']);
            $name        = sanitize_text_field($data['name']);
            $email       = sanitize_email($data['email']);
            $phone       = preg_replace('/[^0-9+]/', '', $data['phone']);
            $agreement   = sanitize_text_field($data['agreement']);

            $datetime = date_create();
            $datetime->setTimezone(wp_timezone());

            $post_content = implode("\n", [
                $name,
                $email,
                $phone,
                self::translate_credit_type($credit_type),
                $rate,
                'Agreement: ' . $agreement,
            ]);

            $post_data = array(
                'post_title'   => $name,
                'post_content' => $post_content,
                'post_type'    => self::post_type,
                'post_status'  => 'publish',
                'post_date'    => $datetime->format('Y-m-d H:i:s'),
            );

            $post_id = wp_insert_post($post_data);

            if ($post_id) {
                update_post_meta($post_id, '_watchdog_credit_type', $credit_type);
                update_post_meta($post_id, '_watchdog_rate', $rate);
                update_post_meta($post_id, '_watchdog_email', $email);
                update_post_meta($post_id, '_watchdog_phone', $phone);
                update_post_meta($post_id, '_watchdog_status', 0);
                update_post_meta($post_id, '_watchdog_agreement', $agreement);
                update_post_meta($post_id, '_watchdog_user_lang', ICL_LANGUAGE_CODE);

                $_SESSION['watchdog-ok'] = 1;

//                self::check_watchdog($post_id); // работает не корректно. будет по крону со всеми отправляться
            }
        }
    }

    private static function generateRandomString($length = 10, $num = false)
    {
        if ($num) {
            $characters = '0123456789';
        } else {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

//    public function seed_watchdog_form_data()
//    {
//        if (isset($_GET['seed']) && (int)$_GET['seed'] > 0) {
//            $seed_types = array_keys(self::get_translate_credit_type_arr());
//            for ($i = 0; $i < $_GET['seed']; $i++) {
//                $credit_type = $seed_types[rand(0, count($seed_types) - 1)];
//                $rate        = rand(30, 100) / 10;
//                $name        = self::generateRandomString();
//                $email       = str_repeat('a', rand(5, 10)) . '@' . str_repeat('g', rand(5, 10)) . '.com';
//                $phone       = '+' . self::generateRandomString(13, true);
//
//                $datetime = date_create('@' . mt_rand(time() - 604800, time()));
//                $datetime->setTimezone(wp_timezone());
//
//                $post_content = implode("\n", [
//                    $name,
//                    $email,
//                    $phone,
//                    self::translate_credit_type($credit_type),
//                    $rate,
//                ]);
//
//                $post_data = array(
//                    'post_title'   => $name,
//                    'post_content' => $post_content,
//                    'post_type'    => self::post_type,
//                    'post_status'  => 'publish',
//                    'post_date'    => $datetime->format('Y-m-d H:i:s'),
//                );
//
//                $post_id = wp_insert_post($post_data);
//
//                if ($post_id) {
//                    update_post_meta($post_id, '_watchdog_credit_type', $credit_type);
//                    update_post_meta($post_id, '_watchdog_rate', $rate);
//                    update_post_meta($post_id, '_watchdog_email', $email);
//                    update_post_meta($post_id, '_watchdog_phone', $phone);
//                    update_post_meta($post_id, '_watchdog_status', 0);
//                }
//            }
//        }
//    }

    /**
     * Register dashboard post type
     * @return void
     */
    public function register_watchdog_post_type()
    {
        register_post_type(self::post_type, [
            'label'       => null,
            'labels'      => [
                'name'               => __('Watchdog', 'watchdog'),
                'singular_name'      => __('Watchdog', 'watchdog'),
                'add_new'            => __('Add Watchdog', 'watchdog'),
                'add_new_item'       => __('Add new Watchdog', 'watchdog'),
                'edit_item'          => __('Edit Watchdog', 'watchdog'),
                'new_item'           => __('New Watchdog', 'watchdog'),
                'all_items'          => __('All Watchdogs', 'watchdog'),
                'view_item'          => __('Watchdog', 'watchdog'),
                'search_items'       => __('Find Watchdog', 'watchdog'),
                'not_found'          => __('Watchdogs not found', 'watchdog'),
                'not_found_in_trash' => __('Trash is empty', 'watchdog'),
                'menu_name'          => __('Watchdog', 'watchdog'),
            ],
            'public'      => false,
            'show_ui'     => false,
            'has_archive' => false,
//            'menu_icon' => 'dashicons-grid-view',
//            'menu_position' => 20,
            'supports'    => array('title', 'editor')
        ]);
    }

    public function add_watchdog_menu()
    {
        add_menu_page(
            __('Watchdog', 'watchdog'),
            __('Watchdog', 'watchdog'),
            'manage_options',
            'watchdog-inbox',
            [$this, 'get_watchdog_inbox'],
            'dashicons-pets'
        );

        $hook = add_submenu_page(
            'watchdog-inbox',
            __('Watchdog', 'watchdog'),
            __('Watchdog inbox', 'watchdog'),
            'manage_options',
            'watchdog-inbox'
        );
        add_action("load-$hook", [$this, 'get_watchdog_inbox_load']);

        add_submenu_page(
            'watchdog-inbox',
            __('Watchdog settings', 'watchdog'),
            __('Watchdog settings', 'watchdog'),
            'manage_options',
            'watchdog-settings',
            [$this, 'get_watchdog_template']
        );
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
//d($q->request);
        self::$found_items = $q->found_posts;

        foreach ((array)$posts as &$post) {
            $post->meta = get_post_meta($post->ID);
        }
        return $posts;
    }

    public static function count($args = '')
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

    public function get_watchdog_inbox_load()
    {
        require_once WATCHDOG_PLUGIN_DIR . '/class.watchdog-list.php';

        // создаем экземпляр и сохраним его дальше выведем
        $GLOBALS['Watchdog_List_Table'] = new Watchdog_List_Table();
    }

    public function get_watchdog_inbox()
    {
        ?>
        <div class="wrap">

            <h1 class="wp-heading-inline"><?php echo get_admin_page_title() ?></h1>
            <hr class="wp-header-end">
            <form method="get" action="">
                <input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>"/>
                <input type="hidden" name="post_status"
                       value="<?php echo isset($_REQUEST['post_status']) ? esc_attr($_REQUEST['post_status']) : ''; ?>"/>
                <?php $GLOBALS['Watchdog_List_Table']->search_box(__('Search', 'watchdog'), 'watchdog-list'); ?>
                <?php $GLOBALS['Watchdog_List_Table']->display(); ?>
            </form>

        </div>
        <?php
    }

    public static function translate_credit_type($type, $lang = ICL_LANGUAGE_CODE)
    {
        global $sitepress;
        $sitepress->switch_lang($lang);
        $credit_arr = self::get_translate_credit_type_arr();
        $sitepress->switch_lang(ICL_LANGUAGE_CODE);
        return $credit_arr[$type];
    }


    public static function get_translate_credit_type_arr()
    {
        return [
            'new_mortgage'              => __("New mortgage", 'watchdog'),
            'refinancing'               => __("Refinancing", 'watchdog'),
            'american_mortgage'         => __("American mortgage", 'watchdog'),
            'personal_loan'             => __("Personal loan", 'watchdog'),
            'auto_loan'                 => __("Auto loan", 'watchdog'),
            'credit_card'               => __('Credit card', 'watchdog'),
            'housing_renovation'        => __('Housing & Renovation', 'watchdog'),
            'refinancing_consolidation' => __('Refinancing & Consolidation', 'watchdog'),
        ];
    }

    public function get_watchdog_template()
    {
        // Сохранение настроек
        if ($_POST) {
            update_option('new_mortgage', stripslashes($_POST['new_mortgage']));
            update_option('refinancing', stripslashes($_POST['refinancing']));
            update_option('american_mortgage', stripslashes($_POST['american_mortgage']));
            update_option('housing_renovation', stripslashes($_POST['housing_renovation']));
            update_option('refinancing_consolidation', stripslashes($_POST['refinancing_consolidation']));
            update_option('personal_loan', stripslashes($_POST['personal_loan']));
            update_option('auto_loan', stripslashes($_POST['auto_loan']));
            echo '<div id="message" class="updated fade"><p><strong>Успешно сохраненно</strong></p></div>';
        }
        ?>
        <div class="wrap">
            <h3><?= get_admin_page_title(); ?></h3>
            <?php if (isset($_GET['send_test_mail'])) : ?>
                <form method="get" novalidate="novalidate">
                    <input type="hidden" name="page" value="watchdog-settings">
                    <input type="text" name="send_test_mail" style="width: 500px;"
                           value="<?= $_GET['send_test_mail'] ?>">
                    <button type="submit">Send</button>
                </form>
            <?php endif; ?>

            <form method="post" novalidate="novalidate">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label>Credit types %</label></th>
                    </tr>
                    <tr>
                        <td>
                            <label style=""><?php _e("New mortgage", 'watchdog'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="number" name="new_mortgage" size="30" min="0" max="100" step="0.01"
                                   value="<?php echo get_option('new_mortgage'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label style=""><?php _e("Refinancing", 'watchdog'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="number" name="refinancing" size="30" min="0" max="100" step="0.01"
                                   value="<?php echo get_option('refinancing'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label style=""><?php _e("American mortgage", 'watchdog'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="number" name="american_mortgage" size="30" min="0" max="100" step="0.01"
                                   value="<?php echo get_option('american_mortgage'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label style=""><?php _e("Refinancing & Consolidation", 'watchdog'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="number" name="refinancing_consolidation" size="30" min="0" max="100"
                                   step="0.01"
                                   value="<?php echo get_option('refinancing_consolidation'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label style=""><?php _e("Housing & Renovation", 'watchdog'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="number" name="housing_renovation" size="30" min="0" max="100" step="0.01"
                                   value="<?php echo get_option('housing_renovation'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label style=""><?php _e("Personal loan", 'watchdog'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="number" name="personal_loan" size="30" min="0" max="100" step="0.01"
                                   value="<?php echo get_option('personal_loan'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label style=""><?php _e("Auto loan", 'watchdog'); ?>
                            </label>
                        </td>
                        <td>
                            <input type="number" name="auto_loan" size="30" min="0" max="100" step="0.01"
                                   value="<?php echo get_option('auto_loan'); ?>">
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" class="button button-primary button-large"
                           value="<?php _e("Save", 'watchdog'); ?>"/>
                </p>
            </form>
        </div>
        <?php
    }

    /*    public function send_test_mail()
        {
            if (isset($_GET['send_test_mail'])) {
                $data_item = [
                    'name'         => 'John Smith',
                    'phone'        => '0123456789',
                    'email'        => 'email@example.com',
                    'credit_type'  => self::translate_credit_type('american_mortgage', 'cs'),
                    'client_rate'  => 42 . ' %',
                    'date'         => date('d.m.Y G:i'),
                    'current_rate' => 45 . ' %',
                ];
                sendMessage($_GET['send_test_mail'], $data_item, 'watchdog', 'cs');
            }
        }*/
}

