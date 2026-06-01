<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Calculator_List_Table extends WP_List_Table
{
    private $is_trash = false;

    public static function define_columns()
    {
        $columns = array(
            'cb'                   => '<input type="checkbox" />',
            'id'                   => __('ID', 'calculator'),
            'user'                 => __('User', 'calculator'),
            'credit_type'          => __('Credit type', 'calculator'),
            'loan_amount'          => __('Loan amount', 'calculator'),
            'total_payment_amount' => __('Total payment', 'calculator'),
            'interest_rate'        => __('Interest rate', 'calculator'),
//            'rpsn'                 => __('RPSN', 'calculator'),
//            'insurance_payment'    => __('Insurance payment', 'calculator'),
            'monthly_payment'      => __('Monthly payment', 'calculator'),
            'date'                 => __('Date', 'calculator'),
            'status'               => __('Status', 'calculator'),
        );

        $columns = apply_filters(
            'manage_calculator_inbox_posts_columns', $columns);

        return $columns;
    }

    function __construct()
    {
        parent::__construct(array(
            'singular' => 'post',
            'plural'   => 'posts',
            'ajax'     => false,
        ));

        $this->bulk_action_handler();

        // screen option
        add_screen_option('per_page', array(
            'label'   => 'Показывать на странице',
            'default' => 20,
            'option'  => 'calculators_per_page',
        ));

        $this->prepare_items();

    }

    // создает элементы таблицы
    public function prepare_items()
    {
        $per_page = $this->get_items_per_page(
            'calculators_per_page'
        );

        $args = array(
            'posts_per_page' => $per_page,
            'offset'         => ($this->get_pagenum() - 1) * $per_page,
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

        $this->items = Calculator::find($args);

        $total_items = Calculator::count();
        $total_pages = ceil($total_items / $per_page);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'total_pages' => $total_pages,
            'per_page'    => $per_page,
        ));
    }

    protected function get_views()
    {
        $status_links = array();
        $post_status  = empty($_REQUEST['post_status'])
            ? '' : $_REQUEST['post_status'];

        // Inbox
        Calculator::find(array(
            'post_status' => 'any',
        ));

        $posts_in_inbox = Calculator::count();

        $inbox = sprintf(
            _nx('Inbox <span class="count">(%s)</span>',
                'Inbox <span class="count">(%s)</span>',
                $posts_in_inbox, 'posts', 'calculator'),
            number_format_i18n($posts_in_inbox)
        );

        $status_links['inbox'] = sprintf('<a href="%1$s"%2$s>%3$s</a>',
            menu_page_url('calculator-inbox', false),
            $this->is_trash ? '' : ' class="current"',
            $inbox);

        // Trash
        Calculator::find(array(
            'post_status' => 'trash',
        ));

        $posts_in_trash = Calculator::count();

        if (empty($posts_in_trash)) {
            return $status_links;
        }

        $trash = sprintf(
            _nx('Trash <span class="count">(%s)</span>',
                'Trash <span class="count">(%s)</span>',
                $posts_in_trash, 'posts', 'calculator'),
            number_format_i18n($posts_in_trash)
        );

        $status_links['trash'] = sprintf('<a href="%1$s"%2$s>%3$s</a>',
            esc_url(add_query_arg(
                array(
                    'post_status' => 'trash',
                ),
                menu_page_url('calculator-inbox', false)
            )),
            'trash' == $post_status ? ' class="current"' : '',
            $trash
        );

        return $status_links;
    }

    // колонки таблицы
    public function get_columns()
    {
        return array(
            'cb'                   => '<input type="checkbox" />',
            'id'                   => __('ID', 'calculator'),
//            'post_title'           => __('Tab', 'calculator'),
            'user'                 => __('User', 'calculator'),
            'credit_type'          => __('Credit type', 'calculator'),
            'loan_amount'          => __('Loan amount', 'calculator'),
            'total_payment_amount' => __('Total payment', 'calculator'),
            'interest_rate'        => __('Interest rate', 'calculator'),
            'rpsn'                 => __('RPSN', 'calculator'),
            'insurance_payment'    => __('Insurance payment', 'calculator'),
            'monthly_payment'      => __('Monthly payment', 'calculator'),
            'date'                 => __('Date', 'calculator'),
            'status'               => __('Status', 'calculator'),
//            'currency'             => __('Currency', 'calculator'),

        );
    }

    // сортируемые колонки
    protected function get_sortable_columns()
    {
        return array(
            'user'                 => array('user', false),
            'credit_type'          => array('credit_type', false),
            'loan_amount'          => array('loan_amount', false),
            'total_payment_amount' => array('total_payment_amount', false),
            'interest_rate'        => array('interest_rate', false),
            'rpsn'                 => array('rpsn', false),
            'insurance_payment'    => array('insurance_payment', false),
            'monthly_payment'      => array('monthly_payment', false),
            'date'                 => array('date', 'desc'),
            'status'               => array('status', false),
        );
    }

    protected function get_bulk_actions()
    {
        $actions = array();

        if ($this->is_trash) {
            $actions['untrash'] = __('Restore', 'calculator');
        }

        if ($this->is_trash or !EMPTY_TRASH_DAYS) {
            $actions['delete'] = __('Delete permanently', 'calculator');
        } else {
            $actions['trash'] = __('Move to trash', 'calculator');
        }

        return $actions;
    }

    // Элементы управления таблицей. Расположены между групповыми действиями и панагией.
    protected function extra_tablenav($which)
    {
        $categories = get_terms(['post_type' => Calculator::post_type, 'taxonomy' => Calculator::taxonomy]);
        ?>

        <div class="alignleft actions">
            <?php
            if ('top' == $which) {
                $this->months_dropdown(Calculator::post_type);
                ?>
                <select name='credit_type' id='credit_type' class='postform'>
                    <option value='0'><?php _e('All credit types', 'calculator'); ?></option>
                    <?php foreach (Calculator::get_translate_credit_type_arr() as $key => $item) : ?>
                        <option class="level-1"
                                value="<?= $key ?>"<?= ($_GET['credit_type'] == $key ? ' selected' : '') ?>>
                            &nbsp;&nbsp;&nbsp;<?= $item ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name='currency' id='currency' class='postform'>
                    <option value='0'><?php _e('All currencies', 'calculator'); ?></option>
                    <option class="level-1" value="czk"<?= ($_GET['currency'] == 'czk' ? ' selected' : '') ?>>CZK
                    </option>
                    <option class="level-1" value="usd"<?= ($_GET['currency'] == 'usd' ? ' selected' : '') ?>>USD
                    </option>
                    <option class="level-1" value="eur"<?= ($_GET['currency'] == 'eur' ? ' selected' : '') ?>>EUR
                    </option>
                </select>
                <?php if (is_countable($categories) && count($categories) > 0) : ?>
                    <select name='category' id='category' class='postform'>
                        <option value='0'><?php _e('All categories', 'calculator'); ?></option>
                        <?php foreach ($categories as $cat) : ?>
                            <option class="level-1"
                                    value="<?= $cat->slug ?>"<?= ($_GET['category'] == $cat->slug ? ' selected' : '') ?>>
                                <?= $cat->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <?php
                submit_button(__('Filter', 'calculator'),
                    'secondary', false, false, array('id' => 'post-query-submit'));

                if (!$this->is_trash) {
                    submit_button(__('Export', 'calculator'),
                        'secondary', 'export-calculators', false);
                }

                if (!$this->is_trash && (
                        isset($_GET['credit_type']) ||
                        isset($_GET['currency']) ||
                        isset($_GET['post_status']) ||
                        isset($_GET['orderby']) ||
                        isset($_GET['s'])
                    )) {
                    $clear = menu_page_url('calculator-inbox', false);
                    echo "<a class='button' href='$clear'>" . __('Clear filters', 'calculator') . "</a>";
                }
            }

            if ($this->is_trash) {
                submit_button(__('Empty trash', 'calculator'),
                    'button-secondary apply', 'delete_all', false);
            }
            ?>
        </div>
        <?php
    }

    // вывод каждой ячейки таблицы...
    protected function column_default($item, $colname)
    {

        if (false && $colname === 'title') {
            // ссылки действия над элементом
            $actions         = array();
            $actions['edit'] = sprintf('<a href="%s">%s</a>', '#', __('edit', 'calculator'));

            return esc_html($item->name) . $this->row_actions($actions);
        } else {
            return isset($item->$colname) ? $item->$colname : print_r($item, 1);
        }

    }

    // заполнение колонки cb
    protected function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['singular'],
            $item->ID
        );
    }

    protected function column_id($item)
    {
        return $item->ID;
    }

    protected function column_user($item)
    {
        if (is_array($item->meta['_calc_user'])) {
            $user_id = array_shift($item->meta['_calc_user']);
            $user    = get_userdata($user_id);
            return $user->user_email;
        } else {
            return '';
        }
    }

    protected function column_credit_type($item)
    {
        if (is_array($item->meta['_mini_calc_credit_type'])) {
            $type = array_shift($item->meta['_mini_calc_credit_type']);
            return Calculator::translate_credit_type($type);
        } else {
            return '';
        }
    }

    protected function column_loan_amount($item)
    {
        if (is_array($item->meta['_mini_calc_loan_amount']) && $item->meta['_mini_calc_loan_amount'][0] != 0 ) {
            return array_shift($item->meta['_mini_calc_loan_amount']) . ' ' . strtoupper($item->meta['_mini_calc_currency'][0]);
        } else {
            return '';
        }
    }

    protected function column_total_payment_amount($item)
    {
        if (is_array($item->meta['_mini_calc_total_payment_amount']) && $item->meta['_mini_calc_total_payment_amount'][0] != 0 ) {
            return array_shift($item->meta['_mini_calc_total_payment_amount']) . ' ' . strtoupper($item->meta['_mini_calc_currency'][0]);
        } else {
            return '';
        }
    }

    protected function column_interest_rate($item)
    {
        if (is_array($item->meta['_mini_calc_interest_rate']) && $item->meta['_mini_calc_interest_rate'][0] != 0 ) {
            return array_shift($item->meta['_mini_calc_interest_rate']) . ' %';
        } else {
            return '';
        }
    }

    protected function column_rpsn($item)
    {
        if (is_array($item->meta['_mini_calc_rpsn'])) {
            return array_shift($item->meta['_mini_calc_rpsn']) . ' %';
        } else {
            return '';
        }
    }

    protected function column_insurance_payment($item)
    {
        if (is_array($item->meta['_mini_calc_insurance_payment'])) {
            return array_shift($item->meta['_mini_calc_insurance_payment']) . ' ' . strtoupper($item->meta['_mini_calc_currency'][0]);
        } else {
            return '';
        }
    }

    protected function column_monthly_payment($item)
    {
        if (is_array($item->meta['_mini_calc_monthly_payment']) && $item->meta['_mini_calc_monthly_payment'][0] != 0 ) {
            return array_shift($item->meta['_mini_calc_monthly_payment']) . ' ' . strtoupper($item->meta['_mini_calc_currency'][0]);
        } else {
            return '';
        }
    }

    protected function column_date($item)
    {
        $datetime = get_post_datetime($item->ID);

        if (false === $datetime) {
            return '';
        }

        $t_time = sprintf(
        /* translators: 1: date, 2: time */
            __('%1$s at %2$s', 'calculator'),
            /* translators: date format, see https://www.php.net/date */
            $datetime->format(__('d.m.Y', 'calculator')),
            /* translators: time format, see https://www.php.net/date */
            $datetime->format(__('G:i', 'calculator'))
        );

        return $t_time;
    }

    /**
     * @param $item
     * @return string|void
     */
    protected function column_status($item)
    {
        if (is_array($item->meta['_calc_status'])) {
            switch (array_shift($item->meta['_calc_status'])) {
                case '-1':
                    return __('Denied', 'calculator');
                    break;
                case '0':
                    return __('Unknown request', 'calculator');
                    break;
                case '1':
                    return __('User registered', 'calculator');
                    break;
                case '2':
                    return __('Finish step 1', 'calculator');
                    break;
                case '3':
                    return __('Waiting start step 2', 'calculator');
                    break;
                case '4':
                    return __('Finish step 2', 'calculator');
                    break;
                case '5':
                    return __('Waiting start step 3', 'calculator');
                    break;
                case '6':
                    return __('Finish step 3', 'calculator');
                    break;
                case '9':
                    return __('Finish All-In-One', 'calculator');
                    break;
                case '10':
                    return __('Waiting Documents', 'calculator');
                    break;
                case '11':
                    return __('Finish Documents', 'calculator');
                    break;
            }
        } else {
            return 'Invalid data';
        }
    }

    protected function handle_row_actions($item, $column_name, $primary)
    {
        if ($column_name !== $primary) {
            return '';
        }

        $actions = array();

        if (current_user_can('calculator_edit_inbox', $item->ID)) {
            $link = add_query_arg(
                array(
                    'post'   => $item->ID,
                    'action' => 'edit',
                ),
                menu_page_url('calculator-inbox', false)
            );

            $actions['edit'] = sprintf('<a href="%1$s">%2$s</a>',
                esc_url($link),
                esc_html(__('View', 'calculator'))
            );
        }

        return $this->row_actions($actions);
    }

}