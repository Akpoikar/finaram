<?php

function get_nationality_options()
{
    $countryNames = [
        __("Afghanistan", 'finanzia'),
        __("Albania", 'finanzia'),
        __("Algeria", 'finanzia'),
        __("Andorra", 'finanzia'),
        __("Angola", 'finanzia'),
        __("Antigua and Barbuda", 'finanzia'),
        __("Argentina", 'finanzia'),
        __("Armenia", 'finanzia'),
        __("Australia", 'finanzia'),
        __("Austria", 'finanzia'),
        __("Azerbaijan", 'finanzia'),
        __("Bahamas", 'finanzia'),
        __("Bahrain", 'finanzia'),
        __("Bangladesh", 'finanzia'),
        __("Barbados", 'finanzia'),
        __("Belarus", 'finanzia'),
        __("Belgium", 'finanzia'),
        __("Belize", 'finanzia'),
        __("Benin", 'finanzia'),
        __("Bhutan", 'finanzia'),
        __("Bolivia", 'finanzia'),
        __("Bosnia and Herzegovina", 'finanzia'),
        __("Botswana", 'finanzia'),
        __("Brazil", 'finanzia'),
        __("Brunei", 'finanzia'),
        __("Bulgaria", 'finanzia'),
        __("Burkina Faso", 'finanzia'),
        __("Burundi", 'finanzia'),
        __("Cabo Verde", 'finanzia'),
        __("Cambodia", 'finanzia'),
        __("Cameroon", 'finanzia'),
        __("Canada", 'finanzia'),
        __("Central African Republic", 'finanzia'),
        __("Chad", 'finanzia'),
        __("Chile", 'finanzia'),
        __("China", 'finanzia'),
        __("Colombia", 'finanzia'),
        __("Comoros", 'finanzia'),
        __("Congo (Democratic Republic)", 'finanzia'),
        __("Congo (Republic)", 'finanzia'),
        __("Costa Rica", 'finanzia'),
        __("Croatia", 'finanzia'),
        __("Cuba", 'finanzia'),
        __("Cyprus", 'finanzia'),
        __("Czech Republic", 'finanzia'),
        __("Denmark", 'finanzia'),
        __("Djibouti", 'finanzia'),
        __("Dominica", 'finanzia'),
        __("Dominican Republic", 'finanzia'),
        __("East Timor (Timor-Leste)", 'finanzia'),
        __("Ecuador", 'finanzia'),
        __("Egypt", 'finanzia'),
        __("El Salvador", 'finanzia'),
        __("Equatorial Guinea", 'finanzia'),
        __("Eritrea", 'finanzia'),
        __("Estonia", 'finanzia'),
        __("Eswatini", 'finanzia'),
        __("Ethiopia", 'finanzia'),
        __("Fiji", 'finanzia'),
        __("Finland", 'finanzia'),
        __("France", 'finanzia'),
        __("Gabon", 'finanzia'),
        __("Gambia", 'finanzia'),
        __("Georgia", 'finanzia'),
        __("Germany", 'finanzia'),
        __("Ghana", 'finanzia'),
        __("Greece", 'finanzia'),
        __("Grenada", 'finanzia'),
        __("Guatemala", 'finanzia'),
        __("Guinea", 'finanzia'),
        __("Guinea-Bissau", 'finanzia'),
        __("Guyana", 'finanzia'),
        __("Haiti", 'finanzia'),
        __("Honduras", 'finanzia'),
        __("Hungary", 'finanzia'),
        __("Iceland", 'finanzia'),
        __("India", 'finanzia'),
        __("Indonesia", 'finanzia'),
        __("Iran", 'finanzia'),
        __("Iraq", 'finanzia'),
        __("Ireland", 'finanzia'),
        __("Israel", 'finanzia'),
        __("Italy", 'finanzia'),
        __("Jamaica", 'finanzia'),
        __("Japan", 'finanzia'),
        __("Jordan", 'finanzia'),
        __("Kazakhstan", 'finanzia'),
        __("Kenya", 'finanzia'),
        __("Kiribati", 'finanzia'),
        __("Korea (North)", 'finanzia'),
        __("Korea (South)", 'finanzia'),
        __("Kosovo", 'finanzia'),
        __("Kuwait", 'finanzia'),
        __("Kyrgyzstan", 'finanzia'),
        __("Laos", 'finanzia'),
        __("Latvia", 'finanzia'),
        __("Lebanon", 'finanzia'),
        __("Lesotho", 'finanzia'),
        __("Liberia", 'finanzia'),
        __("Libya", 'finanzia'),
        __("Liechtenstein", 'finanzia'),
        __("Lithuania", 'finanzia'),
        __("Luxembourg", 'finanzia'),
        __("Madagascar", 'finanzia'),
        __("Malawi", 'finanzia'),
        __("Malaysia", 'finanzia'),
        __("Maldives", 'finanzia'),
        __("Mali", 'finanzia'),
        __("Malta", 'finanzia'),
        __("Marshall Islands", 'finanzia'),
        __("Mauritania", 'finanzia'),
        __("Mauritius", 'finanzia'),
        __("Mexico", 'finanzia'),
        __("Micronesia", 'finanzia'),
        __("Moldova", 'finanzia'),
        __("Monaco", 'finanzia'),
        __("Mongolia", 'finanzia'),
        __("Montenegro", 'finanzia'),
        __("Morocco", 'finanzia'),
        __("Mozambique", 'finanzia'),
        __("Myanmar (Burma)", 'finanzia'),
        __("Namibia", 'finanzia'),
        __("Nauru", 'finanzia'),
        __("Nepal", 'finanzia'),
        __("Netherlands", 'finanzia'),
        __("New Zealand", 'finanzia'),
        __("Nicaragua", 'finanzia'),
        __("Niger", 'finanzia'),
        __("Nigeria", 'finanzia'),
        __("North Macedonia (formerly Macedonia)", 'finanzia'),
        __("Norway", 'finanzia'),
        __("Oman", 'finanzia'),
        __("Pakistan", 'finanzia'),
        __("Palau", 'finanzia'),
        __("Panama", 'finanzia'),
        __("Papua New Guinea", 'finanzia'),
        __("Paraguay", 'finanzia'),
        __("Peru", 'finanzia'),
        __("Philippines", 'finanzia'),
        __("Poland", 'finanzia'),
        __("Portugal", 'finanzia'),
        __("Qatar", 'finanzia'),
        __("Romania", 'finanzia'),
        __("Russia", 'finanzia'),
        __("Rwanda", 'finanzia'),
        __("Saint Kitts and Nevis", 'finanzia'),
        __("Saint Lucia", 'finanzia'),
        __("Saint Vincent and the Grenadines", 'finanzia'),
        __("Samoa", 'finanzia'),
        __("San Marino", 'finanzia'),
        __("Sao Tome and Principe", 'finanzia'),
        __("Saudi Arabia", 'finanzia'),
        __("Senegal", 'finanzia'),
        __("Serbia", 'finanzia'),
        __("Seychelles", 'finanzia'),
        __("Sierra Leone", 'finanzia'),
        __("Singapore", 'finanzia'),
        __("Slovakia", 'finanzia'),
        __("Slovenia", 'finanzia'),
        __("Solomon Islands", 'finanzia'),
        __("Somalia", 'finanzia'),
        __("South Africa", 'finanzia'),
        __("South Sudan", 'finanzia'),
        __("Spain", 'finanzia'),
        __("Sri Lanka", 'finanzia'),
        __("Sudan", 'finanzia'),
        __("Suriname", 'finanzia'),
        __("Sweden", 'finanzia'),
        __("Switzerland", 'finanzia'),
        __("Syria", 'finanzia'),
        __("Taiwan", 'finanzia'),
        __("Tajikistan", 'finanzia'),
        __("Tanzania", 'finanzia'),
        __("Thailand", 'finanzia'),
        __("Togo", 'finanzia'),
        __("Tonga", 'finanzia'),
        __("Trinidad and Tobago", 'finanzia'),
        __("Tunisia", 'finanzia'),
        __("Turkey", 'finanzia'),
        __("Turkmenistan", 'finanzia'),
        __("Tuvalu", 'finanzia'),
        __("Uganda", 'finanzia'),
        __("Ukraine", 'finanzia'),
        __("United Arab Emirates", 'finanzia'),
        __("United Kingdom", 'finanzia'),
        __("United States", 'finanzia'),
        __("Uruguay", 'finanzia'),
        __("Uzbekistan", 'finanzia'),
        __("Vanuatu", 'finanzia'),
        __("Vatican City", 'finanzia'),
        __("Venezuela", 'finanzia'),
        __("Vietnam", 'finanzia'),
        __("Yemen", 'finanzia'),
        __("Zambia", 'finanzia'),
        __("Zimbabwe", 'finanzia'),
    ];

    setlocale(LC_ALL, get_locale().'.UTF-8');
    sort($countryNames, SORT_LOCALE_STRING);

    foreach ($countryNames as $countryName) {
        echo "<option value='{$countryName}'>{$countryName}</option>";
    }
}

function get_days_options()
{
    ?>
    <option value=""><?php _e("Day", 'finanzia'); ?></option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12">12</option>
    <option value="13">13</option>
    <option value="14">14</option>
    <option value="15">15</option>
    <option value="16">16</option>
    <option value="17">17</option>
    <option value="18">18</option>
    <option value="19">19</option>
    <option value="20">20</option>
    <option value="21">21</option>
    <option value="22">22</option>
    <option value="23">23</option>
    <option value="24">24</option>
    <option value="25">25</option>
    <option value="26">26</option>
    <option value="27">27</option>
    <option value="28">28</option>
    <option value="29">29</option>
    <option value="30">30</option>
    <option value="31">31</option>
    <?php
}

function get_months_options()
{
    ?>
    <option value=""><?php _e("Month", 'finanzia'); ?></option>
    <option value="1"><?php _e("January", 'finanzia'); ?></option>
    <option value="2"><?php _e("February", 'finanzia'); ?></option>
    <option value="3"><?php _e("March", 'finanzia'); ?></option>
    <option value="4"><?php _e("April", 'finanzia'); ?></option>
    <option value="5"><?php _e("May", 'finanzia'); ?></option>
    <option value="6"><?php _e("June", 'finanzia'); ?></option>
    <option value="7"><?php _e("July", 'finanzia'); ?></option>
    <option value="8"><?php _e("August", 'finanzia'); ?></option>
    <option value="9"><?php _e("September", 'finanzia'); ?></option>
    <option value="10"><?php _e("October", 'finanzia'); ?></option>
    <option value="11"><?php _e("November", 'finanzia'); ?></option>
    <option value="12"><?php _e("December", 'finanzia'); ?></option>
    <?php
}

function get_years_options($from = 1952, $to = null)
{
    $to = $to ?: date('Y')
    ?>
    <option value=""><?php _e("Year", 'finanzia'); ?></option>
    <?php for ($i = $from; $i <= $to; $i += 1) : ?>
    <option value="<?= $i ?>"><?= $i ?></option>
<?php endfor; ?>

    <?php
}

function get_months_name($number)
{
    $arr = [
        1  => __("January", 'finanzia'),
        2  => __("February", 'finanzia'),
        3  => __("March", 'finanzia'),
        4  => __("April", 'finanzia'),
        5  => __("May", 'finanzia'),
        6  => __("June", 'finanzia'),
        7  => __("July", 'finanzia'),
        8  => __("August", 'finanzia'),
        9  => __("September", 'finanzia'),
        10 => __("October", 'finanzia'),
        11 => __("November", 'finanzia'),
        12 => __("December", 'finanzia'),
    ];
    return $arr[$number];
}