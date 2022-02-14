<?php
function lang($phrase)
{
    static $lang = array(

        'home_admin' => 'Home',
        'categories' => 'Categories',
        'items'     => 'Items',
        'members '  => 'Members',

    );
    return $lang[$phrase];
}