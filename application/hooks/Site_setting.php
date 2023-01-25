<?php


class Site_setting {


    function setSetting(){

        // $urlL = 'http://192.168.0.112/cuendizpos/';
        //  $urlL = 'http://localhost/cuendizpos/';
        // $urlL = 'http://localhost/pos/systemPOS';
        $urlL = 'https://system.cuendizrestaurant.com/';

        $ss_location = $urlL.'/site_setting/';

        define('ss_location', $urlL.'/site_setting/');

        $ss_string = file_get_contents($ss_location . "index.json");
        $ss_json_a = json_decode($ss_string, true);

        define('ss_site_title', $ss_json_a['site_title']);
        define('ss_footer', $ss_json_a['footer']);
        define('ss_background', $ss_json_a['background']);
        define('ss_login_page_logo', $ss_json_a['login_page_logo']);
        define('ss_home_page_logo', $ss_json_a['home_page_logo']);
        define('ss_msg', $ss_json_a['msg']);
        define('ss_msg_start', $ss_json_a['msg_start']);
        define('ss_msg_end', $ss_json_a['msg_end']);
        define('ss_home_mini_logo', $ss_json_a['home_mini_logo']);
        define('ss_print_logo', $ss_json_a['print_logo']);
    }
}
?>
