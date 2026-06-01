<?php

// Старт сессии
function start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'start_session', 1);

// Завершение сессии при выходе
function end_session() {
    session_destroy();
}
add_action('wp_logout', 'end_session');


// Выполняем авторизацию только если пользователь не вошел
if (!is_user_logged_in()) {
    add_action('init', 'ajax_register_init');
}

function ajax_register_init()
{
    add_action('wp_ajax_nopriv_reg_or_login', 'reg_or_login');
    add_action('wp_ajax_nopriv_check_code_form', 'check_code_form');
}

function reg_or_login()
{
    if (!wp_verify_nonce($_POST['login-form-field'], 'login-form-action')) {
        echo json_encode(['success' => false]);
        wp_die();
    }

    if (isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!$email) {
            echo json_encode(['success' => false, 'message' => 'Incorrect email']);
            wp_die();
        }
        $username = $email;
        $password = wp_generate_password(12);

        $user_id = email_exists($email);

        if (!$user_id) {
            $user_id = wp_create_user($username, $password, $email);

            if (!is_wp_error($user_id)) {
                $user = new WP_User($user_id);
                $user->set_role('subscriber'); // Установка роли, например, "subscriber"
            }
        }
        attach_guest_calcs_to_user($user->ID); // сессия не работает
//        if (isset($_SESSION['calc_ids']) && is_array($_SESSION['calc_ids']) && count($_SESSION['calc_ids']) > 0) {
//            if (!get_actual_user_calc($user_id)) {
//                foreach ($_SESSION['calc_ids'] as $calcId) {
//                    update_post_meta($calcId, '_calc_user', $user_id);
//                    update_post_meta($calcId, '_calc_status', 1);
//                }
//            } else {
//                foreach ($_SESSION['calc_ids'] as $calcId) {
//                    wp_trash_post($calcId);
//                }
//            }
//        }

        // Генерируем код для верификации для существующего пользователя
        $verification_code = rand(1000, 9999);
        update_user_meta($user_id, '_verification_code', time() . $verification_code);

        if (sendMessage($email, ['email' => $email, 'code' => $verification_code], 'login_code')) {

            ob_start();
            get_template_part('template-parts/part-login', 'code', ['email' => $email]);
            $html = ob_get_contents();
            ob_end_clean();

            echo json_encode([
                'success' => true,
                'html'    => $html,
            ]);
            wp_die();
        }

    }
    echo json_encode(['success' => false]);
    wp_die();
}

function check_code_form()
{
    if (!wp_verify_nonce($_POST['login-code-form-field'], 'login-code-form-action')) {
        echo json_encode(['success' => false]);
        wp_die();
    }
    $code    = is_array($_POST['code']) ? implode('', $_POST['code']) : '';
    $email   = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $user_id = email_exists($email);
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        wp_die();
    }
    $user_time_code    = get_user_meta($user_id, '_verification_code', true);
    $time              = substr($user_time_code, 0, 10); // Первая часть строки
    $verification_code = substr($user_time_code, 10);    // Вторая часть строки

    if ($time + DAY_IN_SECONDS > time() && $code == $verification_code) {
        // Пользователь ввёл правильный код, устанавливаем его статус "авторизован"
        $user = get_user_by('id', $user_id);
        if (in_array('subscriber', $user->roles)) {
            $new_password = wp_generate_password(12); // Генерируем новый случайный пароль
            wp_set_password($new_password, $user_id); // Меняем пароль пользователя
        }

        nocache_headers();
        wp_clear_auth_cookie();
        wp_set_auth_cookie($user_id);
        wp_set_current_user($user_id, $user->user_login);
        do_action('wp_login', $user->user_login, $user);

        update_user_meta($user_id, '_verification_code', null);

        echo json_encode([
            'success' => true,
            'url'     => get_permalink(1370) // авторизация, а там разберутся
        ]);
        wp_die();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid code']);
        wp_die();
    }
    echo json_encode(['success' => false]);
    wp_die();
}


/**
 * Получаем или создаём уникальный guest_id
 */
function get_guest_id() {
    if (!isset($_COOKIE['guest_id'])) {
        $guest_id = bin2hex(random_bytes(16));
        setcookie('guest_id', $guest_id, time() + 30 * DAY_IN_SECONDS, '/');
        $_COOKIE['guest_id'] = $guest_id; // чтобы сразу было доступно
    } else {
        $guest_id = $_COOKIE['guest_id'];
    }
    return $guest_id;
}

/**
 * Получаем массив calc_ids для гостя
 */
function get_guest_calc_ids() {
    $guest_id = get_guest_id();
    $calc_ids = get_transient('guest_calc_' . $guest_id);
    return is_array($calc_ids) ? $calc_ids : [];
}

/**
 * Добавляем calc_id в хранилище
 */
function add_guest_calc_id($post_id) {
    $calc_ids = get_guest_calc_ids();
    $calc_ids[] = $post_id;
    set_transient('guest_calc_' . get_guest_id(), $calc_ids, 30 * DAY_IN_SECONDS);
}

/**
 * Привязываем все заявки к пользователю
 */
function attach_guest_calcs_to_user($user_id) {
    $guest_id = $_COOKIE['guest_id'] ?? null;
    if (!$guest_id) {
        return;
    }

    $calc_ids = get_guest_calc_ids();

    if ($calc_ids && is_array($calc_ids)) {
        if (!get_actual_user_calc($user_id)) {
            foreach ($calc_ids as $calcId) {
                update_post_meta($calcId, '_calc_user', $user_id);
                update_post_meta($calcId, '_calc_status', 1);
            }
        } else {
            foreach ($calc_ids as $calcId) {
                wp_trash_post($calcId);
            }
        }
    }

    delete_transient('guest_calc_' . $guest_id);
}
