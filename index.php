<?php
/**
 * Author: belst
 * Date:   20-09-2016
 * Email:  gsm@bel.st
 * Last modified by:   belst
 * Last modified time: 20-09-2016
 * License: BSD3
*/


require 'functions.php';

$config = require 'config.php';
$helper = new Helper(
    $config['host'],
    $config['db'],
    $config['username'],
    $config['password'],
    $config['nickstb'],
    $config['usertb'],
    $config['statstb']
    );

// for AJAX
if(Helper::isXHR()) {
    if(isset($_POST['q'])) {
        echo json_encode($helper->get_users_by_name_or_guid($_POST['q']));
    } elseif(isset($_POST['id'])) {
        echo json_encode($helper->get_user_info_by_id($_POST['id']));
    } else {
        $users = json_encode(
            $helper->get_users($config['orderby'], $config['sortdir']));
    }

    return;
}


$page = (isset($_GET['page'])) ? $_GET['page'] : 1;


if(isset($_POST['q'])) {
    $users = $helper->get_users_by_name_or_guid($page, $_POST['q']);
}
else {
    $users = $helper->get_users($page);
}

include 'views/index.tmpl.php';
