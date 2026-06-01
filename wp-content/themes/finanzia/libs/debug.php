<?php

//require __DIR__ . '/debugger/vendor/autoload.php';


if (!function_exists('d')) {
    /**
     * d
     * Дебаг переменной без прерывания выполнения кода
     * помещает в себя любое количество переменных
     *
     * @param mixed $var Переменная или список переменных
     *                   через запятую для отображения их содержимого
     */
    function d(...$var)
    {
        if (function_exists('dump')) {
            dump($var);
        } else {
            echo "<pre>";
            foreach ($var as &$v) {
                var_dump($v);
            }
            echo "</pre>";
        }
    }
}

if (!function_exists('dd')) {
    /**
     * dd
     * Дебаг переменной с прерыванием выполнения кода
     * помещает в себя любое количество переменных
     *
     * @param mixed $var Переменная или список переменных
     *                   через запятую для отображения их содержимого
     */
    function dd(...$var)
    {
        echo "<pre>";
        foreach ($var as &$v) {
            var_dump($v);
        }
        echo "</pre>";
        die;
    }
}

if (!function_exists('cdd')) {
    /**
     * cdd
     * Дебаг переменной под куками с прерыванием выполнения кода
     * помещает в себя любое количество переменных
     *
     * @param mixed $var Переменная или список переменных
     *                   через запятую для отображения их содержимого
     *
     *                   document.cookie = "debug=123";
     */
    function cdd(...$var)
    {
        if ($_COOKIE['debug'] == 123) {
            dd($var);
        }
    }
}

if (!function_exists('ccd')) {
    function ccd(...$var)
    {
        if ($_COOKIE['debug'] == 123) {
            d($var);
        }
    }
}

if (!function_exists('ed')) {
    /**
     * ed
     * Експорт переменной с прерыванием выполнения кода
     * помещает в себя любое количество переменных
     *
     * @param mixed $var Переменная или список переменных
     *                   через запятую для отображения их содержимого
     */
    function ed(...$var)
    {
        echo "<pre>";
        foreach ($var as &$v) {
            var_export($v);
        }
        echo "</pre>";
        die;
    }
}

if (!function_exists('console_log')) {
    /**
     * console_log
     * Запись переменных в файл без прерывания выполнения кода
     * помещает в себя любое количество переменных
     *
     * @param mixed $var Переменная или список переменных
     *                   через запятую для отображения их содержимого
     */
    function console_log()
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
