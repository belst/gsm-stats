<?php
/**
 * Author: belst
 * Date:   20-09-2016
 * Email:  gsm@bel.st
 * Last modified by:   belst
 * Last modified time: 20-09-2016
 * License: BSD3
*/


class Helper {

private $nickstb;
private $usertb;
private $statstb;

private $pdo;


public function __construct($host, $db, $username, $password,
        $nickstb, $usertb, $statstb)
{
    $this->pdo = new PDO("mysql:host={$host};dbname={$db}",
        $username, $password);
    $this->pdo->exec('SET CHARACTER SET utf8');

    $this->nickstb = $nickstb;
    $this->usertb  = $usertb;
    $this->statstb = $statstb;
}

public static function isXHR() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']);
}

public function get_users(
    $page = 1,
    $orderby = 'kills',
    $sortdir = 'DESC',
    $toshow = 30)
{

    $pagestart = ($page - 1) * $toshow;

    $limits = $pagestart.", ".$toshow;
    $order = $orderby." ".$sortdir;

    $stmt = $this->pdo->prepare(
        "SELECT
            u.playerID id,
            u.playerGUID guid,
            s.kills,
            s.deaths,
            s.headShots headshots,
            n.playerAlias nick,
            (s.kills / s.deaths) kd,
            (s.headShots / s.kills * 100) hsr,
            (SELECT count(*) FROM {$this->usertb}) totalrows
        FROM
            {$this->usertb} u,
            {$this->nickstb} n,
            {$this->statstb} s
        WHERE u.playerID = n.playerID AND u.playerID = s.playerID
        GROUP BY u.playerID
        ORDER BY {$order}, s.kills DESC
        LIMIT {$limits}");

    $stmt->execute();

    return $stmt->fetchALL(PDO::FETCH_OBJ);
}

public function get_users_by_name_or_guid(
    $page = 1,
    $query,
    $orderby = 'kills',
    $sortdir = 'DESC',
    $toshow = 30)
{
    $pagestart = ($page - 1) * $toshow;

    $limits = $pagestart.", ".$toshow;
    $order = $orderby." ".$sortdir;

    $stmt = $this->pdo->prepare(
    "SELECT
            u.playerID id,
            u.playerGUID guid,
            s.kills,
            s.deaths,
            s.headShots headshots,
            n.playerAlias nick,
            (s.kills / s.deaths) kd,
            (s.headShots / s.kills * 100) hsr,
            (SELECT count(*) FROM {$this->usertb}) totalrows
        FROM
            {$this->usertb} u,
            {$this->nickstb} n,
            {$this->statstb} s
        WHERE u.playerID = n.playerID AND u.playerID = s.playerID
        GROUP BY u.playerID
        HAVING u.playerGUID LIKE :search OR n.playerAlias LIKE :search
        ORDER BY {$order}, s.kills DESC
        LIMIT {$limits}");

    $stmt->execute(array(':search' => "%{$query}%"));

    return $stmt->fetchALL(PDO::FETCH_OBJ);
}

public function get_user_info_by_id($id) {

    $stmt = $this->pdo->prepare("SELECT playerAlias nick
        FROM {$this->nickstb}
        WHERE playerID = :id
        ORDER BY playerAlias ASC");

    $stmt->execute(array(':id' => $id));

    return $stmt->fetchALL(PDO::FETCH_OBJ);
}

}
