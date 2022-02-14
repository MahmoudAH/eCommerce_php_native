<?php
/*
$do = '';
if (isset($_GET['do'])) {
    $do = $_GET['do'];
} else {
    $do = 'Manage';
    echo '<a href="page.php?do=Add"> Add New Category+ </a> ';
}*/

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

if ($do == 'Manage') {
    echo 'welcome in manage page';
} elseif ($do == 'Add') {
    echo 'welcome in add page';
} elseif ($do == 'Insert') {
    echo 'welcome in insert page';
} else {
    echo 'there is no page';
}
