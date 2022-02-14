<?php
function lang($phrase)
{
    static $lang = array(

        'MESSAGE' => 'مرحبا بك في العاصمه',
        'ADMIN' => 'المدير'
    );
    return $lang[$phrase];
}