<?php

class Theme
{
    static protected $instance;

    private $cache_dir              = 'wp-content/uploads/cache/images';
    private $tmp_dir                = 'wp-content/uploads/tmp';
    private $img_noimg              = 'assets/images/placeholder.png';
    private $new_folder_permissions = 0777;
    private $theme_dir;
    private $theme_url;
    private $home_url;
    private $site_url;
    private $menuLocations;
    private $upload_dir;
    private $webp;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->theme_url     = esc_url(trailingslashit(get_template_directory_uri()));
        $this->menuLocations = get_nav_menu_locations();

        $this->theme_dir  = get_template_directory();
        $this->upload_dir = wp_get_upload_dir();
        $this->home_url   = home_url();
        $this->site_url   = site_url();

        $this->webp = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    }

    public function getAllowedLanguages()
    {
        return apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
    }

    /**
     * @return string full link to theme directory
     */
    public function getThemeUrl()
    {
        return $this->theme_url;
    }

    public function count_fun($id_1, $id_2)
    {
        $num_1 = $id_1 / $id_2 * 100;
        $num_2 = 100 - $num_1;
        $str   = $num_1 . ' ' . $num_2;

        return $str;
    }

    public function find_youtube_id($url)
    {
        if (mb_strlen($url) == 11) {
            return $url;
        }
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        $youtube_id = $match[1];

        return $youtube_id;
    }

    public function setMiniPhone($string)
    {
        return str_replace(array(" ", "(", ")", "-"), "", trim($string));
    }

    /**
     * Формирование даты http://php.net/manual/ru/datetime.createfromformat.php
     *
     * @param $date = строка, содержащая дату
     * @param $from = входной формат даты ('U' - Unixtime, 'Y-m-d H:i:s' - Timestamp)
     * @param $to   = выходной формат даты
     *
     * @return array|string|string[]
     */
    public function getDate($date, $from, $to = null)
    {
        $date  = isset($date) && trim($date) != '' ? $date : time();
        $from  = isset($from) && trim($from) != '' ? $from : 'U';
        $to    = isset($to) && trim($to) != '' ? $to : 'd. m. Y';
        $resdt = DateTime::createFromFormat($from, $date);
        $newdt = $resdt->format($to);
        switch (ICL_LANGUAGE_CODE) {
            case "uk":
                if (stripos($to, 'F') !== false) {
                    $res = str_replace(
                        [
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November',
                            'December'
                        ],
                        [
                            'Січня',
                            'Лютого',
                            'Березня',
                            'Квітня',
                            'Травня',
                            'Червня',
                            'Липня',
                            'Серпня',
                            'Вересня',
                            'Жовтня',
                            'Жистопада',
                            'Грудня'
                        ],
                        $newdt
                    );
                } else {
                    $res = str_replace(
                        ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        ["Січ", "Лют", "Бер", "Кві", "Тра", "Чер", "Лип", "Сер", "Вер", "Жов", "Лис", "Гру"],
                        $newdt
                    );
                }
                break;
            case "ru":
                if (stripos($to, 'F') !== false) {
                    $res = str_replace(
                        [
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November',
                            'December'
                        ],
                        [
                            'Января',
                            'Февраля',
                            'Марта',
                            'Апреля',
                            'Мая',
                            'Июня',
                            'Июля',
                            'Августа',
                            'Сентября',
                            'Октября',
                            'Ноября',
                            'Декабря'
                        ],
                        $newdt
                    );
                } else {
                    $res = str_replace(
                        ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
                        $newdt
                    );
                }
                break;
            case "cs":
                if (stripos($to, 'F') !== false) {
                    $res = str_replace(
                        [
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November',
                            'December'
                        ],
                        [
                            'Leden',
                            'Únor',
                            'Březen',
                            'Duben',
                            'Květen',
                            'Červen',
                            'Červenec',
                            'Srpen',
                            'Září',
                            'Říjen',
                            'listopad',
                            'Prosinec'
                        ],
                        $newdt
                    );
                } else {
                    $res = str_replace(
                        ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        ["Led", "Úno", "Bře", "Dub", "Kvě", "Čvn", "Čvc", "Srp", "Zář", "Říj", "Lis", "Pro"],
                        $newdt
                    );
                }
                break;
            default:
                $res = $newdt;
                break;
        }

        return $res;
    }

    public function getMenuTree($location)
    {
        if (!array_key_exists($location, $this->menuLocations)) {
            return false;
        }

        $item       = wp_get_nav_menu_object($this->menuLocations[$location]);
        $menu_items = wp_get_nav_menu_items($item->term_id, array('update_post_term_cache' => false));


        //id элементов становятся ключами
        if (is_array($menu_items) && count($menu_items) > 0) {
            $buff = [];
            foreach ($menu_items as $k => $item) {
                $buff[$item->ID] = (array)$item;
            }
        }
        $tree = array();
        foreach ($buff as $id => &$node) {
            //Если нет вложений
            if (!$node['menu_item_parent']) {
                $tree[$id] = &$node;
            } else {
                //Если есть потомки то перебераем массив
                $buff[$node['menu_item_parent']]['children'][$id] = &$node;
            }
        }
        return $tree;
    }

    /**
     * Simple menu generator
     * @return mixed return or print simple menu
     */
    public function build_menu(array $config = array())
    {
        $default = array(
            'location'      => '',
            'wrapper'       => 'li',
            'wrapper_class' => '',
            'target'        => '', // 'target' => ' target="_blank"'
            'link_class'    => '',
            'before_link'   => '',
            'after_link'    => '',
            'echo'          => true
        );

        $config = array_merge($default, $config);

        $out = '';

        if (!array_key_exists($config['location'], $this->menuLocations)) {
            return '';
        }

        $item       = wp_get_nav_menu_object($this->menuLocations[$config['location']]);
        $menu_items = wp_get_nav_menu_items($item->term_id, array('update_post_term_cache' => false));

        if (is_array($menu_items) && count($menu_items) > 0) {
            foreach ($menu_items as $item) {
                if ($config['wrapper'] != '') {
                    $out .= '<' . $config['wrapper'] . ' class="' . $config['wrapper_class'] . '">';
                }

                $out .= $config['before_link'];

                if ($item->object_id == get_queried_object_id()) {
                    $out .= '<span class="active">' . $item->title . '</span>';
                } else {
                    $out .= '<a href="' . $item->url . '" class="' . $config['link_class'] . '"' . $config['target'] . '>' . $item->title . '</a>';
                }

                $out .= $config['after_link'];

                if ($config['wrapper'] != '') {
                    $out .= '</' . $config['wrapper'] . '>' . PHP_EOL;
                }
            }

            if ($config['echo']) {
                echo $out;
            } else {
                return $out;
            }
        } else {
            return '';
        }

        return '';
    }

    public function getMainLogo()
    {
        $theme_dir = $this->theme_url;
        $img_noimg = $this->img_noimg;

        if (has_custom_logo()) {
            $image_id   = get_theme_mod('custom_logo');
            $image_logo = wp_get_attachment_image_src($image_id, 'full');
            $image_logo = array_shift($image_logo);
        } else {
            $image_logo = $theme_dir . $img_noimg;
        }

        return $image_logo;
    }

    public function getSecondLogo()
    {
        $theme_dir = $this->theme_url;
        $img_noimg = $this->img_noimg;

        $image = get_theme_mod('second_logo');

        if (!$image) {
            $image = $theme_dir . $img_noimg;
        }

        return $image;
    }

    public function R($img = "", $opt = array(), $folder = "")
    {
        $cacheFolder = $this->cache_dir;
        $theme_dir   = $this->theme_dir;
        $img_noimg   = $this->img_noimg;

        if (!is_dir(ABSPATH . $cacheFolder)) {
            mkdir(ABSPATH . $cacheFolder, $this->new_folder_permissions, true);
        }

        $img = str_replace($this->site_url, '', $img);
        $img = rawurldecode($img);

        if (empty($img) || !is_file(ABSPATH . $img)) {
            $img = $theme_dir . '/' . $img_noimg;
            $img = str_replace(ABSPATH, "", $img);
        }

        // allow read in phpthumb cache folder
        if (!is_file(ABSPATH . $cacheFolder . '/.htaccess')) {
            file_put_contents(ABSPATH . $cacheFolder . '/.htaccess', "order deny,allow\nallow from all\n");
        }

        if (!is_dir(ABSPATH . $this->tmp_dir)) {
            mkdir(ABSPATH . $this->tmp_dir, $this->new_folder_permissions, true);
        }

        $allowedExt = array("png", "gif", "jpeg", "jpg", "svg");

        if ($this->webp) {
            $allowedExt[] = 'webp';
        }

        $path_parts = pathinfo(ABSPATH . $img);
        $ext        = strtolower($path_parts['extension']);

        if (is_string($opt)) {
            $opt = strtr($opt, array("," => "&", "_" => "=", '{' => '[', '}' => ']'));
            parse_str($opt, $params);
        } else {
            $params = $opt;
            $opt    = http_build_query($params);
        }

        if (!isset($params['f']) || $ext == 'svg') {
            $params['f'] = $ext;
        }

        if (!in_array($params['f'], $allowedExt)) {
            $params['f'] = $ext ? $ext : 'jpg';
            $params['q'] = isset($params['q']) ? $params['q'] : 96;
        }

        $fname = md5($img . $opt) . "." . $params['f'];

        if ($folder != "") {
            $folder = explode("/", $folder);
            foreach ($folder as $tfolder) {
                if (!empty($tfolder)) {
                    $cacheFolder .= "/" . $tfolder;
                    if (!is_dir($cacheFolder)) {
                        mkdir($cacheFolder, $this->new_folder_permissions, true);
                    }
                }
            }
        } else {
            $cacheFolder = $cacheFolder . '/' . $fname[0];
            if (!is_dir($cacheFolder)) {
                mkdir($cacheFolder, $this->new_folder_permissions, true);
            }
        }

        $outputFilename = ABSPATH . $cacheFolder . '/' . $fname;

        if (!file_exists($outputFilename)) {
            if ($ext == 'svg') {
                $svg = file_get_contents(ABSPATH . $img);
                $reW = '/(.*<svg[^>]* width=")([\d.]+)(.*)/si';
                $reH = '/(.*<svg[^>]* height=")([\d.]+)(.*)/si';

                preg_match($reW, $svg, $mw);
                preg_match($reH, $svg, $mh);
                $width  = intval(trim($mw[2]));
                $height = intval(trim($mh[2]));

                if ($width && $height && $params['w'] && $params['h']) {
                    $scale     = min($params['w'] / $width, $params['h'] / $height);
                    $newWidth  = intval($scale * $width);
                    $newHeight = intval($scale * $height);

                    $svg = preg_replace($reW, "\${1}{$newWidth}\${3}", $svg);
                    $svg = preg_replace($reH, "\${1}{$newHeight}\${3}", $svg);
                } elseif (($width || $height) && ($params['w'] || $params['h'])) {
                    if ($params['w'] && $width) {
                        $scale = $params['w'] / $width;
                        if ($height) {
                            $height = intval($scale * $height);
                        }
                    }
                    if ($params['h'] && $height) {
                        $scale = $params['h'] / $height;
                        if ($width) {
                            $width = intval($scale * $width);
                        }
                    }
                    if ($height && $height > $params['h']) {
                        $svg = preg_replace($reW, "\${1}{$width}\${3}", $svg);
                        $svg = preg_replace($reH, "\${1}{$params['h']}\${3}", $svg);
                    }
                    if ($width && $width > $params['w']) {
                        $svg = preg_replace($reW, "\${1}{$params['w']}\${3}", $svg);
                        $svg = preg_replace($reH, "\${1}{$height}\${3}", $svg);
                    }
                    if ($height <= $params['h'] && $width <= $params['w']) {
                        if ($params['w'] > $params['h']) {
                            $svg = preg_replace($reW, "\${1}{$width}\${3}", $svg);
                            $svg = preg_replace($reH, "\${1}{$params['h']}\${3}", $svg);
                        } else {
                            $svg = preg_replace($reW, "\${1}{$params['w']}\${3}", $svg);
                            $svg = preg_replace($reH, "\${1}{$height}\${3}", $svg);
                        }
                    }
                }
                file_put_contents($outputFilename, $svg);
            } else {
                $phpThumb                          = new \phpthumb();
                $phpThumb->config_temp_directory   = ABSPATH . $this->cache_dir;
                $phpThumb->config_document_root    = ABSPATH;
                $phpThumb->config_cache_directory  = ABSPATH . $this->cache_dir;
                $phpThumb->config_output_interlace = true;
                $phpThumb->setSourceFilename(ABSPATH . $img);
                foreach ($params as $key => $value) {
                    $phpThumb->setParameter($key, $value);
                }
                if ($phpThumb->GenerateThumbnail()) {
                    $phpThumb->RenderToFile($outputFilename);
                } else {
                    echo implode('<br/>', $phpThumb->debugmessages);
                    die;
                }
            }
        }

        return $this->site_url . "/" . ltrim($cacheFolder . "/" . rawurlencode($fname), '/');
    }

    public function clear_cache()
    {
        $cache_dir = ABSPATH . DIRECTORY_SEPARATOR . $this->cache_dir;
        $files     = glob($cache_dir);

        return $files;
    }

    public function mb_ucfirst($string): string
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }
}

function theme()
{
    return Theme::getInstance();
}