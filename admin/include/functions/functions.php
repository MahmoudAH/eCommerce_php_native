<?php

/**
 * func to get page title
 */

function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'default';
    }
}
/**
 * func to redirect
 */
function redirectHome($msg, $url = null, $sec = 3)
{

    if ($url === null) {
        $url = 'index.php';
    } else {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = 'index.php';
        }
    }
    echo $msg;
    header("refresh:$sec;url:$url");
    exit;
}

/***
 * func to count users
 */
function countItem($item, $table)
{
    global $connect;
    $stm = $connect->prepare("SELECT count($item) FROM $table");
    $stm->execute();
    return $stm->fetchColumn();
}
/**
 * check function
 *check if value exist in db
 * @param [type] $value
 * @return row counts in db
 */
function checkItem($input, $table, $value)
{
    global $connect;
    $stm = $connect->prepare("SELECT $input FROM $table WHERE $input= ?");
    $stm->execute(array($value));
    return $stm->rowCount();
}

/**
 *get latest record function 
 */
function getLatest($input, $table, $order, $limit = 5)
{
    global $connect;
    $q = $connect->prepare("SELECT $input FROM $table ORDER BY $order DESC LIMIT $limit");
    $q->execute();
    return $q->fetchAll();
}