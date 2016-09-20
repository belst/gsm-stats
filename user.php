<?php
/**
 * @Author: belst
 * @Date:   20-09-2016
 * @Email:  gsm@bel.st
 * @Last modified by:   belst
 * @Last modified time: 20-09-2016
 * @License: BSD3
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

if (isset($_GET['id'])) {
	$user_info = $helper->get_user_info_by_id($_GET['id']);
}


include 'views/user.tmpl.php';
