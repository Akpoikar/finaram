<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

// расширять класс нужно после или во время admin_init
// класс удобнее поместить в отдельный файл.

class Watchdog_List_Table extends WP_List_Table
{
    public $is_trash;

    function __construct()
    {
        parent::__construct(array(
            'singular' => 'watchdog',
            'plural'   => 'watchdogs',
            'ajax'     => false,
        ));

        $this->bulk_action_handler();

        // screen option
        add_screen_option('per_page', array(
            'label'   => 'Показывать на странице',
            'default' => 20,
            'option'  => 'watchdogs_per_page',
        ));

        $this->prepare_items();

        add_action('wp_print_scripts', [$this, '_list_table_css']);
    }

    // создает элементы таблицы
    public function prepare_items()
    {
        $per_page = $this->get_items_per_page(
            'watchdogs_per_page'
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

        if (!empty($_REQUEST['orderby'])) {
            switch ($_REQUEST['orderby']) {
                case 'credit_type':
                    $args['meta_key'] = '_watchdog_credit_type';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'rate':
                    $args['meta_key'] = '_watchdog_rate';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'email':
                    $args['meta_key'] = '_watchdog_email';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'phone':
                    $args['meta_key'] = '_watchdog_phone';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'status':
                    $args['meta_key'] = '_watchdog_status';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'language':
                    $args['meta_key'] = '_watchdog_user_lang';
                    $args['orderby']  = 'meta_value';
                    break;
                case 'agreement':
                    $args['meta_key'] = '_watchdog_agreement';
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
                'key'   => '_watchdog_credit_type',
                'value' => $_REQUEST['credit_type'],
            ]];
        }

        if (!empty($_REQUEST['post_status'])) {
            if ('trash' == $_REQUEST['post_status']) {
                $args['post_status'] = 'trash';
                $this->is_trash      = true;
            }
        }

        $this->items = Watchdog::find($args);

        $total_items = Watchdog::count();
        $total_pages = ceil($total_items / $per_page);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'total_pages' => $total_pages,
            'per_page'    => $per_page,
        ));
    }

    // колонки таблицы
    public function get_columns()
    {
        return array(
//            'cb'          => '<input type="checkbox" />',
            'post_title'  => __('Name', 'watchdog'),
            'phone'       => __('Phone', 'watchdog'),
            'email'       => __('Email', 'watchdog'),
            'credit_type' => __('Type', 'watchdog'),
            'rate'        => __('Rate', 'watchdog'),
            'date'        => __('Date', 'watchdog'),
            'status'      => __('Status', 'watchdog'),
            'language'    => __('Language', 'watchdog'),
            'agreement'   => __('Agreement', 'watchdog'),
        );
    }

    // сортируемые колонки
    protected function get_sortable_columns()
    {
        return array(
            'post_title'  => array('title', false),
            'phone'       => array('phone', false),
            'email'       => array('email', false),
            'credit_type' => array('credit_type', false),
            'rate'        => array('rate', false),
            'date'        => array('date', 'desc'),
            'status'      => array('status', false),
            'language'    => array('language', false),
            'agreement'   => array('agreement', false),
        );
    }

    protected function get_bulk_actions()
    {
//        return array(
//            'delete' => 'Delete',
//        );
        return false;
    }

    // Элементы управления таблицей. Расположены между групповыми действиями и панагией.
    protected function extra_tablenav($which)
    {
        ?>
        <div class="alignleft actions">
            <?php
            if ('top' == $which) {
            $this->months_dropdown(Watchdog::post_type);
            ?>
            <select name='credit_type' id='credit_type' class='postform'>
                <option value='0'><?php _e('All', 'watchdog'); ?></option>
                <?php foreach (Watchdog::get_translate_credit_type_arr() as $key => $item) : ?>
                    <option class="level-1"
                            value="<?= $key ?>"<?= (isset($_GET['credit_type']) && $_GET['credit_type'] == $key ? ' selected' : '') ?>>
                        &nbsp;&nbsp;&nbsp;<?= $item ?></option>
                <?php endforeach; ?>
            </select>
                <?php
                submit_button(__('Filter', 'watchdog'),
                    'secondary', false, false, array('id' => 'post-query-submit'));

                if (!$this->is_trash) {
                    submit_button(__('Export', 'watchdog'),
                        'secondary', 'export', false);
                }
                }

                if ($this->is_trash) {
                    submit_button(__('Empty trash', 'watchdog'),
                        'button-secondary apply', 'delete_all', false);
                }
                ?>
        </div>
        <?php
    }

    static function _list_table_css()
    {
        ?>
        <style>
            table.watchdogs .column-post_title {
                width: 15%;
            }

            table.watchdogs .column-phone {
                width: 11%;
            }

            table.watchdogs .column-email {
                width: 15%;
            }

            table.watchdogs .column-credit_type {
                width: 10%;
            }

            table.watchdogs .column-rate {
                width: 5%;
            }

            table.watchdogs .column-date {
                width: 10%;
            }

            table.watchdogs .column-status {
                width: 10%;
            }

            table.watchdogs .column-language {
                width: 7%;
            }

            table.watchdogs .column-agreement {
                width: 7%;
            }
        </style>
        <?php
    }

    // вывод каждой ячейки таблицы...
    protected function column_default($item, $colname)
    {

        if (false && $colname === 'title') {
            // ссылки действия над элементом
            $actions         = array();
            $actions['edit'] = sprintf('<a href="%s">%s</a>', '#', __('edit', 'watchdog'));

            return esc_html($item->name) . $this->row_actions($actions);
        } else {
            return isset($item->$colname) ? $item->$colname : print_r($item, 1);
        }

    }

    // заполнение колонки cb
    protected function column_cb($item)
    {
        echo '<input type="checkbox" name="licids[]" id="cb-select-' . $item->id . '" value="' . $item->id . '" />';
    }

    protected function column_phone($item)
    {
        if (is_array($item->meta['_watchdog_phone'])) {
            return array_shift($item->meta['_watchdog_phone']);
        } else {
            return '';
        }
    }

    protected function column_email($item)
    {
        if (is_array($item->meta['_watchdog_email'])) {
            return array_shift($item->meta['_watchdog_email']);
        } else {
            return '';
        }
    }

    protected function column_credit_type($item)
    {
        if (is_array($item->meta['_watchdog_credit_type'])) {
            $type = array_shift($item->meta['_watchdog_credit_type']);
            return Watchdog::translate_credit_type($type);
        } else {
            return '';
        }
    }

    protected function column_rate($item)
    {
        if (is_array($item->meta['_watchdog_rate'])) {
            return array_shift($item->meta['_watchdog_rate']) . ' %';
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
            __('%1$s at %2$s', 'watchdog'),
            /* translators: date format, see https://www.php.net/date */
            $datetime->format(__('d.m.Y', 'watchdog')),
            /* translators: time format, see https://www.php.net/date */
            $datetime->format(__('G:i', 'watchdog'))
        );

        return $t_time;
    }

    /**
     * @param $item
     * @return string|void
     */
    protected function column_status($item)
    {
        if (is_array($item->meta['_watchdog_status'])) {
            switch (array_shift($item->meta['_watchdog_status'])) {
                case '1':
                    return '<input type="checkbox" value="Sent" checked disabled/>Sent';
                    break;
                case '0':
                    return '<input type="checkbox" value="Pending" disabled/>Pending';
                    break;
                case '2':
                    return 'Invalid data';
                    break;
            }
        } else {
            return 'Invalid data';
        }
    }

    protected function column_language($item)
    {
        if (is_array($item->meta['_watchdog_user_lang'])) {
            return array_shift($item->meta['_watchdog_user_lang']);
        } else {
            return '';
        }
    }

    protected function column_agreement($item)
    {
        if (isset($item->meta['_watchdog_agreement']) && is_array($item->meta['_watchdog_agreement'])) {
            return array_shift($item->meta['_watchdog_agreement']);
        } else {
            return '';
        }
    }

}