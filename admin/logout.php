<?php
session_start();
session_unset();  //un set session data
session_destroy(); //destrop session
header('location:index.php');
exit;
