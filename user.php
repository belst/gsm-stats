<?php

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
